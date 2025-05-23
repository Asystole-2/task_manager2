<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ProjectManagement;
use App\Models\Task;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

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
        $events = CalendarEvent::where('user_id', $userId)->get();

        return inertia('Activity/Index', [
            'activities' => $activities,
            'projects' => $projects,
            'tasks' => $tasks,
            'events' => $events
        ]);
    }

    public function destroyActivity(Activity $activity)
    {
        $activity->delete();
        return redirect()->back()->with('success', 'Activity deleted successfully');
    }

    public function destroyProject(ProjectManagement $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully');
    }

    public function destroyTask(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully');
    }

    public function destroyEvent(CalendarEvent $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully');
    }
}
