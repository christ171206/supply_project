<?php
// Script pour synchroniser les images des produits avec la base de données

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Produit;
use Illuminate\Support\Facades\File;

// Charger l'application
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "=== SYNCHRONISATION DES IMAGES DE PRODUITS ===\n\n";

// Récupérer le répertoire des images
$imageDir = public_path('storage/produits');
$files = File::files($imageDir);

// Grouper les images par produit
$productImages = [];
foreach ($files as $file) {
    $filename = $file->getFilename();

    // Extraire le nom du produit (tout avant le dernier chiffre)
    // Exemple: "Apple Magic Trackpad 2 1.jpg" → "Apple Magic Trackpad 2"
    $name = preg_replace('/\s+\d+\.(jpg|jpeg|png)$/i', '', $filename);

    if (!isset($productImages[$name])) {
        $productImages[$name] = [];
    }
    $productImages[$name][] = $filename;
}

// Trier les images par numéro pour chaque produit
foreach ($productImages as &$images) {
    usort($images, function ($a, $b) {
        // Extraire le numéro de chaque image
        preg_match('/(\d+)\.(jpg|jpeg|png)$/i', $a, $matchA);
        preg_match('/(\d+)\.(jpg|jpeg|png)$/i', $b, $matchB);
        $numA = isset($matchA[1]) ? (int)$matchA[1] : 0;
        $numB = isset($matchB[1]) ? (int)$matchB[1] : 0;
        return $numA - $numB;
    });
}

echo "🔍 Photos trouvées pour " . count($productImages) . " produits\n\n";

// Synchroniser avec la base de données
$synced = 0;
$notFound = 0;

foreach ($productImages as $productName => $images) {
    // Chercher le produit exactement
    $product = Produit::where('nom', $productName)->first();

    if ($product) {
        // Mettre à jour les images
        $product->update([
            'images' => $images,
            'image' => $images[0] ?? null
        ]);
        $synced++;

        if ($synced <= 5) {
            echo "✓ " . $productName . " (" . count($images) . " images)\n";
        }
    } else {
        $notFound++;
        if ($notFound <= 3) {
            echo "✗ Non trouvé: " . $productName . "\n";
        }
    }
}

echo "\n";
if ($synced > 5) {
    echo "... et " . ($synced - 5) . " autres produits\n";
}

echo "\n📊 RÉSUMÉ:\n";
echo "   ✓ Produits synchronisés: $synced\n";
echo "   ✗ Produits non trouvés: $notFound\n";
echo "   📷 Total d'images: " . count($files) . "\n";

// Vérifier si nous devons chercher des variantes
if ($notFound > 0) {
    echo "\n⚠️  Certains noms de produits ne correspondent pas exactement.\n";
    echo "   Les variantes (ex: 'Kingston Kingston FURY DDR') peuvent être stockées différemment.\n";
}

$kernel->terminate($request, $response);
