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
        // Support Tickets Table
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('support_type', ['produit', 'commande', 'paiement', 'livraison', 'compte', 'autre'])->default('autre');
            $table->string('subject', 255);
            $table->text('description');
            $table->enum('status', ['ouvert', 'fermé'])->default('ouvert');
            $table->enum('priority', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
            $table->enum('contact_method', ['plateforme', 'whatsapp'])->default('plateforme');
            $table->string('whatsapp_number', 20)->nullable();
            $table->timestamp('response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });

        // Support Messages Table
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->string('attachment_url')->nullable();
            $table->boolean('is_from_support')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('support_ticket_id');
            $table->index('user_id');
            $table->index('created_at');
        });

        // Vendor Message Templates Table
        Schema::create('vendor_message_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 100);
            $table->text('content');
            $table->enum('category', ['Promotion', 'Service', 'Autre'])->default('Autre');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_message_templates');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
    }
};
