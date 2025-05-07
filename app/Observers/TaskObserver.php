<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\CalendarEvent;

class TaskObserver
{
    public function created(Task $task)
    {
        if ($task->due_date) {
            CalendarEvent::create([
                'title' => $task->title,
                'description' => $task->description,
                'start' => $task->due_date,
                'end' => $task->due_date->addHour(),
                'all_day' => false,
                'color' => $this->getPriorityColor($task->priority),
                'user_id' => $task->creator_id,
            ]);
        }
    }

    public function updated(Task $task)
    {
        if ($task->isDirty('due_date')) {
            $event = CalendarEvent::where('title', $task->title)
                ->where('user_id', $task->creator_id)
                ->first();

            if ($event) {
                $event->update([
                    'start' => $task->due_date,
                    'end' => $task->due_date->addHour(),
                ]);
            } else {
                CalendarEvent::create([
                    'title' => $task->title,
                    'description' => $task->description,
                    'start' => $task->due_date,
                    'end' => $task->due_date->addHour(),
                    'all_day' => false,
                    'color' => $this->getPriorityColor($task->priority),
                    'user_id' => $task->creator_id,
                ]);
            }
        }
    }

    public function deleted(Task $task)
    {
        CalendarEvent::where('title', $task->title)
            ->where('user_id', $task->creator_id)
            ->delete();
    }

    private function getPriorityColor($priority)
    {
        return match ($priority) {
            'high' => '#ef4444', // red
            'medium' => '#f59e0b', // amber
            'low' => '#10b981', // emerald
            default => '#3b82f6', // blue
        };
    }
}
