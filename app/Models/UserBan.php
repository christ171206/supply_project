<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBan extends Model
{
    protected $fillable = [
        'user_id',
        'reason',
        'details',
        'is_active',
        'banned_at',
        'unbanned_at',
        'banned_by',
        'unbanned_by',
    ];

    protected $dates = ['banned_at', 'unbanned_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function unbannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unbanned_by');
    }

    /**
     * Débannir un utilisateur
     */
    public function unban(User $admin): void
    {
        $this->update([
            'is_active' => false,
            'unbanned_at' => now(),
            'unbanned_by' => $admin->id,
        ]);

        $this->user->update([
            'is_banned' => false,
            'banned_until' => null,
        ]);
    }
}
