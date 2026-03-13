<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'categorie_id',
        'user_id',
        'nom',
        'slug',
        'description',
        'prix',
        'stock',
        'stock_minimum',
        'est_actif',
        'featured',
        'image',
        'images',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'featured' => 'boolean',
        'images' => 'array',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function panierItems()
    {
        return $this->hasMany(PanierItem::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    /**
     * Images Cloudinary du produit
     */
    public function cloudinaryImages()
    {
        return $this->hasMany(ProduitImage::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(StockMouvement::class);
    }

    public function stockAlert()
    {
        return $this->hasOne(StockAlert::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'produit_id', 'user_id');
    }

    // Méthode pour vérifier si le stock est critique
    public function isStockCritique()
    {
        return $this->stock <= $this->stock_minimum;
    }

    /**
     * Vérifier et déclencher une alerte si nécessaire
     */
    public function checkAndTriggerStockAlert(): void
    {
        try {
            $alert = $this->stockAlert;

            // Si pas d'alerte configurée, on ne fait rien
            if (!$alert || !$alert->is_active) {
                return;
            }

            // Vérifier si on doit envoyer une alerte
            $isBelowThreshold = $this->stock <= $alert->alert_threshold;

            if ($isBelowThreshold) {
                // Déterminer le type d'alerte
                $alertType = $this->stock === 0 ? 'critical' : 'low';

                // Vérifier si on a déjà envoyé une alerte aujourd'hui
                $lastAlert = $alert->last_alert_sent;
                $shouldSendAlert = !$lastAlert || $lastAlert->diffInHours(now()) >= 24;

                if ($shouldSendAlert) {
                    // Déclencher l'événement
                    \App\Events\StockAlertTriggered::dispatch($this, $alert, $alertType);

                    // Mettre à jour last_alert_sent
                    $alert->update(['last_alert_sent' => now()]);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Erreur vérification alerte stock: " . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le stock et déclencher les alertes
     */
    public function updateStockWithAlert(int $newQuantity, string $movementType, string $reason, ?int $commandeId = null): void
    {
        $this->update(['stock' => $newQuantity]);
        $this->checkAndTriggerStockAlert();
    }

    // Méthode pour enregistrer un mouvement de stock
    public function enregistrerMouvement($type, $quantite, $motif, $user_id, $commande_id = null, $note = null)
    {
        return StockMouvement::create([
            'produit_id' => $this->id,
            'type' => $type,
            'quantite' => $quantite,
            'motif' => $motif,
            'user_id' => $user_id,
            'commande_id' => $commande_id,
            'note' => $note,
        ]);
    }
}
