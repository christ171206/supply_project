<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Obtenir les promotions actives pour la boutique du vendeur
     */
    public function getVendorPromotions()
    {
        $vendor = Auth::user();
        if ($vendor->role !== 'vendeur') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Promotions "virtuelles" basées sur les stats du vendeur
        $promotions = [];

        // Flash Sale simulée: top 3 produits avec 15% de réduction
        $topProducts = $vendor->produits()
            ->with('ligneCommandes')
            ->get()
            ->sortByDesc(fn($p) => $p->ligneCommandes->count())
            ->take(3);

        if ($topProducts->count() > 0) {
            $promotions[] = [
                'id' => 'flash_sale_' . now()->format('Y-m-d'),
                'type' => 'flash_sale',
                'titre' => '⚡ Vente Flash - Top Produits',
                'description' => '15% de réduction sur nos 3 meilleures ventes',
                'discount_percent' => 15,
                'produits' => $topProducts->pluck('id')->toArray(),
                'valid_until' => now()->addDays(1)->toDateTimeString(),
                'active' => true,
            ];
        }

        // Bundle Deal: 2 produits = 10% + 3 produits = 20%
        if ($vendor->produits()->count() >= 3) {
            $promotions[] = [
                'id' => 'bundle_' . now()->format('Y-m'),
                'type' => 'bundle',
                'titre' => '📦 Offre Bundle',
                'description' => 'Achetez 3 produits et obtenez 20% de réduction',
                'discount_percent' => 20,
                'min_items' => 3,
                'valid_until' => now()->endOfMonth()->toDateTimeString(),
                'active' => true,
            ];
        }

        // New Merchant Boost (premier 30 jours)
        if ($vendor->created_at->diffInDays(now()) <= 30) {
            $promotions[] = [
                'id' => 'new_merchant_boost',
                'type' => 'new_merchant',
                'titre' => '🌟 Boost Nouveau Vendeur',
                'description' => 'Jusqu\'à 25% de réduction sur tous vos produits',
                'discount_percent' => 25,
                'valid_until' => $vendor->created_at->addDays(30)->toDateTimeString(),
                'active' => true,
            ];
        }

        // Free Shipping threshold
        $promotions[] = [
            'id' => 'free_shipping',
            'type' => 'free_shipping',
            'titre' => '🚚 Livraison Gratuite',
            'description' => 'Livraison gratuite à partir de 150 000 F',
            'threshold' => 150000,
            'active' => true,
            'valid_until' => now()->endOfMonth()->toDateTimeString(),
        ];

        return response()->json([
            'total' => count($promotions),
            'promotions' => $promotions,
        ]);
    }

    /**
     * Valider un code promo
     */
    public function validatePromoCode(Request $request)
    {
        $code = strtoupper($request->input('code'));
        $cartTotal = $request->input('total', 0);

        // Simulated promo codes
        $validCodes = [
            'WELCOME15' => ['discount' => 15, 'min_total' => 0, 'type' => 'percent'],
            'BLACKFRIDAY30' => ['discount' => 30, 'min_total' => 50000, 'type' => 'percent'],
            'SUMMER20' => ['discount' => 20, 'min_total' => 100000, 'type' => 'percent'],
            'SAVE5K' => ['discount' => 5000, 'min_total' => 50000, 'type' => 'fixed'],
            'LOYALTY10' => ['discount' => 10, 'min_total' => 0, 'type' => 'percent'], // Authenticated only
        ];

        if (!isset($validCodes[$code])) {
            return response()->json([
                'valid' => false,
                'message' => 'Code promo invalide',
            ], 422);
        }

        $promo = $validCodes[$code];

        // Check minimum
        if ($cartTotal < $promo['min_total']) {
            return response()->json([
                'valid' => false,
                'message' => 'Commande insuffisante: minimum ' . number_format($promo['min_total'], 0) . ' F',
            ], 422);
        }

        // Calculate discount
        $discount = $promo['type'] === 'percent'
            ? ($cartTotal * $promo['discount']) / 100
            : $promo['discount'];

        $newTotal = $cartTotal - $discount;

        return response()->json([
            'valid' => true,
            'code' => $code,
            'discount_type' => $promo['type'],
            'discount_value' => $promo['type'] === 'percent' ? $promo['discount'] . '%' : number_format($promo['discount'], 0) . ' F',
            'discount_amount' => round($discount, 2),
            'new_total' => round($newTotal, 2),
            'message' => '✓ Code appliqué avec succès',
        ]);
    }

    /**
     * Obtenir les règles de promo actuelles (publiques)
     */
    public function getPromoRules()
    {
        return response()->json([
            'active_promos' => [
                [
                    'code' => 'WELCOME15',
                    'label' => '15% de réduction bienvenue',
                    'type' => 'percent',
                ],
                [
                    'code' => 'SUMMER20',
                    'label' => '20% d\'été',
                    'type' => 'percent',
                    'min_spend' => '100K',
                ],
            ],
            'free_shipping_threshold' => 150000,
            'bundle_offers' => [
                [
                    'items' => 3,
                    'discount' => '20%',
                ],
                [
                    'items' => 5,
                    'discount' => '30%',
                ],
            ],
        ]);
    }
}
