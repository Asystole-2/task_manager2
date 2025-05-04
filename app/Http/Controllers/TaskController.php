<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Task::where('creator_id', auth()->id())
            ->orWhere('assignee_id', auth()->id())
            ->with(['creator', 'assignee']); // Eager load relationships

        // Sorting
        $sortBy = $request->input('sort_by', 'due_date');
        $sortDirection = $request->input('sort_direction', 'asc');

        if (in_array($sortBy, ['title', 'priority', 'due_date', 'status'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Pagination
        $tasks = $query->paginate(10); // Adjust per page as needed

        return Inertia::render('Dashboard', [
            'tasks' => $tasks->items(),

            'filters' => $request->only(['sort_by', 'sort_direction']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'],
            'creator_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task created successfully!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()
            ->back()
            ->with('success', 'Task deleted successfully!');
    }

    // Add to TaskController.php
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task status updated!');
    }
}
