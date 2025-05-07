<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'project_management_id',
        'description',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectManagement(): BelongsTo
    {
        return $this->belongsTo(ProjectManagement::class);
    }
}
