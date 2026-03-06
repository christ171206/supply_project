<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commande;
use App\Models\Dispute;
use App\Models\DeliveryTracking;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminOrderController extends Controller
{
    /**
     * Lister toutes les commandes
     */
    public function index(Request $request)
    {
        $query = Commande::with('user', 'ligneCommandes', 'deliveryZone');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }

        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->input('delivery_status'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        $commandes = $query->latest()->paginate(15);

        return view('admin.orders.index', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load('user', 'ligneCommandes', 'ligneCommandes.produit', 'deliveryTracking', 'dispute');

        return view('admin.orders.show', [
            'commande' => $commande,
        ]);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:en_attente,confirmee,expediee,livree,annulee',
        ]);

        $commande->update(['statut' => $request->input('status')]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Mettre à jour le statut de livraison
     */
    public function updateDeliveryStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:pending,picked_up,in_transit,delivered,failed',
            'notes' => 'nullable|string',
        ]);

        $commande->update([
            'delivery_status' => $request->input('status'),
        ]);

        DeliveryTracking::create([
            'commande_id' => $commande->id,
            'status' => $request->input('status'),
            'notes' => $request->input('notes', ''),
        ]);

        return redirect()->back()->with('success', 'Statut de livraison mis à jour.');
    }

    /**
     * Annuler une commande
     */
    public function cancel(Request $request, Commande $commande)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $commande->update([
            'statut' => 'cancelled',
        ]);

        // Enregistrer la raison dans les notes
        $commande->update([
            'notes' => $commande->notes . "\n[Admin Cancellation: " . $request->input('reason') . "]",
        ]);

        return redirect()->back()->with('success', 'Commande annulée.');
    }

    /**
     * Suivre une commande en direct
     */
    public function tracking(Commande $commande)
    {
        $tracking = [];

        // Vérifier si la table existe avant d'accéder aux données
        if (\Illuminate\Support\Facades\Schema::hasTable('delivery_trackings')) {
            $tracking = DeliveryTracking::where('commande_id', $commande->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.orders.tracking', [
            'commande' => $commande,
            'tracking' => $tracking,
        ]);
    }

    /**
     * Vue globale des livraisons
     */
    public function deliveryOverview(Request $request)
    {
        $query = Commande::where('delivery_status', '!=', 'delivered');

        if ($request->filled('status')) {
            $query->where('delivery_status', $request->input('status'));
        }

        // Charger les relations (deliveryTracking seulement si la table existe)
        $commandes = $query->with('user', 'deliveryZone')
            ->latest()
            ->paginate(15);

        // Charger les trackings après si la table existe
        if (\Illuminate\Support\Facades\Schema::hasTable('delivery_trackings')) {
            $commandes->load('deliveryTracking');
        }

        return view('admin.orders.delivery-overview', [
            'commandes' => $commandes,
        ]);
    }
}
