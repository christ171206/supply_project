<?php

namespace Database\Seeders;

use App\Models\User;
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
                'vendor_status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Comptes de test créés avec succès!');
    }
}
