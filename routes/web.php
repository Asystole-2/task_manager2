<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Frontend routes
Route::get('/', function () {
    return Inertia::render('Dashboard');});

Route::middleware('auth')->group(function () {
    Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
    Route::get('/boards/{id}', [BoardController::class, 'show'])->name('boards.show');
});

// API routes
Route::middleware('auth:api')->group(function () {
    // Board routes
    Route::post('/boards', [BoardController::class, 'store']);
    Route::put('/boards/{id}', [BoardController::class, 'update']);
    Route::delete('/boards/{id}', [BoardController::class, 'destroy']);

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
});
