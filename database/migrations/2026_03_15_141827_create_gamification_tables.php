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
        // Table des types de badges
        Schema::create('badge_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // 'premier_vendeur', 'elite', 'top_products', etc
            $table->string('name'); // 💎 Premier Vendeur
            $table->string('emoji'); // 💎
            $table->text('description');
            $table->string('condition'); // Rule for auto-award
            $table->integer('required_value')->nullable(); // e.g., 50 products for Premier
            $table->timestamps();
        });

        // Badges possédés par chaque utilisateur
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('badge_id');
            $table->timestamp('awarded_at')->useCurrent();
            $table->text('reason')->nullable(); // Why they got it
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('badge_id')->references('id')->on('badge_types')->onDelete('cascade');
            $table->unique(['user_id', 'badge_id']); // One badge per user
        });

        // Points de gamification
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('total_points')->default(0); // Total cumulé
            $table->integer('this_month')->default(0); // Points du mois
            $table->integer('level')->default(1); // Bronze 1-10, Silver 11-25, Gold 26+
            $table->string('tier')->default('bronze'); // bronze, silver, gold, platinum
            $table->longText('breakdown')->nullable(); // JSON: points source breakdown
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('tier');
            $table->index('total_points');
        });

        // Historique des points (traçabilité)
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('points');
            $table->string('type'); // 'sale', 'review', 'milestone', 'badge_award', etc
            $table->text('description');
            $table->nullableMorphs('related'); // Can relate to Commande, Avis, etc
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('user_points');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badge_types');
    }
};
