<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\VendorOrderNotification;
use App\Models\LigneCommande;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        try {
            $commande = $event->commande;
            
            // Récupérer les lignes de commande avec les infos du produit
            $lignesCommandes = $commande->ligneCommandes()->with('produit')->get();

            // Grouper par vendeur
            $vendorGroups = [];
            foreach ($lignesCommandes as $ligne) {
                $vendorId = $ligne->produit->user_id;
                if (!isset($vendorGroups[$vendorId])) {
                    $vendorGroups[$vendorId] = [];
                }
                $vendorGroups[$vendorId][] = [
                    'nom_produit' => $ligne->produit->nom,
                    'quantite' => $ligne->quantite,
                    'prix_unitaire' => $ligne->prix_unitaire,
                    'sous_total' => $ligne->sous_total,
                    'produit_id' => $ligne->produit_id,
                ];
            }

            // Envoyer une notification à chaque vendeur concerné
            foreach ($vendorGroups as $vendorId => $items) {
                try {
                    $vendor = \App\Models\User::find($vendorId);
                    if ($vendor && $vendor->email) {
                        Mail::send(new VendorOrderNotification($commande, $vendor, $items));
                    }
                } catch (\Exception $emailError) {
                    \Illuminate\Support\Facades\Log::warning(
                        "Erreur envoi email vendeur #$vendorId: " . $emailError->getMessage()
                    );
                    // Continuer même si l'email échoue - la commande ne doit pas être bloquée
                }
            }
        } catch (\Exception $error) {
            \Illuminate\Support\Facades\Log::error(
                'Erreur SendVendorOrderNotification: ' . $error->getMessage(),
                ['exception' => $error]
            );
            // Ne pas relancer l'exception - laisser la commande être créée
        }
    }
}
