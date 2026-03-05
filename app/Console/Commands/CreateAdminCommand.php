<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AdminRole;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create an admin user account';

    public function handle()
    {
        try {
            // Vérifier si l'admin existe déjà
            $existingAdmin = User::where('email', 'admin@supply.ci')->first();
            if ($existingAdmin) {
                $this->error('L\'admin admin@supply.ci existe déjà.');
                return 1;
            }

            // Créer l'utilisateur admin
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@supply.ci',
                'password' => bcrypt('admin123')
            ]);

            // Récupérer le rôle super_admin
            $role = AdminRole::where('name', 'super_admin')->first();

            // Mettre à jour l'utilisateur avec les flags admin
            $admin->update([
                'is_admin' => true,
                'admin_role_id' => $role->id
            ]);

            $this->info('✅ Compte admin créé avec succès!');
            $this->info('   Email: admin@supply.ci');
            $this->info('   Password: admin123');
            $this->info('   Rôle: Super Admin');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return 1;
        }
    }
}
