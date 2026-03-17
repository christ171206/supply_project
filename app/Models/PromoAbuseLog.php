<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoAbuseLog extends Model
{
    protected $fillable = [
        'user_id',
        'rule_id',
        'order_id',
        'violation_type',
        'details',
        'potential_loss',
        'action_taken',
        'admin_notes',
    ];

    protected $casts = [
        'details' => 'array',
        'potential_loss' => 'decimal:2',
    ];

    /**
     * Get the user who violated the rule
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the rule that was violated
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(PromoAbuseRule::class, 'rule_id');
    }

    /**
     * Get the related order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'order_id');
    }

    /**
     * Scope: Recent violations only
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Filter by violation type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('violation_type', $type);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get violation type label
     */
    public function getTypeLabel(): string
    {
        return match ($this->violation_type) {
            'attempted' => 'Tentativo détectée',
            'blocked' => 'Bloquée',
            'flagged' => 'Signalée',
            default => 'Inconnu',
        };
    }

    /**
     * Get action label
     */
    public function getActionLabel(): string
    {
        return match ($this->action_taken) {
            'none' => 'Aucune',
            'warning' => 'Avertissement',
            'blocked' => 'Bloquée',
            'manual_review' => 'Révision manuelle',
            default => 'Inconnu',
        };
    }
}
