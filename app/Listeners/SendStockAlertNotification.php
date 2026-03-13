<?php

namespace App\Listeners;

use App\Events\StockAlertTriggered;
use App\Mail\StockAlertMail;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendStockAlertNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(StockAlertTriggered $event): void
    {
        try {
            $produit = $event->produit;
            $vendor = $produit->vendor;
            $alertType = $event->alertType;

            // 1. Envoyer email au vendeur
            try {
                Mail::to($vendor)->send(new StockAlertMail(
                    $produit,
                    $vendor,
                    $alertType,
                    $produit->stock,
                    $event->alert->alert_threshold
                ));
            } catch (\Exception $emailError) {
                Log::warning("Erreur envoi email alerte stock #{$produit->id}: " . $emailError->getMessage());
            }

            // 2. Créer notification pour le vendeur en BD
            try {
                Notification::create([
                    'user_id' => $vendor->id,
                    'type' => 'stock_alert_' . $alertType,
                    'titre' => $alertType === 'critical' ? '🚨 Rupture de stock' : '⚠️ Stock faible',
                    'message' => $event->broadcastWith()['message'],
                    'lu' => false,
                ]);
            } catch (\Exception $notifError) {
                Log::warning("Erreur création notification alerte stock: " . $notifError->getMessage());
            }

            // 3. Notifier l'admin aussi si rupture critique
            if ($alertType === 'critical') {
                try {
                    // Notifier tous les admins
                    $admins = \App\Models\User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'type' => 'critical_stock_alert',
                            'titre' => '🚨 ALERTE CRITIQUE - Rupture de stock détectée',
                            'message' => "Produit: {$produit->nom} | Vendeur: {$vendor->name} | Stock: {$produit->stock}",
                            'lu' => false,
                        ]);
                    }
                } catch (\Exception $adminNotifError) {
                    Log::warning("Erreur notification admin alerte critique: " . $adminNotifError->getMessage());
                }
            }

            Log::info("✅ Alerte stock traitée - Type: $alertType, Produit: {$produit->nom}, Vendeur: {$vendor->name}");
        } catch (\Exception $error) {
            Log::error('Erreur SendStockAlertNotification: ' . $error->getMessage(), ['exception' => $error]);
        }
    }
}
