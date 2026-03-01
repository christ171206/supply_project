<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'status',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device_type',
        'city',
        'country',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation: Owner of this security log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get events for a specific user ordered by latest first
     */
    public static function getLatestEvents($userId, $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get suspicious activity (failed login attempts, etc)
     */
    public static function getSuspiciousActivity($userId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('user_id', $userId)
            ->where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Format event type for display
     */
    public function getEventLabel(): string
    {
        return match ($this->event_type) {
            'login' => '🔓 Connexion',
            'logout' => '🚪 Déconnexion',
            'password_change' => '🔐 Changement de mot de passe',
            'delete_account' => '🗑️ Suppression du compte',
            'email_change' => '📧 Changement d\'email',
            'profile_update' => '👤 Mise à jour du profil',
            'photo_upload' => '📸 Upload photo',
            'failed_login' => '❌ Connexion échouée',
            default => $this->event_type,
        };
    }

    /**
     * Format device info for display
     */
    public function getDeviceLabel(): string
    {
        $browser = $this->browser ?: 'Unknown';
        $platform = $this->platform ?: 'Unknown';
        return "{$browser} • {$platform}";
    }

    /**
     * Get location label
     */
    public function getLocationLabel(): string
    {
        if ($this->city && $this->country) {
            return "{$this->city}, {$this->country}";
        } elseif ($this->country) {
            return $this->country;
        }
        return $this->ip_address ?: 'Unknown';
    }

    /**
     * Check if this is a suspicious login (different device/location)
     */
    public function isSuspicious(): bool
    {
        // Check if user has logged in from this IP before
        $previousLogins = self::where('user_id', $this->user_id)
            ->where('ip_address', '!=', $this->ip_address)
            ->where('event_type', 'login')
            ->where('status', 'success')
            ->exists();

        return $previousLogins && $this->event_type === 'login';
    }
}
