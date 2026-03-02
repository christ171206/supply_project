<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VendeurProduitController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VendorStatisticsController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [ProduitController::class, 'index'])->name('accueil');
Route::get('/produits', [ProduitController::class, 'catalogue'])->name('produits.catalogue');
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');

// API Routes
Route::get('/api/produits/{id}', function ($id) {
    $produit = \App\Models\Produit::findOrFail($id, ['id', 'nom', 'prix', 'image', 'stock', 'description']);
    return response()->json([
        'success' => true,
        'data' => $produit
    ]);
});

// Currency Converter API
Route::get('/api/currency/rates', function () {
    $service = new \App\Services\CurrencyConverterService();
    $rates = $service->fetchRates();
    return response()->json($rates);
});

Route::post('/api/currency/convert', function (\Illuminate\Http\Request $request) {
    $amount = $request->input('amount', 0);
    $from = $request->input('from', 'XOF');
    $to = $request->input('to', 'EUR');

    $service = new \App\Services\CurrencyConverterService();
    $converted = $service->convert($amount, $from, $to);

    return response()->json([
        'original_amount' => $amount,
        'original_currency' => $from,
        'converted_amount' => round($converted, 2),
        'target_currency' => $to,
        'timestamp' => now()->timestamp,
    ]);
});

// AJAX Validation Routes (Real-time form validation)
Route::post('/api/validate/email', [ValidationController::class, 'validateEmail'])->name('validate.email');
Route::post('/api/validate/username', [ValidationController::class, 'validateUsername'])->name('validate.username');
Route::post('/api/validate/password', [ValidationController::class, 'validatePassword'])->name('validate.password');

// Real-Time Search Routes
Route::post('/api/search/live', [SearchController::class, 'liveSearch'])->name('search.live');
Route::post('/api/search/suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestions');

// API Messages (for WebSocket server)
Route::post('/api/messages/store', [MessageController::class, 'apiStore']);

// Panier (accessible sans auth)
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::get('/panier/count', [PanierController::class, 'count'])->name('panier.count');
Route::post('/panier/ajouter/{produitId}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::patch('/panier/{itemId}', [PanierController::class, 'modifier'])->name('panier.modifier');
Route::delete('/panier/{itemId}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'vider'])->name('panier.vider');

// Favoris (accessible sans auth - affichage des favoris)
Route::get('/favoris', [FavoriteController::class, 'index'])->name('favoris.index');

// Routes authentifiées
Route::middleware('auth')->group(function () {
    // Dashboard Client
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/mes-commandes', [ClientDashboardController::class, 'commandes'])->name('client.commandes');
    Route::get('/commande/{id}', [ClientDashboardController::class, 'commandeDetail'])->name('client.commande-detail');
    Route::post('/commande/{id}/cancel', [ClientDashboardController::class, 'cancelCommande'])->name('client.commande.cancel');
    Route::get('/mon-profil', [ClientDashboardController::class, 'profil'])->name('client.profil');
    Route::put('/mon-profil', [ClientDashboardController::class, 'updateProfil'])->name('client.profil.update');
    Route::patch('/mon-profil/photo', [ClientDashboardController::class, 'updateProfilPhoto'])->name('client.profil.photo');

    // Commandes (Client)
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/paiement', [CommandeController::class, 'create'])->name('commandes.create');
    Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::get('/commandes/{id}/facture', [CommandeController::class, 'facture'])->name('commandes.facture');
    Route::get('/commandes/{id}/download-pdf', [CommandeController::class, 'downloadPDF'])->name('commandes.download-pdf');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{id}/payment-success', [CommandeController::class, 'paymentSuccess'])->name('commandes.payment-success');

    // Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Avis
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    Route::delete('/avis/{avis}', [AvisController::class, 'destroy'])->name('avis.destroy');

    // Favoris (toggle et check authentifiés)
    Route::post('/favoris/{productId}/toggle', [FavoriteController::class, 'toggle'])->name('favoris.toggle');
    Route::get('/favoris/{productId}/check', [FavoriteController::class, 'isFavorited'])->name('favoris.check');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('client.messages');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{userId}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::delete('/messages/{messageId}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
});

// Routes Vendeur
Route::middleware(['auth', 'vendeur'])->prefix('vendeur')->name('vendeur.')->group(function () {
    Route::get('/dashboard', [VendeurProduitController::class, 'dashboard'])->name('dashboard');
    Route::get('/apercu', [VendeurProduitController::class, 'apercu'])->name('apercu');

    // Stock Management
    Route::get('/stock', [VendeurProduitController::class, 'stock'])->name('stock');

    // Statistiques
    Route::get('/statistiques', [VendeurProduitController::class, 'statistiques'])->name('statistiques');

    // Messages
    Route::get('/messages', [VendeurProduitController::class, 'messages'])->name('messages');
    Route::get('/messages/{userId}', [VendeurProduitController::class, 'messagesShow'])->name('messages.show');
    Route::post('/messages/{userId}', [VendeurProduitController::class, 'messageSend'])->name('messages.send');
    Route::delete('/messages/{messageId}', [VendeurProduitController::class, 'messageDelete'])->name('messages.delete');

    // Avis
    Route::get('/avis', [VendeurProduitController::class, 'avis'])->name('avis');

    // Paramètres
    Route::get('/parametres', [VendeurProduitController::class, 'parametres'])->name('parametres');
    Route::put('/parametres', [VendeurProduitController::class, 'updateParametres'])->name('parametres.update');
    Route::delete('/parametres', [VendeurProduitController::class, 'deleteShop'])->name('parametres.delete');

    Route::get('/historique', [VendeurProduitController::class, 'historique'])->name('historique');
    Route::get('/profil', [VendeurProduitController::class, 'profil'])->name('profil');
    Route::put('/profil', [VendeurProduitController::class, 'updateProfil'])->name('profil.update');
    Route::patch('/profil/photo', [VendeurProduitController::class, 'updateProfilPhoto'])->name('profil.photo');
    Route::resource('produits', VendeurProduitController::class);
    Route::get('/commandes', [CommandeController::class, 'vendeurCommandes'])->name('commandes');
    Route::get('/commandes/{id}', [CommandeController::class, 'vendeurCommandeDetail'])->name('commandes.show');
    Route::patch('/commandes/{id}/status', [CommandeController::class, 'updateCommandeStatus'])->name('commandes.update-status');
    Route::post('/commandes/{id}/cancel', [CommandeController::class, 'cancelCommande'])->name('commandes.cancel');
    Route::delete('/commandes/{id}', [CommandeController::class, 'deleteCommande'])->name('commandes.delete');

    // Vendor Statistics (Premium Feature)
    Route::get('/api/statistics/sales', [VendorStatisticsController::class, 'getSalesData'])->name('statistics.sales');
    Route::get('/api/statistics/inventory', [VendorStatisticsController::class, 'getInventoryStatus'])->name('statistics.inventory');
    Route::get('/api/statistics/customers', [VendorStatisticsController::class, 'getCustomerMetrics'])->name('statistics.customers');

    // Role Switching - Vendeur to Client only
    Route::post('/switch-client', [VendeurProduitController::class, 'switchToClient'])->name('switch-client');
});

require __DIR__ . '/auth.php';
