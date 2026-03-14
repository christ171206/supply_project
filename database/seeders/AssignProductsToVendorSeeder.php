<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class AssignProductsToVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Trouver le vendeur de test
        $vendor = User::where('email', 'testshop@supply.ci')
            ->orWhere('email', 'vendeur@test.com')
            ->first();

        if (!$vendor) {
            $this->command->error('❌ Vendeur de test non trouvé!');
            return;
        }

        // Assigner tous les produits à ce vendeur
        $updated = Produit::query()->update(['user_id' => $vendor->id]);

        $this->command->info("✅ {$updated} produits assignés au vendeur: {$vendor->email}");
    }
}
