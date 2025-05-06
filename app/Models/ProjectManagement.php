<?php

namespace App\Models;

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
        'is_active'
    ];

    // Relationship with owner (User)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship with tasks
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
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
}
