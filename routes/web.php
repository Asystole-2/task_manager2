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

Route::get('/guest-view', function () {
    return inertia('Guest/View');
})->name('guest.view');

Route::middleware(['auth', 'verified'])->group(function () {
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

    // Project Routes
    Route::prefix('ProjectManagement')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('ProjectManagement.index');
        Route::get('/create', [ProjectManagementController::class, 'create'])->name('ProjectManagement.create');
        Route::post('/', [ProjectManagementController::class, 'store'])->name('ProjectManagement.store');
        Route::get('/{projectManagement}', [ProjectManagementController::class, 'show'])->name('ProjectManagement.show');
        // Add other routes as needed
        Route::get('/projects/{projectManagement}/edit', [ProjectManagementController::class, 'edit'])->name('ProjectManagement.edit');
        Route::put('/{projectManagement}', [ProjectManagementController::class, 'update'])->name('ProjectManagement.update');
        Route::post('/project-management/{projectManagement}/tasks', [ProjectManagementController::class, 'addTasks'])
            ->name('ProjectManagement.tasks.add');
    });
    // Activity Route
    Route::get('/activity', [ActivityController::class, 'index'])->name('activities.index');
    // Project Management Routes
//    Route::resource('ProjectManagement', ProjectManagementController::class)
//        ->parameters(['ProjectManagement' => 'projectManagement'])
//        ->names([
//            'index' => 'ProjectManagement.index',
//            'create' => 'ProjectManagement.create',
//            'store' => 'ProjectManagement.store',
//            'show' => 'ProjectManagement.show',
//            'edit' => 'ProjectManagement.edit',
//            'update' => 'ProjectManagement.update',
//            'destroy' => 'ProjectManagement.destroy'
//        ]);

    // Project Member Routes
//    Route::post('/ProjectManagement/{projectManagement}/add-member', [ProjectManagementController::class, 'addMember'])
//        ->name('ProjectManagement.add-member');
//    Route::delete('/ProjectManagement/{projectManagement}/remove-member/{user}', [ProjectManagementController::class, 'removeMember'])
//        ->name('ProjectManagement.remove-member');
//    Route::get('/ProjectManagement/{projectManagement}/available-members', [ProjectManagementController::class, 'availableMembers'])
//        ->name('ProjectManagement.available-members');


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
        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::put('/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    // Calendar Routes
    Route::resource('calendar', CalendarController::class)
        ->except(['edit', 'update']); // Remove edit/update if not needed
    Route::put('/calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');

    // Activity Routes
    Route::get('/activity', [ActivityController::class, 'index'])->name('activities.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
