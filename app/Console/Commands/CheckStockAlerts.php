<?php

namespace App\Console\Commands;

use App\Models\Produit;
use App\Models\StockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckStockAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check-stock {--force : Force checking all products}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check stock levels and trigger alerts if needed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔔 Vérification des alertes de stock...');

        try {
            $alertsTriggered = 0;

            // Récupérer tous les seuils d'alerte actifs
            $alerts = StockAlert::where('is_active', true)
                ->with('produit')
                ->get();

            $this->info("Vérification de {$alerts->count()} produits avec alertes configurées");

            foreach ($alerts as $alert) {
                try {
                    if (!$alert->produit) {
                        continue;
                    }

                    // Vérifier si le stock est sous le seuil
                    if ($alert->produit->stock <= $alert->alert_threshold) {
                        $this->line("  ⚠️ Alerte détectée: {$alert->produit->nom} ({$alert->produit->stock} unités)");

                        // Déclencher l'alerte via la méthode du modèle
                        $alert->produit->checkAndTriggerStockAlert();
                        $alertsTriggered++;
                    }
                } catch (\Exception $e) {
                    $this->warn("  ❌ Erreur pour {$alert->produit?->nom}: {$e->getMessage()}");
                    Log::warning("Erreur vérification alerte stock: " . $e->getMessage());
                }
            }

            $this->info("✅ Vérification terminée. {$alertsTriggered} alerte(s) déclenchée(s)");
            Log::info("✅ CheckStockAlerts - {$alertsTriggered} alertes déclenchées");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erreur: {$e->getMessage()}");
            Log::error("Erreur CheckStockAlerts: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
