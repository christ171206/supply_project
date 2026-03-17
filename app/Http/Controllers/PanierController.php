<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\Produit;
use App\Models\Bundle;
use App\Models\PromoCode;
use App\Models\ClientCoupon;
use App\Models\GlobalOffer;
use App\Services\PromoAbuseValidator;
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

        // Calculate applied global offers
        $appliedOffers = $this->calculateAppliedOffers($items, $total);
        $totalDiscount = $appliedOffers->sum('discount_amount');
        $totalAfterOffers = $total - $totalDiscount;

        // Get applied coupon if any
        $appliedCoupon = session()->get('applied_coupon');
        $couponDiscount = 0;
        if ($appliedCoupon && auth()->check()) {
            $coupon = ClientCoupon::find($appliedCoupon);
            if ($coupon && $coupon->statut === 'actif' && !$coupon->isExpired()) {
                $couponDiscount = $coupon->promoCode->valeur ?? 0;
            } else {
                session()->forget('applied_coupon');
            }
        }

        $finalTotal = max(0, $totalAfterOffers - $couponDiscount);
        $shippingCost = $finalTotal >= 100000 ? 0 : 5000;

        return view('panier.index', [
            'items' => $items,
            'total' => $total,
            'appliedOffers' => $appliedOffers,
            'totalDiscount' => $totalDiscount,
            'appliedCoupon' => $appliedCoupon ? ClientCoupon::find($appliedCoupon) : null,
            'couponDiscount' => $couponDiscount,
            'subtotal' => $totalAfterOffers,
            'shippingCost' => $shippingCost,
            'finalTotal' => $finalTotal + $shippingCost,
            'isGuest' => !auth()->check(),
        ]);
    }

    /**
     * Calculate which global offers apply to this cart
     */
    private function calculateAppliedOffers($items, $total)
    {
        $appliedOffers = collect();

        // Get all active global offers
        $offers = GlobalOffer::active()->get();

        foreach ($offers as $offer) {
            // Check if offer applies to this cart
            if (!$offer->appliesToCart($items->pluck('produit')->filter(), $total)) {
                continue;
            }

            // Calculate discount for this offer
            $discount = $offer->calculateCartDiscount(
                $items->map(fn($item) => [
                    'produit_id' => $item->produit_id,
                    'quantite' => $item->quantite,
                    'prix' => $item->prix_unitaire,
                ]),
                $total
            );

            if ($discount > 0) {
                $appliedOffers->push([
                    'offer' => $offer,
                    'discount_amount' => $discount,
                    'offer_name' => $offer->name,
                    'offer_type' => $offer->getTypeLabel(),
                ]);
            }
        }

        return $appliedOffers;
    }

    /**
     * Apply a coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté'
            ], 401);
        }

        $request->validate([
            'coupon_id' => 'required|integer',
        ]);

        $coupon = ClientCoupon::find($request->coupon_id);

        if (!$coupon || $coupon->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon non valide'
            ], 422);
        }

        if ($coupon->statut !== 'actif') {
            return response()->json([
                'success' => false,
                'message' => 'Ce coupon n\'est pas actif'
            ], 422);
        }

        if ($coupon->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce coupon a expiré'
            ], 422);
        }

        // Validate against abuse rules
        $items = $this->getCartItems();
        $total = $this->getCartTotal($items);
        $validator = new PromoAbuseValidator($coupon->promoCode);
        $validation = $validator->validatePromoUsage(auth()->user(), $items, $total);

        if (!$validation['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $validation['reason'],
                'severity' => $validation['severity']
            ], 422);
        }

        session()->put('applied_coupon', $coupon->id);

        return response()->json([
            'success' => true,
            'message' => '✓ Coupon appliqué!',
            'discount' => $coupon->promoCode->valeur ?? 0,
        ]);
    }

    /**
     * Apply global offer manually
     */
    public function applyOffer(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|integer',
        ]);

        $offer = GlobalOffer::find($request->offer_id);

        if (!$offer || !$offer->is_active || !$offer->isActiveNow()) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non disponible'
            ], 422);
        }

        $items = $this->getCartItems();
        $total = $this->getCartTotal($items);

        if (!$offer->appliesToCart($items->pluck('produit')->filter(), $total)) {
            return response()->json([
                'success' => false,
                'message' => 'Cette offre ne s\'applique pas à votre panier'
            ], 422);
        }

        session()->push('applied_offers', $offer->id);

        return response()->json([
            'success' => true,
            'message' => '✓ Offre appliquée!',
        ]);
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return redirect()->back()->with('success', '✓ Coupon retiré');
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
