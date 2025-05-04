<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: accessors for UI
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->user->name ?? 'U', 0, 1));
    }

    public function getTimeAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
