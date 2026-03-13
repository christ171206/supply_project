<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CheckPWASetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:check-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify PWA setup and generate a status report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== PWA Setup Checker ===');
        $this->newLine();

        $checks = [];

        // Check manifest.json
        $this->line('Checking manifest.json...');
        $manifestPath = public_path('manifest.json');
        $manifest = $this->checkFileAndValidate($manifestPath, 'json');
        $checks['manifest'] = $manifest;
        $this->printCheck('Manifest', $manifest['valid']);

        // Check service-worker.js
        $this->line('Checking service-worker.js...');
        $swPath = public_path('service-worker.js');
        $sw = $this->checkFileAndValidate($swPath, 'javascript');
        $checks['service_worker'] = $sw;
        $this->printCheck('Service Worker', $sw['valid']);

        // Check offline.html
        $this->line('Checking offline.html...');
        $offlinePath = public_path('offline.html');
        $offline = $this->checkFileAndValidate($offlinePath, 'html');
        $checks['offline_page'] = $offline;
        $this->printCheck('Offline Page', $offline['valid']);

        // Check icons
        $this->line('Checking icons directory...');
        $iconsDir = public_path('icons');
        $icons = $this->checkIconsDirectory($iconsDir);
        $checks['icons'] = $icons;
        $this->printCheck('Icons Directory', $icons['valid']);

        // Check layouts
        $this->line('Checking layout templates...');
        $layouts = $this->checkLayouts();
        $checks['layouts'] = $layouts;
        $this->printCheck('Layout Meta Tags', $layouts['valid']);

        // Check routes
        $this->line('Checking routes...');
        $routes = $this->checkRoutes();
        $checks['routes'] = $routes;
        $this->printCheck('PWA Routes', $routes['valid']);

        // Check app.js
        $this->line('Checking app.js...');
        $appJs = $this->checkAppJs();
        $checks['app_js'] = $appJs;
        $this->printCheck('App.js PWA Import', $appJs['valid']);

        $this->newLine();

        // Summary
        $allValid = array_every($checks, fn($check) => $check['valid']);

        if ($allValid) {
            $this->info('✓ PWA Setup is complete and ready!');
        } else {
            $this->error('✗ Some issues detected. See details above.');
        }

        // Generate report
        $this->newLine();
        $this->info('=== Detailed Report ===');
        $this->table(
            ['Component', 'Status', 'Details'],
            array_map(function ($key, $check) {
                return [
                    ucfirst(str_replace('_', ' ', $key)),
                    $check['valid'] ? '✓ OK' : '✗ FAIL',
                    $check['message'] ?? ''
                ];
            }, array_keys($checks), $checks)
        );

        $this->newLine();
        $this->info('For more info, read: PWA_SETUP.md and PWA_IMPLEMENTATION.md');

        return $allValid ? 0 : 1;
    }

    protected function checkFileAndValidate(string $path, string $type): array
    {
        if (!file_exists($path)) {
            return [
                'valid' => false,
                'message' => 'File not found',
                'size' => 0
            ];
        }

        $size = filesize($path);
        $valid = $size > 0;

        return [
            'valid' => $valid,
            'message' => "File exists (" . $this->formatBytes($size) . ")",
            'size' => $size
        ];
    }

    protected function checkIconsDirectory(string $path): array
    {
        if (!is_dir($path)) {
            return [
                'valid' => false,
                'message' => 'Icons directory not found'
            ];
        }

        $required = [
            'icon-192x192.png',
            'icon-192x192-maskable.png',
            'icon-256x256.png',
            'icon-512x512.png',
            'icon-512x512-maskable.png',
            'apple-touch-icon-180x180.png'
        ];

        $files = array_diff(scandir($path), ['.', '..']);
        $missing = array_diff($required, $files);

        if (empty($missing)) {
            return [
                'valid' => true,
                'message' => count($files) . ' icons found (all required present)'
            ];
        }

        return [
            'valid' => false,
            'message' => 'Missing: ' . implode(', ', $missing)
        ];
    }

    protected function checkLayouts(): array
    {
        $required_meta_tags = [
            'rel="manifest"',
            'name="theme-color"',
            'name="apple-mobile-web-app-capable"'
        ];

        $appLayout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $missing = [];
        foreach ($required_meta_tags as $tag) {
            if (strpos($appLayout, $tag) === false) {
                $missing[] = $tag;
            }
        }

        if (empty($missing)) {
            return [
                'valid' => true,
                'message' => 'All PWA meta tags present in layouts'
            ];
        }

        return [
            'valid' => false,
            'message' => 'Missing meta tags: ' . implode(', ', $missing)
        ];
    }

    protected function checkRoutes(): array
    {
        $webRoutes = file_get_contents(base_path('routes/web.php'));
        $apiRoutes = file_get_contents(base_path('routes/api.php'));

        $hasPwaRoute = strpos($webRoutes, 'pwa-install') !== false;
        $hasApiStatus = strpos($apiRoutes, 'pwa/status') !== false;

        if ($hasPwaRoute && $hasApiStatus) {
            return [
                'valid' => true,
                'message' => 'PWA routes registered'
            ];
        }

        return [
            'valid' => false,
            'message' => 'PWA routes missing'
        ];
    }

    protected function checkAppJs(): array
    {
        $appJs = file_get_contents(resource_path('js/app.js'));

        if (strpos($appJs, 'pwa.js') !== false && strpos($appJs, 'requestNotificationPermission') !== false) {
            return [
                'valid' => true,
                'message' => 'PWA module properly imported and initialized'
            ];
        }

        return [
            'valid' => false,
            'message' => 'PWA module not properly configured in app.js'
        ];
    }

    protected function printCheck(string $name, bool $valid): void
    {
        $status = $valid ? '<fg=green>✓</>' : '<fg=red>✗</>';
        $this->line($status . ' ' . $name);
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

// Helper: array_every for PHP < 8.4
if (!function_exists('array_every')) {
    function array_every(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        return true;
    }
}
