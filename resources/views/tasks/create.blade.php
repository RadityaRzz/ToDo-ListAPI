@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline text-sm">← Back</a>
        <h2 class="text-2xl font-bold text-gray-800">New Task</h2>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
            @csrf
            @include('tasks._form')
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                Create Task
            </button>
        </form>
    </div>
</div>
@endsection
