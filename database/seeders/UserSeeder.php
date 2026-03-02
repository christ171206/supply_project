<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un compte vendeur de test
        User::create([
            'name' => 'Test Shop',
            'email' => 'testshop@supply.ci',
            'password' => Hash::make('testshop123'),
            'role' => 'vendor',
            'email_verified_at' => now(),
        ]);

        // Créer un compte client de test
        User::create([
            'name' => 'Client Test',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        // Créer d'autres clients de test
        User::create([
            'name' => 'Alice Martin',
            'email' => 'alice@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Dupont',
            'email' => 'bob@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);
    }
}
