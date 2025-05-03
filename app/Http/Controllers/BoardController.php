<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'boards' => auth()->user()->boards()->with('columns.cards')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|string'
        ]);

        $board = auth()->user()->boards()->create($validated);

        // Create default columns
        $defaultColumns = ['To Do', 'In Progress', 'Done'];
        foreach ($defaultColumns as $index => $title) {
            $board->columns()->create([
                'title' => $title,
                'order' => $index
            ]);
        }

        return response()->json($board->load('columns'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $board = Board::with(['columns' => function($query) {
            $query->orderBy('order')->with(['cards' => function($query) {
                $query->orderBy('order');
            }]);
        }])->findOrFail($id);

        $this->authorize('view', $board);

        return response()->json($board);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $board = Board::findOrFail($id);
        $this->authorize('update', $board);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'cover_image' => 'nullable|string'
        ]);

        $board->update($validated);

        return response()->json($board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $board = Board::findOrFail($id);
        $this->authorize('delete', $board);

        $board->delete();

        return response()->json(null, 204);
    }

    // In BoardController.php
    public function create()
    {
        return Inertia::render('Boards/Create'); // You'll need to create this Vue component
    }
}
