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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter les champs de profil vendeur
            $table->text('boutique_description')->nullable()->after('email');
            $table->string('boutique_telephone')->nullable()->after('boutique_description');
            $table->string('boutique_adresse')->nullable()->after('boutique_telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['boutique_description', 'boutique_telephone', 'boutique_adresse']);
        });
    }
};
