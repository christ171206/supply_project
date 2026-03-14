<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Avis;
use App\Models\User;

echo "=== TEST DES API ===\n\n";

// Test 1: Top Products
echo "1. TOP PRODUCTS API\n";
$products = Produit::selectRaw('produits.nom, COUNT(ligne_commandes.id) as sold, SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as revenue')
    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
    ->leftJoin('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
    ->where('commandes.statut', 'livree')
    ->groupBy('produits.id', 'produits.nom')
    ->orderByDesc('sold')
    ->limit(10)
    ->get();

echo "   Produits retournés: " . count($products) . "\n";
foreach ($products as $p) {
    echo "   - {$p->nom}: {$p->sold} vendus\n";
}

// Test 2: Detailed Stats
echo "\n2. DETAILED STATS API\n";
$startDate = \Carbon\Carbon::now()->startOfMonth();
$endDate = \Carbon\Carbon::now()->endOfMonth();

$totalRevenue = Commande::whereBetween('created_at', [$startDate, $endDate])
    ->where('statut', 'livree')
    ->sum('total');

$totalOrders = Commande::whereBetween('created_at', [$startDate, $endDate])
    ->where('statut', 'livree')
    ->count();

echo "   Période: {$startDate->format('Y-m-d')} à {$endDate->format('Y-m-d')}\n";
echo "   Commandes: $totalOrders\n";
echo "   Revenu: " . number_format($totalRevenue, 0) . " FCFA\n";

// Test 3: Daily Revenue (derniers 7 jours)
echo "\n3. DAILY REVENUE API (7 jours)\n";
$startDate7 = \Carbon\Carbon::now()->subDays(7);
$data = Commande::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
    ->where('statut', 'livree')
    ->where('created_at', '>=', $startDate7)
    ->groupBy('date')
    ->orderBy('date')
    ->get();

echo "   Jours avec ventes: " . count($data) . "\n";
foreach ($data as $row) {
    echo "   - {$row->date}: " . number_format($row->revenue, 0) . " FCFA ({$row->orders} commandes)\n";
}
