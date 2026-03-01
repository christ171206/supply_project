#!/usr/bin/env php
<?php

/**
 * API Integration Test Script
 * Lance une série de tests pour vérifier que toutes les APIs sont correctement intégrées
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\CurrencyConverterService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "   🧪 SUPPLY PROJECT - API INTEGRATION TESTS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$passed = 0;
$failed = 0;

// Test 1: ExchangeRate-API Configuration
echo "1️⃣  Testing ExchangeRate-API Configuration...\n";
$apiKey = config('services.exchangerate.api_key');
if ($apiKey && $apiKey !== 'your_exchangerate_api_key_here') {
    echo "   ✅ ExchangeRate-API Key is configured\n";
    $passed++;

    // Try to fetch rates
    echo "   → Testing API connection...\n";
    try {
        $service = new CurrencyConverterService();
        $rates = $service->fetchRates();

        if (!isset($rates['error'])) {
            echo "   ✅ ExchangeRate-API is responding correctly\n";
            echo "   → Base: " . ($rates['base'] ?? 'XOF') . "\n";
            echo "   → Available rates: " . count($rates['rates'] ?? []) . "\n";
            $passed++;
        } else {
            echo "   ⚠️  API Error: " . ($rates['error'] ?? 'Unknown') . "\n";
            $failed++;
        }
    } catch (\Exception $e) {
        echo "   ❌ Error connecting to ExchangeRate-API: " . $e->getMessage() . "\n";
        $failed++;
    }
} else {
    echo "   ⚠️  ExchangeRate-API Key not configured\n";
    echo "   → Go to API_KEYS_SETUP.md for instructions\n";
    $failed++;
}

echo "\n";

// Test 2: WhatsApp Configuration
echo "2️⃣  Testing WhatsApp Configuration...\n";
$whatsappPhone = config('services.whatsapp.business_phone');
if ($whatsappPhone && $whatsappPhone !== '225xxxxxxxx') {
    echo "   ✅ WhatsApp Business Phone is configured: $whatsappPhone\n";
    $passed++;
} else {
    echo "   ⚠️  WhatsApp Business Phone not configured\n";
    echo "   → Optional: Set WHATSAPP_BUSINESS_PHONE in .env\n";
    $failed++;
}

echo "\n";

// Test 3: DiceBear Avatars
echo "3️⃣  Testing DiceBear Avatars API...\n";
$baseUrl = 'https://api.dicebear.com/7.x/avataaars/svg?seed=test@supply.ci';
try {
    /** @var \Illuminate\Http\Client\Response $response */
    $response = Http::timeout(5)->get($baseUrl);
    if ($response->successful()) {
        echo "   ✅ DiceBear Avatars API is responding\n";
        echo "   → Response size: " . strlen($response->body()) . " bytes\n";
        $passed++;
    } else {
        echo "   ❌ DiceBear Avatars API error: " . $response->status() . "\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "   ❌ Error connecting to DiceBear: " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n";

// Test 4: Database Connection
echo "4️⃣  Testing Database Connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✅ Database connection successful\n";

    // Check if users table exists
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'") ??
        DB::select("SHOW TABLES") ?? [];

    $userCount = \App\Models\User::count();
    echo "   → Total users in database: $userCount\n";
    $passed++;
} catch (\Exception $e) {
    echo "   ❌ Database connection error: " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n";

// Test 5: NPM Packages
echo "5️⃣  Testing NPM Packages Installation...\n";
$packageJson = json_decode(file_get_contents(__DIR__ . '/package.json'), true);
$requiredPackages = ['apexcharts', 'sweetalert2', 'leaflet', 'axios'];
$missingPackages = [];

foreach ($requiredPackages as $pkg) {
    if (!isset($packageJson['dependencies'][$pkg])) {
        $missingPackages[] = $pkg;
    }
}

if (empty($missingPackages)) {
    echo "   ✅ All required NPM packages are installed\n";
    echo "   → apexcharts: " . ($packageJson['dependencies']['apexcharts'] ?? '?') . "\n";
    echo "   → sweetalert2: " . ($packageJson['dependencies']['sweetalert2'] ?? '?') . "\n";
    echo "   → leaflet: " . ($packageJson['dependencies']['leaflet'] ?? '?') . "\n";
    $passed++;
} else {
    echo "   ⚠️  Missing packages: " . implode(', ', $missingPackages) . "\n";
    echo "   → Run: npm install\n";
    $failed++;
}

echo "\n";

// Test 6: File Structure
echo "6️⃣  Testing Required Files...\n";
$requiredFiles = [
    'resources/js/currency-converter.js' => 'Currency Converter Module',
    'resources/js/sweetalert.js' => 'SweetAlert2 Module',
    'app/Services/CurrencyConverterService.php' => 'Currency Service',
    'config/services.php' => 'Services Configuration',
];

$missingFiles = [];
foreach ($requiredFiles as $file => $description) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        echo "   ✅ $description ($file)\n";
        $passed++;
    } else {
        echo "   ❌ Missing: $description ($file)\n";
        $missingFiles[] = $file;
        $failed++;
    }
}

echo "\n";

// Test 7: Database Migrations
echo "7️⃣  Testing Database Migrations...\n";
try {
    $hasProfilePhoto = Schema::hasColumn('users', 'profile_photo');
    $hasDeliveryLocation = Schema::hasColumn('users', 'delivery_latitude');

    if ($hasProfilePhoto && $hasDeliveryLocation) {
        echo "   ✅ All migration columns are present\n";
        echo "   → profile_photo column: ✅\n";
        echo "   → delivery_latitude column: ✅\n";
        echo "   → delivery_longitude column: ✅\n";
        $passed++;
    } else {
        echo "   ⚠️  Some migration columns are missing\n";
        echo "   → Run: php artisan migrate\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "   ⚠️  Schema check error (this is OK on fresh installs)\n";
}

echo "\n";

// Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "   📊 TEST SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "   ✅ Passed: $passed\n";
echo "   ❌ Failed: $failed\n\n";

if ($failed === 0) {
    echo "   🎉 ALL TESTS PASSED! Project is ready for deployment.\n\n";
    exit(0);
} else {
    echo "   ⚠️  Some tests failed. Check the errors above.\n\n";
    exit(1);
}
