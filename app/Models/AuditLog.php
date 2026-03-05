<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'old_values',
        'new_values',
        'reason',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Admin qui a fait l'action
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scopes pour filtrage
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        return $query->where('model_type', $modelType)
            ->when($modelId, fn($q) => $q->where('model_id', $modelId));
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->whereDate('created_at', '>=', now()->subDays($days));
    }

    /**
     * Format l'action en français
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'approve' => 'Approbation',
            'reject' => 'Rejet',
            'ban' => 'Bannissement',
            'unban' => 'Débannissement',
            'adjust_stock' => 'Ajustement stock',
            'resolve_dispute' => 'Résolution litige',
            default => ucfirst($this->action),
        };
    }

    /**
     * Format le type de modèle en français
     */
    public function getModelTypeLabel(): string
    {
        return match ($this->model_type) {
            'User' => 'Utilisateur',
            'Produit' => 'Produit',
            'Commande' => 'Commande',
            'Dispute' => 'Litige',
            'Categorie' => 'Catégorie',
            'StockAlert' => 'Alerte Stock',
            default => $this->model_type,
        };
    }
}
