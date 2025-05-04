<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $tasks = Task::where('user_id', $userId)->get();
        $pendingTasksCount = $tasks->where('status', 'pending')->count();

        $events = Event::where('user_id', $userId)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->get();
        $upcomingEventsCount = $events->count();

        $projects = Project::where(function ($query) use ($userId) {
            $query->where('owner_id', $userId)
                ->orWhereHas('members', fn ($q) => $q->where('user_id', $userId));
        })
            ->where('is_active', true)
            ->get();
        $activeProjectsCount = $projects->count();

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
            'events' => $events,
            'projects' => $projects,
            'pendingTasksCount' => $pendingTasksCount,
            'upcomingEventsCount' => $upcomingEventsCount,
            'activeProjectsCount' => $activeProjectsCount,
        ]);
    }

    public function create()
    {
        return Inertia::render('project-management/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'owner_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => true,
        ]);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load([
            'tasks',
            'owner',
            'members',
            'activities' => function($query) {
                $query->latest()->take(10);
            },
            'activities.user'
        ]);

        return Inertia::render('project-management/Show', [
            'project' => $project,
        ]);
    }
}
