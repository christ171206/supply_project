<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use App\Models\LigneCommande;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportsController extends Controller
{
    /**
     * Page de rapports et statistiques avancées
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Statistiques de base
        $totalRevenue = Commande::whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->sum('total');

        $totalOrders = Commande::whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top produits
        $topProducts = LigneCommande::selectRaw('produits.nom, COUNT(*) as sold, SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as revenue')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->whereBetween('commandes.created_at', [$startDate, $endDate])
            ->where('commandes.statut', 'livree')
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Chiffre d'affaires par période
        $dailyRevenue = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top vendeurs
        $topVendors = User::where('role', 'vendor')
            ->selectRaw('users.shop_name, COUNT(commandes.id) as total_orders, SUM(commandes.total) as total_revenue, AVG(avis.note) as avg_rating')
            ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->leftJoin('avis', 'commandes.id', '=', 'avis.commande_id')
            ->whereRaw('commandes.created_at BETWEEN ? AND ?', [$startDate, $endDate])
            ->where('commandes.statut', 'livree')
            ->groupBy('users.id', 'users.shop_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'topProducts' => $topProducts,
            'dailyRevenue' => $dailyRevenue,
            'topVendors' => $topVendors,
        ]);
    }

    /**
     * Rapport des ventes par période
     */
    public function salesByPeriod(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly, yearly
        $months = $request->input('months', 12);

        $data = [];
        $labels = [];
        $totals = [];

        if ($period === 'monthly') {
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $startDate = $date->clone()->startOfMonth();
                $endDate = $date->clone()->endOfMonth();

                $revenue = Commande::whereBetween('created_at', [$startDate, $endDate])
                    ->where('statut', 'livree')
                    ->sum('total');

                $orders = Commande::whereBetween('created_at', [$startDate, $endDate])
                    ->where('statut', 'livree')
                    ->count();

                $labels[] = $date->format('M Y');
                $totals[] = round($revenue, 2);
                $data[] = ['date' => $date->format('Y-m'), 'revenue' => $revenue, 'orders' => $orders];
            }
        } elseif ($period === 'daily') {
            $startDate = now()->subDays(30);
            $endDate = now();

            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays(29 - $i);
                $revenue = Commande::whereDate('created_at', $date)
                    ->where('statut', 'livree')
                    ->sum('total');

                $orders = Commande::whereDate('created_at', $date)
                    ->where('statut', 'livree')
                    ->count();

                $labels[] = $date->format('d M');
                $totals[] = round($revenue, 2);
                $data[] = ['date' => $date->format('Y-m-d'), 'revenue' => $revenue, 'orders' => $orders];
            }
        }

        return view('admin.reports.sales-by-period', [
            'data' => $data,
            'labels' => $labels,
            'totals' => $totals,
            'period' => $period,
        ]);
    }

    /**
     * Rapport détaillé des commandes
     */
    public function ordersReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        $status = $request->input('status', null);

        $query = Commande::whereBetween('created_at', [$startDate, $endDate]);

        if ($status) {
            $query->where('statut', $status);
        }

        $commandes = $query->with('user', 'ligneCommandes.produit')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistiques par statut
        $statusStats = Commande::selectRaw('statut, COUNT(*) as count, SUM(total) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('statut')
            ->get();

        return view('admin.reports.orders', [
            'commandes' => $commandes,
            'statusStats' => $statusStats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Exporter rapports en CSV
     */
    public function exportCSV(Request $request)
    {
        $type = $request->input('type', 'summary');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $filename = "rapport-{$type}-" . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename='{$filename}'",
        ];

        $callback = function () use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            if ($type === 'sales') {
                fputcsv($file, ['Date', 'Chiffre d\'affaires', 'Nombre de commandes', 'Panier moyen']);

                $data = Commande::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('statut', 'livree')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                foreach ($data as $row) {
                    $average = $row->orders ? round($row->revenue / $row->orders, 2) : 0;
                    fputcsv($file, [
                        $row->date,
                        number_format($row->revenue, 2),
                        $row->orders,
                        number_format($average, 2)
                    ]);
                }
            } elseif ($type === 'products') {
                fputcsv($file, ['Produit', 'Quantités vendues', 'Chiffre d\'affaires', 'Nombre de commandes']);

                $products = Produit::selectRaw('produits.nom, COUNT(ligne_commandes.id) as sold, SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as revenue')
                    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
                    ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
                    ->whereRaw('commandes.created_at BETWEEN ? AND ?', [$startDate, $endDate])
                    ->where('commandes.statut', 'livree')
                    ->groupBy('produits.id', 'produits.nom')
                    ->orderByDesc('revenue')
                    ->get();

                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->nom,
                        $product->sold ?? 0,
                        number_format($product->revenue ?? 0, 2),
                        DB::table('ligne_commandes')
                            ->where('produit_id', $product->id)
                            ->distinct('commande_id')
                            ->count()
                    ]);
                }
            } elseif ($type === 'vendors') {
                fputcsv($file, ['Vendeur', 'Commandes', 'Chiffre d\'affaires', 'Note moyenne']);

                $vendors = User::where('role', 'vendor')
                    ->selectRaw('users.shop_name, COUNT(DISTINCT commandes.id) as orders, SUM(commandes.total) as revenue, AVG(avis.note) as rating')
                    ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
                    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
                    ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
                    ->leftJoin('avis', 'commandes.id', '=', 'avis.commande_id')
                    ->whereRaw('commandes.created_at BETWEEN ? AND ?', [$startDate, $endDate])
                    ->where('commandes.statut', 'livree')
                    ->groupBy('users.id', 'users.shop_name')
                    ->get();

                foreach ($vendors as $vendor) {
                    fputcsv($file, [
                        $vendor->shop_name,
                        $vendor->orders ?? 0,
                        number_format($vendor->revenue ?? 0, 2),
                        round($vendor->rating ?? 0, 2)
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export PDF (stub - à implémenter avec barryvdh/laravel-dompdf)
     */
    public function exportPDF(Request $request)
    {
        $type = $request->input('type', 'summary');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        return response()->json([
            'message' => 'Export PDF: Installez composer require barryvdh/laravel-dompdf',
            'type' => $type,
            'period' => "{$startDate} à {$endDate}",
        ]);
    }
}
