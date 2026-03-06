<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Dispute;
use App\Models\SystemConfiguration;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord admin
     */
    public function index()
    {
        // Statistiques générales
        $totalUsers = User::count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalClients = User::where('role', 'client')->orWhereNull('role')->count();
        $totalProducts = Produit::count();
        $totalOrders = Commande::count();
        $totalRevenue = Commande::sum('total') ?? 0;
        $commissionRate = 10;
        $totalCommission = ($totalRevenue * $commissionRate) / 100;

        // Statistiques ce mois
        $thisMonthOrders = Commande::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $thisMonthRevenue = Commande::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('total') ?? 0;

        // Statistiques mois dernier
        $lastMonthOrders = Commande::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->count();
        $lastMonthRevenue = Commande::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->sum('total') ?? 0;

        // Croissance
        $orderGrowth = $lastMonthOrders > 0 ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 0;
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        // Order statuses
        $ordersAwaitingConfirmation = Commande::where('statut', 'en_attente')->count();
        $ordersConfirmed = Commande::where('statut', 'confirmee')->count();
        $ordersShipped = Commande::where('statut', 'expediee')->count();
        $ordersDelivered = Commande::where('statut', 'livree')->count();
        $cancelledOrders = Commande::where('statut', 'annulee')->count();

        // Other stats
        $pendingDisputes = Dispute::where('status', 'open')->count();
        $vendorsToApprove = User::where('role', 'vendor')->where('email_verified_at', null)->count();
        $bannedUsers = User::where('is_banned', true)->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        // Recent data
        $recentOrders = Commande::with('user')->latest()->limit(10)->get();
        $openDisputes = Dispute::with('user', 'vendor', 'commande')
            ->where('status', 'open')
            ->latest()
            ->limit(5)
            ->get();

        // Top vendors
        $topVendors = User::select('users.*')
            ->selectRaw('COALESCE(SUM(commandes.total), 0) as total_revenue')
            ->selectRaw('COUNT(DISTINCT commandes.id) as total_orders')
            ->leftJoin('produits', 'users.id', '=', 'produits.user_id')
            ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
            ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->where('users.role', 'vendor')
            ->groupBy('users.id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Products awaiting validation
        $productsAwaitingValidation = Produit::latest()
            ->limit(5)
            ->with('vendeur')
            ->get();

        // Growth chart (7 days)
        $growthChartLabels = [];
        $ordersData = [];
        $vendorsData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $label = $date->format('d/m');
            $ordersCount = Commande::whereDate('created_at', $date->format('Y-m-d'))->count();
            $vendorsCount = User::where('role', 'vendor')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->count();

            $growthChartLabels[] = $label;
            $ordersData[] = $ordersCount;
            $vendorsData[] = $vendorsCount;
        }

        // Order status distribution
        $ordersByStatus = [
            'en_attente' => $ordersAwaitingConfirmation,
            'confirmee' => $ordersConfirmed,
            'expediee' => $ordersShipped,
            'livree' => $ordersDelivered,
            'annulee' => $cancelledOrders,
        ];

        // Revenue 7 days
        $revenueDayLabels = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $label = $date->format('d/m');
            $revenue = Commande::whereDate('created_at', $date->format('Y-m-d'))->sum('total') ?? 0;

            $revenueDayLabels[] = $label;
            $revenueData[] = round($revenue, 0);
        }

        // Revenue 30 days
        $revenueLast30DaysLabels = [];
        $revenueLast30Days = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $label = $date->format('d');
            $revenue = Commande::whereDate('created_at', $date->format('Y-m-d'))->sum('total') ?? 0;

            if ($i % 3 == 0) {
                $revenueLast30DaysLabels[] = $label;
            } else {
                $revenueLast30DaysLabels[] = '';
            }
            $revenueLast30Days[] = round($revenue, 0);
        }

        // Hourly activity
        $hourlyActivity = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyActivity[$hour] = Commande::whereRaw('HOUR(created_at) = ?', [$hour])->count();
        }

        // Conversion rate
        $newClientsThisMonth = User::where('role', '!=', 'vendor')
            ->whereMonth('created_at', now()->month)
            ->count();
        $conversionRate = $totalClients > 0 ? round(($newClientsThisMonth / $totalClients) * 100, 1) : 0;

        // Satisfaction rate
        $satisfactionRate = $totalOrders > 0 ? round((($totalOrders - $pendingDisputes) / $totalOrders) * 100, 1) : 100;

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalVendors' => $totalVendors,
            'totalClients' => $totalClients,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalCommission' => $totalCommission,
            'commissionRate' => $commissionRate,
            'thisMonthOrders' => $thisMonthOrders,
            'thisMonthRevenue' => $thisMonthRevenue,
            'orderGrowth' => $orderGrowth,
            'revenueGrowth' => $revenueGrowth,
            'vendorsToApprove' => $vendorsToApprove,
            'pendingDisputes' => $pendingDisputes,
            'bannedUsers' => $bannedUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'recentOrders' => $recentOrders,
            'openDisputes' => $openDisputes,
            'productsAwaitingValidation' => $productsAwaitingValidation,
            'topProducts' => $productsAwaitingValidation,
            'topVendors' => $topVendors,
            'growthChartLabels' => $growthChartLabels,
            'ordersData' => $ordersData,
            'vendorsData' => $vendorsData,
            'ordersByStatus' => $ordersByStatus,
            'revenueDayLabels' => $revenueDayLabels,
            'revenueData' => $revenueData,
            'revenueLast30DaysLabels' => $revenueLast30DaysLabels,
            'revenueLast30Days' => $revenueLast30Days,
            'ordersAwaitingConfirmation' => $ordersAwaitingConfirmation,
            'ordersConfirmed' => $ordersConfirmed,
            'ordersShipped' => $ordersShipped,
            'ordersDelivered' => $ordersDelivered,
            'hourlyActivity' => $hourlyActivity,
            'newClientsThisMonth' => $newClientsThisMonth,
            'conversionRate' => $conversionRate,
            'satisfactionRate' => $satisfactionRate,
        ]);
    }
}
