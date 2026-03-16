<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoints extends Model
{
    protected $fillable = [
        'user_id',
        'total_points',
        'this_month',
        'level',
        'tier',
        'breakdown',
        'last_activity',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'last_activity' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTierColor()
    {
        return match ($this->tier) {
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'platinum' => '#E5E4E2',
            default => '#888888',
        };
    }

    public function getTierEmoji()
    {
        return match ($this->tier) {
            'bronze' => '🥉',
            'silver' => '🥈',
            'gold' => '🥇',
            'platinum' => '👑',
            default => '⭐',
        };
    }
}
