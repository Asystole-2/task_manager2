<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Board $board)
    {
        $this->authorize('view', $board);

        return response()->json([
            'columns' => $board->columns()->orderBy('order')->with('cards')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $order = $board->columns()->max('order') + 1;

        $column = $board->columns()->create(array_merge($validated, [
            'order' => $order
        ]));

        return response()->json($column, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Column $column)
    {
        $this->authorize('view', $column->board);

        return response()->json($column->load('cards'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Column $column)
    {
        $this->authorize('update', $column->board);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255'
        ]);

        $column->update($validated);

        return response()->json($column);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Column $column)
    {
        $this->authorize('delete', $column->board);

        $column->delete();

        // Reorder remaining columns
        $columns = $column->board->columns()->orderBy('order')->get();
        foreach ($columns as $index => $col) {
            $col->update(['order' => $index]);
        }

        return response()->json(null, 204);
    }

    /**
     * Handle column reordering
     */
    public function move(Column $column, Request $request)
    {
        $this->authorize('update', $column->board);

        $request->validate([
            'newOrder' => 'required|integer'
        ]);

        $oldOrder = $column->order;
        $newOrder = $request->newOrder;

        if ($oldOrder < $newOrder) {
            // Moving down
            $column->board->columns()
                ->where('order', '>', $oldOrder)
                ->where('order', '<=', $newOrder)
                ->decrement('order');
        } else {
            // Moving up
            $column->board->columns()
                ->where('order', '>=', $newOrder)
                ->where('order', '<', $oldOrder)
                ->increment('order');
        }

        $column->update(['order' => $newOrder]);

        return response()->json(['message' => 'Column moved successfully']);
    }
}
