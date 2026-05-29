<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="3"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('description', $task->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
        <select name="category" required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">Select...</option>
            <option value="daily" {{ old('category', $task->category ?? '') === 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="school" {{ old('category', $task->category ?? '') === 'school' ? 'selected' : '' }}>School</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
        <select name="sub_category"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">None</option>
            <option value="umum" {{ old('sub_category', $task->sub_category ?? '') === 'umum' ? 'selected' : '' }}>Umum</option>
            <option value="produktif" {{ old('sub_category', $task->sub_category ?? '') === 'produktif' ? 'selected' : '' }}>Produktif</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="pending" {{ old('status', $task->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="done" {{ old('status', $task->status ?? '') === 'done' ? 'selected' : '' }}>Done</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
        <input type="date" name="due_date"
            value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_public" id="is_public" value="1"
        {{ old('is_public', $task->is_public ?? false) ? 'checked' : '' }}
        class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-400">
    <label for="is_public" class="text-sm text-gray-700">Make this task public</label>
</div>
