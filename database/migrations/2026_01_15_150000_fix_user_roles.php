<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corriger les rôles
        DB::table('users')->where('email', 'client@test.com')->update(['role' => 'client']);
        DB::table('users')->where('email', 'vendeur@test.com')->update(['role' => 'vendor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do
    }
};
