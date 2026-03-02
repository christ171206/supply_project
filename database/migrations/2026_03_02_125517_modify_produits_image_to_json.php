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
        Schema::table('produits', function (Blueprint $table) {
            // Ajouter une colonne JSON pour stocker les images multiples
            $table->json('images')->nullable()->after('image');
        });

        // Migrer les données existantes de 'image' vers 'images'
        DB::statement('UPDATE produits SET images = JSON_ARRAY(image) WHERE image IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
