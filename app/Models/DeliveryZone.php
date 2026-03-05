<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeliveryZone extends Model
{
    protected $fillable = ['name', 'delivery_fee', 'delivery_days', 'is_active', 'description'];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function quartiers(): BelongsToMany
    {
        return $this->belongsToMany(Quartier::class, 'delivery_zone_quartiers');
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
}
