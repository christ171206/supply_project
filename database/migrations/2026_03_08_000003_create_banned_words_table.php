<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banned_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->text('description')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by_admin')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->foreign('created_by_admin')->references('id')->on('users')->onDelete('set null');
            $table->index('word');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banned_words');
    }
};
