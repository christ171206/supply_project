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
        Schema::create('promo_abuse_rules', function (Blueprint $table) {
            $table->id();

            // Rule Name & Description
            $table->string('name');
            $table->text('description')->nullable();

            // Rule Type
            $table->enum('rule_type', [
                'limit_per_user',           // Max uses per user
                'limit_per_day',            // Max uses per day globally
                'limit_per_week',           // Max uses per week globally
                'limit_per_month',          // Max uses per month globally
                'min_account_age',          // Account must be X days old
                'min_cart_value',           // Min purchase required
                'max_discount_per_day',     // Max discount amount per day per user
                'forbidden_combination',     // Cannot combine with other promos/coupons
                'excluded_categories',      // Categories that cannot use the promo
                'excluded_vendors',         // Vendors excluded from the promo
                'max_quantity_per_order',   // Max items to discount per order
            ]);

            // Configuration (flexible JSON storage)
            $table->json('config');  // Type-specific config

            // Scope & Application
            $table->enum('applies_to', ['all', 'specific_promo', 'specific_coupon', 'global_offers']);
            $table->unsignedBigInteger('applies_to_id')->nullable();  // If specific promo/coupon/offer

            // Control
            $table->boolean('is_enabled')->default(true);
            $table->integer('severity')->default(1);  // 1=info, 2=warning, 3=block

            // Admin Info
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            // Indexes
            $table->index('is_enabled');
            $table->index('rule_type');
            $table->index('applies_to');
        });

        // Abuse Logs (track violations)
        Schema::create('promo_abuse_logs', function (Blueprint $table) {
            $table->id();

            // What happened
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('rule_id');
            $table->foreign('rule_id')->references('id')->on('promo_abuse_rules')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();  // Related order if applicable
            $table->foreign('order_id')->references('id')->on('commandes')->onDelete('set null');

            // Details
            $table->enum('violation_type', ['attempted', 'blocked', 'flagged']);
            $table->text('details')->nullable();  // JSON with violation details
            $table->decimal('potential_loss', 12, 2)->nullable();  // How much customer saved via abuse

            // Action taken
            $table->enum('action_taken', ['none', 'warning', 'blocked', 'manual_review']);
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('rule_id');
            $table->index('violation_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_abuse_logs');
        Schema::dropIfExists('promo_abuse_rules');
    }
};
