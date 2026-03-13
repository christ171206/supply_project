<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin-user';
    protected $description = 'Créer un utilisateur administrateur';

    public function handle(): int
    {
        // Vérifier si un admin existe
        if (User::where('is_admin', true)->exists()) {
            $this->info('✅ Un admin existe déjà');
            return 0;
        }

        // Créer l'admin
        $admin = User::create([
            'name' => 'Admin Supply',
            'email' => 'admin@supply.local',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
            'role' => 'client', // Les admins sont des clients avec is_admin=true
            'is_admin' => true,
            'country' => 'CI',
        ]);

        $this->info('✅ Admin créé avec succès!');
        $this->info("   Email: {$admin->email}");
        $this->info('   Mot de passe: admin123456');

        return 0;
    }
}
