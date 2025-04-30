<?php

Route::middleware('auth:sanctum')->group(function () {
    // Boards
    Route::apiResource('boards', BoardController::class);

    // Columns with drag-and-drop support
    Route::apiResource('boards.columns', ColumnController::class)->shallow();
    Route::patch('/columns/{column}/move', [ColumnController::class, 'move']);

    // Cards with drag-and-drop support
    Route::apiResource('columns.cards', CardController::class)->shallow();
    Route::patch('/cards/{card}/move', [CardController::class, 'move']);
});
