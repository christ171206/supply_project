<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'commande_id',
        'produit_id',
        'contenu',
        'lu',
        'is_flagged',
        'flag_reason',
        'flagged_by_user',
        'deleted_by_admin',
        'deleted_at',
        'delete_reason',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'is_flagged' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function flaggedByUser()
    {
        return $this->belongsTo(User::class, 'flagged_by_user');
    }

    public function deletedByAdmin()
    {
        return $this->belongsTo(User::class, 'deleted_by_admin');
    }

    /**
     * Signaler un message par un utilisateur
     */
    public function flag(User $user, $reason = null)
    {
        $this->update([
            'is_flagged' => true,
            'flag_reason' => $reason,
            'flagged_by_user' => $user->id,
        ]);
    }

    /**
     * Supprimer un message par un admin
     */
    public function deleteByAdmin(User $admin, $reason = null)
    {
        $this->update([
            'deleted_by_admin' => $admin->id,
            'deleted_at' => now(),
            'delete_reason' => $reason ?? 'Supprimé par modération administrative',
        ]);
    }

    /**
     * Restaurer un message
     */
    public function restore()
    {
        $this->update([
            'deleted_by_admin' => null,
            'deleted_at' => null,
            'delete_reason' => null,
            'is_flagged' => false,
            'flag_reason' => null,
            'flagged_by_user' => null,
        ]);
    }
}
