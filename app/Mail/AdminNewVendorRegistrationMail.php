<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewVendorRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $vendor, public ?User $admin = null) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $admin = $this->admin ?? User::whereJsonContains('roles', 'super_admin')
            ->orWhere('is_admin', true)
            ->first();

        $adminEmail = $admin?->email ?? config('mail.from.address', 'admin@supply.local');
        $adminName  = $admin?->name  ?? 'Admin Supply';

        $shopName = $this->vendor->shop_name ?? $this->vendor->name;

        return new Envelope(
            from: new Address('noreply@supply.local', 'Supply'),
            to: [new Address($adminEmail, $adminName)],
            subject: 'Nouvelle demande vendeur : ' . $shopName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-vendor-registration',
            with: [
                'vendor'            => $this->vendor,
                'adminDashboardUrl' => route('admin.vendors.index'),
                'vendorDetailsUrl'  => route('admin.users.show', $this->vendor->id),
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
