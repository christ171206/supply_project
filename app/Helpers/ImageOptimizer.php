<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

/**
 * Optimiseur d'images sans dépendance externe
 * Utilise les fonctions PHP natives GD Library
 */
class ImageOptimizer
{
    /**
     * Optimiser une image pour catégories (carrée, légère)
     * Résultat: 400x400px, ~15-25KB
     */
    public static function optimizeCategory(UploadedFile $file, $path = 'categories'): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;
        $storageDir = storage_path('app/public/' . $path);

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $finalPath = storage_path('app/public/' . $fullPath);

        // Obtenir les infos de l'image
        $imageInfo = getimagesize($tempPath);
        if (!$imageInfo) {
            throw new \Exception('Fichier image invalide');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Charger l'image source
        if ($mime === 'image/jpeg') {
            $source = imagecreatefromjpeg($tempPath);
        } elseif ($mime === 'image/png') {
            $source = imagecreatefrompng($tempPath);
        } elseif ($mime === 'image/webp') {
            $source = imagecreatefromwebp($tempPath);
        } else {
            throw new \Exception('Format d\'image non supporté: ' . $mime);
        }

        if (!$source) {
            throw new \Exception('Impossible de charger l\'image');
        }

        // Créer image carrée 400x400
        $thumb = imagecreatetruecolor(400, 400);

        // Calculer les dimensions pour crop carré
        $size = min($width, $height);
        $x = intdiv($width - $size, 2);
        $y = intdiv($height - $size, 2);

        // Copier et redimensionner
        imagecopyresampled($thumb, $source, 0, 0, $x, $y, 400, 400, $size, $size);

        // Sauvegarder en JPEG optimisé (qualité 75)
        imagejpeg($thumb, $finalPath, 75);

        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($thumb);

        return $fullPath;
    }

    /**
     * Optimiser une image pour produits (max 600px, aspect ratio conservé)
     * Résultat: ~20-40KB selon aspect ratio
     */
    public static function optimizeProduct(UploadedFile $file, $path = 'produits'): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;
        $storageDir = storage_path('app/public/' . $path);

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $finalPath = storage_path('app/public/' . $fullPath);

        $imageInfo = getimagesize($tempPath);
        if (!$imageInfo) {
            throw new \Exception('Fichier image invalide');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Charger source
        if ($mime === 'image/jpeg') {
            $source = imagecreatefromjpeg($tempPath);
        } elseif ($mime === 'image/png') {
            $source = imagecreatefrompng($tempPath);
        } elseif ($mime === 'image/webp') {
            $source = imagecreatefromwebp($tempPath);
        } else {
            throw new \Exception('Format non supporté');
        }

        if (!$source) {
            throw new \Exception('Impossible de charger l\'image');
        }

        // Redimensionner si plus grand que 600px
        $newWidth = min($width, 600);
        $newHeight = intdiv($height * $newWidth, $width);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Sauvegarder JPEG optimisé (qualité 75 pour web)
        imagejpeg($resized, $finalPath, 75);

        imagedestroy($source);
        imagedestroy($resized);

        return $fullPath;
    }

    /**
     * Supprimer une image
     */
    public static function delete($path): bool
    {
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * Optimiser image générique (max $maxWidth, conserve aspect ratio)
     */
    public static function optimize(UploadedFile $file, $path, $maxWidth = 800, $maxHeight = 800): string
    {
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;
        $storageDir = storage_path('app/public/' . $path);

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $finalPath = storage_path('app/public/' . $fullPath);

        $imageInfo = getimagesize($tempPath);
        if (!$imageInfo) {
            throw new \Exception('Fichier image invalide');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Charger source
        if ($mime === 'image/jpeg') {
            $source = imagecreatefromjpeg($tempPath);
        } elseif ($mime === 'image/png') {
            $source = imagecreatefrompng($tempPath);
        } elseif ($mime === 'image/webp') {
            $source = imagecreatefromwebp($tempPath);
        } else {
            throw new \Exception('Format non supporté: ' . $mime);
        }

        if (!$source) {
            throw new \Exception('Impossible de charger l\'image');
        }

        // Calculer nouvelles dimensions
        $ratio = $width / $height;
        if ($width > $height) {
            $newWidth = min($width, $maxWidth);
            $newHeight = intdiv($newWidth, $ratio);
        } else {
            $newHeight = min($height, $maxHeight);
            $newWidth = intdiv($newHeight * $ratio, 1);
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagejpeg($resized, $finalPath, 75);

        imagedestroy($source);
        imagedestroy($resized);

        return $fullPath;
    }
}
