<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'numero',
        'total',
        'statut',
        'payment_method',
        'mode_paiement',
        'paiement_confirme',
        'adresse_livraison',
        'adresse_detail',
        'telephone_livraison',
        'quartier_id',
        'pays',
        'notes',
        'delivery_zone_id',
        'delivery_status',
        'expected_delivery_date',
    ];

    protected $casts = [
        'paiement_confirme' => 'boolean',
    ];

    /**
     * Générer automatiquement le numéro de commande
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->numero)) {
                // Générer un numéro unique : CMD + date + random
                // Format : CMD-2026031100123 (CMD-YYYYMMDDxxxxx)
                $timestamp = now()->format('YmdHis');
                $random = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $model->numero = 'CMD-' . $timestamp . $random;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function deliveryTracking()
    {
        return $this->hasMany(DeliveryTracking::class);
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }
}
