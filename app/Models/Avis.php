<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = [
        'user_id',
        'produit_id',
        'note',
        'commentaire',
        'lu',
        'is_appropriate',
        'report_reason',
        'deleted_by_admin',
        'deleted_at',
        'delete_reason',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'is_appropriate' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function deletedByAdmin()
    {
        return $this->belongsTo(User::class, 'deleted_by_admin');
    }

    /**
     * Supprimer un avis par un admin
     */
    public function deleteByAdmin(User $admin, $reason = null)
    {
        $this->update([
            'is_appropriate' => false,
            'deleted_by_admin' => $admin->id,
            'deleted_at' => now(),
            'delete_reason' => $reason ?? 'Supprimé par modération administrative',
        ]);
    }

    /**
     * Restaurer un avis
     */
    public function restore()
    {
        $this->update([
            'is_appropriate' => true,
            'deleted_by_admin' => null,
            'deleted_at' => null,
            'delete_reason' => null,
        ]);
    }
}
