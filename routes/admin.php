<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminDisputeController;
use App\Http\Controllers\Admin\AdminConfigurationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAvisController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminBannedWordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard (accessible via /admin/ et /admin/dashboard)
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Gestion des utilisateurs
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('{user}', [AdminUserController::class, 'show'])->name('show');
        Route::get('{user}/documents', [AdminUserController::class, 'verifyDocuments'])->name('documents');
        Route::post('{document}/approve', [AdminUserController::class, 'approveDocument'])->name('approve-document');
        Route::post('{document}/reject', [AdminUserController::class, 'rejectDocument'])->name('reject-document');
        Route::post('{user}/ban', [AdminUserController::class, 'ban'])->name('ban');
        Route::post('{user}/unban', [AdminUserController::class, 'unban'])->name('unban');
        Route::post('{user}/assign-role', [AdminUserController::class, 'assignAdminRole'])->name('assign-role');
        Route::post('{user}/approve-vendor', [AdminUserController::class, 'approveVendor'])->name('approve-vendor');
        Route::post('{user}/reject-vendor', [AdminUserController::class, 'rejectVendor'])->name('reject-vendor');
        Route::get('{user}/activity-log', [AdminUserController::class, 'activityLog'])->name('activity-log');
    });

    // Gestion des produits et stock (Supervision uniquement - Lecture seule)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('{produit}', [AdminProductController::class, 'show'])->name('show');
        Route::post('{produit}/disable', [AdminProductController::class, 'disable'])->name('disable');
        Route::post('{produit}/enable', [AdminProductController::class, 'enable'])->name('enable');
        Route::post('{produit}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::delete('{produit}', [AdminProductController::class, 'destroy'])->name('destroy');
        // RETRAIT: adjust-stock (Gestion du stock = Responsabilité du Vendeur)
        // RETRAIT: configure-alert (Configuration = Responsabilité du Vendeur)
        Route::get('critical-stock', [AdminProductController::class, 'criticalStock'])->name('critical-stock');
        Route::get('featured', [AdminProductController::class, 'featured'])->name('featured');
        Route::get('{produit}/stock-history', [AdminProductController::class, 'stockHistory'])->name('stock-history');
        Route::get('stock-audit', [AdminProductController::class, 'stockAudit'])->name('stock-audit');
    });

    // Gestion des commandes (Supervision uniquement - Lecture seule)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('{commande}', [AdminOrderController::class, 'show'])->name('show');
        // RETRAIT: update-status (Gestion = Responsabilité du Vendeur)
        // RETRAIT: update-delivery-status (Gestion = Responsabilité du Livreur)
        // RETRAIT: cancel (Gestion = Responsabilité du Client/Vendeur)
        Route::get('{commande}/tracking', [AdminOrderController::class, 'tracking'])->name('tracking');
        Route::get('delivery/overview', [AdminOrderController::class, 'deliveryOverview'])->name('delivery-overview');
    });

    // Gestion des litiges
    Route::prefix('disputes')->name('disputes.')->group(function () {
        Route::get('/', [AdminDisputeController::class, 'index'])->name('index');
        Route::get('{dispute}', [AdminDisputeController::class, 'show'])->name('show');
        Route::post('{dispute}/update-status', [AdminDisputeController::class, 'updateStatus'])->name('update-status');
        Route::post('{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('resolve');
        Route::post('{dispute}/close', [AdminDisputeController::class, 'close'])->name('close');
        Route::get('pending', [AdminDisputeController::class, 'pending'])->name('pending');
    });

    // Gestion des vendeurs (validation)
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [AdminVendorController::class, 'index'])->name('index');
        Route::get('approved', [AdminVendorController::class, 'approved'])->name('approved');
        Route::get('{validation}', [AdminVendorController::class, 'show'])->name('show');
        Route::post('{validation}/approve', [AdminVendorController::class, 'approve'])->name('approve');
        Route::post('{validation}/reject', [AdminVendorController::class, 'reject'])->name('reject');
        Route::post('{user}/suspend', [AdminVendorController::class, 'suspend'])->name('suspend');
        Route::post('{user}/reactivate', [AdminVendorController::class, 'reactivate'])->name('reactivate');
    });

    // Gestion des catégories (CRUD complet)
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
        Route::get('create', [AdminCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
        Route::get('{category}', [AdminCategoryController::class, 'show'])->name('show');
        Route::get('{category}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [AdminCategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [AdminCategoryController::class, 'destroy'])->name('destroy');
        Route::post('{category}/toggle', [AdminCategoryController::class, 'toggle'])->name('toggle');
    });

    // Modération des avis
    Route::prefix('avis')->name('avis.')->group(function () {
        Route::get('/', [AdminAvisController::class, 'index'])->name('index');
        Route::get('{avis}', [AdminAvisController::class, 'show'])->name('show');
        Route::post('{avis}/delete', [AdminAvisController::class, 'delete'])->name('delete');
        Route::post('{avis}/restore', [AdminAvisController::class, 'restore'])->name('restore');
        Route::get('inappropriate/list', [AdminAvisController::class, 'inappropriate'])->name('inappropriate');
    });

    // Gestion des messages signalés
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/flagged/list', [AdminMessageController::class, 'flagged'])->name('flagged');
        Route::get('/', [AdminMessageController::class, 'index'])->name('index');
        Route::get('{message}', [AdminMessageController::class, 'show'])->name('show');
        Route::post('{message}/flag', [AdminMessageController::class, 'flag'])->name('flag');
        Route::post('{message}/delete', [AdminMessageController::class, 'delete'])->name('delete');
        Route::post('{message}/restore', [AdminMessageController::class, 'restore'])->name('restore');
        Route::post('{message}/dismiss-flag', [AdminMessageController::class, 'dismissFlag'])->name('dismiss-flag');
    });

    // Gestion des mots bannissants
    Route::prefix('banned-words')->name('banned-words.')->group(function () {
        Route::get('/', [AdminBannedWordController::class, 'index'])->name('index');
        Route::get('create', [AdminBannedWordController::class, 'create'])->name('create');
        Route::post('/', [AdminBannedWordController::class, 'store'])->name('store');
        Route::get('{word}/edit', [AdminBannedWordController::class, 'edit'])->name('edit');
        Route::put('{word}', [AdminBannedWordController::class, 'update'])->name('update');
        Route::delete('{word}', [AdminBannedWordController::class, 'destroy'])->name('destroy');
        Route::post('{word}/toggle', [AdminBannedWordController::class, 'toggle'])->name('toggle');
        Route::post('import', [AdminBannedWordController::class, 'import'])->name('import');
        Route::get('export', [AdminBannedWordController::class, 'export'])->name('export');
    });

    // Audit logs
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AdminAuditController::class, 'index'])->name('index');
        Route::get('{log}', [AdminAuditController::class, 'show'])->name('show');
        Route::get('admin/{admin}', [AdminAuditController::class, 'byAdmin'])->name('by-admin');
        Route::get('export', [AdminAuditController::class, 'export'])->name('export');
        Route::get('statistics', [AdminAuditController::class, 'stats'])->name('stats');
    });

    // Configuration
    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::get('/', [AdminConfigurationController::class, 'index'])->name('index');
        Route::post('update', [AdminConfigurationController::class, 'updateConfiguration'])->name('update');
        Route::get('delivery-zones', [AdminConfigurationController::class, 'manageDeliveryZones'])->name('delivery-zones');
        Route::post('delivery-zones/create', [AdminConfigurationController::class, 'createDeliveryZone'])->name('create-delivery-zone');
        Route::post('delivery-zones/{zone}/update', [AdminConfigurationController::class, 'updateDeliveryZone'])->name('update-delivery-zone');
        Route::post('delivery-zones/{zone}/delete', [AdminConfigurationController::class, 'deleteDeliveryZone'])->name('delete-delivery-zone');
        Route::post('delivery-zones/{zone}/toggle', [AdminConfigurationController::class, 'toggleDeliveryZone'])->name('toggle-delivery-zone');
    });

    // Rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('financial', [AdminReportController::class, 'financialReport'])->name('financial');
        Route::get('vendor-performance', [AdminReportController::class, 'vendorPerformanceReport'])->name('vendor-performance');
        Route::get('product-popularity', [AdminReportController::class, 'productPopularityReport'])->name('product-popularity');
        Route::get('user-activity', [AdminReportController::class, 'userActivityReport'])->name('user-activity');
        Route::get('stock-audit', [AdminReportController::class, 'stockAuditReport'])->name('stock-audit');
        Route::post('export', [AdminReportController::class, 'exportReport'])->name('export');
    });

    // Mode Visualisation Client
    Route::prefix('mode')->name('mode.')->group(function () {
        Route::post('client-enter', [\App\Http\Controllers\Admin\AdminModeController::class, 'enterClientMode'])->name('client-enter');
        Route::post('client-exit', [\App\Http\Controllers\Admin\AdminModeController::class, 'exitClientMode'])->name('client-exit');
        Route::get('status', [\App\Http\Controllers\Admin\AdminModeController::class, 'getStatus'])->name('status');
    });

    // Admin Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('edit', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('edit');
        Route::put('update', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('update');
    });

    // Admin Security Settings
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'index'])->name('index');
        Route::post('password', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'updatePassword'])->name('password');
        Route::post('2fa/enable', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'enableTwoFactor'])->name('2fa.enable');
        Route::post('2fa/disable', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'disableTwoFactor'])->name('2fa.disable');
        Route::get('sessions', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'getSessions'])->name('sessions');
        Route::post('sessions/revoke', [\App\Http\Controllers\Admin\AdminSecurityController::class, 'revokeSession'])->name('sessions.revoke');
    });

    // Admin Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('index');
        Route::post('update', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('update');
        Route::post('export-data', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'exportData'])->name('export-data');
        Route::get('audit-logs', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'getAuditLogs'])->name('audit-logs');
    });
});
