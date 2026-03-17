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
        Schema::create('global_offers', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->text('description')->nullable();

            // Offer Type
            $table->enum('type', [
                'discount_percent',      // % discount
                'discount_fixed',        // Fixed amount discount
                'free_shipping',         // Free shipping
                'buy_x_get_y',          // Buy X get Y free
                'tiered_discount'       // More quantity = more discount
            ]);

            // Value (what to apply)
            $table->decimal('value', 10, 2);  // The % or amount
            $table->decimal('max_discount', 10, 2)->nullable(); // Cap for discounts

            // Target Configuration
            $table->enum('target_type', ['all', 'category', 'vendor', 'product']);
            $table->unsignedBigInteger('target_id')->nullable();  // Category/Vendor/Product ID

            // Conditions
            $table->decimal('min_purchase', 10, 2)->default(0);  // Minimum purchase amount
            $table->integer('min_quantity')->default(1);         // Minimum items

            // Tiered discount config (JSON for buy_x_get_y and tiered)
            $table->json('config')->nullable();  // Flexible config storage

            // Timing
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);

            // Usage Tracking
            $table->integer('usage_count')->default(0);
            $table->decimal('total_discount_given', 12, 2)->default(0);

            // Admin Info
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('target_type');
            $table->index(['start_date', 'end_date']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_offers');
    }
};
