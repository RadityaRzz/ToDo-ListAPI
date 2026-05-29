<?php

use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\WebTaskController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    Route::get('/', [WebTaskController::class, 'index'])->name('dashboard');
    Route::get('/tasks/create', [WebTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [WebTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [WebTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [WebTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [WebTaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/done', [WebTaskController::class, 'markDone'])->name('tasks.done');
});
