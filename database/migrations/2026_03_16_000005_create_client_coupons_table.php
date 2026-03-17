<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table de jonction client-coupon
        Schema::create('client_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->enum('statut', ['actif', 'utilise', 'expire'])->default('actif');
            $table->dateTime('date_utilisee')->nullable();
            $table->dateTime('date_assignee')->useCurrent();
            $table->text('notes')->nullable(); // Ex: "Cadeau d'anniversaire", "Bonus fidélité"
            $table->timestamps();

            // Index pour recherches rapides
            $table->unique(['user_id', 'promo_code_id']);
            $table->index(['user_id', 'statut']);
            $table->index(['date_assignee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_coupons');
    }
};
