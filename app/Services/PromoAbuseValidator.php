<?php

namespace App\Services;

use App\Models\PromoAbuseRule;
use App\Models\PromoAbuseLog;
use App\Models\User;
use App\Models\PromoCode;
use App\Models\GlobalOffer;
use App\Models\ClientCoupon;
use Carbon\Carbon;

class PromoAbuseValidator
{
    /**
     * Validate if user can use a promo code (coupon, code, offer, etc.)
     * Returns: ['allowed' => bool, 'reason' => string, 'severity' => int]
     */
    public function validatePromoUsage(
        User $user,
        ?PromoCode $promoCode = null,
        ?GlobalOffer $globalOffer = null,
        ?ClientCoupon $clientCoupon = null,
        array $cartItems = [],
        float $cartTotal = 0
    ): array {
        $violations = [];

        // Get applicable rules (cached for 24h to reduce DB hits)
        $rules = \Illuminate\Support\Facades\Cache::remember('promo_abuse_rules_enabled', 86400, function () {
            return PromoAbuseRule::enabled()->get();
        });

        foreach ($rules as $rule) {
            // Determine what this rule applies to
            if ($globalOffer && $rule->applies_to === 'global_offers') {
                $result = $this->checkRule($rule, $user, $globalOffer, $cartItems, $cartTotal);
                if (!$result['allowed']) {
                    $violations[] = $result;
                }
            } elseif ($promoCode && $rule->applies_to === 'specific_promo' && $rule->applies_to_id === $promoCode->id) {
                $result = $this->checkRule($rule, $user, $promoCode, $cartItems, $cartTotal);
                if (!$result['allowed']) {
                    $violations[] = $result;
                }
            } elseif ($clientCoupon && $rule->applies_to === 'specific_coupon' && $rule->applies_to_id === $clientCoupon->promo_code_id) {
                $result = $this->checkRule($rule, $user, $clientCoupon, $cartItems, $cartTotal);
                if (!$result['allowed']) {
                    $violations[] = $result;
                }
            } elseif ($rule->applies_to === 'all') {
                $result = $this->checkRule($rule, $user, $promoCode ?? $globalOffer ?? $clientCoupon, $cartItems, $cartTotal);
                if (!$result['allowed']) {
                    $violations[] = $result;
                }
            }
        }

        // Process violations
        if (empty($violations)) {
            return ['allowed' => true, 'reason' => null, 'severity' => 0];
        }

        // Find highest severity violation
        $topViolation = collect($violations)->sortByDesc('severity')->first();

        // Log the violation
        if ($topViolation['severity'] >= 2) {  // Only log significant violations
            $this->logViolation($user, $violations[0], $cartTotal);
        }

        return [
            'allowed' => $topViolation['severity'] < 3,  // Severity 3 = block
            'reason' => $topViolation['reason'],
            'severity' => $topViolation['severity'],
            'all_violations' => $violations,
        ];
    }

    /**
     * Check specific rule
     */
    private function checkRule(PromoAbuseRule $rule, User $user, $promo, array $cartItems = [], float $cartTotal = 0): array
    {
        return match ($rule->rule_type) {
            'limit_per_user' => $this->checkLimitPerUser($rule, $user, $promo),
            'limit_per_day' => $this->checkLimitPerDay($rule, $promo),
            'limit_per_week' => $this->checkLimitPerWeek($rule, $promo),
            'limit_per_month' => $this->checkLimitPerMonth($rule, $promo),
            'min_account_age' => $this->checkMinAccountAge($rule, $user),
            'min_cart_value' => $this->checkMinCartValue($rule, $cartTotal),
            'max_discount_per_day' => $this->checkMaxDiscountPerDay($rule, $user, $promo),
            'forbidden_combination' => $this->checkForbiddenCombination($rule, $cartItems),
            'excluded_categories' => $this->checkExcludedCategories($rule, $cartItems),
            'excluded_vendors' => $this->checkExcludedVendors($rule, $cartItems),
            'max_quantity_per_order' => $this->checkMaxQuantityPerOrder($rule, $cartItems),
            default => ['allowed' => true, 'reason' => null, 'severity' => 0],
        };
    }

