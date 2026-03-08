<?php

namespace App\Services;

use App\Models\BannedWord;

class BannedWordService
{
    /**
     * Vérifier si un texte contient des mots bannisE retourner les résultats
     */
    public static function check($text)
    {
        $bannedWords = BannedWord::getActiveBannedWords();
        $found = [];
        
        foreach ($bannedWords as $word) {
            if (stripos($text, $word) !== false) {
                $found[] = $word;
            }
        }
        
        return $found;
    }

    /**
     * Vérifier si un produit a des mots bannisE retourner true/false
     */
    public static function hasBannedWords($productName)
    {
        return !empty(self::check($productName));
    }

    /**
     * Obtenir message d'erreur si mots bannisont trouvés
     */
    public static function getErrorMessage($text)
    {
        $found = self::check($text);
        
        if (!empty($found)) {
            return "Votre produit contient des mots interdits : " . implode(', ', array_unique($found)) . ". Veuillez modifier le nom ou la description.";
        }
        
        return null;
    }

    /**
     * Valider complètement un produit (nom + description)
     */
    public static function validateProduct($name, $description = null)
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'banned_in' => [],
        ];

        // Vérifier le nom
        $nameBanned = self::check($name);
        if (!empty($nameBanned)) {
            $result['valid'] = false;
            $result['errors'][] = "Le nom du produit contient des mots interdits : " . implode(', ', array_unique($nameBanned));
            $result['banned_in'][] = 'name';
        }

        // Vérifier la description
        if ($description) {
            $descBanned = self::check($description);
            if (!empty($descBanned)) {
                $result['valid'] = false;
                $result['errors'][] = "La description du produit contient des mots interdits : " . implode(', ', array_unique($descBanned));
                $result['banned_in'][] = 'description';
            }
        }

        return $result;
    }

    /**
     * Nettoyer un texte en remplaçant les mots bannispar des étoiles
     */
    public static function censor($text)
    {
        $bannedWords = BannedWord::getActiveBannedWords();
        
        foreach ($bannedWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }
        
        return $text;
    }
}
