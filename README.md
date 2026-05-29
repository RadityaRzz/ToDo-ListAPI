# DailyLife Task Manager

Aplikasi manajemen tugas harian berbasis Laravel 13 dengan REST API (Sanctum) dan tampilan UI menggunakan Blade + Tailwind CSS.

---

## Teknologi yang Digunakan

- Laravel 13
- Laravel Sanctum (autentikasi berbasis token)
- MySQL
- Blade + Tailwind CSS v4

---

## Cara Instalasi

```bash
# 1. Install dependensi PHP
composer install

# 2. Salin file env dan generate key
cp .env.example .env
php artisan key:generate

# 3. Sesuaikan konfigurasi database di file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailylife_taskmanager
DB_USERNAME=root
DB_PASSWORD=

# 4. Jalankan migrasi database
php artisan migrate

# 5. (Opsional) Isi data dummy untuk testing
php artisan db:seed

# 6. Install dan build aset frontend
npm install
npm run build
```

---

## Cara Menjalankan

```bash
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

**Akun demo (setelah db:seed):**
- Email: `demo@example.com`
- Password: `password123`

---

## Daftar Endpoint API

### Publik (Tanpa Auth)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/public/tasks` | Ambil semua task yang bersifat publik |

### Autentikasi

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/register` | Daftar akun baru |
| POST | `/api/login` | Login, mendapatkan token |
| POST | `/api/logout` | Logout (butuh token) |

### Task (Wajib login — Bearer Token)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/tasks` | Ambil semua task milik user yang login |
| POST | `/api/tasks` | Buat task baru |
| GET | `/api/tasks/{id}` | Detail task |
| PUT/PATCH | `/api/tasks/{id}` | Update task |
| DELETE | `/api/tasks/{id}` | Hapus task |
| PATCH | `/api/tasks/{id}/done` | Tandai task sebagai selesai |

**Filter yang tersedia di GET `/api/tasks`:**
- `?status=pending` atau `?status=done`
- `?category=daily` atau `?category=school`
- `?sub_category=umum` atau `?sub_category=produktif`

---

## Format Response API

Semua response menggunakan format JSON yang konsisten:

```json
{
  "success": true,
  "message": "string",
  "data": {}
}
```

---

## Contoh Penggunaan API

### Register
```json
POST /api/register
{
  "name": "Budi",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```json
POST /api/login
{
  "email": "budi@example.com",
  "password": "password123"
}
```
Response akan mengembalikan `token`, gunakan token tersebut di header request selanjutnya:
```
Authorization: Bearer {token}
```

### Buat Task
```json
POST /api/tasks
Authorization: Bearer {token}

{
  "title": "Kerjakan PR Matematika",
  "category": "school",
  "sub_category": "umum",
  "due_date": "2026-05-30",
  "is_public": false
}
```

---

## Struktur Field Task

| Field | Tipe | Keterangan |
|-------|------|------------|
| title | string | Wajib diisi |
| description | text | Opsional |
| category | enum | `daily` atau `school` |
| sub_category | enum | `umum` atau `produktif` (opsional) |
| status | enum | `pending` (default) atau `done` |
| due_date | date | Opsional |
| is_public | boolean | Default `false` |

---

## Penjelasan Teknis Kode

Bagian ini menjelaskan cara kerja komponen utama project supaya lebih mudah dipahami.

---

### 1. Alur Routing — `routes/api.php`

File ini adalah pintu masuk semua request API. Ada dua bagian:

```php
// Bisa diakses siapa saja tanpa login
Route::post('/register', ...);
Route::post('/login', ...);
Route::get('/public/tasks', ...);

