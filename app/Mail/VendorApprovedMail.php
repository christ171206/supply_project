<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $vendor, public ?string $reviewNotes = null) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Plateforme E-commerce'),
            to: [new Address($this->vendor->email, $this->vendor->name)],
            subject: '✅ Félicitations ! Votre compte vendeur a été approuvé',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vendor-approved',
            with: [
                'vendor' => $this->vendor,
                'reviewNotes' => $this->reviewNotes,
                'dashboardUrl' => url('/vendeur/dashboard'),
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
