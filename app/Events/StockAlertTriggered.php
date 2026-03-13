<?php

namespace App\Events;

use App\Models\Produit;
use App\Models\StockAlert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlertTriggered implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Produit $produit,
        public StockAlert $alert,
        public string $alertType // 'critical' ou 'low'
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('vendor-alerts.' . $this->produit->user_id),
            new PrivateChannel('admin-alerts'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'stock.alert-triggered';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $alertLabels = [
            'critical' => '🚨 Rupture de Stock',
            'low' => '⚠️ Stock Faible',
        ];

        return [
            'type' => $this->alertType,
            'product_id' => $this->produit->id,
            'product_name' => $this->produit->nom,
            'current_stock' => $this->produit->stock,
            'minimum_threshold' => $this->alert->alert_threshold,
            'reorder_quantity' => $this->alert->reorder_quantity,
            'vendor_id' => $this->produit->user_id,
            'vendor_name' => $this->produit->vendor->prenom . ' ' . $this->produit->vendor->nom,
            'title' => $alertLabels[$this->alertType] ?? 'Alerte Stock',
            'message' => $this->constructMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Construire le message d'alerte
     */
    private function constructMessage(): string
    {
        if ($this->alertType === 'critical') {
            return "Le produit {$this->produit->nom} est en rupture de stock ! Stock actuel: {$this->produit->stock}";
        }

        return "Le produit {$this->produit->nom} a un stock faible. Stock actuel: {$this->produit->stock}. Minimum requis: {$this->alert->alert_threshold}";
    }
}
