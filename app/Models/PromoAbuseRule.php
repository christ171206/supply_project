<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PromoAbuseRule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'rule_type',
        'config',
        'applies_to',
        'applies_to_id',
        'is_enabled',
        'severity',
        'created_by',
    ];

    protected $casts = [
        'config' => 'array',
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the admin who created this rule
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get abuse logs for this rule
     */
    public function logs(): HasMany
    {
        return $this->hasMany(PromoAbuseLog::class, 'rule_id');
    }

    /**
     * Scope: Only enabled rules
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope: Filter by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('rule_type', $type);
    }

    /**
     * Check if rule applies to this promo/coupon
     */
    public function appliesToPromo($promoId): bool
    {
        if ($this->applies_to === 'all') {
            return true;
        }

        return $this->applies_to_id === $promoId;
    }

    /**
     * Get rule type label
     */
    public function getTypeLabel(): string
    {
        return match ($this->rule_type) {
            'limit_per_user' => 'Limite par utilisateur',
            'limit_per_day' => 'Limite par jour (global)',
            'limit_per_week' => 'Limite par semaine (global)',
            'limit_per_month' => 'Limite par mois (global)',
            'min_account_age' => 'Âge minimum du compte',
            'min_cart_value' => 'Valeur panier minimum',
            'max_discount_per_day' => 'Réduction max par jour',
            'forbidden_combination' => 'Combinaisons interdites',
            'excluded_categories' => 'Catégories exclues',
            'excluded_vendors' => 'Vendeurs exclus',
            'max_quantity_per_order' => 'Quantité max à l\'ordre',
            default => 'Inconnu',
        };
    }

    /**
     * Get severity label
     */
    public function getSeverityLabel(): string
    {
        return match ($this->severity) {
            1 => 'Info',
            2 => 'Avertissement',
            3 => 'Blocage',
            default => 'Inconnu',
        };
    }
}
