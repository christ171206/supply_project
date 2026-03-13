<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PWA Status check endpoint
Route::get('/api/pwa/status', function () {
    $iconsDir = public_path('icons');

    $icons = [];
    if (is_dir($iconsDir)) {
        foreach (scandir($iconsDir) as $file) {
            if ($file !== '.' && $file !== '..') {
                $icons[] = $file;
            }
        }
    }

    return response()->json([
        'pwa' => [
            'manifest' => file_exists(public_path('manifest.json')) ? 'OK' : 'MISSING',
            'service_worker' => file_exists(public_path('service-worker.js')) ? 'OK' : 'MISSING',
            'offline_page' => file_exists(public_path('offline.html')) ? 'OK' : 'MISSING',
            'icons_present' => count($icons),
            'icons' => $icons,
        ],
        'meta_tags' => [
            'theme_color' => '#0a0a0a',
            'viewport_fit' => 'cover',
            'app_name' => 'Supply',
            'display_mode' => 'standalone',
        ],
        'status' => 'ready',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('pwa.status');

// Health check for PWA installations
Route::get('/health/pwa', function () {
    $checks = [
        'manifest_accessible' => @file_get_contents('/manifest.json') !== false,
        'service_worker_accessible' => @file_get_contents('/service-worker.js') !== false,
        'offline_page_accessible' => @file_get_contents('/offline.html') !== false,
        'icons_present' => count(array_diff(scandir(public_path('icons')), ['.', '..'])) > 0,
    ];

    $all_pass = array_all($checks);

    return response()->json([
        'pwa_healthy' => $all_pass,
        'checks' => $checks,
        'timestamp' => now()->toIso8601String(),
    ], $all_pass ? 200 : 500);
})->name('health.pwa');
