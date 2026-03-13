<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMouvement extends Model
{
    protected $fillable = [
        'produit_id',
        'type',
        'quantity',
        'reason',
        'previous_stock',
        'new_stock',
        'notes',
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
