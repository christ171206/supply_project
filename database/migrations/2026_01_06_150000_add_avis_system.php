<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table avis si elle n'existe pas
        if (!Schema::hasTable('avis')) {
            Schema::create('avis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->integer('note')->min(1)->max(5);
                $table->text('commentaire')->nullable();
                $table->timestamps();
            });
        }

        // Ajouter les notes moyennes aux produits (données de semence)
        $produits = [
            'Dell XPS 13' => 4.8,
            'MacBook Pro 14' => 4.9,
            'Sony WH-1000XM5' => 4.7,
            'SteelSeries Arctis 9' => 4.6,
            'JBL Quantum 800' => 4.5,
            'Logitech C920 HD' => 4.4,
            'Razer Kiyo Pro' => 4.6,
            'Elgato Facecam' => 4.5,
            'HP Pavilion 15' => 4.3,
            'ASUS VivoBook 15' => 4.4,
            'BenQ EW2480' => 4.2,
            'LG UltraWide 34' => 4.8,
            'Corsair K95 Platinum' => 4.7,
            'Logitech MX Master 3S' => 4.9,
            'SteelSeries Rival 5' => 4.5,
            'Razer DeathAdder V3' => 4.6,
            'HyperX Fury S' => 4.4,
            'AOC 24G2' => 4.3,
            'ASUS PA248QV' => 4.6,
        ];

        foreach ($produits as $nom => $note) {
            \DB::table('produits')
                ->where('nom', $nom)
                ->update(['note_moyenne' => $note]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
