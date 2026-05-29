@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">My Tasks</h2>
    <a href="{{ route('tasks.create') }}"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + New Task
    </a>
</div>

{{-- Filter Bar --}}
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $filters = [
            'status'       => request('status'),
            'category'     => request('category'),
            'sub_category' => request('sub_category'),
        ];
    @endphp

    <a href="{{ route('dashboard') }}"
        class="px-3 py-1 rounded-full text-sm border {{ !request()->hasAny(['status','category','sub_category']) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400' }}">
        All
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['status' => 'pending'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('status') === 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-600 border-gray-300 hover:border-yellow-400' }}">
        Pending
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['status' => 'done'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('status') === 'done' ? 'bg-green-500 text-white border-green-500' : 'bg-white text-gray-600 border-gray-300 hover:border-green-400' }}">
        Done
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['category' => 'daily'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('category') === 'daily' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
        Daily
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['category' => 'school'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('category') === 'school' ? 'bg-purple-500 text-white border-purple-500' : 'bg-white text-gray-600 border-gray-300 hover:border-purple-400' }}">
        School
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['sub_category' => 'umum'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('sub_category') === 'umum' ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600 border-gray-300 hover:border-orange-400' }}">
        Umum
    </a>
    <a href="{{ route('dashboard', array_merge($filters, ['sub_category' => 'produktif'])) }}"
        class="px-3 py-1 rounded-full text-sm border {{ request('sub_category') === 'produktif' ? 'bg-teal-500 text-white border-teal-500' : 'bg-white text-gray-600 border-gray-300 hover:border-teal-400' }}">
        Produktif
    </a>
</div>

{{-- Daily Tasks --}}
@php
    $daily  = $tasks->where('category', 'daily');
    $school = $tasks->where('category', 'school');
    $schoolUmum      = $school->where('sub_category', 'umum');
    $schoolProduktif = $school->where('sub_category', 'produktif');
    $schoolOther     = $school->whereNotIn('sub_category', ['umum', 'produktif']);
@endphp

@if($daily->count())
<div class="mb-8">
    <h3 class="text-lg font-semibold text-blue-700 mb-3 flex items-center gap-2">
        🏠 Daily Tasks <span class="text-sm font-normal text-gray-400">({{ $daily->count() }})</span>
    </h3>
    <div class="space-y-3">
        @foreach($daily as $task)
            @include('tasks._card', ['task' => $task])
        @endforeach
    </div>
</div>
@endif

@if($schoolUmum->count())
<div class="mb-8">
    <h3 class="text-lg font-semibold text-purple-700 mb-3 flex items-center gap-2">
        🎓 School – Umum <span class="text-sm font-normal text-gray-400">({{ $schoolUmum->count() }})</span>
    </h3>
    <div class="space-y-3">
        @foreach($schoolUmum as $task)
            @include('tasks._card', ['task' => $task])
        @endforeach
    </div>
</div>
@endif

@if($schoolProduktif->count())
<div class="mb-8">
    <h3 class="text-lg font-semibold text-teal-700 mb-3 flex items-center gap-2">
        📚 School – Produktif <span class="text-sm font-normal text-gray-400">({{ $schoolProduktif->count() }})</span>
    </h3>
    <div class="space-y-3">
        @foreach($schoolProduktif as $task)
            @include('tasks._card', ['task' => $task])
        @endforeach
    </div>
</div>
@endif

@if($schoolOther->count())
<div class="mb-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-3">🎓 School – Other</h3>
    <div class="space-y-3">
        @foreach($schoolOther as $task)
            @include('tasks._card', ['task' => $task])
        @endforeach
    </div>
</div>
@endif

@if($tasks->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-5xl mb-4">📭</p>
        <p class="text-lg">No tasks found. Create your first task!</p>
    </div>
@endif

{{-- Pagination --}}
<div class="mt-6">
    {{ $tasks->withQueryString()->links() }}
</div>
@endsection
