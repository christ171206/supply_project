<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\LigneCommande;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorAnalyticsController extends Controller
{
    /**
     * Obtenir les données de ventes par jour (derniers 30 jours)
     */
    public function getDailySalesData()
    {
        $user = Auth::user();

        // Derniers 30 jours
        $startDate = now()->subDays(30);
        $endDate = now();

        $salesByDay = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(DISTINCT id) as orders')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        // Créer un array complet pour chaque jour
        $dates = [];
        $sales = [];
        $orders = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d M');

            $dayData = $salesByDay->firstWhere('date', $date);
            $sales[] = $dayData ? round($dayData->total, 0) : 0;
            $orders[] = $dayData ? $dayData->orders : 0;
        }

        return response()->json([
            'dates' => $dates,
            'sales' => $sales,
            'orders' => $orders,
        ]);
    }

    /**
     * Obtenir les ventes par catégorie (pie chart)
     */
    public function getSalesByCategory()
    {
        $user = Auth::user();

        $salesData = DB::table('ligne_commandes')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->where('produits.user_id', $user->id)
            ->selectRaw('categories.nom as category, SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as total')
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('total')
            ->get();

        $categories = $salesData->pluck('category')->toArray();
        $totals = $salesData->pluck('total')->map(fn($v) => round($v, 0))->toArray();

        return response()->json([
            'categories' => $categories,
            'totals' => $totals,
        ]);
    }

    /**
     * Obtenir les top 5 produits les plus vendus
     */
    public function getTopProducts()
    {
        $user = Auth::user();

        $topProducts = Produit::where('user_id', $user->id)
            ->with('categorie', 'ligneCommandes')
            ->get()
            ->map(function ($p) {
                $p->ventes_quantite = $p->ligneCommandes->sum('quantite');
                $p->revenus = $p->ligneCommandes->sum(fn($lc) => $lc->quantite * $lc->prix_unitaire);
                return $p;
            })
            ->sortByDesc('revenus')
            ->take(5);

        $products = $topProducts->map(fn($p) => [
            'nom' => $p->nom,
            'quantite' => $p->ventes_quantite,
            'revenus' => round($p->revenus, 0),
            'prix' => $p->prix,
            'stocks' => $p->stock,
        ])->toArray();

        return response()->json([
            'products' => $products,
        ]);
    }

    /**
     * Obtenir les statistiques globales (KPIs)
     */
    public function getGlobalStats()
    {
        $user = Auth::user();

        // Ventes totales
        $totalSales = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('total');

        // Nombre de commandes
        $totalOrders = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->distinct('id')->count();

        // Produits
        $totalProducts = Produit::where('user_id', $user->id)->count();

        // Note moyenne
        $averageRating = Avis::whereHas('produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('note') ?? 0;

        // Taux de conversion (commandes livrées / total)
        $completedOrders = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'livree')->distinct('id')->count();

        $conversionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;

        // Panier moyen
        $averageCart = $totalOrders > 0 ? round($totalSales / $totalOrders) : 0;

        return response()->json([
            'total_sales' => round($totalSales, 0),
            'total_orders' => $totalOrders,
            'total_products' => $totalProducts,
            'average_rating' => round($averageRating, 1),
            'conversion_rate' => $conversionRate,
            'average_cart' => $averageCart,
            'completed_orders' => $completedOrders,
        ]);
    }

    /**
     * Obtenir les statistiques du mois actuel vs mois précédent
     */
    public function getMonthComparison()
    {
        $user = Auth::user();

        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();

        // Ventes du mois actuel
        $currentSales = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereBetween('created_at', [$currentMonth, now()])
            ->sum('total');

        // Ventes du mois précédent
        $previousSales = Commande::whereHas('ligneCommandes.produit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereBetween('created_at', [$previousMonth, $previousMonthEnd])
            ->sum('total');

        // Variation %
        $variation = $previousSales > 0 ? round((($currentSales - $previousSales) / $previousSales) * 100) : 0;

        return response()->json([
            'current_month_sales' => round($currentSales, 0),
            'previous_month_sales' => round($previousSales, 0),
            'variation_percentage' => $variation,
            'is_positive' => $variation >= 0,
        ]);
    }

    /**
     * Obtenir les prévisions de rupture de stock
     */
    public function getStockForecasts()
    {
        $user = Auth::user();

        $products = Produit::where('user_id', $user->id)
            ->with('ligneCommandes')
            ->where('stock', '>', 0)
            ->get();

        $forecasts = $products->filter(function ($product) {
            // Calculer la vente moyenne par jour (derniers 7 jours)
            $sevenDaysAgo = now()->subDays(7);
            $salesLast7Days = $product->ligneCommandes()
                ->whereHas('commande', function ($q) use ($sevenDaysAgo) {
                    $q->where('created_at', '>=', $sevenDaysAgo);
                })
                ->sum('quantite');

            $avgSalesPerDay = $salesLast7Days > 0 ? $salesLast7Days / 7 : 0;

            // Ne montrer que les produits avec ventes
            return $avgSalesPerDay > 0;
        })
            ->map(function ($product) {
                $sevenDaysAgo = now()->subDays(7);
                $salesLast7Days = $product->ligneCommandes()
                    ->whereHas('commande', function ($q) use ($sevenDaysAgo) {
                        $q->where('created_at', '>=', $sevenDaysAgo);
                    })
                    ->sum('quantite');

                $avgSalesPerDay = $salesLast7Days / 7;
                $daysRemaining = $avgSalesPerDay > 0 ? ceil($product->stock / $avgSalesPerDay) : 999;

                return [
                    'id' => $product->id,
                    'nom' => $product->nom,
                    'stock' => $product->stock,
                    'ventes_par_jour' => round($avgSalesPerDay, 1),
                    'jours_restants' => $daysRemaining,
                    'alerte' => $daysRemaining <= 7,
                    'critique' => $daysRemaining <= 3,
                ];
            })
            ->sortBy('jours_restants')
            ->take(8);

        return response()->json([
            'forecasts' => $forecasts->values(),
        ]);
    }
}
