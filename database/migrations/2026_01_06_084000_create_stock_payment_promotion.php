<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table Stock
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('idStock');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->timestamp('dateUpdate')->useCurrent();
            $table->timestamps();
        });

        // Table Payment
        Schema::create('payments', function (Blueprint $table) {
            $table->id('idPayment');
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('typePayement', ['mobile_money', 'carte_bancaire', 'paiement_livraison'])->default('paiement_livraison');
            $table->enum('statut', ['en_attente', 'confirme', 'failed'])->default('en_attente');
            $table->timestamps();
        });

        // Table Promotion
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('idPromotion');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->dateTime('dateDebut');
            $table->dateTime('dateFin');
            $table->integer('reduction'); // pourcentage ou montant
            $table->enum('statut', ['active', 'inactive', 'expuired'])->default('active');
            $table->boolean('archive')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('stocks');
    }
};
