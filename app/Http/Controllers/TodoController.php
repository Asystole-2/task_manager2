<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'todos' => Todo::where('user_id', auth()->id())
                ->latest()
                ->get(),
            // Include your other dashboard props here
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Todo::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'completed' => false
        ]);

        return redirect()->back()->with('success', 'Todo added successfully!');
    }

    public function update(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $todo->update([
            'completed' => $request->completed
        ]);

        return redirect()->back()->with('success', 'Todo updated!');
    }

    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return redirect()->back()->with('success', 'Todo deleted!');
    }
}
