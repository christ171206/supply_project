<?php

namespace App\Observers;

use App\Models\Categorie;
use Illuminate\Support\Facades\Cache;

class CategorieObserver
{
    /**
     * Handle the Categorie "created" event.
     */
    public function created(Categorie $categorie): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Categorie "updated" event.
     */
    public function updated(Categorie $categorie): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Categorie "deleted" event.
     */
    public function deleted(Categorie $categorie): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Categorie "restored" event.
     */
    public function restored(Categorie $categorie): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Categorie "force deleted" event.
     */
    public function forceDeleted(Categorie $categorie): void
    {
        $this->clearCache();
    }

    /**
     * Nettoyer tous les caches relatifs aux catégories
     */
    private function clearCache(): void
    {
        Cache::forget('categories_homepage');
        Cache::forget('categories_catalogue');
    }
}
