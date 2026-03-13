<?php

namespace App\Listeners;

use App\Models\Notification;
use App\Models\DeliveryReminder;
use Illuminate\Support\Facades\Log;

class SendDeliveryReminderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Créer un rappel de livraison pour une commande
     */
    public function createDeliveryReminder($commande, int $daysBefore = 1): void
    {
        try {
            // Vérifier qu'un rappel n'existe pas déjà
            $existing = DeliveryReminder::where('commande_id', $commande->id)
                ->where('days_before', $daysBefore)
                ->whereNull('sent_at')
                ->first();

            if ($existing) {
                Log::info("ℹ️ Rappel de livraison existe déjà - Commande: {$commande->numero}");
                return;
            }

            $reminder = DeliveryReminder::create([
                'commande_id' => $commande->id,
                'user_id' => $commande->user_id,
                'scheduled_at' => now()->addDays($daysBefore),
                'days_before' => $daysBefore,
            ]);

            // Créer aussi la notification
            Notification::create([
                'user_id' => $commande->user_id,
                'type' => 'delivery_reminder',
                'titre' => "Rappel de livraison - Commande #{$commande->numero}",
                'message' => "Votre commande #{$commande->numero} devrait être livrée dans {$daysBefore} jour(s). Assurez-vous d'être disponible pour la réception.",
                'lu' => false,
            ]);

            Log::info("✅ Rappel de livraison créé - Commande: {$commande->numero}, Rappel dans {$daysBefore} jours");
        } catch (\Exception $error) {
            Log::error('❌ Erreur SendDeliveryReminderNotification: ' . $error->getMessage(), ['exception' => $error]);
        }
    }
}
