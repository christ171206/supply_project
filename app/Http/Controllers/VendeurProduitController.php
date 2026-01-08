<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Message;
use App\Models\Avis;
use App\Models\StockMouvement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VendeurProduitController extends Controller
{
    /**
     * Dashboard du vendeur
     */
    public function dashboard()
    {
        $user = auth()->user();

        // 💰 Statistiques de base
        $totalVentes = Commande::where('user_id', $user->id)->sum('total');
        $nombreCommandes = Commande::where('user_id', $user->id)->count();
        $panierMoyen = $nombreCommandes > 0 ? $totalVentes / $nombreCommandes : 0;

        // 📊 Taux de complétion des commandes (livrées vs total)
        $commandeslivrees = Commande::where('user_id', $user->id)->where('statut', 'livree')->count();
        $tauxCompletion = $nombreCommandes > 0 ? round(($commandeslivrees / $nombreCommandes) * 100) : 0;

        // 📦 Produits et stock
        $produitsTotal = Produit::where('user_id', $user->id)->count();
        $stockFaible = Produit::where('user_id', $user->id)->whereRaw('stock <= stock_minimum')->count();

        // 🚨 Produits avec stock faible (pour l'alerte détaillée)
        $produitsStockFaible = Produit::where('user_id', $user->id)
                                      ->whereRaw('stock <= stock_minimum')
                                      ->orderBy('stock', 'asc')
                                      ->limit(5)
                                      ->get();

        // 📋 Dernières commandes (avec détails client)
        $derniereCommandes = Commande::with('user')
                                    ->where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        // 📊 Statut des commandes
        $commandesEnAttente = Commande::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $commandesConfirmees = Commande::where('user_id', $user->id)->where('statut', 'confirmee')->count();
        $commandesExpediees = Commande::where('user_id', $user->id)->where('statut', 'expediee')->count();

        // 🏆 Top 5 produits les plus vendus
        $topProduits = Produit::where('user_id', $user->id)
            ->with('categorie', 'ligneCommandes')
            ->get()
            ->map(function($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum(function($lc) {
                    return $lc->quantite * $lc->prix_unitaire;
                });
                return $p;
            })
            ->sortByDesc('ventes_total')
            ->take(5);

        // ⭐ Avis clients récents
        $avisRecents = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'produit')
          ->latest()
          ->limit(5)
          ->get();

        return view('vendeur.dashboard', compact(
            'totalVentes',
            'nombreCommandes',
            'panierMoyen',
            'tauxCompletion',
            'produitsTotal',
            'stockFaible',
            'produitsStockFaible',
            'derniereCommandes',
            'commandesEnAttente',
            'commandesConfirmees',
            'commandesExpediees',
            'commandeslivrees',
            'topProduits',
            'avisRecents'
        ));
    }

    /**
     * Aperçu de la boutique
     */
    public function apercu()
    {
        $user = auth()->user();

        // Statistiques de base
        $totalVentes = Commande::where('user_id', $user->id)->sum('total');
        $nombreCommandes = Commande::where('user_id', $user->id)->count();
        $nombreProduits = Produit::where('user_id', $user->id)->count();
        $panierMoyen = $nombreCommandes > 0 ? $totalVentes / $nombreCommandes : 0;

        // Avis
        $noteMoyenne = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('note') ?? 0;
        $nombreAvis = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Taux de complétude du profil
        $tauxCompletion = 0;
        if ($user->boutique_nom) $tauxCompletion += 20;
        if ($user->boutique_description) $tauxCompletion += 20;
        if ($user->telephone) $tauxCompletion += 20;
        if ($user->adresse) $tauxCompletion += 20;
        if ($user->avatar) $tauxCompletion += 20;

        // Commandes par statut
        $commandesEnAttente = Commande::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $commandesConfirmees = Commande::where('user_id', $user->id)->where('statut', 'confirmee')->count();
        $commandesExpediees = Commande::where('user_id', $user->id)->where('statut', 'expediee')->count();
        $commandeslivrees = Commande::where('user_id', $user->id)->where('statut', 'livree')->count();

        // Top produits
        $topProduits = Produit::where('user_id', $user->id)
            ->with('categorie')
            ->limit(5)
            ->get()
            ->map(function($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum('prix_unitaire');
                return $p;
            });

        // Avis récents
        $avisRecents = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'produit')->latest()->limit(5)->get();

        return view('vendeur.apercu', compact(
            'totalVentes', 'nombreCommandes', 'nombreProduits', 'panierMoyen',
            'noteMoyenne', 'nombreAvis', 'tauxCompletion', 'commandesEnAttente',
            'commandesConfirmees', 'commandesExpediees', 'commandeslivrees',
            'topProduits', 'avisRecents'
        ));
    }

    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        $produits = Produit::with('categorie')
                          ->latest()
                          ->paginate(10);

        return view('vendeur.produits.index', compact('produits'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('vendeur.produits.form', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'est_actif' => 'required|boolean',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'stock' => $validated['stock'],
            'stock_minimum' => $validated['stock_minimum'],
            'est_actif' => $validated['est_actif'],
            'categorie_id' => $validated['categorie_id'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit = Produit::create($data);

        return redirect()->route('vendeur.produits.index')
                       ->with('success', 'Produit créé avec succès!');
    }

    /**
     * Afficher les détails d'un produit
     */
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return view('vendeur.produits.show', compact('produit'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $categories = Categorie::all();
        return view('vendeur.produits.form', compact('produit', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'est_actif' => 'required|boolean',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'stock' => $validated['stock'],
            'stock_minimum' => $validated['stock_minimum'],
            'est_actif' => $validated['est_actif'],
            'categorie_id' => $validated['categorie_id'],
        ];

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                Storage::disk('public')->delete($produit->image);
            }
            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($data);

        return redirect()->route('vendeur.produits.index')
                       ->with('success', 'Produit mis à jour avec succès!');
    }

    /**
     * Supprimer un produit
     */
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);

        // Supprimer l'image si elle existe
        if ($produit->image && Storage::disk('public')->exists($produit->image)) {
            Storage::disk('public')->delete($produit->image);
        }

        $produit->delete();

        return redirect()->route('vendeur.produits.index')
                       ->with('success', 'Produit supprimé avec succès!');
    }

    /**
     * Afficher le profil du vendeur
     */
    public function profil()
    {
        return view('vendeur.profil');
    }

    /**
     * Mettre à jour le profil du vendeur
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'shop_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return redirect()->route('vendeur.profil')
                       ->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Afficher la page Gestion du Stock
     */
    public function stock(Request $request)
    {
        $user = auth()->user();

        $query = Produit::where('user_id', $user->id);

        // Filtrage
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }
        if ($request->filled('statut')) {
            if ($request->statut === 'critique') {
                $query->where('stock', 0);
            } elseif ($request->statut === 'faible') {
                $query->whereBetween('stock', [1, DB::raw('stock_minimum')]);
            } elseif ($request->statut === 'suffisant') {
                $query->whereRaw('stock > stock_minimum');
            }
        }

        $produits = $query->with('categorie')->paginate(15);
        $categories = Categorie::all();

        $produitsTotal = Produit::where('user_id', $user->id)->count();
        $critiques = Produit::where('user_id', $user->id)->where('stock', 0)->count();
        $suffisants = Produit::where('user_id', $user->id)->whereRaw('stock > stock_minimum')->count();

        return view('vendeur.stock', compact('produits', 'categories', 'produitsTotal', 'critiques', 'suffisants'));
    }

    /**
     * Afficher la page Statistiques
     */
    public function statistiques(Request $request)
    {
        $user = auth()->user();
        $periode = (int)$request->get('periode', 7);
        $dateDebut = now()->subDays($periode);

        // CA Total
        $totalCA = Commande::where('user_id', $user->id)
            ->where('created_at', '>=', $dateDebut)
            ->sum('total');

        // Nombre de commandes
        $nombreCommandes = Commande::where('user_id', $user->id)
            ->where('created_at', '>=', $dateDebut)
            ->count();

        // Panier moyen
        $panierMoyen = $nombreCommandes > 0 ? $totalCA / $nombreCommandes : 0;

        // Avis
        $avis = DB::table('avis')
            ->join('produits', 'avis.produit_id', '=', 'produits.id')
            ->where('produits.user_id', $user->id)
            ->select('avis.*');

        $noteMoyenne = (clone $avis)->avg('note') ?? 0;
        $nombreAvis = (clone $avis)->count();

        // Top produits
        $topProduits = Produit::where('user_id', $user->id)
            ->with(['ligneCommandes' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('created_at', '>=', now()->subDays(request('periode', 7)));
                });
            }])
            ->limit(5)
            ->get()
            ->map(function($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum('prix_unitaire');
                return $p;
            });

        // Commandes par statut
        $commandesEnAttente = Commande::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $commandesConfirmees = Commande::where('user_id', $user->id)->where('statut', 'confirmee')->count();
        $commandesExpediees = Commande::where('user_id', $user->id)->where('statut', 'expediee')->count();
        $commandeslivrees = Commande::where('user_id', $user->id)->where('statut', 'livree')->count();

        // Ventes par catégorie
        $ventesCategories = Categorie::with(['produits' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->get();

        return view('vendeur.statistiques', compact(
            'totalCA', 'nombreCommandes', 'panierMoyen', 'noteMoyenne', 'nombreAvis',
            'topProduits', 'commandesEnAttente', 'commandesConfirmees', 'commandesExpediees',
            'commandeslivrees', 'ventesCategories'
        ));
    }

    /**
     * Afficher les Messages
     */
    public function messages(Request $request)
    {
        $user = auth()->user();
        $filtre = $request->get('filtre', 'tous');

        $query = Message::where('to_user_id', $user->id);

        if ($filtre === 'non_lus') {
            $query->where('lu', false);
        }

        $messages = $query->with('fromUser')->latest()->paginate(20);
        $messagesNonLus = Message::where('to_user_id', $user->id)->where('lu', false)->count();
        $messagesTotal = Message::where('to_user_id', $user->id)->count();

        return view('vendeur.messages', compact('messages', 'messagesNonLus', 'messagesTotal'));
    }

    /**
     * Afficher les Avis
     */
    public function avis(Request $request)
    {
        $user = auth()->user();

        $avis = DB::table('avis')
            ->join('produits', 'avis.produit_id', '=', 'produits.id')
            ->join('users', 'avis.user_id', '=', 'users.id')
            ->where('produits.user_id', $user->id)
            ->select('avis.*', 'users.name as user_name', 'users.email as user_email', 'produits.nom as produit_nom')
            ->latest('avis.created_at')
            ->paginate(15);

        // Récupérer les avis complets avec relations
        $avisComplets = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'produit')->latest()->paginate(15);

        $noteMoyenne = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('note') ?? 0;

        $nombreAvis = Avis::whereHas('produit', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $avisParNote = [];
        for ($i = 1; $i <= 5; $i++) {
            $avisParNote[$i] = Avis::whereHas('produit', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('note', $i)->count();
        }

        return view('vendeur.avis', compact('avisComplets', 'noteMoyenne', 'nombreAvis', 'avisParNote'));
    }

    /**
     * Afficher les Paramètres
     */
    public function parametres()
    {
        return view('vendeur.parametres');
    }

    /**
     * Mettre à jour les Paramètres
     */
    public function updateParametres(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'boutique_nom' => 'nullable|string|max:255',
            'boutique_description' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'stock_minimum_defaut' => 'nullable|integer|min:0',
        ]);

        $user->update($validated);

        return redirect()->route('vendeur.parametres')
                       ->with('success', 'Paramètres mis à jour avec succès!');
    }

    /**
     * Supprimer la boutique complètement
     */
    public function deleteShop()
    {
        $user = auth()->user();

        // Supprimer tous les produits
        Produit::where('user_id', $user->id)->delete();

        // Marquer le compte comme inactif (optionnel - laisser tracer)
        // $user->delete();

        auth()->logout();

        return redirect('/')->with('success', 'Votre boutique a été supprimée avec succès.');
    }

    /**
     * Historique des mouvements de stock
     */
    public function historique(Request $request)
    {
        $user = auth()->user();
        $stockService = new StockService();

        // Récupérer les produits du vendeur
        $produits = Produit::where('user_id', $user->id)->orderBy('nom')->get();

        // Récupérer les mouvements de stock du vendeur
        $query = StockMouvement::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['produit', 'user', 'commande']);

        // Appliquer les filtres
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('motif')) {
            $query->where('motif', $request->motif);
        }

        // Paginer les résultats
        $mouvements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('vendeur.historique', [
            'mouvements' => $mouvements,
            'produits' => $produits,
        ]);
    }
}
