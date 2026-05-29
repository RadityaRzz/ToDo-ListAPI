<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
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

        $tasks = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data'    => TaskResource::collection($tasks)->response()->getData(true),
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $request->user()->tasks()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data'    => new TaskResource($task),
        ], 201);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not own this task.',
                'data'    => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully.',
            'data'    => new TaskResource($task),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not own this task.',
                'data'    => null,
            ], 403);
        }

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data'    => new TaskResource($task),
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not own this task.',
                'data'    => null,
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
            'data'    => null,
        ]);
    }

    public function markDone(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not own this task.',
                'data'    => null,
            ], 403);
        }

        $task->update(['status' => 'done']);

        return response()->json([
            'success' => true,
            'message' => 'Task marked as done.',
            'data'    => new TaskResource($task),
        ]);
    }
}
