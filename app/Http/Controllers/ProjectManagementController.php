<?php

namespace App\Http\Controllers;

use App\Models\ProjectManagement;
use Illuminate\Http\Request;

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

        return redirect()->route('project-management.index')
            ->with('success', 'Project created successfully!');
    }

    public function show(ProjectManagement $projectManagement)
    {
        $projectManagement->load(['owner', 'tasks', 'members']);
        return inertia('ProjectManagement/Show', compact('projectManagement'));
    }

    // Add other methods (edit, update, destroy) as needed
}
