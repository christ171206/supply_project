<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shop_name',
        'phone',
        'address',
        'vendor_status',
        'vendor_approved_at',
        'vendor_notes',
        'id_document',
        'profile_photo',
        'delivery_latitude',
        'delivery_longitude',
        'lastname',
        'firstname',
        'email_verification_code',
        'email_verification_code_sent_at',
        'country',
        'is_admin',
        'admin_role_id',
        'is_banned',
        'banned_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function panier()
    {
        return $this->hasOne(Panier::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function produitsFavoris()
    {
        return $this->belongsToMany(Produit::class, 'favorites', 'user_id', 'produit_id');
    }

    public function securityLogs(): HasMany
    {
        return $this->hasMany(SecurityLog::class);
    }

    // Relations Admin
    public function adminRole()
    {
        return $this->belongsTo(AdminRole::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(UserDocument::class);
    }

    public function bans(): HasMany
    {
        return $this->hasMany(UserBan::class);
    }

    public function activeBan()
    {
        return $this->hasOne(UserBan::class)->where('is_active', true);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'admin_id');
    }

    /**
     * Relation avec la validation vendeur
     */
    public function validationVendeur()
    {
        return $this->hasOne(VendorValidation::class);
    }

    /**
     * Vérifier si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Vérifier si l'utilisateur est banni
     */
    public function isBanned(): bool
    {
        if (!$this->is_banned) {
            return false;
        }

        if ($this->banned_until && $this->banned_until->isFuture()) {
            return true;
        }

        return !$this->banned_until;
    }

    /**
     * Bannir un utilisateur
     */
    public function ban(User $admin, string $reason, string $details = '', ?\DateTime $unbannedAt = null): UserBan
    {
        $ban = UserBan::create([
            'user_id' => $this->id,
            'reason' => $reason,
            'details' => $details,
            'is_active' => true,
            'banned_at' => now(),
            'banned_by' => $admin->id,
        ]);

        $this->update([
            'is_banned' => true,
            'banned_until' => $unbannedAt,
        ]);

        return $ban;
    }

    /**
     * Relation avec les badges de gamification
     */
    public function badges()
    {
        return $this->belongsToMany(BadgeType::class, 'user_badges')
            ->withPivot('awarded_at', 'reason')
            ->withTimestamps();
    }

    /**
     * Relation avec les points de gamification
     */
    public function points()
    {
        return $this->hasOne(UserPoints::class);
    }

    /**
     * Relation avec l'historique des points
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Relation avec les avis reçus (en tant que vendeur)
     */
    public function avisRecus()
    {
        return $this->hasManyThrough(Avis::class, Produit::class, 'user_id', 'produit_id');
    }
}
