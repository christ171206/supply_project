<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VendeurProduitController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VendorStatisticsController;
use App\Http\Controllers\CloudinaryImageController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\BundleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

// Routes publiques
Route::get('/', [ProduitController::class, 'index'])->name('accueil');
Route::get('/produits', [ProduitController::class, 'catalogue'])->name('produits.catalogue');
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');
Route::get('/recherche', [SearchController::class, 'index'])->name('search.index');
Route::get('/pwa-install', fn() => view('pwa-demo'))->name('pwa.install');

// API Routes
Route::get('/api/produits/{id}', function ($id) {
    $produit = \App\Models\Produit::findOrFail($id, ['id', 'nom', 'prix', 'image', 'stock', 'description']);
    return response()->json([
        'success' => true,
        'data' => $produit
    ]);
});

// Recommendation API (Client - Public)
Route::prefix('api/recommendations')->name('recommendations.')->group(function () {
    Route::get('/similar/{product}', [\App\Http\Controllers\RecommendationController::class, 'getSimilarProducts'])->name('similar');
    Route::get('/popular/{product}', [\App\Http\Controllers\RecommendationController::class, 'getPopularInCategory'])->name('popular');
    Route::get('/bought-together/{product}', [\App\Http\Controllers\RecommendationController::class, 'getFrequentlyBoughtTogether'])->name('bought-together');
    Route::get('/trending', [\App\Http\Controllers\RecommendationController::class, 'getTrendingProducts'])->name('trending');
    Route::middleware('auth')->group(function () {
        Route::get('/personalized', [\App\Http\Controllers\RecommendationController::class, 'getPersonalizedRecommendations'])->name('personalized');
    });
});

// Smart Search API
Route::prefix('api/search')->name('search.')->group(function () {
    Route::get('/autocomplete', [\App\Http\Controllers\SmartSearchController::class, 'autocomplete'])->name('autocomplete');
    Route::get('/search', [\App\Http\Controllers\SmartSearchController::class, 'search'])->name('search');
    Route::get('/trending', [\App\Http\Controllers\SmartSearchController::class, 'trendingSuggestions'])->name('trending');
});

// Search Results Pages
Route::get('/search/results', [\App\Http\Controllers\SmartSearchController::class, 'results'])->name('search.results');

// Vendor Shop (Public)
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/{vendor}', [\App\Http\Controllers\VendorShopController::class, 'show'])->name('show');
    Route::get('/{vendor}/search', [\App\Http\Controllers\VendorShopController::class, 'search'])->name('search');
    Route::post('/{vendor}/follow', [\App\Http\Controllers\VendorShopController::class, 'follow'])->name('follow');
});

// Advanced Reviews API
Route::prefix('api/reviews')->name('reviews.')->group(function () {
    Route::post('/{product}', [\App\Http\Controllers\AdvancedReviewController::class, 'store'])->name('store');
    Route::get('/{product}/advanced', [\App\Http\Controllers\AdvancedReviewController::class, 'getAdvancedReviews'])->name('advanced');
    Route::post('/{review}/helpful', [\App\Http\Controllers\AdvancedReviewController::class, 'markHelpful'])->name('helpful');
});

// Gamification API
Route::middleware('auth')->prefix('api/gamification')->name('gamification.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\GamificationController::class, 'getProfile'])->name('profile');
    Route::get('/badges', [\App\Http\Controllers\GamificationController::class, 'getAvailableBadges'])->name('badges');
});

