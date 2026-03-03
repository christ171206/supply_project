<?php
// Script pour voir quelles images n'ont pas été liées

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Produit;
use Illuminate\Support\Facades\File;

// Charger l'application
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

$allProducts = Produit::all();
$imageDir = public_path('storage/produits');
$files = File::files($imageDir);

$productImages = [];
foreach ($files as $file) {
    $filename = $file->getFilename();
    $name = preg_replace('/\s+\d+\.(jpg|jpeg|png)$/i', '', $filename);

    if (!isset($productImages[$name])) {
        $productImages[$name] = [];
    }
    $productImages[$name][] = $filename;
}

$notFound = [];

foreach ($productImages as $imageName => $images) {
    $product = $allProducts->firstWhere('nom', $imageName);

    if (!$product) {
        $searchName = strtolower(preg_replace('/[^a-z0-9]/i', '', $imageName));

        foreach ($allProducts as $p) {
            $productSearchName = strtolower(preg_replace('/[^a-z0-9]/i', '', $p->nom));

            if ($productSearchName === $searchName) {
                $product = $p;
                break;
            }

            if (stripos($p->nom, $imageName) !== false || stripos($imageName, $p->nom) !== false) {
                $product = $p;
                break;
            }
        }
    }

    if (!$product) {
        $notFound[] = $imageName;
    }
}

echo "Images non liées (" . count($notFound) . "):\n";
foreach ($notFound as $name) {
    echo "   • $name\n";
}

$kernel->terminate($request, $response);
