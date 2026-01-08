<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'categorie_id',
        'user_id',
        'nom',
        'slug',
        'description',
        'prix',
        'stock',
        'stock_minimum',
        'est_actif',
        'image',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function panierItems()
    {
        return $this->hasMany(PanierItem::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(StockMouvement::class);
    }

    // Méthode pour vérifier si le stock est critique
    public function isStockCritique()
    {
        return $this->stock <= $this->stock_minimum;
    }

    // Méthode pour enregistrer un mouvement de stock
    public function enregistrerMouvement($type, $quantite, $motif, $user_id, $commande_id = null, $note = null)
    {
        return StockMouvement::create([
            'produit_id' => $this->id,
            'type' => $type,
            'quantite' => $quantite,
            'motif' => $motif,
            'user_id' => $user_id,
            'commande_id' => $commande_id,
            'note' => $note,
        ]);
    }
}
