<?php
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Single-page application route
Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'boards' => \App\Models\Board::with('columns.cards')->get(),
        'todos' => \App\Models\Todo::where('user_id', auth()->id())->orderBy('completed')->get(),
        'events' => \App\Models\CalendarEvent::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get()
    ]);
})->name('home');

// API routes for your task management
Route::prefix('api')->group(function () {
    // Board routes
    Route::post('/boards', [BoardController::class, 'store']);
    Route::put('/boards/{board}', [BoardController::class, 'update']);
    Route::delete('/boards/{board}', [BoardController::class, 'destroy']);

    // Column routes
    Route::post('/boards/{board}/columns', [ColumnController::class, 'store']);
    Route::put('/columns/{column}', [ColumnController::class, 'update']);
    Route::delete('/columns/{column}', [ColumnController::class, 'destroy']);
    Route::post('/columns/{column}/move', [ColumnController::class, 'move']);

    // Card routes
    Route::post('/columns/{column}/cards', [CardController::class, 'store']);
    Route::put('/cards/{card}', [CardController::class, 'update']);
    Route::delete('/cards/{card}', [CardController::class, 'destroy']);
    Route::post('/cards/{card}/move', [CardController::class, 'move']);

    // Todo routes
    Route::post('/todos', [TodoController::class, 'store']);
    Route::put('/todos/{todo}', [TodoController::class, 'update']);
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
    Route::post('/todos/{todo}/toggle', [TodoController::class, 'toggleComplete']);

    // Calendar routes
    Route::post('/events', [CalendarController::class, 'store']);
    Route::put('/events/{event}', [CalendarController::class, 'update']);
    Route::delete('/events/{event}', [CalendarController::class, 'destroy']);
});
