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
        // Table des catégories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Table des produits
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->string('nom');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimum')->default(10); // Pour les alertes
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Table des paniers
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table des items du panier
        Schema::create('panier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panier_id')->constrained('paniers')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->timestamps();
        });

        // Table des commandes
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('statut', ['en_attente', 'confirmee', 'expediee', 'livree', 'annulee'])->default('en_attente');
            $table->enum('mode_paiement', ['mobile_money', 'carte_bancaire', 'paiement_livraison'])->default('paiement_livraison');
            $table->boolean('paiement_confirme')->default(false);
            $table->text('adresse_livraison')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Table des lignes de commande
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('restrict');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('sous_total', 10, 2);
            $table->timestamps();
        });

        // Table des messages entre clients et vendeur
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('cascade');
            $table->text('contenu');
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });

        // Table des notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // commande_confirmee, stock_faible, nouveau_message, etc.
            $table->string('titre');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });

        // Table des avis/commentaires sur les produits
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('note')->default(5); // Note de 1 à 5
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('ligne_commandes');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('panier_items');
        Schema::dropIfExists('paniers');
        Schema::dropIfExists('produits');
        Schema::dropIfExists('categories');
    }
};