    /**
     * Check: Limit per user (e.g., max 3 uses per customer)
     */
    private function checkLimitPerUser(PromoAbuseRule $rule, User $user, $promo): array
    {
        $config = $rule->config;
        $maxUses = $config['max_uses'] ?? 1;

        $usageCount = PromoAbuseLog::where('user_id', $user->id)
            ->where('rule_id', $rule->id)
            ->where('violation_type', '!=', 'attempted')
            ->count();

        if ($usageCount >= $maxUses) {
            return [
                'allowed' => false,
                'reason' => "Vous avez atteint la limite d'utilisation ({$maxUses} utilisations max)",
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Limit per day (global)
     */
    private function checkLimitPerDay(PromoAbuseRule $rule, $promo): array
    {
        $config = $rule->config;
        $maxUses = $config['max_uses'] ?? 100;

        $usageCount = PromoAbuseLog::where('rule_id', $rule->id)
            ->where('violation_type', '!=', 'attempted')
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if ($usageCount >= $maxUses) {
            return [
                'allowed' => false,
                'reason' => 'Cette offre a atteint son quota de la journée. Réessayez demain.',
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Limit per week
     */
    private function checkLimitPerWeek(PromoAbuseRule $rule, $promo): array
    {
        $config = $rule->config;
        $maxUses = $config['max_uses'] ?? 100;

        $usageCount = PromoAbuseLog::where('rule_id', $rule->id)
            ->where('violation_type', '!=', 'attempted')
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        if ($usageCount >= $maxUses) {
            return [
                'allowed' => false,
                'reason' => 'Cette offre a atteint son quota hebdomadaire.',
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Limit per month
     */
    private function checkLimitPerMonth(PromoAbuseRule $rule, $promo): array
    {
        $config = $rule->config;
        $maxUses = $config['max_uses'] ?? 100;

        $usageCount = PromoAbuseLog::where('rule_id', $rule->id)
            ->where('violation_type', '!=', 'attempted')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        if ($usageCount >= $maxUses) {
            return [
                'allowed' => false,
                'reason' => 'Cette offre a atteint son quota mensuel.',
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Minimum account age
     */
    private function checkMinAccountAge(PromoAbuseRule $rule, User $user): array
    {
        $config = $rule->config;
        $minDays = $config['min_days'] ?? 7;

        $accountAge = $user->created_at->diffInDays(now());

        if ($accountAge < $minDays) {
            return [
                'allowed' => false,
                'reason' => "Votre compte doit avoir au moins {$minDays} jours pour utiliser cette offre.",
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Minimum cart value
     */
    private function checkMinCartValue(PromoAbuseRule $rule, float $cartTotal): array
    {
        $config = $rule->config;
        $minValue = $config['min_value'] ?? 0;

        if ($cartTotal < $minValue) {
            return [
                'allowed' => false,
                'reason' => "Panier minimum requis: " . number_format($minValue, 0, ',', ' ') . " FCFA",
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Max discount per day per user
     */
    private function checkMaxDiscountPerDay(PromoAbuseRule $rule, User $user, $promo): array
    {
        $config = $rule->config;
        $maxDiscount = $config['max_discount'] ?? 0;

        $totalSavedToday = PromoAbuseLog::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('potential_loss');

        if ($totalSavedToday >= $maxDiscount) {
            return [
                'allowed' => false,
                'reason' => 'Vous avez atteint votre quota de réductions pour la journée.',
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Forbidden combinations (can't use promo + coupon, etc.)
     */
    private function checkForbiddenCombination(PromoAbuseRule $rule, array $cartItems): array
    {
        $config = $rule->config;
        $forbiddenCombos = $config['forbidden_combos'] ?? [];

        // This would check if user is trying to use forbidden combinations
        // e.g., "Cannot use flash_sale AND global_offer together"

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Excluded categories
     */
    private function checkExcludedCategories(PromoAbuseRule $rule, array $cartItems): array
    {
        $config = $rule->config;
        $excludedCategories = $config['excluded_category_ids'] ?? [];

        foreach ($cartItems as $item) {
            // Check if item is in excluded categories
            // This assumes item has category_id or similar
            if (isset($item['category_id']) && in_array($item['category_id'], $excludedCategories)) {
                return [
                    'allowed' => false,
                    'reason' => 'Cette offre ne s\'applique pas à certains articles de votre panier.',
                    'severity' => $rule->severity,
                    'rule_id' => $rule->id,
                ];
            }
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Excluded vendors
     */
    private function checkExcludedVendors(PromoAbuseRule $rule, array $cartItems): array
    {
        $config = $rule->config;
        $excludedVendors = $config['excluded_vendor_ids'] ?? [];

        foreach ($cartItems as $item) {
            if (isset($item['vendor_id']) && in_array($item['vendor_id'], $excludedVendors)) {
                return [
                    'allowed' => false,
                    'reason' => 'Cette offre ne s\'applique pas aux articles de certains vendeurs.',
                    'severity' => $rule->severity,
                    'rule_id' => $rule->id,
                ];
            }
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Check: Max quantity per order
     */
    private function checkMaxQuantityPerOrder(PromoAbuseRule $rule, array $cartItems): array
    {
        $config = $rule->config;
        $maxQty = $config['max_quantity'] ?? 999;

        $totalQty = array_sum(array_column($cartItems, 'quantity'));

        if ($totalQty > $maxQty) {
            return [
                'allowed' => false,
                'reason' => "Quantité maximum: {$maxQty} articles",
                'severity' => $rule->severity,
                'rule_id' => $rule->id,
            ];
        }

        return ['allowed' => true, 'reason' => null, 'severity' => 0];
    }

    /**
     * Log a violation
     */
    private function logViolation(User $user, array $violation, float $potentialLoss = 0): void
    {
        PromoAbuseLog::create([
            'user_id' => $user->id,
            'rule_id' => $violation['rule_id'],
            'violation_type' => 'attempted',
            'details' => $violation,
            'potential_loss' => $potentialLoss,
            'action_taken' => 'none',  // Can be updated manually later
        ]);
    }
}
