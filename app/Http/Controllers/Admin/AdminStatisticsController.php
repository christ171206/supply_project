<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminStatisticsController extends Controller
{
    /**
     * Page principale des statistiques
     */
    public function index()
    {
        // KPIs
        $totalRevenue = Commande::where('statut', 'livree')->sum('total');
        $totalOrders = Commande::count();
        $totalClients = User::where('role', 'client')->count();
        $totalVendors = User::where('role', 'vendor')->count();

        // Derniers 30 jours
        $last30DaysRevenue = Commande::where('statut', 'livree')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total');

        $last30DaysOrders = Commande::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.statistics.index', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalClients' => $totalClients,
            'totalVendors' => $totalVendors,
            'last30DaysRevenue' => $last30DaysRevenue,
            'last30DaysOrders' => $last30DaysOrders,
        ]);
    }

    /**
     * Graphique: Revenu quotidien sur 30 jours
     */
    public function dailyRevenueChart(Request $request)
    {
        $days = $request->input('days', 30);
        $startDate = now()->subDays($days);

        $data = Commande::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->where('statut', 'livree')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Remplir les données manquantes
        $chartData = [];
        $chartOrders = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $found = $data->firstWhere('date', $date);
            $chartData[] = round($found?->revenue ?? 0, 2);
            $chartOrders[] = $found?->orders ?? 0;
            $labels[] = now()->subDays($i)->format('d M');
        }

        return response()->json([
            'labels' => $labels ?? [],
            'revenue' => $chartData,
            'orders' => $chartOrders,
        ]);
    }

    /**
     * Graphique: Top 10 vendeurs
     */
    public function topVendorsChart(Request $request)
    {
        $limit = $request->input('limit', 10);

        $vendors = User::where('role', 'vendor')
            ->selectRaw('users.id, users.shop_name, SUM(commandes.total) as revenue')
            ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->where('commandes.statut', 'livree')
            ->groupBy('users.id', 'users.shop_name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();

        return response()->json([
            'labels' => $vendors->pluck('shop_name'),
            'data' => $vendors->pluck('revenue'),
        ]);
    }

    /**
     * Graphique: Produits populaires
     */
    public function topProductsChart(Request $request)
    {
        $limit = $request->input('limit', 10);

        $products = Produit::selectRaw('produits.nom, COUNT(ligne_commandes.id) as sold')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('sold')
            ->limit($limit)
            ->get();

        return response()->json([
            'labels' => $products->pluck('nom'),
            'data' => $products->pluck('sold'),
        ]);
    }

    /**
     * Graphique: Croissance mensuelle
     */
    public function monthlyGrowthChart(Request $request)
    {
        $months = $request->input('months', 12);
        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startDate = $date->clone()->startOfMonth();
            $endDate = $date->clone()->endOfMonth();

            $monthRevenue = Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->sum('total');

            $monthOrders = Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->count();

            $labels[] = $date->format('M Y');
            $revenue[] = round($monthRevenue, 2);
            $orders[] = $monthOrders;
        }

        return response()->json([
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ]);
    }

    /**
     * Graphique: Statut des commandes
     */
    public function orderStatusChart()
    {
        $statuses = [
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'confirmee' => Commande::where('statut', 'confirmee')->count(),
            'expediee' => Commande::where('statut', 'expediee')->count(),
            'livree' => Commande::where('statut', 'livree')->count(),
            'refusee' => Commande::where('statut', 'refusee')->count(),
            'annulee' => Commande::where('statut', 'annulee')->count(),
        ];

        return response()->json([
            'labels' => array_keys($statuses),
            'data' => array_values($statuses),
        ]);
    }

    /**
     * Graphique: Catégories populaires
     */
    public function categoriesChart(Request $request)
    {
        $limit = $request->input('limit', 8);

        $categories = DB::table('categories')
            ->selectRaw('categories.nom, COUNT(ligne_commandes.id) as sold')
            ->leftJoin('produits', 'categories.id', '=', 'produits.categorie_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('sold')
            ->limit($limit)
            ->get();

        return response()->json([
            'labels' => $categories->pluck('nom'),
            'data' => $categories->pluck('sold'),
        ]);
    }

    /**
     * Graphique: Croissance utilisateurs
     */
    public function userGrowthChart(Request $request)
    {
        $months = $request->input('months', 12);
        $labels = [];
        $clients = [];
        $vendors = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $endDate = $date->clone()->endOfMonth();

            $clientCount = User::where('role', 'client')
                ->where('created_at', '<=', $endDate)
                ->count();

            $vendorCount = User::where('role', 'vendor')
                ->where('created_at', '<=', $endDate)
                ->count();

            $labels[] = $date->format('M Y');
            $clients[] = $clientCount;
            $vendors[] = $vendorCount;
        }

        return response()->json([
            'labels' => $labels,
            'clients' => $clients,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Statistiques détaillées pour rapport
     */
    public function detailedStats(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $stats = [
            'totalRevenue' => Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->sum('total'),
            'totalOrders' => Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->count(),
            'averageOrderValue' => isset($totalOrders) && $totalOrders > 0
                ? round(Commande::whereBetween('created_at', [$startDate, $endDate])->where('statut', 'livree')->sum('total') / Commande::whereBetween('created_at', [$startDate, $endDate])->where('statut', 'livree')->count(), 2)
                : 0,
            'newCustomers' => User::whereBetween('created_at', [$startDate, $endDate])
                ->where('role', 'client')
                ->count(),
            'newVendors' => User::whereBetween('created_at', [$startDate, $endDate])
                ->where('role', 'vendor')
                ->count(),
            'totalReviews' => Review::whereBetween('created_at', [$startDate, $endDate])->count(),
            'averageRating' => Review::whereBetween('created_at', [$startDate, $endDate])->avg('note') ?? 0,
            'conversionRate' => $this->calculateConversionRate($startDate, $endDate),
        ];

        return response()->json($stats);
    }

    /**
     * Exporter les statistiques en CSV
     */
    public function exportCSV(Request $request)
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $filename = 'statistiques-' . $type . '-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename='$filename'",
        ];

        $callback = function () use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            if ($type === 'daily') {
                fputcsv($file, ['Date', 'Chiffre d\'affaires', 'Nombre de commandes']);

                $data = Commande::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
                    ->where('statut', 'livree')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                foreach ($data as $row) {
                    fputcsv($file, [$row->date, number_format($row->revenue, 2), $row->orders]);
                }
            } elseif ($type === 'vendors') {
                fputcsv($file, ['Vendeur', 'Commandes', 'Chiffre d\'affaires', 'Note moyenne']);

                $vendors = User::where('role', 'vendor')
                    ->selectRaw('users.shop_name, COUNT(commandes.id) as orders, SUM(commandes.total) as revenue')
                    ->selectRaw('AVG(avis.note) as rating')
                    ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
                    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
                    ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
                    ->leftJoin('avis', 'commandes.id', '=', 'avis.commande_id')
                    ->whereRaw('commandes.created_at BETWEEN ? AND ?', [$startDate, $endDate])
                    ->where('commandes.statut', 'livree')
                    ->groupBy('users.id', 'users.shop_name')
                    ->get();

                foreach ($vendors as $vendor) {
                    fputcsv($file, [$vendor->shop_name, $vendor->orders, number_format($vendor->revenue, 2), round($vendor->rating ?? 0, 2)]);
                }
            } elseif ($type === 'products') {
                fputcsv($file, ['Produit', 'Catégorie', 'Quantités vendues', 'Chiffre d\'affaires']);

                $products = Produit::selectRaw('produits.nom, categories.nom as categorie, COUNT(ligne_commandes.id) as sold, SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as revenue')
                    ->leftJoin('categories', 'produits.categorie_id', '=', 'categories.id')
                    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
                    ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
                    ->whereRaw('commandes.created_at BETWEEN ? AND ?', [$startDate, $endDate])
                    ->where('commandes.statut', 'livree')
                    ->groupBy('produits.id', 'produits.nom', 'categories.id', 'categories.nom')
                    ->orderByDesc('revenue')
                    ->get();

                foreach ($products as $product) {
                    fputcsv($file, [$product->nom, $product->categorie, $product->sold, number_format($product->revenue, 2)]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporter les statistiques en PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Récupérer les données
        $stats = [
            'totalRevenue' => Commande::whereBetween('created_at', [$startDate, $endDate])->where('statut', 'livree')->sum('total'),
            'totalOrders' => Commande::whereBetween('created_at', [$startDate, $endDate])->where('statut', 'livree')->count(),
            'newCustomers' => User::whereBetween('created_at', [$startDate, $endDate])->where('role', 'client')->count(),
            'newVendors' => User::whereBetween('created_at', [$startDate, $endDate])->where('role', 'vendor')->count(),
        ];

        // Retourner une réponse JSON pour maintenant (le PDF peut être implémenté avec une libraire)
        return response()->json([
            'message' => 'Pour générer un PDF, installez: composer require barryvdh/laravel-dompdf',
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Calculer le taux de conversion
     */
    private function calculateConversionRate($startDate, $endDate)
    {
        $totalVisits = 100; // À implémenter avec analytics
        $totalOrders = Commande::whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->count();

        return $totalVisits > 0 ? round(($totalOrders / $totalVisits) * 100, 2) : 0;
    }
}
