<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'mode_paiement')) {
                $table->enum('mode_paiement', ['mobile_money', 'carte_bancaire', 'paiement_livraison'])->default('paiement_livraison')->change();
            }
            if (!Schema::hasColumn('commandes', 'payment_method')) {
                $table->enum('payment_method', ['wave', 'orange_money', 'mtn_money', 'moov_money', 'cash'])->default('cash')->after('mode_paiement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
