<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Correct namespace for User model
use App\Models\Project;
use App\Models\Task;

class TaskManagerSeeder extends Seeder
{
    public function run()
    {
        // Create admin user first
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@taskmanager.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular users
        $users = User::factory(9)->create();
        $users->push($admin); // Include admin in the users collection

        // Create projects
        $projects = Project::factory(5)->create([
            'owner_id' => $admin->id
        ]);

        // Attach users to projects
        $projects->each(function ($project) use ($users) {
            $project->members()->attach(
                $users->random(rand(3, 7))->pluck('id')->toArray(),
                ['role' => 'member']
            );
            $project->members()->attach(
                $users->random(1)->first()->id,
                ['role' => 'manager']
            );
        });

        // Create tasks for each project
        $projects->each(function ($project) use ($users) {
            Task::factory(rand(5, 15))->create([
                'project_id' => $project->id,
                'creator_id' => $project->owner_id,
                'assignee_id' => $project->members()->inRandomOrder()->first()->id,
            ]);
        });
    }
}
