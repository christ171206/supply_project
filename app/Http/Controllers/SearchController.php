<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Live search for products
     * Returns matching products in real-time as user types
     */
    public function liveSearch(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => []
            ]);
        }

        // Search products by name, description, or category
        $produits = Produit::where('statut', 'actif')
            ->where(function ($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get(['id', 'nom', 'prix', 'image', 'stock', 'wishlistCount'])
            ->map(function ($produit) {
                // Déterminer l'image à afficher
                $imageUrl = asset('images/default-product.jpg');
                if ($produit->images && is_array($produit->images) && count($produit->images) > 0) {
                    $imageUrl = asset('storage/' . $produit->images[0]);
                } elseif ($produit->image) {
                    $imageUrl = asset('storage/' . $produit->image);
                }

                return [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'prix' => number_format($produit->prix, 0, ',', ' ') . ' XOF',
                    'prixRaw' => $produit->prix,
                    'image' => $imageUrl,
                    'stock' => $produit->stock,
                    'inStock' => $produit->stock > 0,
                    'url' => route('produits.show', $produit->id),
                ];
            });

        return response()->json([
            'success' => true,
            'results' => $produits,
            'count' => count($produits),
            'query' => $query
        ]);
    }

    /**
     * Get search suggestions for autocomplete
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        // Get distinct product names and categories that match the query
        $suggestions = Produit::select('nom')
            ->where('statut', 'actif')
            ->where('nom', 'LIKE', "%{$query}%")
            ->distinct()
            ->limit(6)
            ->pluck('nom');

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }
}
