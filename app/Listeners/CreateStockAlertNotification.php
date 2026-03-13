<?php

namespace App\Listeners;

use App\Events\StockAlertTriggered;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class CreateStockAlertNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StockAlertTriggered $event): void
    {
        try {
            $produit = $event->produit;
            $alert = $event->alert;
            $alertType = $event->alertType;

            // Notification pour le vendeur
            $alertTitle = $alertType === 'critical'
                ? "🚨 ALERTE CRITIQUE: Stock insuffisant pour {$produit->nom}"
                : "⚠️ ALERTE: Stock bas pour {$produit->nom}";

            $alertMessage = $alertType === 'critical'
                ? "Le stock du produit '{$produit->nom}' est CRITIQUE ! Quantité actuelle: {$alert->current_stock}. Stock minimum requis: {$alert->min_stock}. Action immédiate recommandée !"
                : "Le stock du produit '{$produit->nom}' est bas. Quantité actuelle: {$alert->current_stock}. Stock minimum: {$alert->min_stock}.";

            Notification::create([
                'user_id' => $produit->user_id,
                'type' => 'stock_alert_' . $alertType,
                'titre' => $alertTitle,
                'message' => $alertMessage,
                'lu' => false,
            ]);

            Log::info("✅ Alerte stock créée - Produit: {$produit->nom}, Type: {$alertType}, Vendeur: {$produit->user_id}");

            // Notification pour admin (alerte critique)
            if ($alertType === 'critical') {
                Notification::create([
                    'user_id' => 1, // Admin ID (à adapter selon votre config)
                    'type' => 'stock_alert_critical_admin',
                    'titre' => "🚨 ALERTE STOCK CRITIQUE: {$produit->nom}",
                    'message' => "Le produit '{$produit->nom}' (Vendeur: {$produit->user->shop_name}) a un stock critique ! Quantité: {$alert->current_stock} / Min: {$alert->min_stock}",
                    'lu' => false,
                ]);

                Log::info("✅ Alerte stock admin créée - Produit: {$produit->nom}");
            }
        } catch (\Exception $error) {
            Log::error('❌ Erreur CreateStockAlertNotification: ' . $error->getMessage(), ['exception' => $error]);
        }
    }
}
