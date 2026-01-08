<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $primaryKey = 'idPromotion';
    protected $fillable = [
        'produit_id',
        'dateDebut',
        'dateFin',
        'reduction',
        'statut',
        'archive',
    ];

    protected $casts = [
        'dateDebut' => 'datetime',
        'dateFin' => 'datetime',
        'archive' => 'boolean',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
