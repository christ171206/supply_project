<?php
/**
 * EXEMPLE D'INTÉGRATION DU STOCKSERVICE
 *
 * Cet exemple montre comment utiliser le StockService
 * pour automatiser les mouvements de stock lors de la validation de commandes
 */

// EXEMPLE 1: Valider une commande et diminuer le stock automatiquement
// ====================================================================

use App\Models\Commande;
use App\Services\StockService;

$commande = Commande::find(1);
$stockService = new StockService();

// Quand le vendeur valide une commande:
if ($commande->statut === 'en_attente') {
    try {
        // Traiter tous les mouvements de stock
        $stockService->traiterValidationCommande($commande);

        // Mettre à jour le statut
        $commande->update(['statut' => 'validée']);

        // Réponse succès
        // return redirect()->back()->with('success', 'Commande validée et stock mis à jour!');
    } catch (\Exception $e) {
        // Stock insuffisant
        // return redirect()->back()->with('error', $e->getMessage());
    }
}


// EXEMPLE 2: Ajouter du stock manuellement
// ==========================================

$produit = $commande->ligneCommandes->first()->produit;

// Ajouter 50 unités
$stockService->augmenterStock(
    $produit,
    50,
    'réapprovisionnement',
    auth()->id()
);

// Un mouvement sera créé automatiquement:
// - type: 'entrée'
// - quantite: 50
// - motif: 'réapprovisionnement'
// - user_id: ID de l'utilisateur actuel


// EXEMPLE 3: Annuler une commande et restaurer le stock
// ======================================================

// Si une commande est annulée:
$stockService->annulerCommandeStock($commande);

// Tous les mouvements seront inversés:
// - Les sorties deviennent des entrées
// - Le stock est restauré
// - Les mouvements sont enregistrés avec motif 'annulation_commande'


// EXEMPLE 4: Vérifier et afficher les produits en stock critique
// ===============================================================

$produitsStockFaible = $stockService->getProduitsStockCritique(auth()->id());

foreach ($produitsStockFaible as $produit) {
    echo "⚠️ " . $produit->nom . " est en stock critique (" . $produit->stock . " < " . $produit->stock_minimum . ")\n";
}


// EXEMPLE 5: Consulter l'historique d'un produit
// ================================================

$historique = $stockService->getHistoriqueStock($produit, 50);

foreach ($historique as $mouvement) {
    echo "[" . $mouvement->created_at->format('d/m/Y H:i') . "] ";
    echo $mouvement->type === 'entrée' ? "📥 +" : "📤 -";
    echo $mouvement->quantite . " - " . $mouvement->motif . "\n";
}


// EXEMPLE 6: Affichage sur le dashboard
// ======================================

// Dans le contrôleur VendeurProduitController@dashboard():
// ========================================================

/*
public function dashboard()
{
    $user = auth()->user();
    $stockService = new StockService();

    // ... autres stats ...

    // Produits en stock critique
    $produitsStockFaible = $stockService->getProduitsStockCritique($user->id);
    $stockFaible = $produitsStockFaible->count();

    return view('vendeur.dashboard', [
        // ... autres variables ...
        'produitsStockFaible' => $produitsStockFaible,
        'stockFaible' => $stockFaible,
    ]);
}


// EXEMPLE 7: Badges visuels sur les produits
// ===========================================

// Dans la vue produits/index.blade.php:
@foreach($produits as $produit)
    @if($produit->isStockCritique())
        <!-- Badge de stock critique -->
        <span class="badge badge-danger">
            ❌ Rupture ({{ $produit->stock }} < {{ $produit->stock_minimum }})
        </span>
    @elseif($produit->stock <= $produit->stock_minimum)
        <!-- Badge de stock faible -->
        <span class="badge badge-warning">
            ⚠️ Faible ({{ $produit->stock }} / {{ $produit->stock_minimum }})
        </span>
    @else
        <!-- Stock OK -->
        <span class="badge badge-success">
            ✅ OK ({{ $produit->stock }} unités)
        </span>
    @endif
@endforeach


// STRUCTURE DE LA TABLE stock_mouvements
// ======================================
/*
id (PK)          | 1  | 2  | 3  | 4
produit_id (FK)  | 5  | 5  | 7  | 5
type             | sortie | entrée | sortie | sortie
quantite         | 2  | 10 | 1  | 3
motif            | commande | réapprov | commande | commande
user_id (FK)     | 1  | 1  | 1  | 1
commande_id (FK) | 9  | NULL | 11 | 12
created_at       | 2026-01-08 | 2026-01-08 | 2026-01-08 | 2026-01-08



 LOGIQUE DE VÉRIFICATION
 =======================

// Avant de diminuer le stock:
if ($produit->stock >= $quantite) {
    // OK - diminuer
    $produit->decrement('stock', $quantite);
    $produit->enregistrerMouvement('sortie', $quantite, 'commande', auth()->id(), $commande->id);
} else {
    // ERREUR - stock insuffisant
    throw new \Exception("Stock insuffisant pour {$produit->nom}");
}

// Après chaque mouvement:
// - Le stock est toujours à jour
// - Un enregistrement dans stock_mouvements existe
// - L'historique est complet et immuable
