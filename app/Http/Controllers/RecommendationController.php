<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\LigneCommande;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    /**
     * Obtenir les produits similaires (même catégorie)
     */
    public function getSimilarProducts($productId)
    {
        $produit = Produit::findOrFail($productId);

        // Produits de la même catégorie, actifs
        $similarProducts = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $productId)
            ->where('est_actif', true)
            ->with('vendeur', 'avis')
            ->limit(8)
            ->get();

        // Calculer les notes moyennes
        $similar = $similarProducts->map(function ($p) {
            $avgRating = $p->avis->pluck('note')->avg() ?? 0;
            return [
                'id' => $p->id,
                'nom' => $p->nom,
                'prix' => $p->prix,
                'image' => $p->image,
                'vendeur_nom' => $p->vendeur->shop_name ?? $p->vendeur->name,
                'note' => round($avgRating, 1),
                'nombre_avis' => $p->avis->count(),
                'stock' => $p->stock,
            ];
        })->toArray();

        return response()->json([
            'type' => 'similar',
            'titre' => 'Produits similaires',
            'produits' => $similar,
        ]);
    }

    /**
     * Produits populaires dans la catégorie
     */
    public function getPopularInCategory($productId)
    {
        $produit = Produit::findOrFail($productId);

        // Produits les plus vendus dans la catégorie
        $popularProducts = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $productId)
            ->where('est_actif', true)
            ->with('ligneCommandes', 'avis', 'vendeur')
            ->get()
            ->map(function ($p) {
                $p->ventes_count = $p->ligneCommandes->count();
                $p->avg_rating = $p->avis->pluck('note')->avg() ?? 0;
                return $p;
            })
            ->sortByDesc('ventes_count')
            ->take(8);

        $popular = $popularProducts->map(fn($p) => [
            'id' => $p->id,
            'nom' => $p->nom,
            'prix' => $p->prix,
            'image' => $p->image,
            'vendeur_nom' => $p->vendeur->shop_name ?? $p->vendeur->name,
            'note' => round($p->avg_rating, 1),
            'nombre_avis' => $p->avis->count(),
            'stock' => $p->stock,
            'ventes' => $p->ventes_count,
        ])->toArray();

        return response()->json([
            'type' => 'popular',
            'titre' => 'Populaire dans cette catégorie',
            'produits' => $popular,
        ]);
    }

    /**
     * Produits souvent achetés ensemble
     */
    public function getFrequentlyBoughtTogether($productId)
    {
        $produit = Produit::findOrFail($productId);

        // Trouver les commandes contenant ce produit
        $commandeIds = LigneCommande::where('produit_id', $productId)
            ->pluck('commande_id')
            ->toArray();

        // Trouver les produits achetés dans les mêmes commandes
        $boughtTogether = LigneCommande::whereIn('commande_id', $commandeIds)
            ->where('produit_id', '!=', $productId)
            ->selectRaw('produit_id, COUNT(*) as frequency')
            ->groupBy('produit_id')
            ->orderByDesc('frequency')
            ->with('produit.avis', 'produit.vendeur')
            ->limit(8)
            ->get()
            ->map(function ($lc) {
                $p = $lc->produit;
                $avgRating = $p->avis->pluck('note')->avg() ?? 0;
                return [
                    'id' => $p->id,
                    'nom' => $p->nom,
                    'prix' => $p->prix,
                    'image' => $p->image,
                    'vendeur_nom' => $p->vendeur->shop_name ?? $p->vendeur->name,
                    'note' => round($avgRating, 1),
                    'nombre_avis' => $p->avis->count(),
                    'stock' => $p->stock,
                    'frequency' => $lc->frequency,
                ];
            })
            ->toArray();

        return response()->json([
            'type' => 'bought_together',
            'titre' => 'Les clients ont aussi acheté',
            'produits' => $boughtTogether,
        ]);
    }

    /**
     * Recommandations personnalisées pour un utilisateur (basé sur l'historique)
     */
    public function getPersonalizedRecommendations()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['produits' => []]);
        }

        // Obtenir les catégories que l'utilisateur a achetées
        $userCategories = LigneCommande::whereHas('commande', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->pluck('produits.categorie_id')
            ->unique()
            ->toArray();

        if (empty($userCategories)) {
            return response()->json(['produits' => []]);
        }

        // Récupérer les produits populaires des catégories intéressantes
        $recommendations = Produit::whereIn('categorie_id', $userCategories)
            ->where('est_actif', true)
            ->with('ligneCommandes', 'avis', 'vendeur')
            ->get()
            ->map(function ($p) {
                $p->ventes_count = $p->ligneCommandes->count();
                $p->avg_rating = $p->avis->pluck('note')->avg() ?? 0;
                return $p;
            })
            ->sortByDesc('ventes_count')
            ->take(8);

        $recos = $recommendations->map(fn($p) => [
            'id' => $p->id,
            'nom' => $p->nom,
            'prix' => $p->prix,
            'image' => $p->image,
            'vendeur_nom' => $p->vendeur->shop_name ?? $p->vendeur->name,
            'note' => round($p->avg_rating, 1),
            'nombre_avis' => $p->avis->count(),
            'stock' => $p->stock,
        ])->toArray();

        return response()->json([
            'type' => 'personalized',
            'titre' => '✨ Recommandé pour vous',
            'produits' => $recos,
        ]);
    }

    /**
     * Trending products globales (tous les utilisateurs)
     */
    public function getTrendingProducts()
    {
        // Produits les plus vendus les 7 derniers jours
        $sevenDaysAgo = now()->subDays(7);

        $trending = Produit::where('est_actif', true)
            ->with(['ligneCommandes' => function ($q) use ($sevenDaysAgo) {
                $q->whereHas('commande', function ($subQ) use ($sevenDaysAgo) {
                    $subQ->where('created_at', '>=', $sevenDaysAgo);
                });
            }, 'avis', 'vendeur'])
            ->get()
            ->map(function ($p) {
                $recentSales = $p->ligneCommandes->count();
                $p->recent_sales = $recentSales;
                $p->avg_rating = $p->avis->pluck('note')->avg() ?? 0;
                return $p;
            })
            ->where('recent_sales', '>', 0)
            ->sortByDesc('recent_sales')
            ->take(8);

        $products = $trending->map(fn($p) => [
            'id' => $p->id,
            'nom' => $p->nom,
            'prix' => $p->prix,
            'image' => $p->image,
            'vendeur_nom' => $p->vendeur->shop_name ?? $p->vendeur->name,
            'note' => round($p->avg_rating, 1),
            'nombre_avis' => $p->avis->count(),
            'stock' => $p->stock,
            'badge' => '🔥 Tendance',
        ])->toArray();

        return response()->json([
            'type' => 'trending',
            'titre' => '🔥 En tendance maintenant',
            'produits' => $products,
        ]);
    }
}
