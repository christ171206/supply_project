<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\Produit;
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
            return $panier ? $panier->items()->with('produit.vendeur')->get() : collect();
        } else {
            $cartSession = session()->get('cart', []);
            $items = collect();

            foreach ($cartSession as $productId => $quantity) {
                $produit = Produit::with('vendeur')->find($productId);
                if ($produit) {
                    $items->push((object)[
                        'id' => $productId,
                        'produit_id' => $productId,
                        'quantite' => $quantity['qty'],
                        'prix_unitaire' => $quantity['price'],
                        'produit' => $produit,
                    ]);
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
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $produit = Produit::findOrFail($produitId);
        $quantite = intval($request->quantite);

        if (auth()->check()) {
            $user = auth()->user();
            $panier = $user->panier ?? Panier::create(['user_id' => $user->id]);

            $panierItem = PanierItem::where('panier_id', $panier->id)
                                   ->where('produit_id', $produitId)
                                   ->first();

            if ($panierItem) {
                $panierItem->quantite += $quantite;
                $panierItem->save();
            } else {
                PanierItem::create([
                    'panier_id' => $panier->id,
                    'produit_id' => $produitId,
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit->prix,
                ]);
            }
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$produitId])) {
                $cart[$produitId]['qty'] += $quantite;
            } else {
                $cart[$produitId] = [
                    'qty' => $quantite,
                    'price' => floatval($produit->prix),
                ];
            }

            session()->put('cart', $cart);
        }

        // Retourner JSON pour les requêtes AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '✓ Produit ajouté au panier!'
            ]);
        }

        return redirect()->back()->with('success', '✓ Produit ajouté au panier!');
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

