<?php
// Test d'envoi d'email
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Mail\AdminNewVendorRegistrationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

try {
    // Chercher un admin
    $admin = User::where('is_admin', true)->first();

    if (!$admin) {
        echo "❌ Aucun admin trouvé en base de données\n";
        exit(1);
    }

    // Chercher un vendeur récent
    $vendor = User::where('role', 'vendor')->latest()->first();

    if (!$vendor) {
        echo "❌ Aucun vendeur trouvé en base de données\n";
        exit(1);
    }

    echo "📧 Envoi d'un email de test...\n";
    echo "   Vendeur: {$vendor->name} ({$vendor->email})\n";
    echo "   Admin: {$admin->name} ({$admin->email})\n\n";

    // Tenter d'envoyer l'email
    Mail::to($admin->email)->send(new AdminNewVendorRegistrationMail($vendor, $admin));

    echo "✅ Email envoyé avec succès!\n";
    echo "   Vérifiez MAILTRAP à: https://mailtrap.io/\n";
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi d'email:\n";
    echo "   " . $e->getMessage() . "\n\n";
    echo "Détails:\n";
    echo $e . "\n";
    exit(1);
}
