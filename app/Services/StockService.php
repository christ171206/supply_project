<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\StockMouvement;
use App\Models\Commande;

class StockService
{
    /**
     * Enregistre une sortie de stock (réduction)
     */
    public function diminuerStock(Produit $produit, int $quantite, string $motif = 'manuel', $user_id = null, $commande_id = null)
    {
        // Vérifier que le stock est suffisant
        if ($produit->stock < $quantite) {
            throw new \Exception("Stock insuffisant pour {$produit->nom}. Disponible: {$produit->stock}, Demandé: {$quantite}");
        }

        // Diminuer le stock
        $produit->decrement('stock', $quantite);

        // Enregistrer le mouvement
        $produit->enregistrerMouvement(
            'sortie',
            $quantite,
            $motif,
            $user_id ?? auth()->id(),
            $commande_id
        );

        return $produit;
    }

    /**
     * Enregistre une entrée de stock (augmentation)
     */
    public function augmenterStock(Produit $produit, int $quantite, string $motif = 'réapprovisionnement', $user_id = null)
    {
        // Augmenter le stock
        $produit->increment('stock', $quantite);

        // Enregistrer le mouvement
        $produit->enregistrerMouvement(
            'entrée',
            $quantite,
            $motif,
            $user_id ?? auth()->id()
        );

        return $produit;
    }

    /**
     * Traiter les mouvements de stock d'une commande validée
     */
    public function traiterValidationCommande(Commande $commande)
    {
        foreach ($commande->ligneCommandes as $ligne) {
            $this->diminuerStock(
                $ligne->produit,
                $ligne->quantite,
                'commande',
                auth()->id(),
                $commande->id
            );
        }
    }

    /**
     * Annuler les mouvements de stock d'une commande
     */
    public function annulerCommandeStock(Commande $commande)
    {
        foreach ($commande->ligneCommandes as $ligne) {
            $this->augmenterStock(
                $ligne->produit,
                $ligne->quantite,
                'annulation_commande',
                auth()->id()
            );
        }
    }

    /**
     * Obtenir les produits en stock critique pour un vendeur
     */
    public function getProduitsStockCritique($user_id)
    {
        return Produit::where('user_id', $user_id)
            ->whereRaw('stock <= stock_minimum')
            ->get();
    }

    /**
     * Obtenir l'historique de stock d'un produit
     */
    public function getHistoriqueStock(Produit $produit, $limit = 50)
    {
        return $produit->mouvementsStock()
            ->with(['user', 'commande'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir l'historique de stock pour tous les produits d'un vendeur
     */
    public function getHistoriqueVendeur($user_id, $limit = 100)
    {
        return StockMouvement::whereHas('produit', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->with(['produit', 'user', 'commande'])
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
    }
}
