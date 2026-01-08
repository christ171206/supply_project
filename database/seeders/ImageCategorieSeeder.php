<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class ImageCategorieSeeder extends Seeder
{
    public function run(): void
    {
        // Mettre à jour les catégories avec leurs images
        $updates = [
            'Ordinateurs Portables' => 'Ordinateur portable.jpg',
            'Écrans' => 'Ecran.jpg',
            'Claviers' => 'Clavier.jpg',
            'Souris' => 'Souris.jpg',
            'Casques Audio' => 'Casque audio.jpg',
            'Webcams' => 'Webcams.jpg',
        ];

        foreach ($updates as $categorieName => $imageName) {
            Categorie::where('nom', $categorieName)->update(['image' => $imageName]);
        }
    }
}
