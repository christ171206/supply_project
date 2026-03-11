<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\DeliveryLocationController;
use App\Http\Controllers\Api\PaymentCinemaController;
use App\Http\Controllers\Api\OrderValidationController;
use App\Http\Controllers\RealtimeNotificationController;
use Illuminate\Support\Facades\Route;

// Routes de localisation Côte d'Ivoire (publiques)
Route::prefix('locations')->group(function () {
    Route::get('regions', [LocationController::class, 'getRegions']);
    Route::get('regions/{regionId}/districts', [LocationController::class, 'getDistrictsByRegion']);
    Route::get('districts/{districtId}/communes', [LocationController::class, 'getCommunesByDistrict']);
    Route::get('communes/{communeId}/quartiers', [LocationController::class, 'getQuartiersByCommune']);
    Route::get('search', [LocationController::class, 'searchLocations']);
});

// Routes de livraison (publiques)
Route::prefix('delivery-locations')->group(function () {
    Route::get('regions', [DeliveryLocationController::class, 'getRegions']);
    Route::get('regions/{region}/districts', [DeliveryLocationController::class, 'getDistricts']);
    Route::get('districts/{district}/communes', [DeliveryLocationController::class, 'getCommunes']);
    Route::get('communes/{commune}/quartiers', [DeliveryLocationController::class, 'getQuartiers']);
    Route::get('search', [DeliveryLocationController::class, 'search']);
});

// Routes de validation et préparation de commande (authentifiées)
Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::post('validate-and-prepare', [OrderValidationController::class, 'validateAndPrepare']);
    Route::get('{commande}', [OrderValidationController::class, 'getOrderDetails']);
    Route::put('{commande}/delivery-location', [OrderValidationController::class, 'updateDeliveryLocation']);
});

// Routes de paiement (authentifiées)
Route::middleware('auth:sanctum')->prefix('payments')->group(function () {
    Route::post('initiate', [PaymentCinemaController::class, 'initiate']);
    Route::post('confirm', [PaymentCinemaController::class, 'confirm']);
    Route::post('check-status', [PaymentCinemaController::class, 'checkStatus']);
    Route::post('cancel', [PaymentCinemaController::class, 'cancel']);
    Route::get('history', [PaymentCinemaController::class, 'history']);
});

// Routes Pusher Notifications en temps réel (authentifiées)
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('init', [RealtimeNotificationController::class, 'init']);
    Route::get('/', [RealtimeNotificationController::class, 'index']);
    Route::get('sound', [RealtimeNotificationController::class, 'sound']);
    Route::post('test', [RealtimeNotificationController::class, 'test']);
});

// Webhook de notification de paiement (publique)
Route::post('payment-webhook', [PaymentCinemaController::class, 'webhook'])->name('payment-webhook');
