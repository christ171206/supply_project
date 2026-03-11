<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorApprovalStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $vendor,
        public string $status, // 'approved' or 'rejected'
        public ?string $reason = null
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('vendor-approval.' . $this->vendor->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'vendor.approval-status-changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $statusMessages = [
            'approved' => '✅ Votre compte vendeur a été approuvé!',
            'rejected' => '❌ Votre demande d\'inscription a été rejetée',
        ];

        return [
            'vendor_id' => $this->vendor->id,
            'vendor_name' => $this->vendor->prenom . ' ' . $this->vendor->nom,
            'status' => $this->status,
            'reason' => $this->reason,
            'approved_at' => now()->format('Y-m-d H:i:s'),
            'message' => $statusMessages[$this->status] ?? 'Mise à jour du statut vendeur',
        ];
    }
}
