<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Models\CalendarEvent;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

// Welcome route - accessible to all (landing page)
Route::get('/', function () {
    return inertia('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// Guest view route
Route::get('/guest-view', function () {
    return inertia('Guest/View');
})->name('guest.view');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard route

    // Add these routes inside the authenticated group
    // Add these routes inside the authenticated group
    Route::delete('/activity/{activity}', [ActivityController::class, 'destroyActivity'])->name('activities.destroy');
    Route::delete('/projects/{project}', [ActivityController::class, 'destroyProject'])->name('projects.destroy');
    Route::delete('/tasks/{task}', [ActivityController::class, 'destroyTask'])->name('tasks.destroy');
    Route::delete('/calendar/{event}', [ActivityController::class, 'destroyEvent'])->name('calendar.destroy');
    Route::get('/dashboard', function () {
        $userId = auth()->id();

        return inertia('Dashboard', [
            'tasks' => Task::where('creator_id', $userId)
                ->orWhere('assignee_id', $userId)
                ->get(),
            'events' => CalendarEvent::where('user_id', $userId)
                ->whereDate('start', '>=', now())
                ->get(),
            'projects' => \App\Models\ProjectManagement::where('owner_id', $userId)->get(),
            'pendingTasksCount' => Task::where(function($query) use ($userId) {
                $query->where('creator_id', $userId)
                    ->orWhere('assignee_id', $userId);
            })->where('status', 'pending')->count(),
            'upcomingEventsCount' => CalendarEvent::where('user_id', $userId)
                ->whereDate('start', '>=', now())
                ->count(),
            'activeProjectsCount' => \App\Models\ProjectManagement::where('owner_id', $userId)
                ->where('is_active', true)
                ->count(),
        ]);
    })->name('dashboard');

    // Project Routes
    Route::prefix('ProjectManagement')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('ProjectManagement.index');
        Route::get('/create', [ProjectManagementController::class, 'create'])->name('ProjectManagement.create');
        Route::post('/', [ProjectManagementController::class, 'store'])->name('ProjectManagement.store');
        Route::get('/{projectManagement}', [ProjectManagementController::class, 'show'])->name('ProjectManagement.show');
        Route::get('/{projectManagement}/edit', [ProjectManagementController::class, 'edit'])->name('ProjectManagement.edit');
        Route::put('/{projectManagement}', [ProjectManagementController::class, 'update'])->name('ProjectManagement.update');
        Route::post('/{projectManagement}/tasks', [ProjectManagementController::class, 'addTasks'])->name('ProjectManagement.tasks.add');
    });

    // Activity Route
    Route::get('/activity', [ActivityController::class, 'index'])->name('activities.index');

    // Calendar Routes
    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
        Route::delete('/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    // To-Do Routes
    Route::prefix('todos')->group(function () {
        Route::get('/', [TodoController::class, 'index'])->name('todos.index');
        Route::post('/', [TodoController::class, 'store'])->name('todos.store');
        Route::patch('/{todo}', [TodoController::class, 'update'])->name('todos.update');
        Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    });

    // Task Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::patch('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
