<?php
// Script amélioré pour synchroniser les images de produits

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Produit;
use Illuminate\Support\Facades\File;

// Charger l'application
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "=== SYNCHRONISATION AMÉLIORÉE DES IMAGES ===\n\n";

// Récupérer tous les produits
$allProducts = Produit::all();

// Récupérer le répertoire des images
$imageDir = public_path('storage/produits');
$files = File::files($imageDir);

// Grouper les images par produit
$productImages = [];
foreach ($files as $file) {
    $filename = $file->getFilename();

    // Extraire le nom du produit (tout avant le dernier " N.")
    $name = preg_replace('/\s+\d+\.(jpg|jpeg|png)$/i', '', $filename);

    if (!isset($productImages[$name])) {
        $productImages[$name] = [];
    }
    $productImages[$name][] = $filename;
}

// Trier les images par numéro pour chaque produit
foreach ($productImages as &$images) {
    usort($images, function ($a, $b) {
        preg_match('/(\d+)\.(jpg|jpeg|png)$/i', $a, $matchA);
        preg_match('/(\d+)\.(jpg|jpeg|png)$/i', $b, $matchB);
        $numA = isset($matchA[1]) ? (int)$matchA[1] : 0;
        $numB = isset($matchB[1]) ? (int)$matchB[1] : 0;
        return $numA - $numB;
    });
}

echo "📸 " . count($productImages) . " groupes d'images trouvés\n";
echo "📦 " . $allProducts->count() . " produits en BD\n\n";

$synced = 0;
$notFound = [];

foreach ($productImages as $imageName => $images) {
    // Cherche exacte d'abord
    $product = $allProducts->firstWhere('nom', $imageName);

    if (!$product) {
        // Cherche approximative: comparer sans espaces/caractères spéciaux
        $searchName = strtolower(preg_replace('/[^a-z0-9]/i', '', $imageName));

        foreach ($allProducts as $p) {
            $productSearchName = strtolower(preg_replace('/[^a-z0-9]/i', '', $p->nom));

            if ($productSearchName === $searchName) {
                $product = $p;
                break;
            }

            // Cherche partielle: le nom du produit contient le nom de l'image ou vice-versa
            if (stripos($p->nom, $imageName) !== false || stripos($imageName, $p->nom) !== false) {
                $product = $p;
                break;
            }
        }
    }

    if ($product) {
        $product->update([
            'images' => $images,
            'image' => $images[0] ?? null
        ]);
        $synced++;

        if ($synced <= 8) {
            echo "✓ " . substr($product->nom, 0, 40) . " (" . count($images) . " images)\n";
        }
    } else {
        $notFound[] = $imageName;
    }
}

echo "\n";
if ($synced > 8) {
    echo "... et " . ($synced - 8) . " autres produits\n";
}

echo "\n📊 RÉSUMÉ:\n";
echo "   ✓ Produits mis à jour: $synced\n";
echo "   ✗ Images non liées: " . count($notFound) . "\n";

if (count($notFound) > 0 && count($notFound) <= 10) {
    echo "\n   Non trouvés:\n";
    foreach ($notFound as $name) {
        echo "      - " . $name . "\n";
    }
}

$kernel->terminate($request, $response);
