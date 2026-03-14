<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VÉRIFICATION DES DONNÉES ===\n\n";

// Vérifier les produits
$products = DB::table('produits')->count();
echo "✓ Produits total: $products\n";

// Vérifier les commandes
$orders = DB::table('commandes')->count();
echo "✓ Commandes total: $orders\n";

// Vérifier les commandes livrées
$delivered = DB::table('commandes')->where('statut', 'livree')->count();
echo "✓ Commandes livrées: $delivered\n";

// Vérifier les lignes de commande
$lines = DB::table('ligne_commandes')->count();
echo "✓ Lignes de commande: $lines\n";

// Revenu total
$revenue = DB::table('commandes')->where('statut', 'livree')->sum('total');
echo "✓ Revenu total: " . number_format($revenue, 0) . " FCFA\n\n";

// Top 5 produits vendus
echo "=== TOP 5 PRODUITS VENDUS ===\n";
$top = DB::table('produits')
    ->selectRaw('produits.nom, COUNT(ligne_commandes.id) as sold, SUM(ligne_commandes.quantite) AS qty')
    ->leftJoin('ligne_commandes', 'produits.id', '=', 'ligne_commandes.produit_id')
    ->groupBy('produits.id', 'produits.nom')
    ->orderByDesc('sold')
    ->limit(5)
    ->get();

if (count($top) > 0) {
    foreach ($top as $p) {
        echo "  • {$p->nom}: {$p->sold} commandes, {$p->qty} quantité\n";
    }
} else {
    echo "  Aucun produit vendu\n";
}

// Statistiques du mois
echo "\n=== STATISTIQUES DE MARS 2026 ===\n";
$march = DB::table('commandes')
    ->selectRaw('COUNT(*) as count, SUM(total) as revenue')
    ->where('statut', 'livree')
    ->whereYear('created_at', 2026)
    ->whereMonth('created_at', 3)
    ->first();

echo "  Commandes de mars: {$march->count}\n";
echo "  Revenu de mars: " . number_format($march->revenue, 0) . " FCFA\n";

// Avis
$reviews = DB::table('avis')->count();
$avgRating = DB::table('avis')->avg('note');
echo "  Avis total: $reviews\n";
echo "  Note moyenne: " . number_format($avgRating, 2) . "/5\n";
