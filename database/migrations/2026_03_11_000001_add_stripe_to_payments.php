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
        Schema::table('payments', function (Blueprint $table) {
            // Colonnes Stripe
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_charge_id')->nullable();
            $table->json('stripe_response')->nullable(); // Stocker la réponse complète Stripe
            $table->enum('payment_type', ['cinetpay', 'stripe', 'manual'])->default('cinetpay');

            // Statuts enrichis
            $table->enum('stripe_status', ['requires_action', 'requires_confirmation', 'requires_payment_method', 'processing', 'succeeded', 'failed', 'canceled'])->nullable();

            // Timestamps pour webhook tracing
            $table->timestamp('stripe_webhook_received_at')->nullable();
            $table->string('idempotency_key')->nullable()->unique(); // Éviter les doubles paiements
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_charge_id',
                'stripe_response',
                'payment_type',
                'stripe_status',
                'stripe_webhook_received_at',
                'idempotency_key',
            ]);
        });
    }
};
