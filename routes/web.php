<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\ActivityController;
use App\Models\Task; // Add this line
use App\Models\CalendarEvent; // Add this line
use App\Models\Project; // Add this line
use App\Models\Activity; // Add this line
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
    Route::get('/dashboard', function () {
        $userId = auth()->id();

        return inertia('Dashboard', [
            'tasks' => \App\Models\Task::where('creator_id', $userId)
                ->orWhere('assignee_id', $userId)
                ->get(),
            'events' => \App\Models\CalendarEvent::where('user_id', $userId)
                ->whereDate('start', '>=', now())
                ->get(),
            'projects' => \App\Models\Project::where('owner_id', $userId)->get(),
            'activities' => \App\Models\Activity::with(['user', 'task', 'project'])
                ->where('user_id', $userId)
                ->orWhereHas('project', function($query) use ($userId) {
                    $query->where('owner_id', $userId);
                })
                ->latest()
                ->take(10)
                ->get(),
        ]);
    })->name('dashboard');

    // Project Routes
    Route::prefix('project-management')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('project-management.index');
        Route::get('/create', [ProjectManagementController::class, 'create'])->name('project-management.create');
        Route::post('/', [ProjectManagementController::class, 'store'])->name('project-management.store');
        Route::get('/{projectManagement}', [ProjectManagementController::class, 'show'])->name('project-management.show');
        // Add other routes as needed
    });
    // Activity Route
    Route::get('/activity', [ActivityController::class, 'index'])->name('activities.index');

    // Calendar Routes
    Route::prefix('calendar')->group(function () {
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
        Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    });

    // Task Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::patch('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
