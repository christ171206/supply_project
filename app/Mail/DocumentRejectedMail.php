<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public UserDocument $document,
        public string $rejectionReason
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Plateforme E-commerce'),
            subject: '⚠️ Document rejeté - Action requise',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $documentTypeLabels = [
            'id_card' => 'Pièce d\'identité',
            'passport' => 'Passeport',
            'driving_license' => 'Permis de conduire',
            'business_license' => 'Licence commerciale',
            'tax_id' => 'Numéro d\'identification fiscale',
            'bank_account' => 'Relevé de compte bancaire',
            'address_proof' => 'Justificatif de domicile',
            'store_front' => 'Photo de la boutique',
        ];

        return new Content(
            markdown: 'emails.document-rejected',
            with: [
                'user' => $this->user,
                'documentType' => $documentTypeLabels[$this->document->document_type] ?? $this->document->document_type,
                'rejectionReason' => $this->rejectionReason,
                'documentSide' => $this->document->document_side ? "({$this->document->document_side})" : '',
                'supportUrl' => 'mailto:support@supply.ci',
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
