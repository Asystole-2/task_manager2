<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;

class ActivityObserver
{
    public function created(Task $task)
    {
        Activity::create([
            'description' => "Created task: {$task->title}",
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'properties' => [
                'task_title' => $task->title,
                'priority' => $task->priority,
            ]
        ]);
    }

    public function updated(Task $task)
    {
        Activity::create([
            'description' => "Updated task: {$task->title}",
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'properties' => $task->getChanges()
        ]);
    }

    public function deleted(Task $task)
    {
        Activity::create([
            'description' => "Deleted task: {$task->title}",
            'user_id' => auth()->id(),
            'properties' => [
                'task_title' => $task->title,
                'priority' => $task->priority,
            ]
        ]);
    }
}
