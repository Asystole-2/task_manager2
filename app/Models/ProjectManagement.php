<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManagement extends Model
{
    use HasFactory;
    protected $table = 'project_managements';
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }
    public function addTasks(Request $request, ProjectManagement $projectManagement)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id,user_id,'.auth()->id()
        ]);

        // For one-to-many relationship
        Task::whereIn('id', $validated['task_ids'])
            ->update(['project_id' => $projectManagement->id]);

        return back()->with('success', 'Tasks added to project successfully!');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class); // Replace Activity::class with the correct model
    }
}
