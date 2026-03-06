<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Produit;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Les administrateurs ne peuvent pas passer de commandes (même en mode client, c'est juste pour voir)
        if (auth()->check() && auth()->user()->is_admin) {
            $message = session('admin_client_mode')
                ? 'En mode visualisation client, vous ne pouvez pas passer de commande. C\'est pour voir comment fonctionne la plateforme.'
                : 'Les administrateurs ne peuvent pas passer de commandes. Activez le mode client pour explorer la plateforme.';
            return redirect('/admin/dashboard')->with('error', $message);
        }

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
        // Les administrateurs ne peuvent pas passer de commandes
        if (auth()->check() && auth()->user()->is_admin) {
            return back()->with('error', 'Les administrateurs n\'ont pas le droit de passer des commandes.');
        }

        Log::info('Store commande - Début', [
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method,
        ]);

        $request->validate([
            'payment_method' => 'required|in:wave,orange_money,mtn_money,moov_money,cash',
            'quartier_id' => 'nullable|exists:ci_quartiers,id',
            'adresse_detail' => 'required|string|min:5',
            'pays' => 'nullable|string|max:255',
            'telephone_livraison' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Extraire seulement les chiffres
                    $digitsOnly = preg_replace('/\D/', '', $value);
                    if (strlen($digitsOnly) < 10) {
                        $fail('Le téléphone doit contenir au moins 10 chiffres.');
                    }
                }
            ],
            'accept_conditions' => 'required|accepted',
            'phone_payment' => 'required_if:payment_method,wave,orange_money,mtn_money,moov_money|string',
        ], [
            'quartier_id.required' => 'Veuillez sélectionner un quartier',
            'quartier_id.exists' => 'Le quartier sélectionné n\'existe pas',
            'adresse_detail.required' => 'L\'adresse détaillée est obligatoire',
            'adresse_detail.min' => 'L\'adresse doit contenir au moins 5 caractères',
            'telephone_livraison.required' => 'Le téléphone de livraison est obligatoire',
            'accept_conditions.required' => 'Vous devez accepter les conditions',
            'phone_payment.required_if' => 'Le numéro de téléphone est obligatoire pour ce mode de paiement',
        ]);

        $user = auth()->user();
        $panier = $user->panier;

        Log::info('Panier info', [
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

            Log::info('Total calculé', ['total' => $total]);

            // Construire l'adresse complète (optionnelle si quartier fourni)
            $adresseLivraison = '';
            if ($request->quartier_id) {
                $quartier = \App\Models\CiQuartier::find($request->quartier_id);
                if ($quartier) {
                    $adresseLivraison = $quartier->name . ', ' . $quartier->commune->name;
                }
            }

            // Si pas d'adresse construite, utiliser le pays et quartier si fournis
            if (!$adresseLivraison) {
                $parts = [];
                if ($request->pays) {
                    $parts[] = $request->pays;
                }
                if ($request->quartier_id) {
                    $quartier = \App\Models\CiQuartier::find($request->quartier_id);
                    if ($quartier) {
                        $parts[] = $quartier->name;
                    }
                }
                $adresseLivraison = implode(', ', $parts) ?: 'Non spécifiée';
            }

            // Créer la commande
            $commande = Commande::create([
                'user_id' => $user->id,
                'total' => $total,
                'statut' => 'en_attente',
                'payment_method' => $request->payment_method,
                'quartier_id' => $request->quartier_id,
                'pays' => $request->input('pays', 'Côte d\'Ivoire'),
                'adresse_livraison' => $adresseLivraison,
                'adresse_detail' => $request->adresse_detail,
                'telephone_livraison' => $request->telephone_livraison,
                'notes' => $request->input('notes', null),
            ]);

            Log::info('Commande créée', ['commande_id' => $commande->id]);

            // Ajouter les lignes de commande
            foreach ($panier->items as $item) {
                Log::info('Création ligne commande', [
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

            Log::info('Lignes commande créées');

            // Créer le paiement
            $paymentCode = 'PAY-' . strtoupper(\Illuminate\Support\Str::random(12));
            $payment = Payment::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'typePayement' => $request->payment_method,
                'payment_code' => $paymentCode,
                'payment_status' => $request->payment_method === 'cash' ? 'initialisee' : 'en_attente',
            ]);

            Log::info('Paiement créé', ['payment_id' => $payment->id, 'code' => $paymentCode]);

            // Vider le panier
            $panier->items()->delete();

            Log::info('Panier vidé');

            DB::commit();

            Log::info('Commande complète - succès', ['commande_id' => $commande->id]);

            // Déclencher l'événement pour notifier les vendeurs
            \App\Events\OrderCreated::dispatch($commande);

            // Rediriger vers la page de confirmation avec possibilité de paiement
            $redirectUrl = route('commandes.show', $commande->id);

            // Si paiement mobile, initier la transaction de paiement
            if (in_array($request->payment_method, ['wave', 'orange_money', 'mtn_money', 'moov_money'])) {
                try {
                    $paymentService = new PaymentService();

                    $paymentData = [
                        'transaction_id' => $paymentCode,
                        'amount' => intval($commande->total),
                        'currency' => 'XOF',
                        'description' => "Commande #" . $commande->id . " - Supply Market",
                        'customer_name' => auth()->user()->name,
                        'customer_email' => auth()->user()->email,
                        'customer_phone' => $request->phone_payment,
                        'return_url' => route('commandes.show', $commande->id),
                        'notify_url' => route('api.payment-webhook'),
                    ];

                    // Mapper les méthodes de paiement
                    $paymentMethods = [
                        'wave' => 'createWavePayment',
                        'orange_money' => 'createOrangeMoneyPayment',
                        'mtn_money' => 'createMobileMoneyPayment',
                        'moov_money' => 'createMobileMoneyPayment',
                    ];

                    $method = $paymentMethods[$request->payment_method] ?? 'createPayment';
                    $response = $paymentService->$method($paymentData);

                    Log::info('Réponse API Paiement', $response);

                    // Si succès, rediriger vers la plateforme de paiement
                    if (isset($response['code']) && $response['code'] == 'SUCCESFUL') {
                        return redirect()->to($response['payment_url'] ?? $redirectUrl)
                            ->with('success', 'Veuillez confirmer votre paiement');
                    } else {
                        // En cas de problème, rediriger vers la commande avec un message
                        return redirect()->to($redirectUrl)
                            ->with('warning', 'Paiement en attente de confirmation');
                    }
                } catch (\Exception $paymentError) {
                    Log::error('Erreur API Paiement: ' . $paymentError->getMessage());
                    // Rediriger vers la commande même en cas d'erreur API
                    return redirect()->to($redirectUrl)
                        ->with('warning', 'Commande créée mais vérification paiement échouée. Veuillez réessayer.');
                }
            } else {
                // Paiement à la livraison (cash)
                return redirect()->to($redirectUrl)
                    ->with('success', 'Commande créée avec succès! Vous paierez à la livraison.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création commande: ' . $e->getMessage(), [
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
    public function vendeurCommandes(Request $request)
    {
        $user = auth()->user();

        // Récupérer les commandes contenant les produits du vendeur
        $query = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'ligneCommandes.produit');

        // Filtrer par statut si demandé
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Rechercher par numéro de commande ou client (en gardant le filtre des produits)
        if ($request->filled('search')) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $derniereCommandes = $query->latest()->paginate(15);

        return view('vendeur.commandes.index', compact('derniereCommandes'));
    }

    /**
     * Afficher les détails d'une commande pour un vendeur
     */
    public function vendeurCommandeDetail($id)
    {
        $user = auth()->user();
        $commande = Commande::with('user', 'ligneCommandes.produit', 'payment')->findOrFail($id);

        // Vérifier que le vendeur a au moins un produit dans cette commande
        $hasVendorProduct = $commande->ligneCommandes->some(function ($ligne) use ($user) {
            return $ligne->produit->user_id === $user->id;
        });

        if (!$hasVendorProduct) {
            abort(403, 'Non autorisé');
        }

        return view('vendeur.commandes.show', compact('commande'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateCommandeStatus(Request $request, $id)
    {
        $user = auth()->user();
        $commande = Commande::findOrFail($id);

        // Vérifier que le vendeur a au moins un produit dans cette commande
        $hasVendorProduct = $commande->ligneCommandes->some(function ($ligne) use ($user) {
            return $ligne->produit->user_id === $user->id;
        });

        if (!$hasVendorProduct) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,expediee,livree'
        ]);

        $commande->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès!');
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

    /**
     * Afficher la facture pour impression/téléchargement
     */
    public function downloadPDF($id)
    {
        $commande = Commande::findOrFail($id);

        // Vérifier que l'utilisateur est propriétaire
        if (auth()->user()->id !== $commande->user_id) {
            abort(403);
        }

        $lignes = $commande->ligneCommandes()->with('produit')->get();
        $payment = $commande->payment;

        // Calculer les sous-totaux
        $sousTotal = $lignes->sum(fn($l) => $l->quantite * $l->prix_unitaire);
        $frais = $sousTotal > 100000 ? 0 : 2500;
        $total = $sousTotal + $frais;

        return view('commandes.facture-pdf', compact('commande', 'lignes', 'sousTotal', 'frais', 'total', 'payment'));
    }

    /**
     * Retour de succès après paiement CinetPay
     */
    public function paymentSuccess($id)
    {
        $commande = Commande::findOrFail($id);

        // Vérifier que l'utilisateur est propriétaire
        if (auth()->user()->id !== $commande->user_id) {
            abort(403);
        }

        $lignes = $commande->ligneCommandes()->with('produit')->get();
        $payment = $commande->payment;

        // Si le paiement n'est pas confirmé, on attend ou on affiche un message d'attente
        if ($payment && $payment->payment_status === 'EN_ATTENTE') {
            return view('commandes.payment-pending', compact('commande', 'lignes', 'payment'));
        }

        return view('commandes.show', compact('commande', 'lignes', 'payment'));
    }

    /**
     * Annuler une commande (côté vendeur)
     */
    public function cancelCommande($id)
    {
        $user = auth()->user();
        $commande = Commande::findOrFail($id);

        // Vérifier que le vendeur a au moins un produit dans cette commande
        $hasVendorProduct = $commande->ligneCommandes->some(function ($ligne) use ($user) {
            return $ligne->produit->user_id === $user->id;
        });

        if (!$hasVendorProduct) {
            return redirect()->back()->with('error', '❌ Non autorisé');
        }

        // Une commande livrée ne peut pas être annulée
        if ($commande->statut === 'livree') {
            return redirect()->back()->with('error', '❌ Impossible d\'annuler une commande livrée');
        }

        // Une commande terminée/annulée ne peut pas être re-annulée
        if ($commande->statut === 'annulee') {
            return redirect()->back()->with('error', '❌ Cette commande est déjà annulée');
        }

        DB::beginTransaction();
        try {
            // Rétablir le stock pour les produits du vendeur seulement
            foreach ($commande->ligneCommandes as $ligne) {
                if ($ligne->produit->user_id === $user->id) {
                    $ligne->produit->increment('stock', $ligne->quantite);
                }
            }

            // Marquer la commande comme annulée avec une raison
            $commande->update(['statut' => 'annulee']);

            DB::commit();
            return redirect()->back()->with('success', '✓ Commande annulée et stock rétabli');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une commande (côté vendeur)
     */
    public function deleteCommande($id)
    {
        $user = auth()->user();
        $commande = Commande::findOrFail($id);

        // Vérifier que le vendeur a au moins un produit dans cette commande
        $hasVendorProduct = $commande->ligneCommandes->some(function ($ligne) use ($user) {
            return $ligne->produit->user_id === $user->id;
        });

        if (!$hasVendorProduct) {
            return redirect()->back()->with('error', '❌ Non autorisé');
        }

        // On ne peut supprimer que les commandes en attente ou annulées
        if (!in_array($commande->statut, ['en_attente', 'annulee'])) {
            return redirect()->back()->with('error', '❌ Impossible de supprimer une commande ' . $commande->statut);
        }

        DB::beginTransaction();
        try {
            // Rétablir le stock avant suppression
            foreach ($commande->ligneCommandes as $ligne) {
                if ($ligne->produit->user_id === $user->id) {
                    $ligne->produit->increment('stock', $ligne->quantite);
                }
            }

            // Supprimer la commande
            $commande->ligneCommandes()->delete();
            $commande->payment()->delete();
            $commande->delete();

            DB::commit();
            return redirect()->route('vendeur.commandes')->with('success', '✓ Commande supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
