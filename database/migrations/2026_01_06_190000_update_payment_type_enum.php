<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('typePayement', ['mobile_money', 'carte_bancaire', 'paiement_livraison', 'wave', 'orange_money', 'mtn_money', 'moov_money', 'cash'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('typePayement', ['mobile_money', 'carte_bancaire', 'paiement_livraison'])->change();
        });
    }
};
