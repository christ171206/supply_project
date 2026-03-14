<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use App\Models\StockMouvement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * Rapports financiers
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $totalRevenue = Commande::whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->sum('total');

        $orderCount = Commande::whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->count();

        $averageOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // Revenu par jour
        $dailyRevenue = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('statut', 'livree')
            ->groupBy('date')
            ->get();

        // Revenu par vendeur
        $vendorRevenue = User::select('users.id', 'users.name', 'users.shop_name')
            ->selectRaw('COUNT(commandes.id) as orders')
            ->selectRaw('SUM(commandes.total) as revenue')
            ->join('produits', 'users.id', '=', 'produits.user_id')
            ->join('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->whereBetween('commandes.created_at', [$startDate, $endDate])
            ->where('commandes.statut', 'livree')
            ->groupBy('users.id', 'users.name', 'users.shop_name')
            ->orderByDesc('revenue')
            ->get();

        return view('admin.reports.financial', [
            'totalRevenue' => $totalRevenue,
            'orderCount' => $orderCount,
            'averageOrderValue' => $averageOrderValue,
            'dailyRevenue' => $dailyRevenue,
            'vendorRevenue' => $vendorRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Rapport de performance des vendeurs
     */
    public function vendorPerformanceReport()
    {
        $vendors = User::where('role', 'vendor')
            ->select('users.id', 'users.name', 'users.shop_name')
            ->selectRaw('COUNT(DISTINCT commandes.id) as total_orders')
            ->selectRaw('SUM(CASE WHEN commandes.statut = "livree" THEN 1 ELSE 0 END) as delivered_orders')
            ->selectRaw('SUM(CASE WHEN commandes.statut = "annulee" THEN 1 ELSE 0 END) as cancelled_orders')
            ->selectRaw('SUM(commandes.total) as total_revenue')
            ->selectRaw('AVG(DATEDIFF(CURDATE(), commandes.created_at)) as avg_delivery_time')
            ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->groupBy('users.id', 'users.name', 'users.shop_name')
            ->orderByDesc('total_revenue')
            ->paginate(15);

        return view('admin.reports.vendor-performance', [
            'vendors' => $vendors,
        ]);
    }

    /**
     * Rapport des produits populaires
     */
    public function productPopularityReport(Request $request)
    {
        $limit = $request->input('limit', 20);

        $products = Produit::select('produits.id', 'produits.nom', 'produits.prix')
            ->selectRaw('COUNT(ligne_commandes.id) as times_sold')
            ->selectRaw('SUM(ligne_commandes.quantite) as total_quantity')
            ->selectRaw('SUM(ligne_commandes.prix_unitaire * ligne_commandes.quantite) as total_revenue')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->groupBy('produits.id', 'produits.nom', 'produits.prix')
            ->orderByDesc('times_sold')
            ->limit($limit)
            ->get();

        return view('admin.reports.product-popularity', [
            'products' => $products,
        ]);
    }

    /**
     * Rapport d'activité des utilisateurs
     */
    public function userActivityReport(Request $request)
    {
        $period = $request->input('period', '30'); // jours

        $recentUsers = User::select('users.id', 'users.name', 'users.email', 'users.role', 'users.created_at')
            ->selectRaw('COUNT(DISTINCT commandes.id) as commandes_count')
            ->selectRaw('SUM(commandes.total) as total_spent')
            ->selectRaw('MAX(commandes.created_at) as last_order')
            ->leftJoin('commandes', 'users.id', '=', 'commandes.user_id')
            ->where('users.created_at', '>=', now()->subDays($period))
            ->groupBy('users.id', 'users.name', 'users.email', 'users.role', 'users.created_at')
            ->orderByDesc('last_order')
            ->limit(15)
            ->get();

        // Statistiques générales
        $activeUsers = User::whereHas('commandes', fn($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->count();

        $newUsersMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalOrders = Commande::where('created_at', '>=', now()->subDays($period))->count();
        $totalRevenue = Commande::where('created_at', '>=', now()->subDays($period))->sum('total');
        $averageBasketValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $conversionRate = User::count() > 0 ? ($totalOrders / User::count()) * 100 : 0;

        return view('admin.reports.user-activity', [
            'recentUsers' => $recentUsers,
            'activeUsers' => $activeUsers,
            'newUsersMonth' => $newUsersMonth,
            'averageBasketValue' => $averageBasketValue,
            'conversionRate' => $conversionRate,
            'period' => $period,
        ]);
    }

    /**
     * Audit du stock
     */
    public function stockAuditReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Produits avec histórique de stock
        $products = Produit::with('stock')
            ->selectRaw('produits.*')
            ->leftJoin('stocks', 'produits.id', '=', 'stocks.produit_id')
            ->where('produits.actif', true)
            ->distinct()
            ->get();

        // Mouvements récents
        $recentMovements = StockMouvement::with('produit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Statistiques
        $totalStock = Produit::join('stocks', 'produits.id', '=', 'stocks.produit_id')
            ->sum('stocks.quantite') ?? 0;

        $lowStockCount = Produit::join('stocks', 'produits.id', '=', 'stocks.produit_id')
            ->whereRaw('stocks.quantite <= stocks.alerte_quantite * 1.5')
            ->where('stocks.quantite', '>', 0)
            ->count();

        $criticalStockCount = Produit::join('stocks', 'produits.id', '=', 'stocks.produit_id')
            ->where('stocks.quantite', '<=', DB::raw('stocks.alerte_quantite'))
            ->count();

        $movementsCount = StockMouvement::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        return view('admin.reports.stock-audit', [
            'products' => $products,
            'recentMovements' => $recentMovements,
            'totalStock' => $totalStock,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount,
            'movementsCount' => $movementsCount,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Télécharger les rapports en CSV
     */
    public function exportReport(Request $request)
    {
        $type = $request->input('type', 'financial');
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        switch ($type) {
            case 'financial':
                return $this->exportFinancialReport($startDate, $endDate);
            case 'vendors':
                return $this->exportVendorReport();
            case 'products':
                return $this->exportProductReport();
            default:
                return redirect()->back()->with('error', 'Type de rapport inconnu.');
        }
    }

    private function exportFinancialReport($startDate, $endDate)
    {
        $filename = 'rapport-financier-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename='$filename'",
        ];

        $callback = function () use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Nombre de Commandes', 'Revenu Total']);

            $data = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->groupBy('date')
                ->get();

            foreach ($data as $row) {
                fputcsv($file, [$row->date, $row->orders, $row->revenue]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportVendorReport()
    {
        // Implémentation similaire
    }

    private function exportProductReport()
    {
        // Implémentation similaire
    }

    /**
     * Rapport annuel complet avec chiffre d'affaires par mois
     */
    public function annualReport(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Initialiser les données
        $monthlyRevenue = [];
        $monthlyOrders = [];
        $monthlyLabels = [];
        $monthlyGrowth = [];

        $janStartLastYear = now()->setYear($year - 1)->startOfYear();
        $currentLastYear = now()->setYear($year - 1)->endOfYear();

        // Calcul pour chaque mois
        for ($month = 1; $month <= 12; $month++) {
            $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
            $endDate = now()->setYear($year)->setMonth($month)->endOfMonth();

            $revenue = Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->sum('total');

            $orders = Commande::whereBetween('created_at', [$startDate, $endDate])
                ->where('statut', 'livree')
                ->count();

            // Calcul de la croissance vs année précédente
            $lastYearStart = now()->setYear($year - 1)->setMonth($month)->startOfMonth();
            $lastYearEnd = now()->setYear($year - 1)->setMonth($month)->endOfMonth();

            $lastYearRevenue = Commande::whereBetween('created_at', [$lastYearStart, $lastYearEnd])
                ->where('statut', 'livree')
                ->sum('total');

            $growth = $lastYearRevenue > 0
                ? round((($revenue - $lastYearRevenue) / $lastYearRevenue) * 100, 1)
                : 0;

            $monthlyRevenue[] = round($revenue, 0);
            $monthlyOrders[] = $orders;
            $monthlyLabels[] = trans('months.' . $month);
            $monthlyGrowth[] = $growth;
        }

        // Totaux annuels
        $totalAnnualRevenue = array_sum($monthlyRevenue);
        $totalAnnualOrders = array_sum($monthlyOrders);
        $averageMonthlyRevenue = round($totalAnnualRevenue / 12, 0);

        // Comparaison avec l'année précédente
        $lastYearRevenue = Commande::whereYear('created_at', $year - 1)
            ->where('statut', 'livree')
            ->sum('total');

        $yearOverYearGrowth = $lastYearRevenue > 0
            ? round((($totalAnnualRevenue - $lastYearRevenue) / $lastYearRevenue) * 100, 1)
            : 0;

        // Top vendeurs de l'année
        $topVendors = User::where('role', 'vendor')
            ->select('users.id', 'users.name', 'users.shop_name')
            ->selectRaw('COUNT(DISTINCT commandes.id) as total_orders')
            ->selectRaw('SUM(commandes.total) as total_revenue')
            ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->whereYear('commandes.created_at', $year)
            ->where('commandes.statut', 'livree')
            ->groupBy('users.id', 'users.name', 'users.shop_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Top produits de l'année
        $topProducts = Produit::select('produits.id', 'produits.nom', 'produits.prix')
            ->selectRaw('COUNT(ligne_commandes.id) as times_sold')
            ->selectRaw('SUM(ligne_commandes.quantite) as total_quantity')
            ->selectRaw('SUM(ligne_commandes.prix_unitaire * ligne_commandes.quantite) as total_revenue')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->whereYear('commandes.created_at', $year)
            ->where('commandes.statut', 'livree')
            ->groupBy('produits.id', 'produits.nom', 'produits.prix')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Statistiques d'activité
        $newUsersCount = User::whereYear('created_at', $year)->count();
        $newVendorsCount = User::where('role', 'vendor')->whereYear('created_at', $year)->count();
        $totalProductsSold = LigneCommande::whereYear('created_at', $year)->sum('quantite');
        $averageOrderValue = $totalAnnualOrders > 0 ? round($totalAnnualRevenue / $totalAnnualOrders, 0) : 0;

        return view('admin.reports.annual', [
            'year' => $year,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyOrders' => $monthlyOrders,
            'monthlyLabels' => $monthlyLabels,
            'monthlyGrowth' => $monthlyGrowth,
            'totalAnnualRevenue' => $totalAnnualRevenue,
            'totalAnnualOrders' => $totalAnnualOrders,
            'averageMonthlyRevenue' => $averageMonthlyRevenue,
            'yearOverYearGrowth' => $yearOverYearGrowth,
            'lastYearRevenue' => $lastYearRevenue,
            'topVendors' => $topVendors,
            'topProducts' => $topProducts,
            'newUsersCount' => $newUsersCount,
            'newVendorsCount' => $newVendorsCount,
            'totalProductsSold' => $totalProductsSold,
            'averageOrderValue' => $averageOrderValue,
        ]);
    }
}
