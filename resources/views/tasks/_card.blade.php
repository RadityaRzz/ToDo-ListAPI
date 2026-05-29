<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-start justify-between gap-4
    {{ $task->status === 'done' ? 'opacity-60' : '' }}">
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap mb-1">
            <span class="font-semibold text-gray-800 {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">
                {{ $task->title }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($task->status) }}
            </span>
            @if($task->is_public)
                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 font-medium">Public</span>
            @endif
        </div>
        @if($task->description)
            <p class="text-sm text-gray-500 truncate">{{ $task->description }}</p>
        @endif
        @if($task->due_date)
            <p class="text-xs text-gray-400 mt-1">📅 Due: {{ $task->due_date->format('d M Y') }}</p>
        @endif
    </div>

    <div class="flex items-center gap-2 shrink-0">
        @if($task->status !== 'done')
            <form method="POST" action="{{ route('tasks.done', $task) }}">
                @csrf
                @method('PATCH')
                <button title="Mark as done"
                    class="text-green-600 hover:text-green-800 text-sm font-medium border border-green-300 hover:border-green-500 px-2 py-1 rounded-lg transition">
                    ✓
                </button>
            </form>
        @endif
        <a href="{{ route('tasks.edit', $task) }}"
            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium border border-indigo-200 hover:border-indigo-400 px-2 py-1 rounded-lg transition">
            Edit
        </a>
        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
            onsubmit="return confirm('Delete this task?')">
            @csrf
            @method('DELETE')
            <button class="text-red-500 hover:text-red-700 text-sm font-medium border border-red-200 hover:border-red-400 px-2 py-1 rounded-lg transition">
                Delete
            </button>
        </form>
    </div>
</div>
