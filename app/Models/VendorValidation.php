<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorValidation extends Model
{
    protected $fillable = [
        'user_id',
        'business_description',
        'business_registration',
        'business_document',
        'business_phone',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur vendeur
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'admin qui a examiné
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approuver la validation
     */
    public function approve($adminId, $notes = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'reviewed_by' => $adminId,
            'review_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Rejeter la validation
     */
    public function reject($adminId, $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'review_notes' => $reason,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Vérifier si c'est approuvé
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
