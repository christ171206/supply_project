<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier la colonne vendor_status pour être un VARCHAR simple au lieu d'ENUM
        DB::statement("ALTER TABLE users MODIFY vendor_status VARCHAR(255) DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Aucune action nécessaire
    }
};
