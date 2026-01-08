<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un utilisateur client de test
        User::updateOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client Test',
                'firstname' => 'Test',
                'lastname' => 'Client',
                'email' => 'client@test.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+225 01 23 45 67',
                'address' => '123 Rue Test, Abidjan, Côte d\'Ivoire',
                'email_verified_at' => now(),
            ]
        );

        // Créer un utilisateur vendeur de test
        User::updateOrCreate(
            ['email' => 'vendeur@test.com'],
            [
                'name' => 'Vendeur Test',
                'firstname' => 'Test',
                'lastname' => 'Vendeur',
                'email' => 'vendeur@test.com',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'phone' => '+225 98 76 54 32',
                'address' => 'Boutique Test, Abidjan',
                'email_verified_at' => now(),
            ]
        );
    }
}
