<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification
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
    public function handle(OrderStatusChanged $event): void
    {
        try {
            $commande = $event->commande;
            $newStatus = $event->newStatus;

            // Mappage des labels de statut
            $statusLabels = [
                'en_attente' => 'En attente de confirmation',
                'confirmee' => 'Confirmée',
                'expediee' => 'Expédiée',
                'livree' => 'Livrée',
                'annulee' => 'Annulée',
                'refusee' => 'Refusée',
            ];

            $statusLabel = $statusLabels[$newStatus] ?? $newStatus;

            // Notification pour le client
            $clientMessage = $this->getClientMessage($newStatus, $commande);
            Notification::create([
                'user_id' => $commande->user_id,
                'type' => 'order_status_change',
                'titre' => "Commande #{$commande->numero} - {$statusLabel}",
                'message' => $clientMessage,
                'lu' => false,
            ]);

            Log::info("✅ Notification statut commande créée - Commande: {$commande->numero}, Client: {$commande->user_id}");

            // Notification pour le vendeur si applicable
            if ($newStatus === 'en_attente' || $newStatus === 'confirmee') {
                $vendorMessage = "Nouvelle commande #{$commande->numero} - {$statusLabel}. Montant: " . number_format($commande->total, 0, ',', ' ') . ' FCFA';

                // Notifier tous les vendeurs impliqués dans la commande
                $vendorIds = $commande->ligneCommandes()->distinct('produit_id')
                    ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
                    ->pluck('produits.user_id')
                    ->unique();

                foreach ($vendorIds as $vendorId) {
                    Notification::create([
                        'user_id' => $vendorId,
                        'type' => 'vendor_order',
                        'titre' => "Nouvelle commande #{$commande->numero}",
                        'message' => $vendorMessage,
                        'lu' => false,
                    ]);
                }

                Log::info("✅ Notification vendeur créée - Commande: {$commande->numero}");
            }
        } catch (\Exception $error) {
            Log::error('❌ Erreur SendOrderStatusNotification: ' . $error->getMessage(), ['exception' => $error]);
        }
    }

    /**
     * Obtenir le message pour le client selon le statut
     */
    private function getClientMessage(string $status, $commande): string
    {
        $messages = [
            'en_attente' => "Votre commande a été reçue. Elle est actuellement en attente de confirmation par le vendeur.",
            'confirmee' => "Votre commande a été confirmée et sera bientôt expédiée.",
            'expediee' => "Votre commande a été expédiée ! Vous recevrez un numéro de suivi très bientôt.",
            'livree' => "Félicitations ! Votre commande a été livrée. Merci pour votre confiance !",
            'annulee' => "Votre commande a été annulée. Pour plus d'informations, contactez le support.",
            'refusee' => "Votre commande a été refusée. Veuillez contacter le support.",
        ];

        return $messages[$status] ?? "Votre commande a été mise à jour.";
    }
}
