<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitImage extends Model
{
    protected $table = 'produit_images';

    protected $fillable = [
        'produit_id',
        'cloudinary_public_id',
        'cloudinary_url',
        'cloudinary_secure_url',
        'width',
        'height',
        'file_size',
        'format',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Relation au produit
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Pédale les images par ordre
     */
    protected static function booted()
    {
        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('order')->orderBy('created_at');
        });
    }
}
