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
        Schema::create('delivery_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('user_id'); // Client
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->integer('days_before')->default(3); // Rappel X jours avant livraison
            $table->dateTime('scheduled_for')->nullable(); // Date d'envoi prévue
            $table->dateTime('sent_at')->nullable(); // Date d'envoi réel
            $table->text('error_message')->nullable(); // Message d'erreur si failed
            $table->integer('retry_count')->default(0); // Nombre de tentatives
            $table->timestamps();

            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
            $table->index('scheduled_for');
            $table->index(['commande_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_reminders');
    }
};
