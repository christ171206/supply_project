<?php

namespace App\Mail;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliveryReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Commande $commande,
        public User $client,
        public int $daysUntilDelivery
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Suivi Livraison'),
            to: [new Address($this->client->email, $this->client->name)],
            subject: '📦 Rappel: Votre colis arrive dans ' . $this->daysUntilDelivery . ' jour(s)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.delivery-reminder',
            with: [
                'client' => $this->client,
                'commande' => $this->commande,
                'daysUntilDelivery' => $this->daysUntilDelivery,
                'totalAmount' => $this->commande->total,
                'orderNumber' => $this->commande->numero ?? 'CMD-' . $this->commande->id,
                'trackingUrl' => route('commandes.track', $this->commande),
                'commandeDetails' => route('commandes.show', $this->commande),
            ],
        );
    }
}