// Promotions API
Route::prefix('api/promotions')->name('promotions.')->group(function () {
    Route::get('/rules', [\App\Http\Controllers\PromotionController::class, 'getPromoRules'])->name('rules');
    Route::post('/validate', [\App\Http\Controllers\PromotionController::class, 'validatePromoCode'])->name('validate');
    Route::middleware('auth')->group(function () {
        Route::get('/vendor', [\App\Http\Controllers\PromotionController::class, 'getVendorPromotions'])->name('vendor');
    });
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

// Webhook Stripe (public - CSRF exempt via middleware)
Route::post('/webhooks/stripe', [PaymentController::class, 'handleWebhook'])->name('webhooks.stripe')->withoutMiddleware('web');

// Favoris (accessible sans auth - affichage des favoris)
Route::get('/favoris', [FavoriteController::class, 'index'])->name('favoris.index');
Route::get('/favoris/{productId}/check', [FavoriteController::class, 'isFavorited'])->name('favoris.check');

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
    Route::get('/commandes/{id}/simulation', [CommandeController::class, 'paymentSimulation'])->name('commandes.payment-simulation');
    Route::get('/commandes/{id}/succes', [CommandeController::class, 'paymentSuccess'])->name('commandes.payment-success');
    Route::get('/commandes/{id}/facture', [CommandeController::class, 'facture'])->name('commandes.facture');
    Route::get('/commandes/{id}/download-pdf', [CommandeController::class, 'downloadPDF'])->name('commandes.download-pdf');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{id}/payment-test', [CommandeController::class, 'paymentTest'])->name('commandes.payment-test');

    // Invoices (Factures)
    Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/{commande}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('show');
        Route::get('/{commande}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('download');
        Route::post('/{commande}/email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('send-email');
        Route::get('/{commande}/data', [\App\Http\Controllers\InvoiceController::class, 'getInvoiceData'])->name('data');
    });

    // Paiement Stripe
    Route::get('/commandes/{id}/payment', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/create-intent', [PaymentController::class, 'createIntent'])->name('payment.create-intent');
    Route::post('/payment/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

    // Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Avis
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    Route::delete('/avis/{avis}', [AvisController::class, 'destroy'])->name('avis.destroy');

    // Favoris (toggle authentifié)
    Route::post('/favoris/{productId}/toggle', [FavoriteController::class, 'toggle'])->name('favoris.toggle');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('client.messages');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{userId}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::delete('/messages/{messageId}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::post('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');
});

// Routes Vendeur
Route::middleware(['auth', 'vendeur', 'vendor-approved'])->prefix('vendeur')->name('vendeur.')->group(function () {
    Route::get('/dashboard', [VendeurProduitController::class, 'dashboard'])->name('dashboard');
    Route::get('/apercu', [VendeurProduitController::class, 'apercu'])->name('apercu');

    // API Notifications
    Route::get('/api/notifications', [VendeurProduitController::class, 'getNotifications'])->name('notifications');

    // Stock Management
    Route::get('/stock', [VendeurProduitController::class, 'stock'])->name('stock');

    // Statistiques
    Route::get('/statistiques', [VendeurProduitController::class, 'statistiques'])->name('statistiques');
    Route::get('/statistiques/export', [VendeurProduitController::class, 'exportStatistiques'])->name('statistiques.export');

    // API Analytics (pour ApexCharts)
    Route::prefix('api/analytics')->name('analytics.')->group(function () {
        Route::get('/daily-sales', [\App\Http\Controllers\VendorAnalyticsController::class, 'getDailySalesData'])->name('daily-sales');
        Route::get('/sales-by-category', [\App\Http\Controllers\VendorAnalyticsController::class, 'getSalesByCategory'])->name('sales-category');
        Route::get('/top-products', [\App\Http\Controllers\VendorAnalyticsController::class, 'getTopProducts'])->name('top-products');
        Route::get('/global-stats', [\App\Http\Controllers\VendorAnalyticsController::class, 'getGlobalStats'])->name('global-stats');
        Route::get('/month-comparison', [\App\Http\Controllers\VendorAnalyticsController::class, 'getMonthComparison'])->name('month-comparison');
        Route::get('/stock-forecasts', [\App\Http\Controllers\VendorAnalyticsController::class, 'getStockForecasts'])->name('stock-forecasts');
    });

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

    // Codes Promo
    Route::get('/promo-codes', [\App\Http\Controllers\PromoCodeController::class, 'index'])->name('promo-codes.index');
    Route::get('/promo-codes/create', [\App\Http\Controllers\PromoCodeController::class, 'create'])->name('promo-codes.create');
    Route::post('/promo-codes', [\App\Http\Controllers\PromoCodeController::class, 'store'])->name('promo-codes.store');
    Route::get('/promo-codes/{promoCode}', [\App\Http\Controllers\PromoCodeController::class, 'show'])->name('promo-codes.show');
    Route::get('/promo-codes/{promoCode}/edit', [\App\Http\Controllers\PromoCodeController::class, 'edit'])->name('promo-codes.edit');
    Route::put('/promo-codes/{promoCode}', [\App\Http\Controllers\PromoCodeController::class, 'update'])->name('promo-codes.update');
    Route::post('/promo-codes/{promoCode}/duplicate', [\App\Http\Controllers\PromoCodeController::class, 'duplicate'])->name('promo-codes.duplicate');
    Route::delete('/promo-codes/{promoCode}', [\App\Http\Controllers\PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
    Route::patch('/promo-codes/{promoCode}/toggle-archive', [\App\Http\Controllers\PromoCodeController::class, 'toggleArchive'])->name('promo-codes.toggle-archive');

    // API Promo
    Route::get('/api/promo-codes/generate', [\App\Http\Controllers\PromoCodeController::class, 'generateCode'])->name('promo-codes.generate');
    Route::post('/api/promo-codes/check', [\App\Http\Controllers\PromoCodeController::class, 'checkCode'])->name('promo-codes.check');

    // Flash Sales
    Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
    Route::get('/flash-sales/create', [FlashSaleController::class, 'create'])->name('flash-sales.create');
    Route::post('/flash-sales', [FlashSaleController::class, 'store'])->name('flash-sales.store');
    Route::get('/flash-sales/{flashSale}', [FlashSaleController::class, 'show'])->name('flash-sales.show');
    Route::get('/flash-sales/{flashSale}/edit', [FlashSaleController::class, 'edit'])->name('flash-sales.edit');
    Route::put('/flash-sales/{flashSale}', [FlashSaleController::class, 'update'])->name('flash-sales.update');
    Route::delete('/flash-sales/{flashSale}', [FlashSaleController::class, 'destroy'])->name('flash-sales.destroy');
    Route::patch('/flash-sales/{flashSale}/toggle', [FlashSaleController::class, 'toggle'])->name('flash-sales.toggle');

    // Bundles
    Route::get('/bundles', [BundleController::class, 'index'])->name('bundles.index');
    Route::get('/bundles/create', [BundleController::class, 'create'])->name('bundles.create');
    Route::post('/bundles', [BundleController::class, 'store'])->name('bundles.store');
    Route::get('/bundles/{bundle}', [BundleController::class, 'show'])->name('bundles.show');
    Route::get('/bundles/{bundle}/edit', [BundleController::class, 'edit'])->name('bundles.edit');
    Route::put('/bundles/{bundle}', [BundleController::class, 'update'])->name('bundles.update');
    Route::delete('/bundles/{bundle}', [BundleController::class, 'destroy'])->name('bundles.destroy');
    Route::patch('/bundles/{bundle}/toggle', [BundleController::class, 'toggle'])->name('bundles.toggle');

    // Vendor Statistics (Premium Feature)
    Route::get('/api/statistics/sales', [VendorStatisticsController::class, 'getSalesData'])->name('statistics.sales');
    Route::get('/api/statistics/inventory', [VendorStatisticsController::class, 'getInventoryStatus'])->name('statistics.inventory');
    Route::get('/api/statistics/customers', [VendorStatisticsController::class, 'getCustomerMetrics'])->name('statistics.customers');

    // Cloudinary Image Management
    Route::prefix('produits/{produit}/images')->name('produits.')->group(function () {
        Route::get('/', [CloudinaryImageController::class, 'gallery'])->name('gallery');
        Route::post('/upload', [CloudinaryImageController::class, 'upload'])->name('upload');
        Route::delete('/{image}', [CloudinaryImageController::class, 'delete'])->name('delete');
        Route::patch('/{image}/primary', [CloudinaryImageController::class, 'setPrimary'])->name('set-primary');
        Route::post('/reorder', [CloudinaryImageController::class, 'reorder'])->name('reorder');
    });

    // Role Switching - Vendeur to Client only
    Route::post('/switch-client', [VendeurProduitController::class, 'switchToClient'])->name('switch-client');
});

// Notifications (pour tous les utilisateurs authentifiés)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications-center', [\App\Http\Controllers\NotificationCenterController::class, 'index'])->name('notifications.center');
    Route::get('/api/notifications/recent', [\App\Http\Controllers\NotificationCenterController::class, 'getRecent'])->name('notifications.recent');
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationCenterController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationCenterController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationCenterController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationCenterController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read', [\App\Http\Controllers\NotificationCenterController::class, 'clearRead'])->name('notifications.clear-read');

    // Alertes vendeur
    Route::get('/vendeur/alerts', [\App\Http\Controllers\VendorAlertsController::class, 'index'])->name('vendor.alerts');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
