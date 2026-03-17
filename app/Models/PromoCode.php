<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PromoCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'description',
        'type_reduction',
        'taux_reduction',
        'max_utilisations',
        'utilisations',
        'montant_minimum',
        'montant_maximum',
        'limit_per_user',
        'date_debut',
        'date_fin',
        'statut',
        'archive',
        'type_distribution',
        'assigned_by',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'archive' => 'boolean',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'promo_code_produit');
    }

    public function utilisations()
    {
        return $this->hasMany(PromoCodeUtilisation::class);
    }

    public function clientCoupons()
    {
        return $this->hasMany(ClientCoupon::class, 'promo_code_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')
            ->where('archive', false)
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now());
    }

    public function scopeInactif($query)
    {
        return $query->where('statut', 'inactif')
            ->orWhere('archive', true);
    }

    public function scopeExpire($query)
    {
        return $query->where('date_fin', '<', now());
    }

    // Méthodes utilitaires
    public function isActive()
    {
        return $this->statut === 'actif'
            && !$this->archive
            && $this->date_debut <= now()
            && $this->date_fin >= now();
    }

    public function canBeUsed()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->max_utilisations && $this->utilisations >= $this->max_utilisations) {
            return false;
        }

        return true;
    }

    public function calculerReduction($montant_panier)
    {
        if (!$this->canBeUsed()) {
            return 0;
        }

        // Vérifier le montant minimum
        if ($this->montant_minimum && $montant_panier < $this->montant_minimum) {
            return 0;
        }

        $reduction = 0;

        if ($this->type_reduction === 'pourcentage') {
            $reduction = ($montant_panier * $this->taux_reduction) / 100;
        } else {
            $reduction = $this->taux_reduction;
        }

        // Appliquer le plafond si défini
        if ($this->montant_maximum && $reduction > $this->montant_maximum) {
            $reduction = $this->montant_maximum;
        }

        return $reduction;
    }

    public function marquerCommeUtilisee()
    {
        $this->increment('utilisations');
    }

    public function pourcentageUtilisation()
    {
        if (!$this->max_utilisations) {
            return null;
        }

        return round(($this->utilisations / $this->max_utilisations) * 100, 2);
    }

    public function joursRestants()
    {
        $remaining = now()->diffInDays($this->date_fin);
        return $remaining > 0 ? $remaining : 0;
    }
}
