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
        Schema::table('commandes', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('commandes', 'estimated_delivery_date')) {
                $table->dateTime('estimated_delivery_date')->nullable()->comment('Date de livraison estimée');
            }

            if (!Schema::hasColumn('commandes', 'actual_delivery_date')) {
                $table->dateTime('actual_delivery_date')->nullable()->comment('Date de livraison réelle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['estimated_delivery_date', 'actual_delivery_date']);
        });
    }
};
