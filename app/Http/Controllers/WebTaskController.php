<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebTaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->tasks();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('sub_category')) {
            $query->where('sub_category', $request->sub_category);
        }

        $tasks = $query->latest()->paginate(20);

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create($request->validated());

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    public function edit(Request $request, Task $task): View|RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $task->update($request->validated());

        return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted.');
    }

    public function markDone(Request $request, Task $task): RedirectResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $task->update(['status' => 'done']);

        return redirect()->route('dashboard')->with('success', 'Task marked as done.');
    }
}
