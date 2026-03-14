<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un compte client de test
        User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        // Créer un compte vendeur de test
        User::firstOrCreate(
            ['email' => 'vendeur@test.com'],
            [
                'name' => 'Vendeur Test',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'shop_name' => 'Tech Store Test',
                'phone' => '+33 6 00 00 00 00',
                'address' => '123 Rue de la Tech, 75000 Paris',
                'vendor_status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        // Assigner tous les produits au vendeur de test (trouver par email ou créer un nouveau si c'est le seul)
        $vendor = User::where('email', 'vendeur@test.com')->orWhere('email', 'testshop@supply.ci')->first();
        if ($vendor) {
            Produit::query()->update(['user_id' => $vendor->id]);
            $this->command->info("📦 Tous les produits assignés à: {$vendor->email}");
        }

        $this->command->info('✅ Comptes de test créés avec succès!');
    }
}
