<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectManagement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'start_date',
        'end_date',
        'is_active',
        'owner_id'
    ];

    // Relationship with owner (User)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship with tasks
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    // Relationship with team members (Users)
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    // Relationship with activities
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
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
