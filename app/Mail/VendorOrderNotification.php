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

class VendorOrderNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Commande $commande,
        public User $vendor,
        public array $vendorItems
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Plateforme E-commerce'),
            to: [new Address($this->vendor->email, $this->vendor->name)],
            subject: '📦 Nouvelle commande reçue - ' . $this->commande->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor-order-notification',
            with: [
                'vendor' => $this->vendor,
                'commande' => $this->commande,
                'items' => $this->vendorItems,
                'client' => $this->commande->user,
                'total' => collect($this->vendorItems)->sum(fn($item) => $item['sous_total']),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
