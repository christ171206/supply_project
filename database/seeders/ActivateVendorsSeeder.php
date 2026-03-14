<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivateVendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre à jour tous les vendeurs pour avoir un statut 'approved'
        DB::table('users')
            ->where('role', 'vendor')
            ->update(['vendor_status' => 'approved']);

        $this->command->info('✅ Tous les vendeurs sont maintenant activés!');
    }
}
