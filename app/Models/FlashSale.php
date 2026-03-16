<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlashSale extends Model
{
    protected $fillable = [
        'user_id',
        'categorie_id',
        'pourcentage_reduction',
        'date_debut',
        'date_fin',
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

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function produits()
    {
        return $this->categorie->produits();
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

    // Methods
    public function isActive()
    {
        return $this->statut === 'actif'
            && !$this->archive
            && $this->date_debut <= now()
            && $this->date_fin >= now();
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

    public function prixReduit($prixOriginal)
    {
        return $prixOriginal * (1 - $this->pourcentage_reduction / 100);
    }
}
