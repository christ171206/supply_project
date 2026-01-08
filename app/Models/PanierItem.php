<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanierItem extends Model
{
    protected $table = 'panier_items';
    protected $fillable = [
        'panier_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
