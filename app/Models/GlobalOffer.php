<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class GlobalOffer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'max_discount',
        'target_type',
        'target_id',
        'min_purchase',
        'min_quantity',
        'config',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'total_discount_given' => 'decimal:2',
        'config' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the admin who created this offer
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: Only active offers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope: Filter by target type
     */
    public function scopeByTargetType($query, string $type)
    {
        return $query->where('target_type', $type);
    }

    /**
     * Scope: Filter by target
     */
    public function scopeByTarget($query, string $type, ?int $id = null)
    {
        $query->where('target_type', $type);
        if ($id) {
            $query->where('target_id', $id);
        }
        return $query;
    }

    /**
     * Check if offer is currently active (not expired and enabled)
     */
    public function isActiveNow(): bool
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    /**
     * Check if offer applies to this product
     */
    public function appliesTo(Produit $product): bool
    {
        if (!$this->isActiveNow()) {
            return false;
        }

        return match ($this->target_type) {
            'all' => true,
            'category' => $product->category_id === $this->target_id,
            'vendor' => $product->user_id === $this->target_id,
            'product' => $product->id === $this->target_id,
            default => false,
        };
    }

    /**
     * Check if offer applies to cart
     */
    public function appliesToCart(array $cartItems, float $cartTotal): bool
    {
        if (!$this->isActiveNow()) {
            return false;
        }

        // Check minimum purchase
        if ($cartTotal < $this->min_purchase) {
            return false;
        }

        // Check minimum quantity
        $totalQuantity = array_sum(array_column($cartItems, 'quantity'));
        if ($totalQuantity < $this->min_quantity) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for a product
     */
    public function calculateDiscount(float $originalPrice, int $quantity = 1): array
    {
        $discount = 0;
        $discountedPrice = $originalPrice;

        switch ($this->type) {
            case 'discount_percent':
                $discount = $originalPrice * ($this->value / 100);
                break;

            case 'discount_fixed':
                $discount = min($originalPrice, $this->value);
                break;

            case 'free_shipping':
                // Handled separately in cart
                $discount = 0;
                break;

            case 'buy_x_get_y':
                $config = $this->config ?? [];
                $buyQuantity = $config['buy_quantity'] ?? 1;
                $getFree = $config['get_quantity'] ?? 1;

                if ($quantity >= $buyQuantity) {
                    $freeItems = intval($quantity / $buyQuantity) * $getFree;
                    $discount = $originalPrice * $freeItems;
                }
                break;

            case 'tiered_discount':
                $config = $this->config ?? [];
                $tiers = $config['tiers'] ?? [];

                foreach ($tiers as $tier) {
                    if ($quantity >= $tier['min_quantity']) {
                        $discount = $originalPrice * ($tier['percentage'] / 100);
                    }
                }
                break;
        }

        // Apply max discount cap if set
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return [
            'original_price' => $originalPrice,
            'discount_amount' => $discount,
            'discounted_price' => max(0, $originalPrice - $discount),
            'discount_percentage' => $originalPrice > 0 ? round(($discount / $originalPrice) * 100, 2) : 0,
            'offer_id' => $this->id,
            'offer_type' => $this->type,
        ];
    }

    /**
     * Calculate total discount for cart
     */
    public function calculateCartDiscount(array $items, float $cartTotal): array
    {
        if (!$this->appliesToCart($items, $cartTotal)) {
            return [
                'applicable' => false,
                'total_discount' => 0,
                'message' => 'Offre non applicable',
            ];
        }

        $totalDiscount = 0;

        switch ($this->type) {
            case 'discount_percent':
                $totalDiscount = $cartTotal * ($this->value / 100);
                break;

            case 'discount_fixed':
                $totalDiscount = min($cartTotal, $this->value);
                break;

            case 'free_shipping':
                // Would be handled as line item
                $totalDiscount = 0;
                break;

            case 'buy_x_get_y':
                // Calculated per product
                $totalDiscount = 0;
                foreach ($items as $item) {
                    $discountInfo = $this->calculateDiscount($item['price'], $item['quantity']);
                    $totalDiscount += $discountInfo['discount_amount'];
                }
                break;

            case 'tiered_discount':
                $totalQuantity = array_sum(array_column($items, 'quantity'));
                $totalDiscount = $cartTotal * ($this->getTieredPercentage($totalQuantity) / 100);
                break;
        }

        // Apply max discount cap if set
        if ($this->max_discount && $totalDiscount > $this->max_discount) {
            $totalDiscount = $this->max_discount;
        }

        return [
            'applicable' => true,
            'total_discount' => round($totalDiscount, 2),
            'discounted_total' => round($cartTotal - $totalDiscount, 2),
            'offer_id' => $this->id,
            'offer_name' => $this->name,
            'offer_type' => $this->type,
        ];
    }

    /**
     * Get tiered percentage for given quantity
     */
    private function getTieredPercentage(int $quantity): float
    {
        $config = $this->config ?? [];
        $tiers = $config['tiers'] ?? [];

        $percentage = 0;
        foreach ($tiers as $tier) {
            if ($quantity >= $tier['min_quantity']) {
                $percentage = $tier['percentage'];
            }
        }

        return $percentage;
    }

    /**
     * Record usage of this offer
     */
    public function recordUsage(float $discountAmount): void
    {
        $this->increment('usage_count');
        $this->increment('total_discount_given', $discountAmount);
    }

    /**
     * Get target description
     */
    public function getTargetDescription(): string
    {
        return match ($this->target_type) {
            'all' => 'Tous les produits',
            'category' => 'Catégorie: ' . ($this->target_id ? Categorie::find($this->target_id)?->nom : 'N/A'),
            'vendor' => 'Vendeur: ' . ($this->target_id ? User::find($this->target_id)?->nom : 'N/A'),
            'product' => 'Produit: ' . ($this->target_id ? Produit::find($this->target_id)?->nom : 'N/A'),
            default => 'Inconnu',
        };
    }

    /**
     * Get offer type label
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'discount_percent' => 'Réduction %',
            'discount_fixed' => 'Réduction fixe',
            'free_shipping' => 'Livraison gratuite',
            'buy_x_get_y' => 'Achetez X obtenez Y',
            'tiered_discount' => 'Réduction progressive',
            default => 'Inconnu',
        };
    }
}
