<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Enregistrer une action d'audit (base générale)
     */
    public static function log(
        string $action,
        string $modelType,
        int|string $modelId,
        string $modelName,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $reason = null
    ): AuditLog {
        $admin = Auth::user();

        return AuditLog::create([
            'admin_id' => $admin?->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'model_name' => $modelName,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'reason' => $reason,
            'ip_address' => self::getClientIp(),
            'user_agent' => Request::userAgent() ?? 'Unknown',
        ]);
    }

    /**
     * Enregistrer une création
     */
    public static function logCreate(
        string $modelType,
        int|string $modelId,
        string $modelName,
        array $newValues,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'create',
            $modelType,
            $modelId,
            $modelName,
            null,
            $newValues,
            $reason
        );
    }

    /**
     * Enregistrer une mise à jour
     */
    public static function logUpdate(
        string $modelType,
        int|string $modelId,
        string $modelName,
        array $oldValues,
        array $newValues,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'update',
            $modelType,
            $modelId,
            $modelName,
            $oldValues,
            $newValues,
            $reason
        );
    }

    /**
     * Enregistrer une suppression
     */
    public static function logDelete(
        string $modelType,
        int|string $modelId,
        string $modelName,
        array $deletedValues,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'delete',
            $modelType,
            $modelId,
            $modelName,
            $deletedValues,
            null,
            $reason
        );
    }

    /**
     * Enregistrer une approbation (vendeurs, documents, etc.)
     */
    public static function logApprove(
        string $modelType,
        int|string $modelId,
        string $modelName,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'approve',
            $modelType,
            $modelId,
            $modelName,
            null,
            null,
            $reason ?? 'Approuvé'
        );
    }

    /**
     * Enregistrer un rejet
     */
    public static function logReject(
        string $modelType,
        int|string $modelId,
        string $modelName,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'reject',
            $modelType,
            $modelId,
            $modelName,
            null,
            null,
            $reason ?? 'Rejeté'
        );
    }

    /**
     * Enregistrer un bannissement
     */
    public static function logBan(
        int|string $userId,
        string $userName,
        array $banDetails,
        string $reason
    ): AuditLog {
        return self::log(
            'ban',
            'User',
            $userId,
            $userName,
            null,
            $banDetails,
            $reason
        );
    }

    /**
     * Enregistrer un débannissement
     */
    public static function logUnban(
        int|string $userId,
        string $userName,
        ?string $reason = null
    ): AuditLog {
        return self::log(
            'unban',
            'User',
            $userId,
            $userName,
            null,
            null,
            $reason ?? 'Débanni'
        );
    }

    /**
     * Récupérer les logs récents
     */
    public static function getRecent(int $limit = 50, int $days = 30)
    {
        return AuditLog::recent($days)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Récupérer les logs par admin
     */
    public static function getByAdmin(int $adminId, int $limit = 50)
    {
        return AuditLog::byAdmin($adminId)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Récupérer les logs par action
     */
    public static function getByAction(string $action, int $limit = 50)
    {
        return AuditLog::byAction($action)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Récupérer les logs pour un modèle spécifique
     */
    public static function getModelHistory(string $modelType, int|string $modelId, int $limit = 50)
    {
        return AuditLog::byModel($modelType, $modelId)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Récupérer les stats d'audit
     */
    public static function getStats(int $days = 30): array
    {
        $recentLogs = AuditLog::recent($days);

        return [
            'total_actions' => $recentLogs->count(),
            'by_action' => $recentLogs->groupBy('action')->map->count(),
            'by_model' => $recentLogs->groupBy('model_type')->map->count(),
            'by_admin' => $recentLogs->with('admin')->groupBy('admin_id')->map(function ($logs) {
                return [
                    'count' => $logs->count(),
                    'admin_name' => $logs->first()->admin->name ?? 'Unknown'
                ];
            }),
        ];
    }

    /**
     * Obtenir l'adresse IP du client
     */
    private static function getClientIp(): string
    {
        if (Request::ip()) {
            return Request::ip();
        }

        return 'Unknown';
    }
}
