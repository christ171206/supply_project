<?php

namespace App\Console\Commands;

use App\Mail\AdminNewVendorRegistrationMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'app:test-email';
    protected $description = 'Test envoyer un email admin pour un nouveau vendeur';

    public function handle(): int
    {
        $this->info('📧 Test d\'envoi d\'email...');

        // Chercher un admin
        $admin = User::where('is_admin', true)->first();
        if (!$admin) {
            $this->error('❌ Aucun admin trouvé');
            return 1;
        }

        // Chercher un vendeur
        $vendor = User::where('role', 'vendor')->latest()->first();
        if (!$vendor) {
            $this->error('❌ Aucun vendeur trouvé');
            return 1;
        }

        $this->info("   Admin: {$admin->email}");
        $this->info("   Vendeur: {$vendor->email}\n");

        try {
            Mail::to($admin->email)->send(new AdminNewVendorRegistrationMail($vendor, $admin));
            $this->info('✅ Email envoyé avec succès!');
            $this->info('   Vérifiez MAILTRAP: https://mailtrap.io/');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
