<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    /**
     * Générer automatiquement le slug à partir du nom
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nom);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug) || $model->isDirty('nom')) {
                $model->slug = Str::slug($model->nom);
            }
        });
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function flashSales()
    {
        return $this->hasMany(FlashSale::class);
    }
}
