<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class PublicTaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::where('is_public', true)->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Public tasks retrieved successfully.',
            'data'    => TaskResource::collection($tasks)->response()->getData(true),
        ]);
    }
}
