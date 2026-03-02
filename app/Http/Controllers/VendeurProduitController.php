<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Message;
use App\Models\Avis;
use App\Models\User;
use App\Models\StockMouvement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendeurProduitController extends Controller
{
    /**
     * Dashboard du vendeur
     */
    public function dashboard()
    {
        $user = Auth::user();

        // 💰 Statistiques de base - Récupérer les commandes qui contiennent les produits du vendeur
        $totalVentes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('total');

        $nombreCommandes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->distinct()->count('commandes.id');

        $panierMoyen = $nombreCommandes > 0 ? $totalVentes / $nombreCommandes : 0;

        // 📊 Taux de complétion des commandes (livrées vs total)
        $commandeslivrees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'livree')->distinct()->count('commandes.id');

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
        $derniereCommandes = Commande::with('user', 'ligneCommandes.produit')
            ->whereHas('ligneCommandes.produit', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->limit(10)
            ->get();

        // 📊 Statut des commandes
        $commandesEnAttente = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'en_attente')->distinct()->count('commandes.id');

        $commandesConfirmees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'confirmee')->distinct()->count('commandes.id');

        $commandesExpediees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'expediee')->distinct()->count('commandes.id');

        // 🏆 Top 5 produits les plus vendus
        $topProduits = Produit::where('user_id', $user->id)
            ->with('categorie', 'ligneCommandes')
            ->get()
            ->map(function ($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum(function ($lc) {
                    return $lc->quantite * $lc->prix_unitaire;
                });
                return $p;
            })
            ->sortByDesc('ventes_total')
            ->take(5);

        // ⭐ Avis clients récents
        $avisRecents = Avis::whereHas('produit', function ($q) use ($user) {
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
        $user = Auth::user();

        // Statistiques de base - Récupérer les commandes qui contiennent les produits du vendeur
        $totalVentes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('total');

        $nombreCommandes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->distinct()->count('commandes.id');

        $nombreProduits = Produit::where('user_id', $user->id)->count();
        $panierMoyen = $nombreCommandes > 0 ? $totalVentes / $nombreCommandes : 0;

        // Avis
        $noteMoyenne = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('note') ?? 0;
        $nombreAvis = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Taux de complétude du profil
        $tauxCompletion = 0;
        if ($user->shop_name) $tauxCompletion += 20;
        if ($user->description) $tauxCompletion += 20;
        if ($user->phone) $tauxCompletion += 20;
        if ($user->address) $tauxCompletion += 20;
        if ($user->profile_photo) $tauxCompletion += 20;

        // Commandes par statut
        $commandesEnAttente = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'en_attente')->distinct()->count('commandes.id');

        $commandesConfirmees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'confirmee')->distinct()->count('commandes.id');

        $commandesExpediees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'expediee')->distinct()->count('commandes.id');

        $commandeslivrees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'livree')->distinct()->count('commandes.id');

        // Top produits
        $topProduits = Produit::where('user_id', $user->id)
            ->with('categorie', 'ligneCommandes')
            ->limit(5)
            ->get()
            ->map(function ($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum(function ($lc) {
                    return $lc->quantite * $lc->prix_unitaire;
                });
                return $p;
            });

        // Avis récents
        $avisRecents = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'produit')->latest()->limit(5)->get();

        return view('vendeur.apercu', compact(
            'totalVentes',
            'nombreCommandes',
            'nombreProduits',
            'panierMoyen',
            'noteMoyenne',
            'nombreAvis',
            'tauxCompletion',
            'commandesEnAttente',
            'commandesConfirmees',
            'commandesExpediees',
            'commandeslivrees',
            'topProduits',
            'avisRecents'
        ));
    }

    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        $user = Auth::user();
        $produits = Produit::where('user_id', $user->id)
            ->with('categorie')
            ->latest()
            ->paginate(15);

        $categories = Categorie::all();

        return view('vendeur.produits.index', compact('produits', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $produit = null;
        $categories = Categorie::all();
        return view('vendeur.produits.form', compact('categories', 'produit'));
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
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'stock' => $validated['stock'],
            'stock_minimum' => $validated['stock_minimum'],
            'est_actif' => $validated['est_actif'],
            'categorie_id' => $validated['categorie_id'],
        ];

        // Traiter les images multiples
        $imagesPaths = [];

        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            // Limiter à 5 images
            foreach (array_slice($uploadedImages, 0, 5) as $image) {
                if ($image->isValid()) {
                    $imagesPaths[] = $image->store('produits', 'public');
                }
            }
        }

        // Si pas d'images multiples mais une image unique (legacy)
        if (!$imagesPaths && $request->hasFile('image')) {
            $imagesPaths[] = $request->file('image')->store('produits', 'public');
            $data['image'] = $imagesPaths[0];
        }

        if ($imagesPaths) {
            $data['images'] = $imagesPaths;
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
        $user = Auth::user();
        $produit = Produit::where('user_id', $user->id)->findOrFail($id);
        return view('vendeur.produits.show', compact('produit'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $user = Auth::user();
        $produit = Produit::where('user_id', $user->id)->findOrFail($id);
        $categories = Categorie::all();
        return view('vendeur.produits.form', compact('produit', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $produit = Produit::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'est_actif' => 'required|boolean',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
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

        // Traiter les images multiples
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            if ($produit->images && is_array($produit->images)) {
                foreach ($produit->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $imagesPaths = [];
            $uploadedImages = $request->file('images');
            // Limiter à 5 images
            foreach (array_slice($uploadedImages, 0, 5) as $image) {
                if ($image->isValid()) {
                    $imagesPaths[] = $image->store('produits', 'public');
                }
            }

            if ($imagesPaths) {
                $data['images'] = $imagesPaths;
                $data['image'] = $imagesPaths[0]; // Définir la première image comme image principale
            }
        } else if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                Storage::disk('public')->delete($produit->image);
            }
            $newImage = $request->file('image')->store('produits', 'public');
            $data['image'] = $newImage;
            $data['images'] = [$newImage];
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
        $user = Auth::user();
        $produit = Produit::where('user_id', $user->id)->findOrFail($id);

        // Supprimer les images multiples
        if ($produit->images && is_array($produit->images)) {
            foreach ($produit->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        } else if ($produit->image && Storage::disk('public')->exists($produit->image)) {
            // Supprimer l'image legacy si elle existe
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
        $user = Auth::user();

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
     * Mettre à jour la photo de profil du vendeur
     */
    public function updateProfilPhoto(Request $request)
    {
        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Supprimer l'ancienne photo si elle existe
        if ($validated['profile_photo'] ?? false) {
            if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                Storage::delete($user->profile_photo);
            }

            // Stocker la nouvelle photo
            $path = $request->file('profile_photo')->store('profils/vendeurs', 'public');
            $user->update(['profile_photo' => $path]);
        }

        return redirect()->route('vendeur.profil')->with('success', 'Photo de profil mise à jour avec succès !');
    }

    /**
     * Afficher la page Gestion du Stock
     */
    public function stock(Request $request)
    {
        $user = Auth::user();

        $query = Produit::where('user_id', $user->id);

        // Filtrage
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->input('categorie'));
        }
        if ($request->filled('statut')) {
            $statut = $request->input('statut');
            if ($statut === 'critique') {
                $query->where('stock', 0);
            } elseif ($statut === 'faible') {
                $query->whereBetween('stock', [1, DB::raw('stock_minimum')]);
            } elseif ($statut === 'suffisant') {
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
        $user = Auth::user();
        $periode = (int)$request->get('periode', 7);
        $dateDebut = now()->subDays($periode);

        // CA Total - Commandes contenant les produits du vendeur
        $totalCA = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('created_at', '>=', $dateDebut)
            ->sum('total');

        // Nombre de commandes
        $nombreCommandes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('created_at', '>=', $dateDebut)
            ->distinct()
            ->count('commandes.id');

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
            ->with(['ligneCommandes' => function ($q) use ($periode) {
                $q->whereHas('commande', function ($q2) use ($periode) {
                    $q2->where('created_at', '>=', now()->subDays($periode));
                });
            }])
            ->limit(5)
            ->get()
            ->map(function ($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum(function ($lc) {
                    return $lc->quantite * $lc->prix_unitaire;
                });
                return $p;
            });

        // Commandes par statut
        $commandesEnAttente = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'en_attente')->distinct()->count('commandes.id');

        $commandesConfirmees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'confirmee')->distinct()->count('commandes.id');

        $commandesExpediees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'expediee')->distinct()->count('commandes.id');

        $commandeslivrees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'livree')->distinct()->count('commandes.id');

        // Ventes par catégorie
        $ventesCategories = Categorie::with(['produits' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->get();

        // 📊 Données pour les graphiques
        // Évolution du CA par jour
        $ventesParJour = Commande::selectRaw('DATE(created_at) as date, SUM(total) as montant')
            ->whereHas('ligneCommandes.produit', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartDates = $ventesParJour->pluck('date')->map(fn($d) => date('d/m', strtotime($d)))->toArray();
        $chartVentes = $ventesParJour->pluck('montant')->toArray();

        // Ventes par catégorie
        $ventesParCategorie = [];
        $donneesCategories = [];
        $couleursCategories = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

        foreach ($ventesCategories as $index => $categorie) {
            $produitsCat = $categorie->produits->filter(function ($p) use ($user) {
                return $p->user_id === $user->id;
            });

            if ($produitsCat->count() > 0) {
                $montant = 0;
                foreach ($produitsCat as $p) {
                    $montant += $p->ligneCommandes->sum(function ($lc) {
                        return $lc->quantite * $lc->prix_unitaire;
                    });
                }
                $ventesParCategorie[$categorie->nom] = $montant;
                $donneesCategories[] = [
                    'label' => $categorie->nom,
                    'value' => $montant,
                    'color' => $couleursCategories[$index % count($couleursCategories)]
                ];
            }
        }

        return view('vendeur.statistiques', compact(
            'totalCA',
            'nombreCommandes',
            'panierMoyen',
            'noteMoyenne',
            'nombreAvis',
            'topProduits',
            'commandesEnAttente',
            'commandesConfirmees',
            'commandesExpediees',
            'commandeslivrees',
            'ventesCategories',
            'chartDates',
            'chartVentes',
            'ventesParCategorie',
            'donneesCategories',
            'periode'
        ));
    }

    /**
     * Afficher les Messages
     */
    public function messages(Request $request)
    {
        $user = Auth::user();
        $filtre = $request->get('filtre', 'tous');

        // Récupérer toutes les conversations avec les clients
        $conversations = Message::where(function ($query) use ($user) {
            $query->where('from_user_id', $user->id)
                ->orWhere('to_user_id', $user->id);
        })
            ->latest()
            ->get()
            ->unique(function ($message) use ($user) {
                // Créer une clé unique pour chaque conversation
                return $message->from_user_id === $user->id
                    ? min($user->id, $message->to_user_id) . '-' . max($user->id, $message->to_user_id)
                    : min($message->from_user_id, $user->id) . '-' . max($message->from_user_id, $user->id);
            })
            ->values()
            ->map(function ($message) use ($user) {
                // Récupérer l'autre utilisateur de la conversation
                $otherUserId = $message->from_user_id === $user->id ? $message->to_user_id : $message->from_user_id;
                $otherUser = User::find($otherUserId);

                // Récupérer le dernier message
                $lastMessage = Message::where(function ($query) use ($user, $otherUserId) {
                    $query->where('from_user_id', $user->id)->where('to_user_id', $otherUserId)
                        ->orWhere('from_user_id', $otherUserId)->where('to_user_id', $user->id);
                })->latest()->first();

                // Récupérer le nombre de messages non lus
                $unreadCount = Message::where('from_user_id', $otherUserId)
                    ->where('to_user_id', $user->id)
                    ->where('lu', false)
                    ->count();

                // Récupérer le produit associé (via la commande)
                $produit = null;
                if ($message->commande_id && $message->commande) {
                    // Obtenir le premier produit de la commande
                    $ligneCommande = $message->commande->ligneCommandes()->first();
                    if ($ligneCommande) {
                        $produit = $ligneCommande->produit;
                    }
                }

                return [
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'produit' => $produit,
                ];
            });

        // Filtrer si demandé
        if ($filtre === 'non_lus') {
            $conversations = $conversations->filter(function ($conv) {
                return $conv['unread_count'] > 0;
            });
        }

        $messagesNonLus = Message::where('to_user_id', $user->id)->where('lu', false)->count();
        $messagesTotal = Message::where(function ($query) use ($user) {
            $query->where('from_user_id', $user->id)
                ->orWhere('to_user_id', $user->id);
        })->count();

        return view('vendeur.messages', compact('conversations', 'messagesNonLus', 'messagesTotal'));
    }

    /**
     * Afficher une conversation détaillée avec un client
     */
    public function messagesShow($userId)
    {
        $user = Auth::user();
        $client = User::findOrFail($userId);

        // Récupérer les messages de cette conversation
        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('from_user_id', $user->id)->where('to_user_id', $userId)
                ->orWhere('from_user_id', $userId)->where('to_user_id', $user->id);
        })
            ->orderBy('created_at', 'asc')
            ->get();

        // Récupérer le produit associé à cette conversation (du premier message avec commande_id)
        $produit = null;
        $firstMessageWithCommande = $messages->where('commande_id', '!=', null)->first();
        if ($firstMessageWithCommande && $firstMessageWithCommande->commande) {
            $ligneCommande = $firstMessageWithCommande->commande->ligneCommandes()->first();
            if ($ligneCommande) {
                $produit = $ligneCommande->produit;
            }
        }

        // Récupérer les commandes du client contenant les produits du vendeur
        $commandes = Commande::where('user_id', $userId)
            ->whereHas('ligneCommandes.produit', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('ligneCommandes.produit')
            ->latest()
            ->limit(5)
            ->get();

        // Marquer les messages reçus comme lus
        Message::where('from_user_id', $userId)
            ->where('to_user_id', $user->id)
            ->where('lu', false)
            ->update(['lu' => true]);

        return view('vendeur.messages.show', compact('client', 'messages', 'user', 'produit', 'commandes'));
    }

    /**
     * Envoyer un message à un client
     */
    public function messageSend(Request $request, $userId)
    {
        $validated = $request->validate([
            'contenu' => 'required|string|min:1|max:5000',
        ]);

        $user = Auth::user();
        $client = User::findOrFail($userId);

        Message::create([
            'from_user_id' => $user->id,
            'to_user_id' => $userId,
            'contenu' => $validated['contenu'],
            'lu' => false,
        ]);

        return redirect()->route('vendeur.messages.show', $userId)
            ->with('success', '✓ Message envoyé avec succès!');
    }

    /**
     * Supprimer un message
     */
    public function messageDelete($messageId)
    {
        $user = Auth::user();
        $message = Message::findOrFail($messageId);

        // Vérifier que l'utilisateur est l'auteur ou le destinataire
        if ($message->from_user_id !== $user->id && $message->to_user_id !== $user->id) {
            return redirect()->back()->with('error', '❌ Vous n\'avez pas la permission de supprimer ce message');
        }

        $userId = $message->from_user_id === $user->id ? $message->to_user_id : $message->from_user_id;
        $message->delete();

        return redirect()->route('vendeur.messages.show', $userId)
            ->with('success', '✓ Message supprimé!');
    }

    /**
     * Afficher les Avis
     */
    public function avis(Request $request)
    {
        $user = Auth::user();

        $avis = DB::table('avis')
            ->join('produits', 'avis.produit_id', '=', 'produits.id')
            ->join('users', 'avis.user_id', '=', 'users.id')
            ->where('produits.user_id', $user->id)
            ->select('avis.*', 'users.name as user_name', 'users.email as user_email', 'produits.nom as produit_nom')
            ->latest('avis.created_at')
            ->paginate(15);

        // Récupérer les avis complets avec relations
        $avisComplets = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('user', 'produit')->latest()->paginate(15);

        $noteMoyenne = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('note') ?? 0;

        $nombreAvis = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $avisParNote = [];
        for ($i = 1; $i <= 5; $i++) {
            $avisParNote[$i] = Avis::whereHas('produit', function ($q) use ($user) {
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
        $user = Auth::user();

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
        $user = Auth::user();

        // Supprimer tous les produits
        Produit::where('user_id', $user->id)->delete();

        // Marquer le compte comme inactif (optionnel - laisser tracer)
        // $user->delete();

        \Illuminate\Support\Facades\Auth::logout();

        return redirect('/')->with('success', 'Votre boutique a été supprimée avec succès.');
    }

    /**
     * Historique des mouvements de stock
     */
    public function historique(Request $request)
    {
        $user = Auth::user();
        $stockService = new StockService();

        // Récupérer les produits du vendeur
        $produits = Produit::where('user_id', $user->id)->orderBy('nom')->get();

        // Récupérer les mouvements de stock du vendeur
        $query = StockMouvement::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['produit', 'user', 'commande']);

        // Appliquer les filtres
        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->input('produit_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('motif')) {
            $query->where('motif', $request->input('motif'));
        }

        // Paginer les résultats
        $mouvements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('vendeur.historique', [
            'mouvements' => $mouvements,
            'produits' => $produits,
        ]);
    }

    /**
     * Basculer vers le mode client
     */
    public function switchToClient(Request $request)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est authentifié et est vendeur
        if (!$user || $user->role !== 'vendor') {
            return redirect('/login');
        }

        try {
            // Changer le rôle de l'utilisateur à client
            $user->update(['role' => 'client']);

            // Recharger l'utilisateur
            $user = $user->fresh();
            Auth::login($user, true);

            return redirect('/dashboard')->with('success', 'Vous êtes maintenant en mode client.');
        } catch (\Exception $e) {
            \Log::error('Erreur basculement mode client: ' . $e->getMessage());
            return redirect('/')->with('error', 'Erreur lors du basculement en mode client.');
        }
    }
}
