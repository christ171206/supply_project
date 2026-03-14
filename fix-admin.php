<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🔧 Mise à jour admin@supply.ci...\n\n";

// Mettre à jour l'admin
$admin = User::where('email', 'admin@supply.ci')->first();

if ($admin) {
    $admin->update([
        'is_admin' => true,
        'vendor_status' => null,
    ]);
    echo "✅ Admin mis à jour: {$admin->email}\n";
    echo "   is_admin: " . ($admin->is_admin ? 'true ✅' : 'false ❌') . "\n";
    echo "   Mot de passe: admin123\n";
} else {
    echo "❌ Admin non trouvé\n";
}
