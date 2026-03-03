<?php
// Script pour afficher les noms des produits en base de données

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Produit;

// Charger l'application
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "=== NOMS DES PRODUITS EN BD ===\n\n";

$products = Produit::select('id', 'nom')->orderBy('nom')->get();

echo "Total produits: " . $products->count() . "\n\n";

// Afficher les produits qui commencent par "A" ou "AM"
$productsA = $products->filter(function ($p) {
    return strpos(strtolower($p->nom), 'amd') === 0 ||
        strpos(strtolower($p->nom), 'asus') === 0 ||
        strpos(strtolower($p->nom), 'apc') === 0 ||
        strpos(strtolower($p->nom), 'amazon') === 0 ||
        strpos(strtolower($p->nom), 'anker') === 0 ||
        strpos(strtolower($p->nom), 'apple') === 0 ||
        strpos(strtolower($p->nom), 'arctic') === 0 ||
        strpos(strtolower($p->nom), 'audio') === 0;
});

foreach ($productsA as $product) {
    echo "- " . $product->nom . "\n";
}

$kernel->terminate($request, $response);
