<?php

namespace Database\Seeders;

use App\Models\BadgeType;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'code' => 'premier_vendeur',
                'name' => '💎 Premier Vendeur',
                'emoji' => '💎',
                'description' => 'Vous êtes un vendeur Premium avec 50+ produits et rating excellent',
                'condition' => '50+ products AND avg_rating >= 4.5',
                'required_value' => 50,
            ],
            [
                'code' => 'elite_seller',
                'name' => '⭐ Vendeur Elite',
                'emoji' => '⭐',
                'description' => '20+ ventes et rating 4.0+. Expert reconnu de la plateforme',
                'condition' => '20+ sales AND avg_rating >= 4.0',
                'required_value' => 20,
            ],
            [
                'code' => 'top_products',
                'name' => '🏆 Top Produits',
                'emoji' => '🏆',
                'description' => '5 produits en top ventes. Vos produits sont très demandés',
                'condition' => '5+ top-selling products',
                'required_value' => 5,
            ],
            [
                'code' => 'reliable_seller',
                'name' => '🎯 Vendeur Fiable',
                'emoji' => '🎯',
                'description' => '50+ avis positifs. La communauté vous fait confiance',
                'condition' => '50+ positive reviews',
                'required_value' => 50,
            ],
            [
                'code' => 'speed_master',
                'name' => '⚡ Maître Rapide',
                'emoji' => '⚡',
                'description' => 'Livraisons rapides en moyenne < 2 jours',
                'condition' => 'avg_delivery < 2 days',
                'required_value' => null,
            ],
            [
                'code' => 'community_driver',
                'name' => '🗣️ Champion Communauté',
                'emoji' => '🗣️',
                'description' => '100+ avis laissés. Vous aidez la communauté à choisir',
                'condition' => '100+ reviews given',
                'required_value' => 100,
            ],
            [
                'code' => 'rising_star',
                'name' => '🌟 Étoile Montante',
                'emoji' => '🌟',
                'description' => 'Nouveau vendeur populaire: 10+ avis en 30 jours',
                'condition' => 'new vendor + 10+ reviews in 30 days',
                'required_value' => 10,
            ],
            [
                'code' => 'customer_favorite',
                'name' => '💕 Chouchou Client',
                'emoji' => '💕',
                'description' => 'Vous êtes dans les 5% des vendeurs les plus appréciés',
                'condition' => 'top 5% rating',
                'required_value' => null,
            ],
        ];

        foreach ($badges as $badge) {
            BadgeType::firstOrCreate(
                ['code' => $badge['code']],
                $badge
            );
        }
    }
}
