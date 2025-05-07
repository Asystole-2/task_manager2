<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ProjectManagement;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $activities = Activity::with(['user', 'task', 'project'])
            ->latest()
            ->take(20)
            ->get();

        $projects = ProjectManagement::where('owner_id', $userId)->get();
        $tasks = Task::where('creator_id', $userId)
            ->orWhere('assignee_id', $userId)
            ->get();

        return inertia('Activity/Index', [
            'activities' => $activities,
            'projects' => $projects,
            'tasks' => $tasks
        ]);
    }
}
