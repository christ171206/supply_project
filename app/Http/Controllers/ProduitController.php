<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProduitController extends Controller
{
    /**
     * Afficher la page d'accueil avec produits en vedette
     * Utilise le cache pour catégories (change rarement)
     */
    public function index()
    {
        // Rediriger les administrateurs
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }

        // Rediriger les vendeurs
        if (auth()->check() && auth()->user()->role === 'vendor') {
            return redirect()->route('vendeur.dashboard');
        }

        // Cache categories pour 24h
        $categories = Cache::remember('categories_homepage', 86400, function () {
            return Categorie::select('id', 'nom', 'image')->get();
        });

        // Eager loading + select colonnes essentielles seulement
        $produits = Produit::select('id', 'categorie_id', 'user_id', 'nom', 'slug', 'description', 'prix', 'stock', 'image', 'images', 'est_actif')
            ->with('vendeur:id,name,shop_name')
            ->where('est_actif', true)
            ->latest()
            ->limit(8)
            ->get();

        $data = [
            'produits' => $produits,
            'categories' => $categories,
            'total_produits' => Cache::remember('total_produits', 86400, fn() => Produit::where('est_actif', true)->count()),
            'total_vendeurs' => Cache::remember('total_vendeurs', 86400, fn() => User::where('role', 'vendor')->where('vendor_status', 'approved')->count()),
        ];

        return view('accueil', $data);
    }

    /**
     * Afficher le catalogue avec filtres
     * Eager loading + pagination optimization
     */
    public function catalogue(Request $request)
    {
        // Cache categories
        $categories = Cache::remember('categories_catalogue', 86400, function () {
            return Categorie::select('id', 'nom', 'image')->get();
        });

        // Optimize: select seulement colonnes nécessaires
        $query = Produit::select('id', 'categorie_id', 'user_id', 'nom', 'slug', 'description', 'prix', 'stock', 'image', 'images', 'est_actif', 'created_at')
            ->with('vendeur:id,name,shop_name')
            ->where('est_actif', true);

        // Filtrer par catégorie
        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtrer par prix
        if ($request->has('prix_min') && $request->prix_min) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max') && $request->prix_max) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Recherche (optimized avec index on nom)
        if ($request->has('recherche') && $request->recherche) {
            $terme = $request->recherche;
            $query->where(function ($q) use ($terme) {
                $q->where('nom', 'like', "%{$terme}%")
                    ->orWhere('description', 'like', "%{$terme}%");
            });
        }

        // Tri
        $tri = $request->get('tri', 'latest');
        switch ($tri) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            default:
                $query->latest();
        }

        $produits = $query->paginate(12);

        return view('produits.catalogue', [
            'produits' => $produits,
            'categories' => $categories,
        ]);
    }

    /**
     * Afficher les détails d'un produit
     * Eager loading complet pour éviter N+1 queries
     */
    public function show($id)
    {
        // Eager load vendeur + avis + user des avis + categorie
        $produit = Produit::with([
            'vendeur:id,name,shop_name,profile_photo',
            'avis.user:id,name,profile_photo',
            'categorie:id,nom'
        ])->findOrFail($id);

        // Produits similaires avec eager loading
        $produitsSimilaires = Produit::select('id', 'categorie_id', 'user_id', 'nom', 'slug', 'description', 'prix', 'stock', 'image', 'est_actif')
            ->with('vendeur:id,name,shop_name')
            ->where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $id)
            ->where('est_actif', true)
            ->limit(4)
            ->get();

        // Récupérer avis avec pagination
        $avis = $produit->avis()
            ->with('user:id,name,profile_photo')
            ->latest()
            ->paginate(5);

        // Stats avis (calcul rapide)
        $allAvis = $produit->avis()->get();
        $noteMoyenne = round($allAvis->avg('note') ?? 0, 1);
        $nombreAvis = $allAvis->count();
        $distributionNotes = [
            5 => $allAvis->where('note', 5)->count(),
            4 => $allAvis->where('note', 4)->count(),
            3 => $allAvis->where('note', 3)->count(),
            2 => $allAvis->where('note', 2)->count(),
            1 => $allAvis->where('note', 1)->count(),
        ];

        return view('produits.show', [
            'produit' => $produit,
            'produitsSimilaires' => $produitsSimilaires,
            'avis' => $avis,
            'noteMoyenne' => $noteMoyenne,
            'nombreAvis' => $nombreAvis,
            'distributionNotes' => $distributionNotes,
        ]);
    }
}
