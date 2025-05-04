<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;

// Welcome route - accessible to all
Route::get('/', function () {
    return inertia('Welcome');
})->name('home');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard', [
            'tasks' => \App\Models\Task::where('creator_id', auth()->id())
                ->orWhere('assignee_id', auth()->id())
                ->get(),
            'events' => \App\Models\CalendarEvent::where('user_id', auth()->id())->get(),
        ]);
    })->name('dashboard');

    // Calendar Routes
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
        Route::put('/{event}', [CalendarController::class, 'update'])->name('calendar.update');
        Route::delete('/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    // Task Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
