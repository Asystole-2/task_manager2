<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard
     */
    public function index()
    {
        return Inertia::render('Dashboard', [
            'boards' => auth()->user()->boards()
                ->withCount('tasks')
                ->latest()
                ->get(),
            'totalTasks' => auth()->user()->tasks()->count(),
            'upcomingDeadlines' => auth()->user()->boards()
                ->where('due_date', '>=', now())
                ->count()
        ]);
    }
}
