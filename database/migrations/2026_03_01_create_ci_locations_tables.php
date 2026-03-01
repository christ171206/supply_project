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
        // Table des régions
        Schema::create('ci_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        // Table des districts
        Schema::create('ci_districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('ci_regions')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->unique(['region_id', 'code']);
        });

        // Table des communes
        Schema::create('ci_communes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('ci_districts')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->unique(['district_id', 'code']);
        });

        // Table des quartiers
        Schema::create('ci_quartiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commune_id')->constrained('ci_communes')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->unique(['commune_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_quartiers');
        Schema::dropIfExists('ci_communes');
        Schema::dropIfExists('ci_districts');
        Schema::dropIfExists('ci_regions');
    }
};
