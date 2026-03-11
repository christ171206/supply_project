<?php

namespace App\Events;

use App\Models\Commande;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Commande $commande,
        public string $oldStatus,
        public string $newStatus
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user-notifications.'.$this->commande->user_id),
            new PrivateChannel('vendor-notifications.'.$this->commande->vendeur_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.status-changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $statusLabels = [
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];

        return [
            'id' => $this->commande->id,
            'numero' => $this->commande->numero,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_label' => $statusLabels[$this->oldStatus] ?? $this->oldStatus,
            'new_status_label' => $statusLabels[$this->newStatus] ?? $this->newStatus,
            'updated_at' => $this->commande->updated_at->format('Y-m-d H:i:s'),
            'message' => 'Commande #'.$this->commande->numero.': '.($statusLabels[$this->newStatus] ?? $this->newStatus),
        ];
    }
}
