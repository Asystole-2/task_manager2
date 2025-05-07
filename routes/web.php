<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\ActivityController;

Route::get('/', function () {
    return inertia('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/guest-view', function () {
    return inertia('Guest/View');
})->name('guest.view');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $userId = auth()->id();

        return inertia('Dashboard', [
            'tasks' => \App\Models\Task::where('creator_id', $userId)
                ->orWhere('assignee_id', $userId)
                ->get(),
            'events' => \App\Models\CalendarEvent::where('user_id', $userId)
                ->whereDate('start', '>=', now())
                ->get(),
            'projects' => \App\Models\ProjectManagement::where('owner_id', $userId)->get(),
            'pendingTasksCount' => \App\Models\Task::where(function($query) use ($userId) {
                $query->where('creator_id', $userId)
                    ->orWhere('assignee_id', $userId);
            })->where('status', 'pending')->count(),
            'upcomingEventsCount' => \App\Models\CalendarEvent::where('user_id', $userId)
                ->whereDate('start', '>=', now())
                ->count(),
            'activeProjectsCount' => \App\Models\ProjectManagement::where('owner_id', $userId)
                ->where('is_active', true)
                ->count(),
        ]);
    })->name('dashboard');

    // Project Management Routes
    Route::resource('project-management', ProjectManagementController::class)
        ->parameters(['project-management' => 'projectManagement'])
        ->names([
            'index' => 'ProjectManagement.index',
            'create' => 'ProjectManagement.create',
            'store' => 'ProjectManagement.store',
            'show' => 'ProjectManagement.show',
            'edit' => 'ProjectManagement.edit',
            'update' => 'ProjectManagement.update',
            'destroy' => 'ProjectManagement.destroy'
        ]);

    // Other routes remain the same...
    Route::get('/activity', [ActivityController::class, 'index'])->name('activities.index');

    Route::prefix('calendar')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
        Route::delete('/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::patch('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
