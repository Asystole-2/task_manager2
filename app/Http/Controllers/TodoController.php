<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Store a newly created todo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'boolean'
        ]);

        $todo = Todo::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'completed' => $request->completed ?? false
        ]);

        return response()->json($todo, 201);
    }

    /**
     * Update the specified todo.
     */
    public function update(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'completed' => 'sometimes|boolean'
        ]);

        $todo->update($request->only(['title', 'completed']));

        return response()->json($todo);
    }

    /**
     * Remove the specified todo.
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return response()->json(null, 204);
    }

    /**
     * Toggle the completed status of a todo.
     */
    public function toggleComplete(Todo $todo)
    {
        $this->authorize('update', $todo);

        $todo->update([
            'completed' => !$todo->completed
        ]);

        return response()->json($todo);
    }
}
