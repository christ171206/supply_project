<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitImage;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CloudinaryImageController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryImageService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Afficher la galerie d'images d'un produit
     */
    public function gallery(Produit $produit)
    {
        // Vérifier que c'est le vendeur propriétaire
        if (auth()->user()->id !== $produit->user_id) {
            abort(403, 'Non autorisé');
        }

        $images = $produit->cloudinaryImages()->get();

        return view('vendeur.produits.gallery', [
            'produit' => $produit,
            'images' => $images,
            'cloudName' => $this->cloudinary->getCloudName(),
        ]);
    }

    /**
     * Uploader une image pour un produit
     */
    public function upload(Request $request, Produit $produit)
    {
        // Vérifier l'autorisation
        if (auth()->user()->id !== $produit->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ], [
            'image.required' => 'Veuillez sélectionner une image',
            'image.image' => 'Le fichier doit être une image valide',
            'image.max' => 'L\'image ne doit pas dépasser 5MB',
        ]);

        try {
            $file = $request->file('image');

            // Upload vers Cloudinary
            $result = $this->cloudinary->upload(
                $file,
                'products',
                "product_{$produit->id}_" . time()
            );

            if (!$result['success']) {
                return response()->json(['error' => $result['error']], 400);
            }

            // Sauvegarder en base de données
            $image = ProduitImage::create([
                'produit_id' => $produit->id,
                'cloudinary_public_id' => $result['public_id'],
                'cloudinary_url' => $result['url'],
                'cloudinary_secure_url' => $result['url'],
                'width' => $result['width'],
                'height' => $result['height'],
                'file_size' => $result['size'],
                'format' => $result['format'],
                'is_primary' => $produit->cloudinaryImages()->count() === 0, // Première = principale
            ]);

            // Mettre à jour le produit avec l'image principale si c'est la première
            if ($image->is_primary) {
                $produit->update([
                    'primary_image_cloudinary_id' => $result['public_id'],
                ]);
            }

            Log::info('Produit image uploaded', [
                'produit_id' => $produit->id,
                'image_id' => $image->id,
                'cloudinary_id' => $result['public_id'],
            ]);

            return response()->json([
                'success' => true,
                'image' => [
                    'id' => $image->id,
                    'url' => $result['url'],
                    'thumbnail' => $this->cloudinary->getOptimizedUrl($result['public_id'], [
                        'width' => 220,
                        'height' => 220,
                        'crop' => 'fill',
                    ]),
                    'is_primary' => $image->is_primary,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Image upload error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer une image
     */
    public function delete(Produit $produit, ProduitImage $image)
    {
        // Vérifier l'autorisation
        if (auth()->user()->id !== $produit->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($image->produit_id !== $produit->id) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        try {
            // Supprimer de Cloudinary
            $deleted = $this->cloudinary->delete($image->cloudinary_public_id);

            if (!$deleted) {
                Log::warning('Failed to delete image from Cloudinary', [
                    'public_id' => $image->cloudinary_public_id,
                ]);
            }

            // Supprimer de la base de données
            $wasPrimary = $image->is_primary;
            $imageId = $image->id;
            $image->delete();

            // Si c'était l'image principale, en définir une autre
            if ($wasPrimary) {
                $nextImage = $produit->cloudinaryImages()->first();
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                    $produit->update(['primary_image_cloudinary_id' => $nextImage->cloudinary_public_id]);
                } else {
                    $produit->update(['primary_image_cloudinary_id' => null]);
                }
            }

            Log::info('Produit image deleted', [
                'produit_id' => $produit->id,
                'image_id' => $imageId,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Image deletion error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Définir une image comme principale
     */
    public function setPrimary(Produit $produit, ProduitImage $image)
    {
        // Vérifier l'autorisation
        if (auth()->user()->id !== $produit->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($image->produit_id !== $produit->id) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        try {
            // Retirer is_primary des autres images
            $produit->cloudinaryImages()->update(['is_primary' => false]);

            // Définir celle-ci comme principale
            $image->update(['is_primary' => true]);
            $produit->update(['primary_image_cloudinary_id' => $image->cloudinary_public_id]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Réorganiser les images
     */
    public function reorder(Request $request, Produit $produit)
    {
        // Vérifier l'autorisation
        if (auth()->user()->id !== $produit->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:produit_images,id',
        ]);

        try {
            foreach ($request->order as $index => $imageId) {
                ProduitImage::where('id', $imageId)
                    ->where('produit_id', $produit->id)
                    ->update(['order' => $index]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
