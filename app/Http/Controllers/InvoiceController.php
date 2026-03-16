<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Afficher la facture en ligne
     */
    public function show(Commande $commande)
    {
        // Vérifier l'accès: client ou vendeur
        if (
            Auth::id() !== $commande->user_id &&
            !Auth::user()?->produits()->whereIn('id', $commande->ligneCommandes->pluck('produit_id'))->exists()
        ) {
            abort(403);
        }

        $commande->load('ligneCommandes.produit.vendeur', 'client', 'deliveryLocation');

        return view('invoices.show', compact('commande'));
    }

    /**
     * Télécharger la facture en PDF
     */
    public function downloadPdf(Commande $commande)
    {
        // Vérifier l'accès
        if (
            Auth::id() !== $commande->user_id &&
            !Auth::user()?->produits()->whereIn('id', $commande->ligneCommandes->pluck('produit_id'))->exists()
        ) {
            abort(403);
        }

        $commande->load('ligneCommandes.produit.vendeur', 'client', 'deliveryLocation');

        // Retourner la vue comme réponse PDF
        return response()
            ->view('invoices.pdf', compact('commande'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Facture-' . $commande->numero . '.pdf"');
    }

    /**
     * Envoyer la facture par email
     */
    public function sendEmail(Commande $commande, Request $request)
    {
        if (Auth::id() !== $commande->user_id) {
            abort(403);
        }

        $email = $request->input('email', $commande->client->email);

        // Retourner JSON pour simple API test
        return response()->json(['message' => 'Facture prête à envoyer: ' . $email]);
    }

    /**
     * Obtenir les détails de la commande (API)
     */
    public function getInvoiceData(Commande $commande)
    {
        if (
            Auth::id() !== $commande->user_id &&
            !Auth::user()?->produits()->whereIn('id', $commande->ligneCommandes->pluck('produit_id'))->exists()
        ) {
            abort(403);
        }

        $commande->load('ligneCommandes.produit.vendeur', 'client', 'deliveryLocation');

        $items = $commande->ligneCommandes->map(function ($ligne) {
            $produit = $ligne->produit;
            return [
                'nom' => $produit->nom,
                'sku' => $produit->code ?? 'N/A',
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'total' => $ligne->prix_unitaire * $ligne->quantite,
                'vendeur' => $produit->vendeur->shop_name ?? $produit->vendeur->name,
            ];
        })->toArray();

        $subtotal = array_sum(array_column($items, 'total'));
        $tax = $subtotal * 0.18; // 18% TVA
        $total = $subtotal + $tax;

        return response()->json([
            'numero' => $commande->numero,
            'statut' => $commande->statut,
            'date' => $commande->created_at->format('d/m/Y'),
            'date_livraison' => $commande->delivered_at?->format('d/m/Y'),
            'client' => [
                'nom' => $commande->client->name,
                'email' => $commande->client->email,
                'phone' => $commande->client->phone,
            ],
            'adresse_livraison' => [
                'adresse' => $commande->client->address,
                'ville' => $commande->deliveryLocation?->city,
                'code_postal' => $commande->deliveryLocation?->postal_code,
                'pays' => $commande->pays,
            ],
            'items' => $items,
            'montants' => [
                'sous_total' => round($subtotal, 2),
                'tva' => round($tax, 2),
                'total' => round($total, 2),
            ],
        ]);
    }
}
