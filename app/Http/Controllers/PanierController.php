<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\Produit;
use App\Models\Bundle;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    /**
     * Obtenir les items du panier (session ou BDD)
     */
    private function getCartItems()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $panier = $user->panier;
            return $panier ? $panier->items()->with('produit.vendeur', 'bundle.produits')->get() : collect();
        } else {
            $cartSession = session()->get('cart', []);
            $items = collect();

            foreach ($cartSession as $key => $data) {
                // Détecter si c'est un bundle (préfixe 'bundle_')
                if (strpos($key, 'bundle_') === 0) {
                    $bundleId = (int)str_replace('bundle_', '', $key);
                    $bundle = Bundle::with('produits')->find($bundleId);
                    if ($bundle) {
                        $items->push((object)[
                            'id' => $key,
                            'bundle_id' => $bundleId,
                            'quantite' => $data['qty'],
                            'prix_unitaire' => $data['price'],
                            'bundle' => $bundle,
                            'is_bundle' => true,
                        ]);
                    }
                } else {
                    // C'est un produit
                    $produit = Produit::with('vendeur')->find($key);
                    if ($produit) {
                        $items->push((object)[
                            'id' => $key,
                            'produit_id' => $key,
                            'quantite' => $data['qty'],
                            'prix_unitaire' => $data['price'],
                            'produit' => $produit,
                            'is_bundle' => false,
                        ]);
                    }
                }
            }
            return $items;
        }
    }

    /**
     * Calculer le total du panier
     */
    private function getCartTotal($items)
    {
        return $items->sum(fn($item) => $item->quantite * $item->prix_unitaire);
    }

    /**
     * Afficher le panier
     */
    public function index()
    {
        $items = $this->getCartItems();
        $total = $this->getCartTotal($items);

        return view('panier.index', [
            'items' => $items,
            'total' => $total,
            'isGuest' => !auth()->check(),
        ]);
    }

    /**
     * Obtenir le nombre d'articles du panier (pour le badge)
     */
    public function count()
    {
        $items = $this->getCartItems();
        return response()->json([
            'count' => $items->count(),
        ]);
    }

    /**
     * Ajouter un produit au panier
     */
    public function ajouter(Request $request, $produitId)
    {
        // Les administrateurs ne peuvent pas ajouter d'articles au panier
        if (auth()->check() && auth()->user()->is_admin) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les administrateurs n\'ont pas le droit d\'ajouter des articles au panier.'
                ], 403);
            }
            return back()->with('error', 'Les administrateurs n\'ont pas le droit d\'ajouter des articles au panier.');
        }

        // Vérifier si c'est un bundle ou un produit
        $bundle = Bundle::where('id', $produitId)->where('statut', 'actif')->first();
        $produit = $bundle ? null : Produit::find($produitId);

        if (!$bundle && !$produit) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article non trouvé'
                ], 404);
            }
            return back()->with('error', 'Article non trouvé');
        }

        // Article à ajouter (produit ou bundle)
        $article = $bundle ?: $produit;
        $isBundle = (bool)$bundle;
        $prix = $isBundle ? $bundle->prix_bundle : $produit->prix;
        $nom = $article->nom;

        // Pour les produits, vérifier le stock
        if (!$isBundle && $article->stock <= 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit indisponible'
                ], 422);
            }
            return back()->with('error', 'Produit indisponible');
        }

        // Récupérer la quantité
        $rawQuantite = null;

        if ($request->isJson()) {
            $data = $request->json()->all();
            $rawQuantite = $data['quantite'] ?? null;
        } else {
            $rawQuantite = $request->input('quantite');
        }

        // Sécuriser la conversion
        $quantite = 1;
        if ($rawQuantite !== null && $rawQuantite !== '') {
            $strValue = strval($rawQuantite);
            $cleanQuantite = (int)preg_replace('/[^0-9]/', '', $strValue);
            if ($cleanQuantite > 0) {
                $quantite = $cleanQuantite;
            }
        }

        if ($quantite < 1 || !is_int($quantite)) {
            $quantite = 1;
        }

        // Pour les produits, vérifier le stock
        if (!$isBundle && $quantite > $article->stock) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant. Maximum disponible: ' . $article->stock
                ], 422);
            }
            return back()->with('error', 'Stock insuffisant. Maximum disponible: ' . $article->stock);
        }

        if (auth()->check()) {
            $user = auth()->user();
            $panier = $user->panier ?? Panier::create(['user_id' => $user->id]);

            // Chercher un item existant
            if ($isBundle) {
                $panierItem = PanierItem::where('panier_id', $panier->id)
                    ->where('bundle_id', $produitId)
                    ->first();
            } else {
                $panierItem = PanierItem::where('panier_id', $panier->id)
                    ->where('produit_id', $produitId)
                    ->where('bundle_id', null)
                    ->first();
            }

            if ($panierItem) {
                $panierItem->quantite += $quantite;
                $panierItem->save();
            } else {
                if ($isBundle) {
                    PanierItem::create([
                        'panier_id' => $panier->id,
                        'bundle_id' => $produitId,
                        'quantite' => $quantite,
                        'prix_unitaire' => $prix,
                    ]);
                } else {
                    PanierItem::create([
                        'panier_id' => $panier->id,
                        'produit_id' => $produitId,
                        'quantite' => $quantite,
                        'prix_unitaire' => $prix,
                    ]);
                }
            }
        } else {
            $cart = session()->get('cart', []);

            // Utiliser un préfixe pour les bundles en session
            $cartKey = $isBundle ? 'bundle_' . $produitId : $produitId;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['qty'] += $quantite;
            } else {
                $cart[$cartKey] = [
                    'qty' => $quantite,
                    'price' => floatval($prix),
                    'is_bundle' => $isBundle,
                ];
            }

            session()->put('cart', $cart);
        }

        // Retourner JSON pour les requêtes AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '✓ ' . ($isBundle ? 'Bundle' : 'Produit') . ' ajouté au panier!'
            ]);
        }

        return redirect()->back()->with('success', '✓ ' . ($isBundle ? 'Bundle' : 'Produit') . ' ajouté au panier!');
    }

    /**
     * Modifier la quantité d'un item
     */
    public function modifier(Request $request, $itemId)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        if (auth()->check()) {
            $item = PanierItem::findOrFail($itemId);
            $item->quantite = $request->quantite;
            $item->save();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$itemId])) {
                $cart[$itemId]['qty'] = intval($request->quantite);
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Quantité mise à jour');
    }

    /**
     * Supprimer un item du panier
     */
    public function supprimer($itemId)
    {
        if (auth()->check()) {
            $item = PanierItem::findOrFail($itemId);
            $item->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$itemId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', '✓ Article supprimé');
    }

    /**
     * Vider le panier
     */
    public function vider()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->panier) {
                PanierItem::where('panier_id', $user->panier->id)->delete();
            }
        } else {
            session()->put('cart', []);
        }

        return redirect()->back()->with('success', 'Panier vidé');
    }
}
