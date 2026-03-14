<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔄 Mise à jour des statuts des vendeurs...\n\n";

// Récupérer tous les vendeurs avec un statut différent de 'approved'
$vendeurs = User::where('role', 'vendor')
    ->where('vendor_status', '!=', 'approved')
    ->get();

if ($vendeurs->isEmpty()) {
    echo "✅ Tous les vendeurs sont déjà approuvés!\n";
} else {
    foreach ($vendeurs as $vendeur) {
        $oldStatus = $vendeur->vendor_status;
        $vendeur->update(['vendor_status' => 'approved']);
        echo "✅ {$vendeur->email} ({$oldStatus} → approved)\n";
    }
}

// Afficher le récapitulatif
echo "\n📊 Récapitulatif:\n";
$activeVendors = User::where('role', 'vendor')->where('vendor_status', 'approved')->count();
echo "🏪 Vendeurs actifs: {$activeVendors}\n";
