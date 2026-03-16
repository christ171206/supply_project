<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendeurShippingConfig extends Model
{
    protected $fillable = [
        'user_id',
        'montant_minimum_gratuit',
        'frais_base',
        'gratuit_active',
    ];

    protected $casts = [
        'gratuit_active' => 'boolean',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
