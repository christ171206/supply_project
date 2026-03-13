<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\StockAlert;
use Illuminate\Support\Facades\Auth;

class VendorAlertsController extends Controller
{
    /**
     * Afficher les alertes de stock du vendeur
     */
    public function index()
    {
        $vendor = Auth::user();

        // Alertes critiques
        $criticalAlerts = StockAlert::selectRaw('stock_alerts.*, produits.nom')
            ->join('produits', 'stock_alerts.produit_id', '=', 'produits.id')
            ->where('produits.user_id', $vendor->id)
            ->where('stock_alerts.alert_type', 'critical')
            ->where('stock_alerts.dismissed_at', null)
            ->orderBy('stock_alerts.created_at', 'desc')
            ->get();

        // Alertes bas stock
        $lowAlerts = StockAlert::selectRaw('stock_alerts.*, produits.nom')
            ->join('produits', 'stock_alerts.produit_id', '=', 'produits.id')
            ->where('produits.user_id', $vendor->id)
            ->where('stock_alerts.alert_type', 'low')
            ->where('stock_alerts.dismissed_at', null)
            ->orderBy('stock_alerts.created_at', 'desc')
            ->get();

        // Produits avec stock normal
        $normalStock = Produit::where('user_id', $vendor->id)
            ->whereNotIn('id', $criticalAlerts->pluck('produit_id'))
            ->whereNotIn('id', $lowAlerts->pluck('produit_id'))
            ->count();

        // Toutes les alertes pour le filtre
        $filter = request()->input('filter');
        if ($filter === 'critical') {
            $alerts = $criticalAlerts;
        } elseif ($filter === 'low') {
            $alerts = $lowAlerts;
        } else {
            $alerts = $criticalAlerts->merge($lowAlerts);
        }

        return view('vendor.alerts', [
            'alerts' => $alerts,
            'criticalAlerts' => count($criticalAlerts),
            'lowAlerts' => count($lowAlerts),
            'normalStock' => $normalStock,
            'filter' => $filter,
        ]);
    }
}
