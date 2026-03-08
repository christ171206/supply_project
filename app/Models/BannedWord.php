<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    protected $fillable = [
        'word',
        'description',
        'severity',
        'is_active',
        'created_by_admin',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin');
    }

    /**
     * Statiquement récupérer les mots bannisactifs
     */
    public static function getActiveBannedWords()
    {
        return self::where('is_active', true)->pluck('word')->toArray();
    }

    /**
     * Vérifier si un texte contient des mots bannisE retourner correspondances
     */
    public static function checkForBannedWords($text)
    {
        $bannedWords = self::getActiveBannedWords();
        $found = [];
        
        foreach ($bannedWords as $word) {
            if (stripos($text, $word) !== false) {
                $found[] = $word;
            }
        }
        
        return $found;
    }

    /**
     * Retourner la raison de refus si mots bannisont trouvés
     */
    public static function getBlockingReasonIfBanned($text)
    {
        $found = self::checkForBannedWords($text);
        
        if (!empty($found)) {
            return "Contenu refusé : mots interdits détectés (" . implode(', ', $found) . ")";
        }
        
        return null;
    }
}
