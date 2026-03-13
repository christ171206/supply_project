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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('quartiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commune_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->foreignId('quartier_id')->nullable()->constrained('quartiers')->onDelete('set null');
            $table->string('adresse_detail')->nullable();
            $table->string('telephone_livraison')->nullable();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_code')->nullable()->unique();
            $table->string('provider_transaction_id')->nullable()->unique();
            $table->enum('payment_status', ['initialisee', 'en_attente', 'confirmee', 'echouee', 'annulee'])->default('initialisee');
            $table->text('response_data')->nullable();
            $table->timestamp('payment_initiated_at')->nullable();
            $table->timestamp('payment_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $cols = ['payment_code', 'provider_transaction_id', 'payment_status', 'response_data', 'payment_initiated_at', 'payment_confirmed_at'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'quartier_id')) {
                $table->dropForeign(['quartier_id']);
                $table->dropColumn('quartier_id');
            }
            if (Schema::hasColumn('commandes', 'adresse_detail')) {
                $table->dropColumn('adresse_detail');
            }
            if (Schema::hasColumn('commandes', 'telephone_livraison')) {
                $table->dropColumn('telephone_livraison');
            }
        });

        Schema::dropIfExists('quartiers');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regions');
    }
};
