<?php

namespace App\Http\Controllers;

use App\Models\ProjectManagement;
use Illuminate\Http\Request;
use App\Models\Task;
class ProjectManagementController extends Controller
{
    public function index()
    {
        $projects = ProjectManagement::where('owner_id', auth()->id())->get();
        return inertia('ProjectManagement/Index', compact('projects'));
    }

    public function create()
    {
        return inertia('ProjectManagement/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        ProjectManagement::create([
            ...$validated,
            'owner_id' => auth()->id()
        ]);

        return redirect()->route('ProjectManagement.index')
            ->with('success', 'Project created successfully!');
    }

    public function show(ProjectManagement $projectManagement)
    {
        $projectManagement->load(['tasks', 'owner', 'members', 'activities']);

        $availableTasks = Task::where('creator_id', auth()->id())
            ->whereDoesntHave('project', function($query) use ($projectManagement) {
                $query->where('project_id', $projectManagement->id);
            })
            ->get();

        return inertia('ProjectManagement/Show', [
            'project' => $projectManagement,
            'availableTasks' => $availableTasks
        ]);
    }

    // Add other methods (edit, update, destroy) as needed

    public function edit(ProjectManagement $projectManagement)
    {
        return inertia('ProjectManagement/Edit', compact('projectManagement'));
    }

    public function update(Request $request, ProjectManagement $projectManagement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $projectManagement->update($validated);

        return redirect()->route('ProjectManagement.show', $projectManagement)
            ->with('success', 'Project updated successfully!');
    }

    // In app/Models/ProjectManagement.php
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }



    public function addTasks(Request $request, ProjectManagement $projectManagement)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id', // Validate that each ID exists in the tasks table
        ]);

        // Logic to associate tasks with the project
        foreach ($validated['task_ids'] as $taskId) {
            $task = Task::find($taskId);
            $task->project_id = $projectManagement->id; // Assuming you have a project_id column in the tasks table
            $task->save();
        }

        return back()->with('success', 'Tasks added to project successfully!');
    }
}
