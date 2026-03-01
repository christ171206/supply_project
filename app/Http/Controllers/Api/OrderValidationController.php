<?php

namespace App\Http\Controllers\Api;

use App\Models\Commande;
use App\Models\CiRegion;
use App\Models\CiDistrict;
use App\Models\CiCommune;
use App\Models\CiQuartier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderValidationController extends Controller
{
    /**
     * Validate and prepare an order for payment
     * This endpoint is called before initiating payment to set delivery location
     */
    public function validateAndPrepare(Request $request): JsonResponse
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'quartier_id' => 'required|exists:ci_quartiers,id',
            'adresse_livraison' => 'required|string|min:5|max:255',
            'telephone_livraison' => 'required|string|regex:/^[0-9]{10}$/',
            'adresse_detail' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $commande = Commande::findOrFail($request->commande_id);

        // Vérifier que l'utilisateur est propriétaire
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Vérifier que la commande n'est pas déjà confirmée
        if ($commande->statut === 'confirmee' || $commande->statut === 'livree') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette commande ne peut pas être modifiée',
            ], 400);
        }

        // Vérifier que le quartier existe et récupérer les données géographiques
        $quartier = CiQuartier::find($request->quartier_id);
        if (!$quartier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quartier introuvable',
            ], 404);
        }

        // Récupérer la commune, le district et la région
        $commune = $quartier->commune;
        $district = $commune->district;
        $region = $district->region;

        // Mettre à jour la commande
        $commande->update([
            'quartier_id' => $request->quartier_id,
            'adresse_livraison' => $request->adresse_livraison,
            'telephone_livraison' => $request->telephone_livraison,
            'adresse_detail' => $request->input('adresse_detail'),
            'notes' => $request->input('notes'),
        ]);

        // Charger la commande avec ses relations
        $commande->load('ligneCommandes.produit', 'quartier');

        // Calculer le résumé de la commande
        $summary = [
            'orderId' => $commande->id,
            'total' => $commande->total,
            'itemCount' => $commande->ligneCommandes->count(),
            'items' => $commande->ligneCommandes->map(fn($item) => [
                'produit_id' => $item->produit_id,
                'produit_nom' => $item->produit->nom ?? 'Produit',
                'quantite' => $item->quantite,
                'prix_unitaire' => $item->prix_unitaire,
                'montant' => $item->quantite * $item->prix_unitaire,
            ]),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Commande préparée avec succès',
            'data' => [
                'commande_id' => $commande->id,
                'status' => $commande->statut,
                'delivery_location' => [
                    'quartier_id' => $quartier->id,
                    'quartier_nom' => $quartier->name,
                    'commune_id' => $commune->id,
                    'commune_nom' => $commune->name,
                    'district_id' => $district->id,
                    'district_nom' => $district->name,
                    'region_id' => $region->id,
                    'region_nom' => $region->name,
                ],
                'delivery_address' => [
                    'adresse' => $request->adresse_livraison,
                    'detail' => $request->input('adresse_detail'),
                    'telephone' => $request->telephone_livraison,
                    'notes' => $request->input('notes'),
                ],
                'order_summary' => $summary,
                'ready_for_payment' => true,
            ],
        ]);
    }

    /**
     * Get order details with current delivery location
     */
    public function getOrderDetails(Commande $commande): JsonResponse
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        $commande->load('ligneCommandes.produit', 'quartier.commune.district.region', 'user');

        $quartier = $commande->quartier;
        $locationInfo = [];

        if ($quartier) {
            $locationInfo = [
                'quartier_id' => $quartier->id,
                'quartier_nom' => $quartier->name,
                'commune_id' => $quartier->commune->id,
                'commune_nom' => $quartier->commune->name,
                'district_id' => $quartier->commune->district->id,
                'district_nom' => $quartier->commune->district->name,
                'region_id' => $quartier->commune->district->region->id,
                'region_nom' => $quartier->commune->district->region->name,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'commande_id' => $commande->id,
                'status' => $commande->statut,
                'total' => $commande->total,
                'created_at' => $commande->created_at,
                'items' => $commande->ligneCommandes->map(fn($item) => [
                    'produit_id' => $item->produit_id,
                    'produit_nom' => $item->produit->nom,
                    'quantite' => $item->quantite,
                    'prix_unitaire' => $item->prix_unitaire,
                    'montant' => $item->quantite * $item->prix_unitaire,
                ]),
                'delivery_info' => [
                    'location' => $locationInfo,
                    'adresse' => $commande->adresse_livraison,
                    'adresse_detail' => $commande->adresse_detail,
                    'telephone' => $commande->telephone_livraison,
                    'notes' => $commande->notes,
                ],
                'payment_info' => [
                    'method' => $commande->mode_paiement,
                    'is_confirmed' => $commande->paiement_confirme,
                ],
            ],
        ]);
    }

    /**
     * Update delivery location for an order
     */
    public function updateDeliveryLocation(Request $request, Commande $commande): JsonResponse
    {
        $request->validate([
            'quartier_id' => 'required|exists:ci_quartiers,id',
            'adresse_livraison' => 'required|string|min:5|max:255',
            'telephone_livraison' => 'required|string|regex:/^[0-9]{10}$/',
            'adresse_detail' => 'nullable|string|max:255',
        ]);

        // Vérifier que l'utilisateur est propriétaire
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Vérifier que la commande peut être modifiée
        if (in_array($commande->statut, ['confirmee', 'livree'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette commande ne peut pas être modifiée',
            ], 400);
        }

        // Vérifier que le quartier existe
        $quartier = CiQuartier::find($request->quartier_id);
        if (!$quartier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quartier introuvable',
            ], 404);
        }

        // Mettre à jour
        $commande->update([
            'quartier_id' => $request->quartier_id,
            'adresse_livraison' => $request->adresse_livraison,
            'telephone_livraison' => $request->telephone_livraison,
            'adresse_detail' => $request->input('adresse_detail'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Adresse de livraison mise à jour',
            'data' => [
                'commande_id' => $commande->id,
                'quartier_nom' => $quartier->name,
                'adresse_livraison' => $request->adresse_livraison,
                'telephone_livraison' => $request->telephone_livraison,
            ],
        ]);
    }
}
