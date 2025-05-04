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
        return $this->hasMany(Task::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }
}
