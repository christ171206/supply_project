<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    protected $fillable = [
        'commande_id',
        'user_id',
        'vendor_id',
        'type',
        'description',
        'status',
        'admin_notes',
        'resolution',
        'resolution_amount',
        'resolved_by',
        'resolved_at',
    ];

    protected $dates = ['resolved_at'];

    protected $casts = [
        'resolution_amount' => 'decimal:2',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Résoudre le litige
     */
    public function resolve(User $admin, string $resolution, ?float $amount = null, string $notes = ''): void
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolution_amount' => $amount,
            'admin_notes' => $notes,
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);
    }
}
