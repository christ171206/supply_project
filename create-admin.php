<?php
require 'vendor/autoload.php';

use App\Models\User;
use App\Models\AdminRole;

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

try {
    // Créer l'utilisateur admin
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@supply.ci',
        'password' => bcrypt('admin123'),
        'role' => 'admin'
    ]);

    // Récupérer le rôle super_admin
    $role = AdminRole::where('name', 'super_admin')->first();

    // Mettre à jour l'utilisateur avec les flags admin
    $admin->update([
        'is_admin' => true,
        'admin_role_id' => $role->id
    ]);

    echo "✅ Compte admin créé avec succès!\n";
    echo "   Email: admin@supply.ci\n";
    echo "   Password: admin123\n";
    echo "   Rôle: Super Admin\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
