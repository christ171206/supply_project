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
use Illuminate\Support\Facades\Log;

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
        try {
            $user = Auth::user();
            \Log::info('Suppression produit - Début', ['user_id' => $user->id, 'produit_id' => $id]);

            $produit = Produit::where('user_id', $user->id)->findOrFail($id);
            \Log::info('Produit trouvé', ['nom' => $produit->nom]);

            // Supprimer les images multiples
            if ($produit->images && is_array($produit->images)) {
                foreach ($produit->images as $image) {
                    // Ajouter le préfixe "produits/" s'il n'existe pas
                    $imagePath = str_starts_with($image, 'produits/') ? $image : 'produits/' . $image;
                    \Log::info('Vérification image', ['image' => $image, 'path' => $imagePath]);

                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                        \Log::info('Image supprimée', ['path' => $imagePath]);
                    }
                }
            } else if ($produit->image) {
                // Ajouter le préfixe "produits/" s'il n'existe pas
                $imagePath = str_starts_with($produit->image, 'produits/') ? $produit->image : 'produits/' . $produit->image;
                \Log::info('Vérification image legacy', ['image' => $produit->image, 'path' => $imagePath]);

                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    \Log::info('Image legacy supprimée', ['path' => $imagePath]);
                }
            }

            $produit->delete();
            \Log::info('Produit supprimé', ['produit_id' => $id]);

            return redirect()->route('vendeur.produits.index')
                ->with('success', 'Produit supprimé avec succès!');
        } catch (\Exception $e) {
            \Log::error('Erreur suppression produit', [
                'produit_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('vendeur.produits.index')
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
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
     * Exporter les statistiques en CSV ou PDF
     */
    /**
     * Exporter les statistiques en CSV ou PDF avec formats différents
     */
    public function exportStatistiques(Request $request)
    {
        $format = $request->get('format', 'csv');
        $periode = (int)$request->get('periode', 7);
        $user = Auth::user();

        // Récupérer les données complètes
        $stats = $this->getStatistiquesCompletes($user, $periode);

        switch ($format) {
            case 'csv-complet':
                return $this->exportCSVComplet($user, $stats, $periode);
            case 'pdf-complet':
                return $this->exportPDFComplet($user, $stats, $periode);
            case 'csv':
            default:
                return $this->exportCSV($user, $stats, $periode);
        }
    }

    /**
     * Récupérer toutes les statistiques complètes
     */
    private function getStatistiquesCompletes($user, $periode)
    {
        $dateDebut = now()->subDays($periode);

        // 💰 KPIs
        $totalCA = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('created_at', '>=', $dateDebut)
            ->sum('total');

        $nombreCommandes = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('created_at', '>=', $dateDebut)
            ->distinct()
            ->count('commandes.id');

        $panierMoyen = $nombreCommandes > 0 ? $totalCA / $nombreCommandes : 0;

        // ⭐ Avis
        $avis = DB::table('avis')
            ->join('produits', 'avis.produit_id', '=', 'produits.id')
            ->where('produits.user_id', $user->id)
            ->select('avis.*');

        $noteMoyenne = (clone $avis)->avg('note') ?? 0;
        $nombreAvis = (clone $avis)->count();

        // 🏆 Top 5 produits
        $topProduits = Produit::where('user_id', $user->id)
            ->with(['ligneCommandes' => function ($q) use ($dateDebut) {
                $q->whereHas('commande', function ($q2) use ($dateDebut) {
                    $q2->where('created_at', '>=', $dateDebut);
                });
            }])
            ->get()
            ->map(function ($p) {
                $p->ventes_nombre = $p->ligneCommandes->count();
                $p->ventes_total = $p->ligneCommandes->sum(function ($lc) {
                    return $lc->quantite * $lc->prix_unitaire;
                });
                return $p;
            })
            ->sortByDesc('ventes_total')
            ->take(5)
            ->values();

        // 📦 Statut des commandes
        $commandesEnAttente = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $dateDebut)->where('statut', 'en_attente')->distinct()->count('commandes.id');

        $commandesConfirmees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $dateDebut)->where('statut', 'confirmee')->distinct()->count('commandes.id');

        $commandesExpediees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $dateDebut)->where('statut', 'expediee')->distinct()->count('commandes.id');

        $commandeslivrees = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $dateDebut)->where('statut', 'livree')->distinct()->count('commandes.id');

        // 📊 Répartition par catégorie
        $repartitionCategories = [];
        $categories = Categorie::with(['produits' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->get();

        foreach ($categories as $categorie) {
            $produitsCat = $categorie->produits;
            if ($produitsCat->count() > 0) {
                $montant = 0;
                $nombre = 0;
                foreach ($produitsCat as $p) {
                    $ventes = $p->ligneCommandes->filter(function ($lc) use ($dateDebut) {
                        return $lc->commande->created_at >= $dateDebut;
                    });
                    $montant += $ventes->sum(function ($lc) {
                        return $lc->quantite * $lc->prix_unitaire;
                    });
                    $nombre += $ventes->count();
                }
                if ($montant > 0) {
                    $repartitionCategories[$categorie->nom] = [
                        'montant' => $montant,
                        'nombre' => $nombre
                    ];
                }
            }
        }

        // 📈 Évolution du CA par jour
        $evolutionCA = Commande::selectRaw('DATE(created_at) as date, SUM(total) as montant')
            ->whereHas('ligneCommandes.produit', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [date('d/m/Y', strtotime($item->date)) => $item->montant];
            });

        return [
            'totalCA' => $totalCA,
            'nombreCommandes' => $nombreCommandes,
            'panierMoyen' => $panierMoyen,
            'noteMoyenne' => $noteMoyenne,
            'nombreAvis' => $nombreAvis,
            'topProduits' => $topProduits,
            'commandesEnAttente' => $commandesEnAttente,
            'commandesConfirmees' => $commandesConfirmees,
            'commandesExpediees' => $commandesExpediees,
            'commandeslivrees' => $commandeslivrees,
            'repartitionCategories' => $repartitionCategories,
            'evolutionCA' => $evolutionCA
        ];
    }

    /**
     * Générer et télécharger CSV simple
     */
    private function exportCSV($user, $stats, $periode)
    {
        $filename = 'statistiques_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function () use ($user, $stats, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            // En-tête
            fputcsv($file, ['STATISTIQUES VENDEUR'], ';');
            fputcsv($file, ['Période: Derniers ' . $periode . ' jours'], ';');
            fputcsv($file, ['Date d\'export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, [], ';');

            // KPIs
            fputcsv($file, ['INDICATEURS CLÉS'], ';');
            fputcsv($file, ['Métrique', 'Valeur'], ';');
            fputcsv($file, ['Chiffre d\'Affaires', number_format($stats['totalCA'], 0, ',', ' ') . ' CFA'], ';');
            fputcsv($file, ['Nombre de Commandes', $stats['nombreCommandes']], ';');
            fputcsv($file, ['Panier Moyen', number_format($stats['panierMoyen'], 0, ',', ' ') . ' CFA'], ';');
            fputcsv($file, ['Note Moyenne', round($stats['noteMoyenne'], 1) . '/5'], ';');
            fputcsv($file, ['Nombre d\'Avis', $stats['nombreAvis']], ';');
            fputcsv($file, [], ';');

            // Statut des commandes
            fputcsv($file, ['STATUT DES COMMANDES'], ';');
            fputcsv($file, ['Statut', 'Nombre'], ';');
            fputcsv($file, ['En Attente', $stats['commandesEnAttente']], ';');
            fputcsv($file, ['Confirmées', $stats['commandesConfirmees']], ';');
            fputcsv($file, ['Expédiées', $stats['commandesExpediees']], ';');
            fputcsv($file, ['Livrées', $stats['commandeslivrees']], ';');
            fputcsv($file, [], ';');

            // Top produits
            fputcsv($file, ['TOP 5 PRODUITS'], ';');
            fputcsv($file, ['Position', 'Produit', 'Nombre de Ventes', 'Chiffre d\'Affaires'], ';');
            foreach ($stats['topProduits'] as $idx => $produit) {
                fputcsv($file, [$idx + 1, $produit->nom, $produit->ventes_nombre, number_format($produit->ventes_total, 0, ',', ' ') . ' CFA'], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Générer et télécharger CSV complet (avec catégories et évolution)
     */
    private function exportCSVComplet($user, $stats, $periode)
    {
        $filename = 'statistiques_complet_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function () use ($user, $stats, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            // En-tête
            fputcsv($file, ['STATISTIQUES VENDEUR COMPLÈTES'], ';');
            fputcsv($file, ['Période: Derniers ' . $periode . ' jours'], ';');
            fputcsv($file, ['Date d\'export: ' . now()->format('d/m/Y H:i:s')], ';');
            fputcsv($file, [], ';');

            // KPIs
            fputcsv($file, ['INDICATEURS CLÉS'], ';');
            fputcsv($file, ['Métrique', 'Valeur'], ';');
            fputcsv($file, ['Chiffre d\'Affaires', number_format($stats['totalCA'], 0, ',', ' ') . ' CFA'], ';');
            fputcsv($file, ['Nombre de Commandes', $stats['nombreCommandes']], ';');
            fputcsv($file, ['Panier Moyen', number_format($stats['panierMoyen'], 0, ',', ' ') . ' CFA'], ';');
            fputcsv($file, ['Note Moyenne', round($stats['noteMoyenne'], 1) . '/5'], ';');
            fputcsv($file, ['Nombre d\'Avis', $stats['nombreAvis']], ';');
            fputcsv($file, [], ';');

            // Statut des commandes
            fputcsv($file, ['STATUT DES COMMANDES'], ';');
            fputcsv($file, ['Statut', 'Nombre'], ';');
            fputcsv($file, ['En Attente', $stats['commandesEnAttente']], ';');
            fputcsv($file, ['Confirmées', $stats['commandesConfirmees']], ';');
            fputcsv($file, ['Expédiées', $stats['commandesExpediees']], ';');
            fputcsv($file, ['Livrées', $stats['commandeslivrees']], ';');
            fputcsv($file, [], ';');

            // Top produits
            fputcsv($file, ['TOP 5 PRODUITS'], ';');
            fputcsv($file, ['Position', 'Produit', 'Nombre de Ventes', 'Chiffre d\'Affaires'], ';');
            foreach ($stats['topProduits'] as $idx => $produit) {
                fputcsv($file, [$idx + 1, $produit->nom, $produit->ventes_nombre, number_format($produit->ventes_total, 0, ',', ' ') . ' CFA'], ';');
            }
            fputcsv($file, [], ';');

            // Répartition par catégorie
            fputcsv($file, ['RÉPARTITION PAR CATÉGORIE'], ';');
            fputcsv($file, ['Catégorie', 'Montant', 'Nombre de Ventes', 'Pourcentage'], ';');
            $totalCA = $stats['totalCA'];
            foreach ($stats['repartitionCategories'] as $categorie => $data) {
                $pourcentage = $totalCA > 0 ? round(($data['montant'] / $totalCA) * 100, 2) . '%' : '0%';
                fputcsv($file, [$categorie, number_format($data['montant'], 0, ',', ' ') . ' CFA', $data['nombre'], $pourcentage], ';');
            }
            fputcsv($file, [], ';');

            // Évolution du CA par jour
            fputcsv($file, ['ÉVOLUTION DU CA PAR JOUR'], ';');
            fputcsv($file, ['Date', 'Montant'], ';');
            foreach ($stats['evolutionCA'] as $date => $montant) {
                fputcsv($file, [$date, number_format($montant, 0, ',', ' ') . ' CFA'], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Générer et télécharger PDF complet (avec graphiques ASCII et tableaux)
     */
    private function exportPDFComplet($user, $stats, $periode)
    {
        // Génération du graphique ASCII pour l'évolution du CA
        $chartEvolution = $this->generateASCIIChart($stats['evolutionCA'], 'Évolution du CA (derniers ' . $periode . ' jours)');

        // Génération du graphique ASCII pour la répartition par catégorie
        $chartCategories = $this->generateASCIIChart(
            array_map(function ($v) { return $v['montant']; }, $stats['repartitionCategories']),
            'Répartition par Catégorie',
            array_keys($stats['repartitionCategories'])
        );

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Statistiques Vendeur Complètes</title>
            <style>
                * { margin: 0; padding: 0; }
                body { font-family: "Geist", Arial, sans-serif; margin: 40px; color: #0a0a0a; line-height: 1.6; }
                h1 { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0a0a0a; padding-bottom: 15px; font-size: 28px; }
                h2 { margin-top: 30px; margin-bottom: 15px; background-color: #f7f7f5; padding: 12px; border-left: 4px solid #0a0a0a; font-size: 16px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px; }
                table th, table td { padding: 10px; text-align: left; border: 1px solid #e0e0dc; }
                table th { background-color: #f7f7f5; font-weight: 600; }
                .kpi-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 25px; }
                .kpi-box { padding: 12px; border: 1px solid #e0e0dc; border-radius: 6px; }
                .kpi-label { font-size: 10px; color: #a0a09a; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; }
                .kpi-value { font-size: 18px; font-weight: 700; color: #0a0a0a; margin-top: 5px; font-family: "Geist Mono", monospace; }
                .meta { text-align: center; color: #a0a09a; font-size: 11px; margin: 25px 0; }
                .chart { margin: 25px 0; padding: 15px; background-color: #f7f7f5; border: 1px solid #e0e0dc; border-radius: 6px; }
                .chart pre { font-family: "Courier New", monospace; font-size: 11px; overflow-x: auto; }
                .status-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 25px; }
                .status-box { padding: 12px; border: 1px solid #e0e0dc; border-radius: 6px; text-align: center; }
                .status-label { font-size: 11px; color: #a0a09a; text-transform: uppercase; }
                .status-value { font-size: 20px; font-weight: 700; margin-top: 5px; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <h1>Statistiques Vendeur - Rapport Complet</h1>
            <div class="meta">
                <p>Période: Derniers ' . $periode . ' jours</p>
                <p>Date d\'export: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>

            <h2>Indicateurs Clés</h2>
            <div class="kpi-grid">
                <div class="kpi-box">
                    <div class="kpi-label">Chiffre d\'Affaires</div>
                    <div class="kpi-value">' . number_format($stats['totalCA'], 0, ',', ' ') . ' CFA</div>
                </div>
                <div class="kpi-box">
                    <div class="kpi-label">Commandes</div>
                    <div class="kpi-value">' . $stats['nombreCommandes'] . '</div>
                </div>
                <div class="kpi-box">
                    <div class="kpi-label">Panier Moyen</div>
                    <div class="kpi-value">' . number_format($stats['panierMoyen'], 0, ',', ' ') . ' CFA</div>
                </div>
                <div class="kpi-box">
                    <div class="kpi-label">Note Moyenne</div>
                    <div class="kpi-value">' . round($stats['noteMoyenne'], 1) . '/5</div>
                </div>
                <div class="kpi-box">
                    <div class="kpi-label">Avis</div>
                    <div class="kpi-value">' . $stats['nombreAvis'] . '</div>
                </div>
            </div>

            <h2>Statut des Commandes</h2>
            <div class="status-grid">
                <div class="status-box">
                    <div class="status-label">En Attente</div>
                    <div class="status-value">' . $stats['commandesEnAttente'] . '</div>
                </div>
                <div class="status-box">
                    <div class="status-label">Confirmées</div>
                    <div class="status-value">' . $stats['commandesConfirmees'] . '</div>
                </div>
                <div class="status-box">
                    <div class="status-label">Expédiées</div>
                    <div class="status-value">' . $stats['commandesExpediees'] . '</div>
                </div>
                <div class="status-box">
                    <div class="status-label">Livrées</div>
                    <div class="status-value">' . $stats['commandeslivrees'] . '</div>
                </div>
            </div>

            <h2>Top 5 Produits</h2>
            <table>
                <tr>
                    <th>Position</th>
                    <th>Produit</th>
                    <th>Ventes</th>
                    <th>Chiffre d\'Affaires</th>
                </tr>';

        foreach ($stats['topProduits'] as $idx => $produit) {
            $html .= '<tr>
                <td>' . ($idx + 1) . '</td>
                <td>' . htmlspecialchars($produit->nom) . '</td>
                <td>' . $produit->ventes_nombre . '</td>
                <td>' . number_format($produit->ventes_total, 0, ',', ' ') . ' CFA</td>
            </tr>';
        }

        $html .= '</table>

            <h2>Répartition par Catégorie</h2>
            <table>
                <tr>
                    <th>Catégorie</th>
                    <th>Montant</th>
                    <th>Ventes</th>
                    <th>Pourcentage</th>
                </tr>';

        $totalCA = $stats['totalCA'];
        foreach ($stats['repartitionCategories'] as $categorie => $data) {
            $pourcentage = $totalCA > 0 ? round(($data['montant'] / $totalCA) * 100, 2) : 0;
            $html .= '<tr>
                <td>' . htmlspecialchars($categorie) . '</td>
                <td>' . number_format($data['montant'], 0, ',', ' ') . ' CFA</td>
                <td>' . $data['nombre'] . '</td>
                <td>' . $pourcentage . '%</td>
            </tr>';
        }

        $html .= '</table>

            <h2>Graphique - Répartition par Catégorie</h2>
            <div class="chart">
                <pre>' . htmlspecialchars($chartCategories) . '</pre>
            </div>

            <h2>Évolution du Chiffre d\'Affaires</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Montant</th>
                </tr>';

        foreach ($stats['evolutionCA'] as $date => $montant) {
            $html .= '<tr>
                <td>' . htmlspecialchars($date) . '</td>
                <td>' . number_format($montant, 0, ',', ' ') . ' CFA</td>
            </tr>';
        }

        $html .= '</table>

            <h2>Graphique - Évolution du CA</h2>
            <div class="chart">
                <pre>' . htmlspecialchars($chartEvolution) . '</pre>
            </div>

        </body>
        </html>';

        return response()->view('pdf', ['html' => $html])
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="statistiques_complet_' . now()->format('Y-m-d') . '.pdf"');
    }

    /**
     * Générer un graphique ASCII en barres
     */
    private function generateASCIIChart($data, $title = '', $labels = null)
    {
        if (empty($data)) {
            return $title . "\n" . "Aucune donnée disponible";
        }

        $chart = $title . "\n";
        $chart .= str_repeat("─", 50) . "\n\n";

        if (!is_array($data) || (isset($data[0]) && is_array($data[0]))) {
            return $chart . "Format de données invalide";
        }

        $values = $this->is_assoc($data) ? array_values($data) : $data;
        $keys = $this->is_assoc($data) ? array_keys($data) : ($labels ?? array_keys($data));

        if (empty($values)) {
            return $chart . "Aucune donnée disponible";
        }

        $maxValue = max($values);
        if ($maxValue == 0) {
            $maxValue = 1;
        }

        $maxLabelWidth = max(array_map('strlen', array_map('strval', $keys)));
        $chartWidth = 40;

        foreach ($keys as $idx => $label) {
            $value = $values[$idx] ?? 0;
            $barLength = (int)(($value / $maxValue) * $chartWidth);
            $bar = str_repeat("█", max(0, $barLength));
            $label_str = substr((string)$label, 0, $maxLabelWidth);
            $label_str = str_pad($label_str, $maxLabelWidth, " ", STR_PAD_RIGHT);

            $chart .= sprintf("%s │ %s %s\n", $label_str, $bar, number_format($value, 0, ',', ' '));
        }

        $chart .= "\n" . str_repeat("─", 50) . "\n";

        return $chart;
    }

    /**
     * Helper pour vérifier si un array est associatif
     */
    private function is_assoc($arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
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

        // Récupérer les messages de cette conversation avec les produits associés
        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('from_user_id', $user->id)->where('to_user_id', $userId)
                ->orWhere('from_user_id', $userId)->where('to_user_id', $user->id);
        })
            ->with('produit')  // Charger le produit associé
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

    /**
     * Récupérer les notifications du vendeur
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = [];

        // 🔴 Messages non lus
        $unreadMessages = Message::where('to_user_id', $user->id)
            ->where('lu', false)
            ->with(['fromUser' => function ($q) {
                $q->select('id', 'name', 'shop_name');
            }, 'produit' => function ($q) {
                $q->select('id', 'nom', 'image');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Message::where('to_user_id', $user->id)->where('lu', false)->count();

        if ($unreadCount > 0) {
            $notifications[] = [
                'type' => 'messages',
                'count' => $unreadCount,
                'title' => $unreadCount . ' message' . ($unreadCount > 1 ? 's' : '') . ' non lu' . ($unreadCount > 1 ? 's' : ''),
                'icon' => 'chat-bubble-left',
                'color' => 'blue',
                'data' => $unreadMessages,
                'link' => route('vendeur.messages')
            ];
        }

        // 🛒 Commandes en attente
        $pendingOrders = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereIn('statut', ['confirmée', 'en attente', 'en cours de traitement'])
            ->with(['ligneCommandes.produit' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->select('id', 'nom', 'image');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingCount = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereIn('statut', ['confirmée', 'en attente', 'en cours de traitement'])->count();

        if ($pendingCount > 0) {
            $notifications[] = [
                'type' => 'orders',
                'count' => $pendingCount,
                'title' => $pendingCount . ' commande' . ($pendingCount > 1 ? 's' : '') . ' en attente',
                'icon' => 'shopping-cart',
                'color' => 'orange',
                'data' => $pendingOrders,
                'link' => route('vendeur.commandes')
            ];
        }

        // ⭐ Avis clients non lus
        $unreadReviews = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('lu', 0)
            ->with(['user' => function ($q) {
                $q->select('id', 'name');
            }, 'produit' => function ($q) {
                $q->select('id', 'nom', 'image');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadReviewsCount = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('lu', 0)->count();

        if ($unreadReviewsCount > 0) {
            $notifications[] = [
                'type' => 'reviews',
                'count' => $unreadReviewsCount,
                'title' => $unreadReviewsCount . ' avi' . ($unreadReviewsCount > 1 ? 's' : 's') . ' client',
                'icon' => 'star',
                'color' => 'yellow',
                'data' => $unreadReviews,
                'link' => route('vendeur.avis')
            ];
        }

        // 📦 Produits en stock critique
        $criticalStock = Produit::where('user_id', $user->id)
            ->whereRaw('stock <= stock_minimum')
            ->select('id', 'nom', 'stock', 'stock_minimum', 'image')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $criticalCount = Produit::where('user_id', $user->id)->whereRaw('stock <= stock_minimum')->count();

        if ($criticalCount > 0) {
            $notifications[] = [
                'type' => 'stock',
                'count' => $criticalCount,
                'title' => $criticalCount . ' produit' . ($criticalCount > 1 ? 's' : '') . ' en stock critique',
                'icon' => 'cube',
                'color' => 'red',
                'data' => $criticalStock,
                'link' => route('vendeur.stock')
            ];
        }

        $totalNotifications = $unreadCount + $pendingCount + $unreadReviewsCount + $criticalCount;

        return response()->json([
            'success' => true,
            'total' => $totalNotifications,
            'notifications' => $notifications,
            'timestamp' => now()
        ]);
    }
}
