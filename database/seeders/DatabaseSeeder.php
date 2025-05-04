<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // Add this line
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Task::truncate();
        Project::truncate();
        CalendarEvent::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create a test user
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);

        // Create some tasks
        Task::create([
            'title' => 'Complete project setup',
            'description' => 'Set up the initial project structure and dependencies',
            'priority' => 'high',
            'creator_id' => $user->id,
            'due_date' => now()->addDays(3),
        ]);

        Task::firstOrCreate([
            'title' => 'Complete project setup',
        ], [
            'description' => 'Set up the initial project structure and dependencies',
            'priority' => 'high',
            'creator_id' => $user->id,
            'due_date' => now()->addDays(3),
            'project_id' => Project::firstOrCreate(['name' => 'Default Project'], [
                'owner_id' => $user->id,
            ])->id,
        ]);

        // Create some calendar events
        CalendarEvent::firstOrCreate([
            'title' => 'Team Meeting',
            'description' => 'Weekly team sync',
            'start' => now()->addDay()->setTime(10, 0),
            'end' => now()->addDay()->setTime(11, 0),
            'user_id' => $user->id,
            'color' => '#6a4c93', // Purple
        ]);

        CalendarEvent::create([
            'title' => 'Project Deadline',
            'description' => 'Final project submission',
            'start' => now()->addDays(7),
            'all_day' => true,
            'user_id' => $user->id,
            'color' => '#cc5500', // Burnt orange
        ]);
    }
}
