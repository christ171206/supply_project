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
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
