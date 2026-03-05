<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
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
