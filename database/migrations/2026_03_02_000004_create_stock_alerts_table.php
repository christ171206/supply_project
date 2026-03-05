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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('alert_threshold')->default(5); // Seuil d'alerte global
            $table->integer('reorder_quantity')->default(20); // Quantité à commander
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_alert_sent')->nullable();
            $table->timestamps();

            $table->index(['produit_id', 'is_active']);
        });

        // Ajouter colonne minimum de stock si elle n'existe pas
        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'stock_minimum')) {
                $table->integer('stock_minimum')->default(5);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'stock_minimum')) {
                $table->dropColumn('stock_minimum');
            }
        });

        Schema::dropIfExists('stock_alerts');
    }
};
