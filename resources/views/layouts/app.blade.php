<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DailyLife Task Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<nav class="bg-indigo-700 text-white px-6 py-4 flex items-center justify-between shadow">
    <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight">📋 DailyLife Tasks</a>
    <div class="flex items-center gap-4">
        @auth
            <span class="text-sm text-indigo-200">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm bg-indigo-500 hover:bg-indigo-400 px-3 py-1 rounded transition">Logout</button>
            </form>
        @endauth
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 py-8">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
