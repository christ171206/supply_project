<?php

namespace App\Jobs;

use App\Mail\DeliveryReminderMail;
use App\Models\DeliveryReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDeliveryReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Récupérer les rappels en attente d'envoi
            $reminders = DeliveryReminder::getPendingReminders();

            Log::info("🔔 Envoi de " . count($reminders) . " rappels de livraison");

            foreach ($reminders as $reminder) {
                try {
                    $commande = $reminder->commande;
                    $client = $reminder->user;

                    // Envoyer l'email
                    Mail::to($client)->send(new DeliveryReminderMail(
                        $commande,
                        $client,
                        $reminder->days_before
                    ));

                    // Marquer comme envoyé
                    $reminder->markAsSent();

                    Log::info("✅ Rappel livraison envoyé - Commande: {$commande->numero}, Client: {$client->name}");
                } catch (\Exception $error) {
                    Log::error("❌ Erreur envoi rappel livraison #{$reminder->id}: " . $error->getMessage());
                    $reminder->markAsFailed($error->getMessage());
                }
            }

            Log::info("✅ Traitement des rappels de livraison terminé");
        } catch (\Exception $error) {
            Log::error('Erreur SendDeliveryReminders: ' . $error->getMessage(), ['exception' => $error]);
        }
    }
}
