<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    protected $fillable = [
        'produit_id',
        'alert_threshold',
        'reorder_quantity',
        'is_active',
        'last_alert_sent',
    ];

    protected $dates = ['last_alert_sent'];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Vérifier si le stock est sous le seuil d'alerte
     */
    public function isStockBelowThreshold(): bool
    {
        return $this->produit->stock <= $this->alert_threshold;
    }
}
