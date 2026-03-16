<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Vendeur/créateur
            $table->string('code')->unique(); // Code promo (ex: SUMMER2024)
            $table->text('description')->nullable(); // Description

            // Réduction
            $table->enum('type_reduction', ['pourcentage', 'montant_fixe'])->default('pourcentage');
            $table->decimal('taux_reduction', 8, 2); // 10.00 pour 10% ou 5000 pour 5000 FCFA

            // Limitations
            $table->integer('max_utilisations')->nullable(); // Nombre max d'utilisations (null = illimité)
            $table->integer('utilisations')->default(0); // Nombre d'utilisations actuelles
            $table->decimal('montant_minimum')->nullable(); // Montant minimum avant réduction
            $table->decimal('montant_maximum')->nullable(); // Montant maximum de réduction

            // Dates
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            // Status
            $table->enum('statut', ['actif', 'inactif', 'expire'])->default('actif');

            // Traçabilité
            $table->boolean('archive')->default(false);
            $table->timestamps();

            // Index pour optimisation
            $table->index('code');
            $table->index(['user_id', 'statut']);
            $table->index(['date_debut', 'date_fin']);
        });

        // Table pivot pour les produits ciblés (optionnel)
        Schema::create('promo_code_produit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['promo_code_id', 'produit_id']);
        });

        // Table pour tracker les utilisations
        Schema::create('promo_code_utilisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('montant_reduction', 10, 2);
            $table->timestamps();

            $table->unique(['promo_code_id', 'commande_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_code_utilisations');
        Schema::dropIfExists('promo_code_produit');
        Schema::dropIfExists('promo_codes');
    }
};
