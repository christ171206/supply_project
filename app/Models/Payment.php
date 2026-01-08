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
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
