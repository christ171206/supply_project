<?php

namespace Database\Seeders;

use App\Models\PromoAbuseRule;
use App\Models\GlobalOffer;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarketingHubSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user
        $admin = User::where('is_admin', true)->first();
        if (!$admin) {
            $this->command->warn('Aucun utilisateur admin trouvé. Création skippée.');
            return;
        }

        // ============================================
        // SAMPLE ANTI-ABUSE RULES
        // ============================================

        // Rule 1: Max 5 uses per user
        PromoAbuseRule::create([
            'name' => 'Limite d\'utilisation par utilisateur',
            'description' => 'Chaque utilisateur ne peut utiliser un code promo que 5 fois maximum',
            'rule_type' => 'limit_per_user',
            'config' => ['max_uses' => 5],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 2, // Warning
            'created_by' => $admin->id,
        ]);

        // Rule 2: Min 7 days account age
        PromoAbuseRule::create([
            'name' => 'Âge minimum du compte',
            'description' => 'Nouveau compte: 7 jours minimum avant d\'utiliser les promos',
            'rule_type' => 'min_account_age',
            'config' => ['min_days' => 7],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 3, // Block
            'created_by' => $admin->id,
        ]);

        // Rule 3: Min cart value 10,000 FCFA
        PromoAbuseRule::create([
            'name' => 'Panier minimum pour promo',
            'description' => 'Le panier doit atteindre 10.000 FCFA minimum',
            'rule_type' => 'min_cart_value',
            'config' => ['min_value' => 10000],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 2,
            'created_by' => $admin->id,
        ]);

        // Rule 4: Max 100,000 FCFA discount per day per user
        PromoAbuseRule::create([
            'name' => 'Limite des réductions par jour',
            'description' => 'Chaque utilisateur peut économiser max 100.000 FCFA par jour',
            'rule_type' => 'max_discount_per_day',
            'config' => ['max_discount' => 100000],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 3,
            'created_by' => $admin->id,
        ]);

        // Rule 5: Global daily quota - 1,000,000 FCFA max
        PromoAbuseRule::create([
            'name' => 'Limite quotidienne globale',
            'description' => 'Maximum 1.000.000 FCFA de réductions distribuées par jour globalement',
            'rule_type' => 'limit_per_day',
            'config' => ['max_uses' => 0, 'max_daily_discount' => 1000000],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 3,
            'created_by' => $admin->id,
        ]);

        // Rule 6: Max 3 uses per week
        PromoAbuseRule::create([
            'name' => 'Limite hebdomadaire',
            'description' => 'Chaque code peut être utilisé max 3 fois par semaine (global)',
            'rule_type' => 'limit_per_week',
            'config' => ['max_uses' => 3],
            'applies_to' => 'all',
            'applies_to_id' => null,
            'is_enabled' => true,
            'severity' => 2,
            'created_by' => $admin->id,
        ]);

        // ============================================
        // SAMPLE GLOBAL OFFERS
        // ============================================

        // Offer 1: 20% off all Electronics category
        $electronicsCategory = Categorie::where('slug', 'electroniques')->first() ??
            Categorie::where('nom', 'like', '%lectronique%')->first();

        if ($electronicsCategory) {
            GlobalOffer::create([
                'name' => 'Promo Électroniques -20%',
                'description' => 'Réduction de 20% sur tous les produits électroniques',
                'type' => 'discount_percent',
                'value' => 20,
                'max_discount' => 50000,
                'target_type' => 'category',
                'target_id' => $electronicsCategory->id,
                'min_purchase' => 15000,
                'min_quantity' => 1,
                'config' => [],
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'usage_count' => 0,
                'total_discount_given' => 0,
                'created_by' => $admin->id,
            ]);
        }

        // Offer 2: Free shipping on orders > 100,000 FCFA
        GlobalOffer::create([
            'name' => 'Livraison gratuite',
            'description' => 'Livraison offerte pour commandes supérieures à 100.000 FCFA',
            'type' => 'free_shipping',
            'value' => 0,
            'max_discount' => 5000,
            'target_type' => 'all',
            'target_id' => null,
            'min_purchase' => 100000,
            'min_quantity' => 1,
            'config' => [],
            'start_date' => now(),
            'end_date' => now()->addDays(90),
            'is_active' => true,
            'usage_count' => 0,
            'total_discount_given' => 0,
            'created_by' => $admin->id,
        ]);

        // Offer 3: Fixed discount 15,000 FCFA on all products
        GlobalOffer::create([
            'name' => 'Réduction fixe 15.000 FCFA',
            'description' => 'Économisez 15.000 FCFA sur toute commande',
            'type' => 'discount_fixed',
            'value' => 15000,
            'max_discount' => 15000,
            'target_type' => 'all',
            'target_id' => null,
            'min_purchase' => 50000,
            'min_quantity' => 2,
            'config' => [],
            'start_date' => now(),
            'end_date' => now()->addDays(14),
            'is_active' => true,
            'usage_count' => 0,
            'total_discount_given' => 0,
            'created_by' => $admin->id,
        ]);

        // Offer 4: Buy 2 Get 1 offer
        GlobalOffer::create([
            'name' => 'Achetez 2, Recevez 1 Gratuit',
            'description' => 'Sur les articles de mode: achetez 2 recevez 1 gratuit',
            'type' => 'buy_x_get_y',
            'value' => 1,
            'max_discount' => 30000,
            'target_type' => 'category',
            'target_id' => Categorie::where('slug', 'mode')->first()?->id ??
                Categorie::where('nom', 'like', '%mode%')->first()?->id,
            'min_purchase' => 20000,
            'min_quantity' => 3,
            'config' => [
                'buy_quantity' => 2,
                'get_quantity' => 1,
            ],
            'start_date' => now(),
            'end_date' => now()->addDays(21),
            'is_active' => true,
            'usage_count' => 0,
            'total_discount_given' => 0,
            'created_by' => $admin->id,
        ]);

        // Offer 5: Tiered discount - buy more, save more
        GlobalOffer::create([
            'name' => 'Réduction progressive',
            'description' => 'Réductions progressives selon la quantité achetée',
            'type' => 'tiered_discount',
            'value' => 5,
            'max_discount' => 100000,
            'target_type' => 'all',
            'target_id' => null,
            'min_purchase' => 30000,
            'min_quantity' => 5,
            'config' => [
                'tiers' => [
                    ['min_qty' => 5, 'percentage' => 5],
                    ['min_qty' => 10, 'percentage' => 10],
                    ['min_qty' => 20, 'percentage' => 15],
                ],
            ],
            'start_date' => now(),
            'end_date' => now()->addDays(60),
            'is_active' => true,
            'usage_count' => 0,
            'total_discount_given' => 0,
            'created_by' => $admin->id,
        ]);

        $this->command->info('✓ Marketing Hub seeders créés avec succès!');
        $this->command->line('  - 6 Règles anti-abus');
        $this->command->line('  - 5 Offres globales');
    }
}
