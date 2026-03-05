<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'produit_id')) {
                $table->foreignId('produit_id')->nullable()->constrained('produits')->onDelete('cascade')->after('commande_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'produit_id')) {
                $table->dropForeign(['produit_id']);
                $table->dropColumn('produit_id');
            }
        });
    }
};
