<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Afficher les commandes de l'utilisateur connecté
     */
    public function index()
    {
        $commandes = auth()->user()->commandes()->latest()->paginate(10);
        return view('commandes.index', compact('commandes'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::findOrFail($id);

        // Vérifier que l'utilisateur est propriétaire ou vendeur
        if (auth()->user()->id !== $commande->user_id && auth()->user()->role !== 'vendeur') {
            abort(403);
        }

        $lignes = $commande->ligneCommandes()->with('produit')->get();
        $payment = $commande->payment;

        return view('commandes.show', compact('commande', 'lignes', 'payment'));
    }

    /**
     * Afficher le formulaire de paiement
     * Redirection vers login si non authentifié
     */
    public function create(Request $request)
    {
        // Si non authentifié, rediriger vers login avec intention de retour
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour valider votre commande');
        }

        // Récupérer les items du panier
        $user = auth()->user();
        $panier = $user->panier;

        if (!$panier || $panier->items->isEmpty()) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide');
        }

        $items = $panier->items()->with('produit')->get();
        $total = $items->sum(fn($item) => $item->quantite * $item->prix_unitaire);

        return view('commandes.create', compact('items', 'total', 'panier'));
    }

    /**
     * Créer une nouvelle commande depuis le panier
     */
    public function store(Request $request)
    {
        \Log::info('Store commande - Début', [
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method,
        ]);

        $request->validate([
            'payment_method' => 'required|in:wave,orange_money,mtn_money,moov_money,cash',
            'adresse_livraison' => 'required|string',
        ]);

        $user = auth()->user();
        $panier = $user->panier;

        \Log::info('Panier info', [
            'panier' => $panier ? $panier->id : 'null',
            'items_count' => $panier ? $panier->items()->count() : 0,
        ]);

        if (!$panier || $panier->items()->count() === 0) {
            return redirect()->back()->with('error', 'Votre panier est vide!');
        }

        try {
            DB::beginTransaction();

            // Calculer le total
            $total = $panier->items()->sum(DB::raw('quantite * prix_unitaire'));

            \Log::info('Total calculé', ['total' => $total]);

            // Créer la commande
            $commande = Commande::create([
                'user_id' => $user->id,
                'total' => $total,
                'statut' => 'en_attente',
                'payment_method' => $request->payment_method,
                'adresse_livraison' => $request->adresse_livraison,
                'notes' => $request->notes,
            ]);

            \Log::info('Commande créée', ['commande_id' => $commande->id]);

            // Ajouter les lignes de commande
            foreach ($panier->items as $item) {
                \Log::info('Création ligne commande', [
                    'produit_id' => $item->produit_id,
                    'quantite' => $item->quantite,
                ]);
                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $item->produit_id,
                    'quantite' => $item->quantite,
                    'prix_unitaire' => $item->prix_unitaire,
                    'sous_total' => $item->quantite * $item->prix_unitaire,
                ]);

                // Décrémenter le stock
                $item->produit->decrement('stock', $item->quantite);
            }

            \Log::info('Lignes commande créées');

            // Créer le paiement (simulé)
            Payment::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'typePayement' => $request->payment_method,
                'statut' => $request->payment_method === 'cash' ? 'en_attente' : 'confirme',
            ]);

            \Log::info('Paiement créé');

            // Vider le panier
            $panier->items()->delete();

            \Log::info('Panier vidé');

            DB::commit();

            \Log::info('Commande complète - succès', ['commande_id' => $commande->id]);

            return redirect()->route('commandes.show', $commande->id)
                           ->with('success', 'Commande créée avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur création commande: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les commandes reçues (pour vendeur)
     */
    public function vendeurCommandes()
    {
        $commandes = Commande::with('user', 'ligneCommandes')
                             ->latest()
                             ->paginate(10);

        return view('vendeur.commandes', compact('commandes'));
    }

    /**
     * Afficher les détails d'une commande pour un vendeur
     */
    public function vendeurCommandeDetail($id)
    {
        $commande = Commande::with('user', 'ligneCommandes.produit')->findOrFail($id);
        $lignes = $commande->ligneCommandes;
        $payment = $commande->payment;

        return view('vendeur.commandes-detail', compact('commande', 'lignes', 'payment'));
    }

    /**
     * Générer et afficher la facture (printable/downloadable)
     */
    public function facture($id)
    {
        $commande = Commande::findOrFail($id);

        // Vérifier que l'utilisateur est propriétaire
        if (auth()->user()->id !== $commande->user_id) {
            abort(403);
        }

        $lignes = $commande->ligneCommandes()->with('produit')->get();
        $payment = $commande->payment;

        return view('commandes.facture', compact('commande', 'lignes', 'payment'));
    }
}
