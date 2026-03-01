<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VendorStatisticsController extends Controller
{
    /**
     * Get vendor sales analytics data
     */
    public function getSalesData(Request $request): JsonResponse
    {
        $vendeurId = Auth::id();
        $days = $request->input('days', 7);

        // Get sales data for the last N days
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Get daily sales
        /** @var \Illuminate\Database\Query\Builder $query */
        $dailySales = LigneCommande::selectRaw(
            'DATE(lignes_commandes.created_at) as date,
             SUM(lignes_commandes.quantite * lignes_commandes.prix_unitaire) as ventes,
             COUNT(DISTINCT lignes_commandes.commande_id) as commandes'
        )
            ->join('produits', 'lignes_commandes.produit_id', '=', 'produits.id')
            ->where('produits.user_id', $vendeurId)
            ->whereBetween('lignes_commandes.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Get product performance data
        $topProducts = Produit::where('user_id', $vendeurId)
            ->with(['lignesCommandes' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($produit) {
                $totalVentes = $produit->lignesCommandes->sum(function ($ligne) {
                    return $ligne->quantite * $ligne->prix_unitaire;
                });
                return [
                    'nom' => $produit->nom,
                    'ventes' => (int) $totalVentes,
                    'quantite' => $produit->lignesCommandes->sum('quantite'),
                ];
            })
            ->sortByDesc('ventes')
            ->take(5)
            ->values();

        // Get performance indicators
        $totalVentes = $dailySales->sum('ventes');
        $totalCommandes = Commande::whereHas('lignes', function ($q) use ($vendeurId, $startDate, $endDate) {
            $q->join('produits', 'lignes_commandes.produit_id', '=', 'produits.id')
                ->where('produits.user_id', $vendeurId)
                ->whereBetween('lignes_commandes.created_at', [$startDate, $endDate]);
        })->count();

        $averageOrderValue = $totalCommandes > 0 ? $totalVentes / $totalCommandes : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'dates' => $dailySales->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m')),
                'ventes' => $dailySales->pluck('ventes')->map(fn($v) => round($v, 0))->values(),
                'commandes' => $dailySales->pluck('commandes')->values(),
                'topProducts' => $topProducts,
                'indicators' => [
                    'totalVentes' => round($totalVentes, 0),
                    'totalCommandes' => $totalCommandes,
                    'averageOrderValue' => round($averageOrderValue, 0),
                    'totalProducts' => Produit::where('user_id', $vendeurId)->count(),
                ]
            ]
        ]);
    }

    /**
     * Get vendor inventory status
     */
    public function getInventoryStatus(Request $request): JsonResponse
    {
        $vendeurId = Auth::id();

        $produits = Produit::where('user_id', $vendeurId)
            ->selectRaw('
                CASE
                    WHEN stock = 0 THEN "Rupture"
                    WHEN stock < 10 THEN "Critique"
                    WHEN stock < 50 THEN "Bas"
                    ELSE "Bon"
                END as statut,
                COUNT(*) as nombre
            ')
            ->groupBy('statut')
            ->get();

        $statusCounts = [
            'Bon' => 0,
            'Bas' => 0,
            'Critique' => 0,
            'Rupture' => 0
        ];

        foreach ($produits as $produit) {
            $statusCounts[$produit->statut] = $produit->nombre;
        }

        return response()->json([
            'success' => true,
            'data' => $statusCounts
        ]);
    }

    /**
     * Get customer satisfaction metrics
     */
    public function getCustomerMetrics(Request $request): JsonResponse
    {
        $vendeurId = Auth::id();

        // Get reviews/ratings
        $avis = \App\Models\Avis::whereHas('produit', function ($q) use ($vendeurId) {
            $q->where('user_id', $vendeurId);
        })->get();

        $averageRating = $avis->isNotEmpty() ? $avis->avg('note') : 0;
        $totalReviews = $avis->count();

        // Rating distribution
        $ratingDistribution = [
            5 => $avis->where('note', 5)->count(),
            4 => $avis->where('note', 4)->count(),
            3 => $avis->where('note', 3)->count(),
            2 => $avis->where('note', 2)->count(),
            1 => $avis->where('note', 1)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'averageRating' => round($averageRating, 1),
                'totalReviews' => $totalReviews,
                'ratingDistribution' => $ratingDistribution,
                'positivePercentage' => $totalReviews > 0 ? round(((($ratingDistribution[5] + $ratingDistribution[4]) / $totalReviews) * 100), 0) : 0
            ]
        ]);
    }
}
