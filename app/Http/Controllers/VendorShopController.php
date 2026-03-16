<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produit;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorShopController extends Controller
{
    /**
     * Afficher la boutique publique d'un vendeur
     */
    public function show($vendorId)
    {
        // Obtenir le vendeur avec ses données
        $vendor = User::where('id', $vendorId)
            ->where('role', 'vendeur')
            ->where('vendor_status', 'approved')
            ->firstOrFail();

        // Vérifier que c'est un vendeur approuvé
        if (!$vendor->vendor_approved_at) {
            abort(404, 'Cette boutique n\'est pas disponible');
        }

        // Produits du vendeur (actifs seulement)
        $products = Produit::where('user_id', $vendorId)
            ->where('est_actif', true)
            ->with('categorie', 'avis')
            ->paginate(12);

        // Enrichir les produits avec les avis
        $products->getCollection()->transform(function ($p) {
            $p->avg_rating = $p->avis()->avg('note') ?? 0;
            $p->review_count = $p->avis()->count();
            return $p;
        });

        // Statistiques du vendeur
        $stats = [
            'total_products' => Produit::where('user_id', $vendorId)->where('est_actif', true)->count(),
            'avg_rating' => Avis::whereHas('produit', fn($q) => $q->where('user_id', $vendorId))
                ->avg('note') ?? 0,
            'review_count' => Avis::whereHas('produit', fn($q) => $q->where('user_id', $vendorId))->count(),
            'total_sales' => $vendor->commandes()->count(),
        ];

        // Récents avis
        $recentReviews = Avis::whereHas('produit', fn($q) => $q->where('user_id', $vendorId))
            ->with('user', 'produit')
            ->latest()
            ->take(5)
            ->get();

        // Badge vendeur
        $badge = $this->getVendorBadge($vendor, $stats);

        return view('vendor.shop', compact(
            'vendor',
            'products',
            'stats',
            'recentReviews',
            'badge'
        ));
    }

    /**
     * Rechercher dans la boutique d'un vendeur
     */
    public function search($vendorId, Request $request)
    {
        $vendor = User::findOrFail($vendorId);
        $query = $request->input('q', '');
        $sort = $request->input('sort', 'relevance');

        $products = Produit::where('user_id', $vendorId)
            ->where('est_actif', true)
            ->where(function ($q) use ($query) {
                if ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                }
            });

        // Tri
        match ($sort) {
            'price_asc' => $products->orderBy('prix', 'asc'),
            'price_desc' => $products->orderBy('prix', 'desc'),
            'newest' => $products->latest(),
            default => $products->latest(),
        };

        $products = $products->with('categorie', 'avis')->paginate(12);

        $products->getCollection()->transform(function ($p) {
            $p->avg_rating = $p->avis()->avg('note') ?? 0;
            $p->review_count = $p->avis()->count();
            return $p;
        });

        return view('vendor.shop-search', compact('vendor', 'products', 'query', 'sort'));
    }

    /**
     * Suivre/unfollower un vendeur (bonus)
     */
    public function follow(Request $request, $vendorId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentification requise'], 401);
        }

        $vendor = User::findOrFail($vendorId);
        $user = Auth::user();

        // Logique simple de suivi (stocké dans relationship)
        // Pour l'MVP, on peut juste retourner un message

        return response()->json([
            'success' => true,
            'message' => 'Suivi ajouté',
        ]);
    }

    /**
     * Déterminer le badge du vendeur
     */
    private function getVendorBadge($vendor, $stats)
    {
        // Vendeur Premium: 50+ produits ET avg rating >= 4.5
        if ($stats['total_products'] >= 50 && $stats['avg_rating'] >= 4.5) {
            return [
                'name' => '💎 Vendeur Premium',
                'color' => 'gold',
                'description' => 'Vendeur fiable avec excellentes notes',
            ];
        }

        // Vendeur Fiable: 20+ produits ET avg rating >= 4.0
        if ($stats['total_products'] >= 20 && $stats['avg_rating'] >= 4.0) {
            return [
                'name' => '⭐ Vendeur Fiable',
                'color' => 'blue',
                'description' => 'Vendeur avec bonnes notes de clients',
            ];
        }

        // Nouveau vendeur: moins de 2 mois
        if ($vendor->created_at->isAfter(now()->subMonths(2))) {
            return [
                'name' => '🆕 Nouveau Vendeur',
                'color' => 'green',
                'description' => 'Nouveau sur la plateforme',
            ];
        }

        return null;
    }
}
