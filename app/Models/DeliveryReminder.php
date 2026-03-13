<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryReminder extends Model
{
    protected $fillable = [
        'commande_id',
        'user_id',
        'status',
        'days_before',
        'scheduled_for',
        'sent_at',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Relation avec la commande
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Relation avec l'utilisateur (client)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupérer les rappels en attente d'envoi
     */
    public static function getPendingReminders()
    {
        return self::where('status', 'pending')
            ->where('scheduled_for', '<=', now())
            ->with(['commande', 'user'])
            ->orderBy('scheduled_for', 'asc')
            ->get();
    }

    /**
     * Marquer comme envoyé
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'retry_count' => 0,
        ]);
    }

    /**
     * Marquer comme échoué
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->retry_count++;

        // Abandonner après 3 tentatives
        if ($this->retry_count >= 3) {
            $this->update([
                'status' => 'failed',
                'error_message' => $errorMessage,
            ]);
        } else {
            // Réessayer dans 1 heure
            $this->update([
                'scheduled_for' => now()->addHour(),
                'error_message' => $errorMessage,
            ]);
        }
    }
}
