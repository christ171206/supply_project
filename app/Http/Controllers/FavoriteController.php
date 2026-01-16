<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Produit;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $favoris = $user->produitsFavoris()->with('categorie', 'vendeur')->paginate(12);
        } else {
            // Pour les utilisateurs non connectés, afficher une page vide
            // Les favoris sont gérés en localStorage
            $favoris = collect([]);
        }

        return view('favoris.index', compact('favoris'));
    }

    public function toggle($productId)
    {
        $produit = Produit::findOrFail($productId);

        if (auth()->check()) {
            // Utilisateur connecté: sauvegarder en BD
            $user = auth()->user();
            $favorite = Favorite::where('user_id', $user->id)
                ->where('produit_id', $productId)
                ->first();

            if ($favorite) {
                $favorite->delete();
                $isFavorited = false;
            } else {
                Favorite::create([
                    'user_id' => $user->id,
                    'produit_id' => $productId,
                ]);
                $isFavorited = true;
            }
        } else {
            // Utilisateur non connecté: localStorage géré côté client
            $isFavorited = true; // Toujours true pour localStorage
        }

        return response()->json(['success' => true, 'is_favorited' => $isFavorited]);
    }

    public function isFavorited($productId)
    {
        if (!auth()->check()) {
            // Non connecté: vérifier via localStorage (côté client)
            return response()->json(['is_favorited' => false]);
        }

        $user = auth()->user();
        $isFavorited = Favorite::where('user_id', $user->id)
            ->where('produit_id', $productId)
            ->exists();

        return response()->json(['is_favorited' => $isFavorited]);
    }
}


