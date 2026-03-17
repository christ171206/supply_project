<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCoupon extends Model
{
    protected $fillable = [
        'user_id',
        'promo_code_id',
        'statut',
        'date_utilisee',
        'notes',
    ];

    protected $casts = [
        'date_utilisee' => 'datetime',
        'date_assignee' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')
            ->whereHas('promoCode', function ($q) {
                $q->where('date_fin', '>=', now())
                    ->where('date_debut', '<=', now());
            });
    }

    public function scopeUtilise($query)
    {
        return $query->where('statut', 'utilise');
    }

    public function scopeExpire($query)
    {
        return $query->where('statut', 'expire')
            ->orWhereHas('promoCode', function ($q) {
                $q->where('date_fin', '<', now());
            });
    }

    // Methods
    public function markAsUsed()
    {
        $this->update([
            'statut' => 'utilise',
            'date_utilisee' => now(),
        ]);
    }

    public function isExpired()
    {
        if ($this->statut === 'expire') {
            return true;
        }
        return $this->promoCode->date_fin < now();
    }

    public function isActive()
    {
        return $this->statut === 'actif'
            && $this->promoCode->date_fin >= now()
            && $this->promoCode->date_debut <= now();
    }
}
