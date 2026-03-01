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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Event details
            $table->string('event_type'); // 'login', 'logout', 'password_change', 'delete_account', etc.
            $table->string('status')->default('success'); // 'success', 'failed'

            // Device information
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable(); // 'Chrome', 'Firefox', 'Safari', etc.
            $table->string('platform')->nullable(); // 'Windows', 'Linux', 'Mac', 'iOS', etc.
            $table->string('device_type')->nullable(); // 'desktop', 'mobile', 'tablet'

            // Location (optional, can be enriched later with IP geolocation)
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            // Message/Details
            $table->text('message')->nullable();
            $table->json('metadata')->nullable(); // Additional data (JSON)

            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('event_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
