<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientOrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private string $statusLabel;
    private string $statusColor;

    /**
     * Create a new message instance.
     */
    public function __construct(public Commande $commande)
    {
        $this->setStatusInfo($commande->statut);
    }

    /**
     * Définir les informations d'affichage selon le statut
     */
    private function setStatusInfo(string $status): void
    {
        $statusMap = [
            'en_attente' => [
                'label' => 'En attente de confirmation',
                'color' => '#f59e0b',
            ],
            'confirmee' => [
                'label' => 'Confirmée',
                'color' => '#60a5fa',
            ],
            'expediee' => [
                'label' => 'Expédiée',
                'color' => '#a78bfa',
            ],
            'livree' => [
                'label' => 'Livrée',
                'color' => '#22c55e',
            ],
            'annulee' => [
                'label' => 'Annulée',
                'color' => '#f87171',
            ],
        ];

        $info = $statusMap[$status] ?? $statusMap['en_attente'];
        $this->statusLabel = $info['label'];
        $this->statusColor = $info['color'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->commande->statut) {
            'confirmee' => 'Votre commande a été confirmée',
            'expediee'  => 'Votre commande a été expédiée',
            'livree'    => 'Votre commande a été livrée',
            'annulee'   => 'Votre commande a été annulée',
            default     => 'Mise à jour de votre commande',
        };

        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply'),
            to: [new Address($this->commande->user->email, $this->commande->user->name)],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client-order-status-updated',
            with: [
                'commande'    => $this->commande,
                'statusLabel' => $this->statusLabel,
                'statusColor' => $this->statusColor,
                'orderUrl'    => route('commandes.show', $this->commande),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}