<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewClientRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $client, public ?User $admin = null) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Utiliser l'admin passé en paramètre, sinon trouver le premier admin
        $admin = $this->admin ?? User::whereJsonContains('roles', 'super_admin')
            ->orWhere('is_admin', true)
            ->first();

        $adminEmail = $admin?->email ?? config('mail.from.address', 'admin@supply.local');
        $adminName = $admin?->name ?? 'Admin Supply';

        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply - Plateforme E-commerce'),
            to: [new Address($adminEmail, $adminName)],
            subject: '👤 Nouvelle inscription client : ' . $this->client->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin-new-client-registration',
            with: [
                'client' => $this->client,
                'adminDashboardUrl' => route('admin.users.index'),
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
