<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('creator_id', auth()->id())
            ->orWhere('assignee_id', auth()->id())
            ->with(['creator', 'assignee']);

        // Sorting
        $sortBy = $request->input('sort_by', 'due_date');
        $sortDirection = $request->input('sort_direction', 'asc');

        if ($sortBy === 'priority') {
            $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
            $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low') {$sortDirection}");
        } else if (in_array($sortBy, ['title', 'due_date', 'status'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $tasks = $query->get();
        $pendingTasksCount = Task::where(function($query) {
            $query->where('creator_id', auth()->id())
                ->orWhere('assignee_id', auth()->id());
        })->where('status', 'pending')->count();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'due_date' => $task->due_date,
                    'days_left' => $task->due_date ? now()->diffInDays($task->due_date, false) : null,
                    'creator' => $task->creator,
                    'assignee' => $task->assignee,
                ];
            }),
            'filters' => $request->only(['sort_by', 'sort_direction']),
            'pendingTasksCount' => $pendingTasksCount,
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

    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task->load(['creator', 'assignee'])
        ]);
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()
            ->back()
            ->with('success', 'Task deleted successfully!');
    }
}
