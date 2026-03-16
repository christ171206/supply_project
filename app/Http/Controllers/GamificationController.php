<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BadgeType;
use App\Models\UserPoints;
use App\Models\PointTransaction;
use App\Models\Commande;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{
    /**
     * Obtenir le profil de gamification de l'utilisateur actuel
     */
    public function getProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $points = UserPoints::firstOrCreate(
            ['user_id' => $user->id],
            ['total_points' => 0, 'this_month' => 0, 'level' => 1, 'tier' => 'bronze']
        );

        $badges = $user->badges()->get()->map(function ($badge) {
            return [
                'id' => $badge->id,
                'code' => $badge->code,
                'name' => $badge->name,
                'emoji' => $badge->emoji,
                'description' => $badge->description,
                'awarded_at' => $badge->pivot->awarded_at,
                'reason' => $badge->pivot->reason,
            ];
        });

        return response()->json([
            'user_id' => $user->id,
            'points' => [
                'total' => $points->total_points,
                'this_month' => $points->this_month,
                'level' => $points->level,
                'tier' => $points->tier,
                'tier_emoji' => $points->getTierEmoji(),
                'tier_color' => $points->getTierColor(),
                'next_level_needs' => $this->getPointsForNextLevel($points->level),
            ],
            'badges' => $badges,
            'badge_count' => count($badges),
            'achievements_progress' => $this->getAchievementsProgress($user),
        ]);
    }

    /**
     * Obtenir les badges disponibles et le statut de déverrouillage
     */
    public function getAvailableBadges()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $userBadgeIds = $user->badges()->pluck('badge_id')->toArray();
        $allBadges = BadgeType::all();

        $badges = $allBadges->map(function ($badge) use ($userBadgeIds, $user) {
            $unlocked = in_array($badge->id, $userBadgeIds);
            $progress = $this->getBadgeProgress($user, $badge);

            return [
                'id' => $badge->id,
                'code' => $badge->code,
                'name' => $badge->name,
                'emoji' => $badge->emoji,
                'description' => $badge->description,
                'condition' => $badge->condition,
                'unlocked' => $unlocked,
                'progress_label' => $progress['label'],
                'progress_percent' => $progress['percent'],
                'current_value' => $progress['current'],
                'required_value' => $badge->required_value,
            ];
        });

        return response()->json([
            'total_badges' => count($allBadges),
            'unlocked' => count($userBadgeIds),
            'badges' => $badges,
        ]);
    }

    /**
     * Ajouter des points manuellement (admin ou système)
     */
    public function addPoints($userId, $points, $type, $description, $relatedType = null, $relatedId = null)
    {
        $user = User::findOrFail($userId);
        $userPoints = UserPoints::firstOrCreate(['user_id' => $userId]);

        // Ajouter les points
        $userPoints->total_points += $points;
        $userPoints->this_month += $points;
        $userPoints->last_activity = now();

        // Calculer le tier basé sur les points
        $userPoints->tier = $this->calculateTier($userPoints->total_points);
        $userPoints->level = $this->calculateLevel($userPoints->total_points);

        $userPoints->save();

        // Enregistrer la transaction
        PointTransaction::create([
            'user_id' => $userId,
            'points' => $points,
            'type' => $type,
            'description' => $description,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
        ]);

        // Vérifier les badges à débloquer
        $this->checkBadges($user);

        return $userPoints;
    }

    /**
     * Triggerpoints automatiques selon les actions
     */
    public function awardPointsForAction($user, $action, $value = 1)
    {
        $points = 0;
        $description = '';

        switch ($action) {
            case 'sale_completed':
                $points = max(10, min(100, $value)); // Entre 10 et 100 points selon montant
                $description = "Vente complétée (+$value tND)";
                break;
            case 'positive_review':
                $points = 15;
                $description = "Avis positif reçu";
                break;
            case 'first_sale':
                $points = 50;
                $description = "Première vente!";
                break;
            case 'streak_day':
                $points = 5;
                $description = "Jour de streak actif";
                break;
            case 'milestone_10_sales':
                $points = 100;
                $description = "Jalon: 10 ventes";
                break;
            case 'milestone_50_sales':
                $points = 250;
                $description = "Jalon: 50 ventes";
                break;
            case 'milestone_100_sales':
                $points = 500;
                $description = "Jalon: 100 ventes";
                break;
            case 'response_quick':
                $points = 5;
                $description = "Réponse rapide aux messages";
                break;
            default:
                $points = 0;
        }

        if ($points > 0) {
            $this->addPoints($user->id, $points, $action, $description);
        }
    }

    /**
     * Vérifier et attribuer les badges automatiquement
     */
    public function checkBadges(User $user)
    {
        $badges = BadgeType::all();

        foreach ($badges as $badge) {
            // Skip si déjà possédé
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $shouldAward = false;
            $reason = '';

            switch ($badge->code) {
                case 'premier_vendeur':
                    // 50 produits ET rating >= 4.5
                    if (
                        $user->produits()->count() >= 50 &&
                        $user->avisRecus()->avg('note') >= 4.5
                    ) {
                        $shouldAward = true;
                        $reason = "50+ produits avec rating 4.5+";
                    }
                    break;

                case 'elite_seller':
                    // 20 ventes ET 4.0+ rating
                    $saleCount = Commande::whereHas('ligneCommandes', function ($q) use ($user) {
                        $q->where('produits.user_id', $user->id);
                    })->count();
                    if ($saleCount >= 20 && $user->avisRecus()->avg('note') >= 4.0) {
                        $shouldAward = true;
                        $reason = "20+ ventes avec rating 4.0+";
                    }
                    break;

                case 'top_products':
                    // 5 produits en top 100 ventes
                    $topCount = $user->produits()
                        ->withCount('ligneCommandes')
                        ->having('ligne_commandes_count', '>', 10)
                        ->count();
                    if ($topCount >= 5) {
                        $shouldAward = true;
                        $reason = "5+ produits populaires";
                    }
                    break;

                case 'reliable_seller':
                    // 50+ avis positifs
                    $positiveReviews = $user->avisRecus()->where('note', '>=', 4)->count();
                    if ($positiveReviews >= 50) {
                        $shouldAward = true;
                        $reason = "50+ avis positifs";
                    }
                    break;

                case 'speed_master':
                    // Moyenne livraison < 2 jours
                    $avgDelivery = Commande::where('user_id', $user->id)
                        ->whereNotNull('delivered_at')
                        ->selectRaw('AVG(DATEDIFF(delivered_at, created_at)) as avg_days')
                        ->first()?->avg_days;
                    if ($avgDelivery && $avgDelivery < 2) {
                        $shouldAward = true;
                        $reason = "Livraisons rapides: $avgDelivery jours en moyenne";
                    }
                    break;

                case 'community_driver':
                    // 100+ avis laissés
                    if ($user->avis()->count() >= 100) {
                        $shouldAward = true;
                        $reason = "100+ avis laissés";
                    }
                    break;

                case 'rising_star':
                    // Nouveau vendeur avec 10+ avis en 30 jours
                    if (
                        $user->created_at->diffInDays(now()) <= 30 &&
                        $user->avisRecus()->count() >= 10
                    ) {
                        $shouldAward = true;
                        $reason = "Étoile montante: nouveau vendeur populaire";
                    }
                    break;
            }

            if ($shouldAward) {
                $user->badges()->attach($badge->id, [
                    'awarded_at' => now(),
                    'reason' => $reason,
                ]);
            }
        }
    }

    /**
     * Obtenir le statut de progression pour un badge spécifique
     */
    private function getBadgeProgress($user, $badge)
    {
        $current = 0;
        $required = $badge->required_value ?? 0;

        switch ($badge->code) {
            case 'premier_vendeur':
                $current = $user->produits()->count();
                return [
                    'label' => "$current/50 produits",
                    'percent' => min(100, ($current / 50) * 100),
                    'current' => $current,
                ];

            case 'elite_seller':
                $current = Commande::whereHas('ligneCommandes', function ($q) use ($user) {
                    $q->where('produits.user_id', $user->id);
                })->count();
                return [
                    'label' => "$current/20 ventes",
                    'percent' => min(100, ($current / 20) * 100),
                    'current' => $current,
                ];

            case 'reliable_seller':
                $current = $user->avisRecus()->where('note', '>=', 4)->count();
                return [
                    'label' => "$current/50 avis positifs",
                    'percent' => min(100, ($current / 50) * 100),
                    'current' => $current,
                ];

            case 'community_driver':
                $current = $user->avis()->count();
                return [
                    'label' => "$current/100 avis",
                    'percent' => min(100, ($current / 100) * 100),
                    'current' => $current,
                ];

            default:
                return [
                    'label' => 'En cours',
                    'percent' => 0,
                    'current' => 0,
                ];
        }
    }

    /**
     * Obtenir le statut des réalisations de l'utilisateur
     */
    private function getAchievementsProgress($user)
    {
        $saleCount = Commande::whereHas('ligneCommandes', function ($q) use ($user) {
            $q->where('produits.user_id', $user->id);
        })->count();

        return [
            'products_count' => $user->produits()->count(),
            'sales_count' => $saleCount,
            'positive_reviews' => $user->avisRecus()->where('note', '>=', 4)->count(),
            'total_reviews_given' => $user->avis()->count(),
        ];
    }

    /**
     * Calculer le tier basé sur les points
     */
    private function calculateTier($points)
    {
        if ($points >= 1000) return 'platinum';
        if ($points >= 500) return 'gold';
        if ($points >= 100) return 'silver';
        return 'bronze';
    }

    /**
     * Calculer le niveau basé sur les points
     */
    private function calculateLevel($points)
    {
        return (int) floor($points / 50) + 1;
    }

    /**
     * Obtenir les points nécessaires pour le niveau suivant
     */
    private function getPointsForNextLevel($currentLevel)
    {
        return ($currentLevel * 50);
    }
}
