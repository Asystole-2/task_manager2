<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['user', 'task', 'project'])
            ->latest()
            ->take(20)
            ->get();

        return inertia('Activity/Index', [
            'activities' => $activities
        ]);
    }
}
