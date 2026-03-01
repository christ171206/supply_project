<?php

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Make app
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Produit;

echo "🔧 Attribution des produits au vendeur 'test'...\n\n";

// 1. Créer ou trouver le vendeur test
$vendeurTest = User::where('name', 'test')->first();

if (!$vendeurTest) {
    echo "❌ Utilisateur 'test' non trouvé.\n";
    echo "📝 Création de l'utilisateur 'test'...\n";

    $vendeurTest = User::create([
        'name' => 'test',
        'email' => 'test@test.com',
        'password' => bcrypt('password'),
        'shop_name' => 'Test Shop',
        'phone' => '+225 0769237065',
        'est_vendeur' => true,
    ]);

    echo "✅ Utilisateur 'test' créé avec l'ID: {$vendeurTest->id}\n\n";
} else {
    echo "✅ Utilisateur 'test' trouvé (ID: {$vendeurTest->id})\n\n";
}

// 2. Attribuer tous les produits sans vendeur
$produitsOrphelins = Produit::whereNull('user_id')->count();
echo "📦 Produits sans vendeur: {$produitsOrphelins}\n";

if ($produitsOrphelins > 0) {
    Produit::whereNull('user_id')->update(['user_id' => $vendeurTest->id]);
    echo "✅ {$produitsOrphelins} produits attribués au vendeur 'test'\n\n";
} else {
    echo "ℹ️  Aucun produit sans vendeur\n\n";
}

// 3. Afficher les statistiques
$totalProduits = Produit::count();
$produitsDuTest = Produit::where('user_id', $vendeurTest->id)->count();

echo "📊 Statistiques:\n";
echo "   • Total de produits: {$totalProduits}\n";
echo "   • Produits du vendeur 'test': {$produitsDuTest}\n";
echo "   • Pourcentage: " . round(($produitsDuTest / $totalProduits) * 100, 2) . "%\n\n";

echo "✨ Opération terminée avec succès!\n";
