<?php

/**
 * Test Script for Marketing Hub Integration
 * Tests Flash Sales and Bundles
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\FlashSale;
use App\Models\Bundle;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;

echo "\n=== MARKETING HUB TEST SUITE ===\n\n";

// Test 1: Check Categorie-FlashSale relationship
echo "✓ Test 1: Categorie-FlashSale Relationship\n";
$categorie = Categorie::first();
if ($categorie) {
    echo "  - Found categorie: {$categorie->nom}\n";
    $flashSales = $categorie->flashSales()->count();
    echo "  - Flash Sales linked: {$flashSales}\n";
} else {
    echo "  ⚠ No categories found\n";
}

// Test 2: Check Flash Sales
echo "\n✓ Test 2: Flash Sales\n";
$activeSales = FlashSale::actif()->count();
$allSales = FlashSale::count();
echo "  - Total Flash Sales: {$allSales}\n";
echo "  - Active Flash Sales: {$activeSales}\n";

// Test 3: Check Bundles
echo "\n✓ Test 3: Bundles\n";
$allBundles = Bundle::count();
$activeBundles = Bundle::actif()->count();
echo "  - Total Bundles: {$allBundles}\n";
echo "  - Active Bundles: {$activeBundles}\n";

// Test 4: Check Bundle-Product relationships
echo "\n✓ Test 4: Bundle-Product Relationships\n";
$bundles = Bundle::with('produits')->limit(3)->get();
foreach ($bundles as $bundle) {
    $count = $bundle->produits->count();
    echo "  - Bundle '{$bundle->nom}': {$count} products\n";
}

// Test 5: Test FlashSale price calculation
echo "\n✓ Test 5: Flash Sale Price Calculation\n";
if ($activeSales > 0) {
    $sale = FlashSale::actif()->first();
    $prixOriginal = 100000;
    $prixReduit = $sale->prixReduit($prixOriginal);
    $reduction = round((($prixOriginal - $prixReduit) / $prixOriginal) * 100);
    echo "  - Original Price: " . number_format($prixOriginal, 0, ',', ' ') . " FCFA\n";
    echo "  - Reduced Price: " . number_format($prixReduit, 0, ',', ' ') . " FCFA\n";
    echo "  - Discount: {$reduction}% (Configured: {$sale->pourcentage_reduction}%)\n";
}

// Test 6: Check panier_items table structure
echo "\n✓ Test 6: Panier Items Table Structure\n";
$columns = DB::getSchemaBuilder()->getColumnListing('panier_items');
echo "  - Columns: " . implode(', ', $columns) . "\n";
$hasBundleId = in_array('bundle_id', $columns);
echo "  - Has bundle_id: " . ($hasBundleId ? 'Yes' : 'No') . "\n";

// Test 7: Check Flash Sale Scopes
echo "\n✓ Test 7: Flash Sale Scopes\n";
$active = FlashSale::actif()->count();
$inactive = FlashSale::inactif()->count();
$expired = FlashSale::expire()->count();
echo "  - Actif: {$active}\n";
echo "  - Inactif: {$inactive}\n";
echo "  - Expired: {$expired}\n";

// Test 8: Check Bundle Scopes
echo "\n✓ Test 8: Bundle Scopes\n";
$activeBundles = Bundle::actif()->count();
$availableBundles = Bundle::disponible()->count();
echo "  - Actif: {$activeBundles}\n";
echo "  - Disponible: {$availableBundles}\n";

echo "\n=== ALL TESTS COMPLETED ===\n\n";
