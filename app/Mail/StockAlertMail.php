<?php

namespace App\Mail;

use App\Models\Produit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Produit $produit,
        public User $vendor,
        public string $alertType, // 'critical' ou 'low'
        public int $currentStock,
        public int $minimumThreshold
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->alertType === 'critical'
            ? '🚨 ALERTE CRITIQUE - Rupture de stock : ' . $this->produit->nom
            : '⚠️ Alerte Stock - Niveau faible : ' . $this->produit->nom;

        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Alerte Stock'),
            to: [new Address($this->vendor->email, $this->vendor->name)],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.stock-alert',
            with: [
                'vendor' => $this->vendor,
                'produit' => $this->produit,
                'alertType' => $this->alertType,
                'currentStock' => $this->currentStock,
                'minimumThreshold' => $this->minimumThreshold,
                'vendorDashboardUrl' => route('vendeur.dashboard'),
                'productManageUrl' => route('vendeur.produits.edit', $this->produit),
            ],
        );
    }
}
