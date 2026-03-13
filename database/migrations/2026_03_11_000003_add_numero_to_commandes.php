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
            // Ajouter numéro unique de commande (ex: CMD-001234) si pas déjà existant
            if (!Schema::hasColumn('commandes', 'numero')) {
                $table->string('numero')->unique()->nullable()->after('id');
            }
            // Ajouter payment_method s'il n'existe pas (différent de mode_paiement)
            if (!Schema::hasColumn('commandes', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('mode_paiement');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'numero')) {
                $table->dropColumn('numero');
            }
            if (Schema::hasColumn('commandes', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
