<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project)
    {
        Activity::create([
            'description' => "Created project: {$project->name}",
            'user_id' => auth()->id(),
            'project_id' => $project->id,
            'properties' => [
                'project_name' => $project->name,
                'status' => $project->is_active ? 'Active' : 'Inactive',
            ]
        ]);
    }

    public function updated(Project $project)
    {
        // Only log meaningful changes
        if ($project->isDirty(['name', 'description', 'is_active'])) {
            Activity::create([
                'description' => "Updated project: {$project->name}",
                'user_id' => auth()->id(),
                'project_id' => $project->id,
                'properties' => [
                    'changes' => $project->getChanges(),
                    'original' => $project->getOriginal()
                ]
            ]);
        }
    }

    public function deleted(Project $project)
    {
        Activity::create([
            'description' => "Deleted project: {$project->name}",
            'user_id' => auth()->id(),
            'properties' => [
                'project_name' => $project->name,
                'status' => $project->is_active ? 'Active' : 'Inactive',
            ]
        ]);
    }
}
