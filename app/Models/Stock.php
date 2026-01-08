<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $primaryKey = 'idStock';
    protected $fillable = [
        'produit_id',
        'quantite',
        'dateUpdate',
    ];

    protected $casts = [
        'dateUpdate' => 'datetime',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
