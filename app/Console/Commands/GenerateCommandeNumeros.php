<?php

namespace App\Console\Commands;

use App\Models\Commande;
use Illuminate\Console\Command;

class GenerateCommandeNumeros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commande:generate-numeros';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer les numéros de commande pour les commandes existantes sans numéro';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $commandes = Commande::whereNull('numero')->get();

        if ($commandes->isEmpty()) {
            $this->info('Aucune commande sans numéro trouvée.');
            return 0;
        }

        $this->info("Génération des numéros pour {$commandes->count()} commande(s)...");

        $updated = 0;
        foreach ($commandes as $commande) {
            try {
                // Générer un numéro basé sur la date de création
                // Format : CMD-YYYYMMDDHHmmss + random 5 digits
                $timestamp = $commande->created_at->format('YmdHis');
                $random = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $numero = 'CMD-' . $timestamp . $random;

                // Assurer l'unicité
                $counter = 0;
                $baseNumero = $numero;
                while (Commande::where('numero', $numero)->exists() && $counter < 100) {
                    $random = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    $numero = 'CMD-' . $timestamp . $random;
                    $counter++;
                }

                $commande->update(['numero' => $numero]);
                $updated++;
                $this->line("  ✓ Commande #{$commande->id} → {$numero}");
            } catch (\Exception $e) {
                $this->error("  ✗ Erreur pour Commande #{$commande->id}: {$e->getMessage()}");
            }
        }

        $this->info("✓ {$updated} commande(s) mise(s) à jour avec succès!");
        return 0;
    }
}
