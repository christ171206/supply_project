<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'description',
        'prix_bundle',
        'prix_original',
        'date_debut',
        'date_fin',
        'quantite_disponible',
        'quantite_vendues',
        'statut',
        'archive',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'archive' => 'boolean',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'bundle_produits')
            ->withPivot('quantite')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')
            ->where('archive', false)
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now());
    }

    public function scopeDisponible($query)
    {
        return $query->actif()
            ->where(function ($q) {
                $q->whereNull('quantite_disponible')
                    ->orWhereRaw('quantite_disponible > quantite_vendues');
            });
    }

    // Methods
    public function isActive()
    {
        return $this->statut === 'actif'
            && !$this->archive
            && $this->date_debut <= now()
            && $this->date_fin >= now();
    }

    public function isDisponible()
    {
        return $this->isActive()
            && (!$this->quantite_disponible || $this->quantite_vendues < $this->quantite_disponible);
    }

    public function pourcentageEconomie()
    {
        if (!$this->prix_original) return 0;
        return round((($this->prix_original - $this->prix_bundle) / $this->prix_original) * 100);
    }

    public function joursRestants()
    {
        if (!$this->isActive()) return null;
        return now()->diffInDays($this->date_fin);
    }

    public function heuresRestantes()
    {
        if (!$this->isActive()) return null;
        return now()->diffInHours($this->date_fin);
    }

    public function getPrixTotalOriginal()
    {
        return $this->produits->sum(function ($p) {
            return $p->pivot->quantite * $p->prix;
        });
    }
}
