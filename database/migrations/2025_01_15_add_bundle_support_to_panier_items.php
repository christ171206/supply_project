<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('panier_items', function (Blueprint $table) {
            // Ajouter une colonne bundle_id nullable
            $table->foreignId('bundle_id')
                ->nullable()
                ->after('produit_id')
                ->constrained('bundles')
                ->onDelete('cascade');

            // Index composé pour faire des requêtes efficaces
            $table->index(['panier_id', 'produit_id', 'bundle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panier_items', function (Blueprint $table) {
            $table->dropForeignKey(['bundle_id']);
            $table->dropColumn('bundle_id');
            $table->dropIndex(['panier_id', 'produit_id', 'bundle_id']);
        });
    }
};