// Hanya bisa diakses kalau sudah login (ada token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', ...);
    Route::get('/tasks', ...);
    // dst...
});
```

Cara kerjanya:
- Route di luar group → siapa saja bisa akses
- Route di dalam `auth:sanctum` → Laravel otomatis cek header `Authorization: Bearer {token}`, kalau tidak ada atau token salah, langsung ditolak dengan response `401 Unauthenticated`

---

### 2. Cara Kerja Token Sanctum — `AuthController.php`

**Register & Login — dapat token:**
```php
// Setelah user berhasil dibuat / login, kita generate token
$token = $user->createToken('auth_token')->plainTextToken;
```
Token ini disimpan di tabel `personal_access_tokens` di database. Token inilah yang harus dikirim di setiap request yang butuh auth.

**Logout — hapus token:**
```php
$request->user()->currentAccessToken()->delete();
```
Logout di Sanctum bukan sekedar "keluar", tapi token-nya benar-benar dihapus dari database. Jadi kalau token lama dipakai lagi, sudah tidak bisa.

**Cara pakai token di request:**
```
Authorization: Bearer 1|abc123xyz...
```
Header ini dikirim di setiap request ke endpoint yang butuh auth.

---

### 3. Ownership Check — `TaskController.php`

Ini bagian penting yang memastikan user hanya bisa akses task miliknya sendiri.

```php
if ($task->user_id !== $request->user()->id) {
    return response()->json([
        'success' => false,
        'message' => 'Forbidden. You do not own this task.',
        'data'    => null,
    ], 403);
}
```

Cara kerjanya:
- `$task->user_id` → ID pemilik task yang diminta
- `$request->user()->id` → ID user yang sedang login (dari token)
- Kalau tidak sama → tolak dengan HTTP `403 Forbidden`
- Kalau sama → lanjut proses

Contoh skenario: User A punya task ID 5. User B coba akses `GET /api/tasks/5` → dapat `403`, tidak bisa lihat datanya.

**Cara buat task juga otomatis terikat ke user yang login:**
```php
// tasks() adalah relasi hasMany dari User ke Task
// Jadi user_id otomatis terisi dari user yang sedang login
$task = $request->user()->tasks()->create($request->validated());
```

---

### 4. Form Request & Validasi — `StoreTaskRequest.php`

Form Request adalah class khusus untuk menangani validasi input. Tujuannya supaya logika validasi tidak campur aduk di dalam controller.

```php
public function rules(): array
{
    return [
        'title'        => ['required', 'string', 'max:255'],  // wajib diisi
        'description'  => ['nullable', 'string'],              // boleh kosong
        'category'     => ['required', Rule::in(['daily', 'school'])], // harus salah satu dari nilai ini
        'sub_category' => ['nullable', Rule::in(['umum', 'produktif'])],
        'status'       => ['nullable', Rule::in(['pending', 'done'])],
        'due_date'     => ['nullable', 'date'],                // kalau diisi, harus format tanggal
        'is_public'    => ['nullable', 'boolean'],
    ];
}
```

Kalau validasi gagal, Laravel otomatis return response `422 Unprocessable Entity` dengan detail field mana yang salah — tanpa perlu nulis logika if-else di controller.

**Kenapa pakai Form Request, bukan validasi langsung di controller?**
- Controller jadi lebih bersih dan fokus ke logika bisnis
- Validasi bisa dipakai ulang kalau ada endpoint lain yang butuh aturan sama
- Lebih mudah dibaca dan di-maintain

---

### 5. Resource Class — `TaskResource.php`

Resource class mengontrol data apa saja yang boleh keluar ke response. Kita tidak pernah return model langsung karena bisa bocor field yang tidak perlu.

```php
// JANGAN begini — return model mentah
return response()->json($task);

// HARUS begini — lewat Resource dulu
return response()->json([
    'data' => new TaskResource($task)
]);
```

Di `TaskResource`, kita tentukan sendiri field apa yang dikirim:
```php
return [
    'id'          => $this->id,
    'title'       => $this->title,
    'status'      => $this->status,
    'due_date'    => $this->due_date?->format('Y-m-d'),
    // dst...
];
```

Hasilnya response selalu konsisten dan rapi, tidak ada field aneh yang ikut keluar.
