<?php
// Créer un admin utilisateur
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Vérifier si un admin existe déjà
    $existingAdmin = User::where('is_admin', true)->first();

    if ($existingAdmin) {
        echo "✅ Admin existe déjà: {$existingAdmin->name} ({$existingAdmin->email})\n";
        exit(0);
    }

    // Créer un admin
    $admin = User::create([
        'name' => 'Admin Supply',
        'email' => 'admin@supply.local',
        'password' => Hash::make('admin123456'),
        'email_verified_at' => now(),
        'role' => 'admin',
        'is_admin' => true,
        'country' => 'CI',
    ]);

    echo "✅ Admin créé avec succès!\n";
    echo "   Nom: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Mot de passe: admin123456\n";
} catch (\Exception $e) {
    echo "❌ Erreur lors de la création de l'admin:\n";
    echo "   " . $e->getMessage() . "\n";
    exit(1);
}
