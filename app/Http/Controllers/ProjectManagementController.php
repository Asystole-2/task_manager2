<?php

namespace App\Http\Controllers;

use App\Models\ProjectManagement;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{
    public function index()
    {
        $projects = ProjectManagement::where('owner_id', auth()->id())->get();
        return inertia('ProjectManagement/Index', ['projects' => $projects]);
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
        $projectManagement->load([
            'owner',
            'tasks',
            'members',
            'activities' => function($query) {
                $query->latest()->limit(10);
            },
            'activities.user'
        ]);

        return inertia('ProjectManagement/Show', [
            'project' => $projectManagement
        ]);
    }

    public function edit(ProjectManagement $projectManagement)
    {
        $projectManagement->load(['owner', 'tasks', 'members']);
        return inertia('ProjectManagement/Edit', ['project' => $projectManagement]);
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

    public function addMember(Request $request, ProjectManagement $projectManagement)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|max:255',
        ]);

        // Prevent duplicate members
        if ($projectManagement->members()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()->with('error', 'User is already a member of this project');
        }

        $projectManagement->members()->attach($request->user_id, [
            'role' => $request->role ?? 'member'
        ]);

        return redirect()->back()->with('success', 'Member added successfully!');
    }

    public function removeMember(ProjectManagement $projectManagement, User $user)
    {
        $projectManagement->members()->detach($user->id);
        return redirect()->back()->with('success', 'Member removed successfully!');
    }

    public function availableMembers(ProjectManagement $projectManagement)
    {
        $currentMemberIds = $projectManagement->members()->pluck('users.id');
        $availableUsers = User::whereNotIn('id', $currentMemberIds)
            ->where('id', '!=', $projectManagement->owner_id)
            ->get(['id', 'name', 'email']);

        return response()->json($availableUsers);
    }

    public function destroy(ProjectManagement $projectManagement)
    {
        $projectManagement->delete();
        return redirect()->route('ProjectManagement.index')
            ->with('success', 'Project deleted successfully!');
    }
}
