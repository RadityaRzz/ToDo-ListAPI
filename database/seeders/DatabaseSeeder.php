<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::create([
            'name'     => 'Demo User',
            'email'    => 'demo@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Contoh tasks bawaan buat testing
        $tasks = [
            [
                'title'        => 'Beli bahan masak',
                'description'  => 'Beli sayur, telur, dan minyak goreng',
                'category'     => 'daily',
                'sub_category' => null,
                'status'       => 'pending',
                'due_date'     => now()->addDays(1)->toDateString(),
                'is_public'    => true,
            ],
            [
                'title'        => 'Olahraga pagi',
                'description'  => 'Lari 30 menit',
                'category'     => 'daily',
                'sub_category' => null,
                'status'       => 'done',
                'due_date'     => now()->toDateString(),
                'is_public'    => false,
            ],
            [
                'title'        => 'Kerjakan PR Matematika',
                'description'  => 'Halaman 45-50',
                'category'     => 'school',
                'sub_category' => 'umum',
                'status'       => 'pending',
                'due_date'     => now()->addDays(2)->toDateString(),
                'is_public'    => true,
            ],
            [
                'title'        => 'Buat laporan praktikum',
                'description'  => 'Laporan kimia bab reaksi asam basa',
                'category'     => 'school',
                'sub_category' => 'produktif',
                'status'       => 'pending',
                'due_date'     => now()->addDays(3)->toDateString(),
                'is_public'    => false,
            ],
            [
                'title'        => 'Belajar Laravel',
                'description'  => 'Pelajari Sanctum dan API Resource',
                'category'     => 'school',
                'sub_category' => 'produktif',
                'status'       => 'done',
                'due_date'     => null,
                'is_public'    => true,
            ],
        ];

        foreach ($tasks as $task) {
            $user->tasks()->create($task);
        }
    }
}
