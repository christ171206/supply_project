<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvancedReviewController extends Controller
{
    /**
     * Poster un avis avancé
     */
    public function store(Request $request, $productId)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentification requise'], 401);
        }

        $produit = Produit::findOrFail($productId);
        $user = Auth::user();

        // Validation
        $validated = $request->validate([
            'note' => 'required|integer|between:1,5',
            'qualite_note' => 'required|integer|between:1,5',
            'livraison_note' => 'required|integer|between:1,5',
            'communication_note' => 'nullable|integer|between:1,5',
            'rapport_qualite_prix' => 'required|integer|between:1,5',
            'commentaire' => 'required|string|min:10|max:1000',
            'points_positifs' => 'nullable|string|max:500',
            'points_negatifs' => 'nullable|string|max:500',
            'recommande' => 'boolean',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:2048',
        ]);

        try {
            // Gérer les images
            $imageUrls = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $imageUrls[] = 'storage/' . $path;
                }
            }

            // Créer l'avis
            $avis = Avis::create([
                'user_id' => $user->id,
                'produit_id' => $productId,
                'note' => $validated['note'],
                'commentaire' => $validated['commentaire'],
                'qualite_note' => $validated['qualite_note'],
                'livraison_note' => $validated['livraison_note'],
                'communication_note' => $validated['communication_note'] ?? null,
                'rapport_qualite_prix' => $validated['rapport_qualite_prix'],
                'points_positifs' => $validated['points_positifs'] ?? null,
                'points_negatifs' => $validated['points_negatifs'] ?? null,
                'recommande' => $validated['recommande'] ?? true,
                'type_acheteur' => $this->isVerifiedPurchase($user, $produit) ? 'verifie' : 'non_verifie',
                'contient_images' => count($imageUrls) > 0,
                'images_urls' => count($imageUrls) > 0 ? $imageUrls : null,
            ]);

            // Recalculer la note moyenne du produit
            $avgNote = Avis::where('produit_id', $productId)->avg('note');
            $produit->update(['note_moyenne' => round($avgNote, 1)]);

            return response()->json([
                'success' => true,
                'message' => 'Avis publié avec succès!',
                'avis' => $avis,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtenir les avis avec filtrag et tri avancés
     */
    public function getAdvancedReviews($productId, Request $request)
    {
        $produit = Produit::findOrFail($productId);

        $filter = $request->input('filter', 'all');
        $sort = $request->input('sort', 'recent');
        $minRating = $request->input('min_rating', 0);

        $reviews = Avis::where('produit_id', $productId)
            ->where('is_appropriate', true)
            ->whereNull('deleted_at');

        // Filtrer par type d'avis
        match ($filter) {
            'verified' => $reviews->where('type_acheteur', 'verifie'),
            'with_images' => $reviews->where('contient_images', true),
            'recommended' => $reviews->where('recommande', true),
            default => null,
        };

        // Filtrer par note minimum
        if ($minRating > 0) {
            $reviews->where('note', '>=', $minRating);
        }

        // Tri
        match ($sort) {
            'helpful' => $reviews->orderByDesc('utilite_votes'),
            'recent' => $reviews->latest(),
            'rating_high' => $reviews->orderByDesc('note'),
            'rating_low' => $reviews->orderBy('note'),
            default => $reviews->latest(),
        };

        $reviews = $reviews->with('user')
            ->paginate(5);

        // Calculer les stats
        $stats = [
            'total' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->count(),
            'avg_rating' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->avg('note'),
            'avg_quality' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->avg('qualite_note'),
            'avg_delivery' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->avg('livraison_note'),
            'avg_price_quality' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->avg('rapport_qualite_prix'),
            'recommended_percent' => Avis::where('produit_id', $productId)->where('is_appropriate', true)->where('recommande', true)->count() / max(1, Avis::where('produit_id', $productId)->where('is_appropriate', true)->count()) * 100,
        ];

        return response()->json([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total(),
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * Marquer un avis comme utile
     */
    public function markHelpful($reviewId)
    {
        $avis = Avis::findOrFail($reviewId);
        $avis->increment('utilite_votes');

        return response()->json(['success' => true, 'votes' => $avis->utilite_votes]);
    }

    /**
     * Vérifier si l'utilisateur a acheté ce produit
     */
    private function isVerifiedPurchase($user, $produit)
    {
        return \App\Models\LigneCommande::whereHas('commande', function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->where('statut', 'livree');
        })
            ->where('produit_id', $produit->id)
            ->exists();
    }
}
