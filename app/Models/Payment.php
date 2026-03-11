<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'idPayment';
    protected $fillable = [
        'commande_id',
        'montant',
        'typePayement',
        'statut',
        'payment_code',
        'provider_transaction_id',
        'payment_status',
        'response_data',
        'payment_initiated_at',
        'payment_confirmed_at',
        // Colonnes Stripe
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'stripe_response',
        'payment_type',
        'stripe_status',
        'stripe_webhook_received_at',
        'idempotency_key',
    ];

    protected $casts = [
        'stripe_response' => 'array',
        'response_data' => 'array',
        'payment_initiated_at' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'stripe_webhook_received_at' => 'datetime',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}

