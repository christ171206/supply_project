<?php

namespace App\Http\Controllers\Vendeur;

use App\Models\Produit;
use App\Models\StockMouvement;
use Illuminate\Routing\Controller;

class StockController extends Controller
{
    /**
     * Dashboard avec alertes de stock
     */
    public function dashboard()
    {
        $user = auth()->user();
        $produits = Produit::where('user_id', $user->id)->get();

        return view('vendeur.dashboard', [
            'total_produits' => $produits->count(),
            'produits_en_stock' => $produits->where('stock', '>', 0)->count(),
            'stock_critique' => $produits->filter(fn($p) => $p->isStockCritique())->count(),
            'produits_critiques' => $produits->filter(fn($p) => $p->isStockCritique())->values(),
        ]);
    }

    /**
     * Page des alertes de stock
     */
    public function alertes()
    {
        $user = auth()->user();
        
        $produits_critiques = Produit::where('user_id', $user->id)
            ->whereRaw('stock <= stock_minimum')
            ->orderBy('stock', 'asc')
            ->get();

        $produits_rupture = $produits_critiques->where('stock', 0);
        $produits_alerte = $produits_critiques->where('stock', '>', 0);

        return view('vendeur.stock.alertes', [
            'produits_rupture' => $produits_rupture,
            'produits_alerte' => $produits_alerte,
        ]);
    }

    /**
     * Historique complet des mouvements de stock
     */
    public function historique()
    {
        $user = auth()->user();
        
        $query = StockMouvement::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // Filtres
        if (request('produit_id')) {
            $query->where('produit_id', request('produit_id'));
        }
        
        if (request('type')) {
            $query->where('type', request('type'));
        }
        
        if (request('motif')) {
            $query->where('motif', request('motif'));
        }
        
        if (request('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }
        
        if (request('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $mouvements = $query->latest('created_at')->paginate(20);
        $produits = Produit::where('user_id', $user->id)->pluck('nom', 'id');

        return view('vendeur.stock.historique', compact('mouvements', 'produits'));
    }

    /**
     * Vue détail d'un produit avec son historique
     */
    public function detailProduit($id)
    {
        $user = auth()->user();
        $produit = Produit::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $mouvements = $produit->mouvementsStock()
            ->latest('created_at')
            ->paginate(15);

        return view('vendeur.stock.detail-produit', compact('produit', 'mouvements'));
    }

    /**
     * Statistiques de stock
     */
    public function statistiques()
    {
        $user = auth()->user();
        $produits = Produit::where('user_id', $user->id)->get();

        $stats = [
            'total_produits' => $produits->count(),
            'stock_total_unite' => $produits->sum('stock'),
            'produits_en_stock' => $produits->where('stock', '>', 0)->count(),
            'produits_rupture' => $produits->where('stock', 0)->count(),
            'produits_critique' => $produits->filter(fn($p) => $p->isStockCritique())->count(),
            'valeur_stock_total' => $produits->sum(fn($p) => $p->stock * $p->prix),
        ];

        // Mouvements ce mois
        $mouvements_mois = StockMouvement::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $stats['mouvements_mois'] = $mouvements_mois->count();
        $stats['entrees_mois'] = $mouvements_mois->where('type', 'entrée')->sum('quantite');
        $stats['sorties_mois'] = $mouvements_mois->where('type', 'sortie')->sum('quantite');

        return view('vendeur.stock.statistiques', compact('stats', 'produits'));
    }
}
