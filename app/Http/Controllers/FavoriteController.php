<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Produit;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $favoris = $user->produitsFavoris()->with('categorie', 'vendeur')->paginate(12);
        
        return view('favoris.index', compact('favoris'));
    }

    public function toggle($productId)
    {
        $user = auth()->user();
        $produit = Produit::findOrFail($productId);

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

        return response()->json(['success' => true, 'is_favorited' => $isFavorited]);
    }

    public function isFavorited($productId)
    {
        if (!auth()->check()) {
            return response()->json(['is_favorited' => false]);
        }

        $user = auth()->user();
        $isFavorited = Favorite::where('user_id', $user->id)
            ->where('produit_id', $productId)
            ->exists();

        return response()->json(['is_favorited' => $isFavorited]);
    }
}

