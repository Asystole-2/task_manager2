<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'project_management_id',
        'creator_id',
        'assignee_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public const STATUS_STANDBY = 'standby';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_DONE = 'done';

    public static function statuses(): array
    {
        return [
            self::STATUS_STANDBY => 'Standby',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_DONE => 'Done',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function projectManagement(): BelongsTo
    {
        return $this->belongsTo(ProjectManagement::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function getTimeRemainingAttribute(): ?array
    {
        if (!$this->due_date) {
            return null;
        }

        $now = now();
        $due = $this->due_date;

        if ($due <= $now) {
            return null;
        }

        return [
            'days' => $now->diffInDays($due),
            'hours' => $now->diffInHours($due) % 24,
            'minutes' => $now->diffInMinutes($due) % 60,
        ];
    }
}
