<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Afficher la page d'accueil avec les produits en vedette
     */
    public function index()
    {
        // Rediriger les administrateurs vers le dashboard admin
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }

        // Rediriger les vendeurs vers le dashboard
        if (auth()->check() && auth()->user()->role === 'vendor') {
            return redirect()->route('vendeur.dashboard');
        }

        $categories = Categorie::all();
        $produits = Produit::with('vendeur')->latest()->limit(8)->get();

        $data = [
            'produits' => $produits,
            'categories' => $categories,
        ];

        return view('accueil', $data);
    }

    /**
     * Afficher le catalogue avec filtres
     */
    public function catalogue(Request $request)
    {
        $query = Produit::with('vendeur');
        $categories = Categorie::all();

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

        // Recherche
        if ($request->has('recherche') && $request->recherche) {
            $terme = $request->recherche;
            $query->where('nom', 'like', "%{$terme}%")
                ->orWhere('description', 'like', "%{$terme}%");
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
     */
    public function show($id)
    {
        $produit = Produit::with('vendeur')->findOrFail($id);
        $produitsSimilaires = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();
        $avis = $produit->avis()
            ->with('user')
            ->latest()
            ->paginate(5);

        // Statistiques des avis
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
