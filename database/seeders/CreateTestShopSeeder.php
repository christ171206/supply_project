<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateTestShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer ou récupérer le vendeur Test Shop
        $vendor = User::updateOrCreate(
            ['email' => 'testshop@supply.ci'],
            [
                'name' => 'Test Shop',
                'phone' => '+225 0123456789',
                'password' => Hash::make('testshop123'),
                'role' => 'vendor',
                'shop_name' => 'Test Shop',
                'email_verified_at' => now(),
            ]
        );

        // Assigner tous les produits à Test Shop
        $count = Produit::query()->update(['user_id' => $vendor->id]);

        $this->command->info("✓ Compte 'Test Shop' créé/mis à jour (ID: {$vendor->id})");
        $this->command->info("✓ {$count} produit(s) assigné(s) à Test Shop");
        $this->command->info("───────────────────────────────────────");
        $this->command->info("📧 Email: testshop@supply.ci");
        $this->command->info("🔐 Mot de passe: testshop123");
        $this->command->info("───────────────────────────────────────");
    }
}
