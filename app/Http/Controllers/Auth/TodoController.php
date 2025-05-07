<?php
namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->get(); // Fetch user's todos
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Todo::create([
            'title' => $validated['title'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo created successfully!');
    }
}
