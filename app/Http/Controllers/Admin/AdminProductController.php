<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produit;
use App\Models\StockAlert;
use App\Models\Stock;
use App\Models\StockMouvement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminProductController extends Controller
{
    /**
     * Lister tous les produits
     */
    public function index(Request $request)
    {
        $query = Produit::with('vendeur', 'categorie');

        // Recherche (groupée pour OR condition)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtres (toujours combinés avec AND)
        if ($request->filled('vendor_id')) {
            $query->where('user_id', $request->input('vendor_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('categorie_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('est_actif', $request->input('status') === 'active');
        }

        $produits = $query->paginate(15);

        return view('admin.products.index', [
            'produits' => $produits,
        ]);
    }

    /**
     * Montrer les détails d'un produit
     */
    public function show(Produit $produit)
    {
        $produit->load('vendeur', 'categorie', 'stocks', 'mouvementsStock');
        $stockAlert = StockAlert::where('produit_id', $produit->id)->first();
        $stockHistory = $produit->mouvementsStock()->latest()->get();

        return view('admin.products.show', [
            'produit' => $produit,
            'stockAlert' => $stockAlert,
            'stockHistory' => $stockHistory,
        ]);
    }

    /**
     * Ajuster le stock manuellement
     */
    public function adjustStock(Request $request, Produit $produit)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'reason' => 'required|string', // inventory_error, loss, damage, correction, etc.
        ]);

        $currentStock = $produit->stock;
        $newStock = $currentStock + $request->input('quantity');
        $reason = $request->input('reason');

        $produit->update(['stock' => $newStock]);

        // Enregistrer le mouvement
        StockMouvement::create([
            'produit_id' => $produit->id,
            'quantity' => $request->input('quantity'),
            'type' => $request->input('quantity') > 0 ? 'addition' : 'withdrawal',
            'reason' => $reason,
            'previous_stock' => $currentStock,
            'new_stock' => $newStock,
            'notes' => $request->input('notes', ''),
        ]);

        return redirect()->back()->with('success', 'Stock ajusté avec succès.');
    }

    /**
     * Configurer les seuils d'alerte
     */
    public function configureAlert(Request $request, Produit $produit)
    {
        $request->validate([
            'alert_threshold' => 'required|integer|min:1',
            'reorder_quantity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        StockAlert::updateOrCreate(
            ['produit_id' => $produit->id],
            [
                'alert_threshold' => $request->input('alert_threshold'),
                'reorder_quantity' => $request->input('reorder_quantity'),
                'is_active' => $request->input('is_active', true),
            ]
        );

        return redirect()->back()->with('success', 'Seuil d\'alerte configuré.');
    }

    /**
     * Voir les produits en stock critique
     */
    public function criticalStock()
    {
        $alerts = StockAlert::where('is_active', true)
            ->with('produit')
            ->get()
            ->filter(fn($alert) => $alert->isStockBelowThreshold());

        return view('admin.products.critical-stock', [
            'alerts' => $alerts,
        ]);
    }

    /**
     * Historique des mouvements de stock
     */
    public function stockHistory(Produit $produit)
    {
        $mouvements = StockMouvement::where('produit_id', $produit->id)
            ->latest()
            ->paginate(20);

        return view('admin.products.stock-history', [
            'produit' => $produit,
            'mouvements' => $mouvements,
        ]);
    }

    /**
     * Audit complet du stock
     */
    public function stockAudit(Request $request)
    {
        $query = StockMouvement::query();

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->input('reason'));
        }

        $mouvements = $query->with('produit')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.products.stock-audit', [
            'mouvements' => $mouvements,
        ]);
    }

    /**
     * Désactiver un produit
     */
    public function disable(Request $request, Produit $produit)
    {
        $produit->update(['est_actif' => false]);
        return redirect()->route('admin.products.index', $request->query())->with('success', 'Produit désactivé avec succès.');
    }

    /**
     * Activer un produit
     */
    public function enable(Request $request, Produit $produit)
    {
        $produit->update(['est_actif' => true]);
        return redirect()->route('admin.products.index', $request->query())->with('success', 'Produit activé avec succès.');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Request $request, Produit $produit)
    {
        $nom = $produit->nom;
        $produit->delete();
        return redirect()->route('admin.products.index', $request->query())->with('success', "Produit « $nom » supprimé avec succès.");
    }

    /**
     * Afficher les produits vedettes
     */
    public function featured(Request $request)
    {
        $query = Produit::with('vendeur', 'categorie')
            ->where('featured', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nom', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $produits = $query->paginate(15);

        return view('admin.products.featured', [
            'produits' => $produits,
        ]);
    }

    /**
     * Basculer le statut vedette d'un produit
     */
    public function toggleFeatured(Request $request, Produit $produit)
    {
        $produit->update(['featured' => !$produit->featured]);
        $status = $produit->featured ? 'ajouté aux' : 'retiré des';
        return redirect()->route('admin.products.index', $request->query())->with('success', "Produit « {$produit->nom} » $status produits vedettes.");
    }
}
