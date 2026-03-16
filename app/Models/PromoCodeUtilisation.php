<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUtilisation extends Model
{
    protected $table = 'promo_code_utilisations';

    protected $fillable = [
        'promo_code_id',
        'commande_id',
        'user_id',
        'montant_reduction',
    ];

    protected $casts = [
        'montant_reduction' => 'decimal:2',
    ];

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
