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
        'notes',
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
}
