<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePWAIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:generate-icons {--source-image= : Path to source image for icons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PWA icons from source image or create placeholder icons';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating PWA icons...');

        $iconsDir = public_path('icons');
        $sourceImage = $this->option('source-image') ?? resource_path('images/logo.png');

        // Check if GD is available
        if (!extension_loaded('gd')) {
            $this->error('GD extension is not installed. Please install php-gd extension.');
            $this->info('For Windows WAMP: Uncomment extension=gd in php.ini');
            return 1;
        }

        // Icon sizes needed
        $sizes = [192, 256, 512];

        if (file_exists($sourceImage)) {
            $this->generateFromImage($sourceImage, $iconsDir, $sizes);
        } else {
            $this->generatePlaceholderIcons($iconsDir, $sizes);
        }

        // Generate maskable versions
        $this->generateMaskableIcons($iconsDir, $sizes);

        // Generate apple touch icon
        $this->generateAppleTouchIcon($iconsDir);

        // Generate badges
        $this->generateBadges($iconsDir);

        $this->info('✓ PWA icons generated successfully!');
        $this->info('Icons are ready in: ' . $iconsDir);

        return 0;
    }

    /**
     * Generate icons from source image
     */
    private function generateFromImage(string $sourcePath, string $outputDir, array $sizes): void
    {
        $this->line('Loading source image: ' . $sourcePath);

        $sourceImage = imagecreatefrompng($sourcePath);
        if (!$sourceImage) {
            $this->warn('Could not load source image, generating placeholder icons instead');
            $this->generatePlaceholderIcons($outputDir, $sizes);
            return;
        }

        foreach ($sizes as $size) {
            $this->line("Generating icon {$size}x{$size}...");

            $resized = imagecreatetruecolor($size, $size);

            // Preserve transparency
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefill($resized, 0, 0, $transparent);

            imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $size, $size, imagesx($sourceImage), imagesy($sourceImage));

            imagepng($resized, $outputDir . "/icon-{$size}x{$size}.png");
            imagedestroy($resized);

            $this->info("  ✓ icon-{$size}x{$size}.png");
        }

        imagedestroy($sourceImage);
    }

    /**
     * Generate placeholder icons with text
     */
    private function generatePlaceholderIcons(string $outputDir, array $sizes): void
    {
        $this->line('Generating placeholder icons...');

        foreach ($sizes as $size) {
            $this->line("Generating placeholder {$size}x{$size}...");

            $image = imagecreatetruecolor($size, $size);

            // Colors
            $black = imagecolorallocate($image, 10, 10, 10);      // #0a0a0a
            $white = imagecolorallocate($image, 255, 255, 255);    // #ffffff
            $gray = imagecolorallocate($image, 112, 112, 112);     // #707070

            // Background
            imagefilledrectangle($image, 0, 0, $size, $size, $white);

            // Border
            imagerectangle($image, 0, 0, $size - 1, $size - 1, $black);

            // Draw simple design - large 'S' for Supply using imagestring
            $fontSize = $size > 256 ? 5 : 4;
            $text = 'S';

            // Calculate position for centered text
            $stringWidth = imagefontwidth($fontSize) * strlen($text);
            $stringHeight = imagefontheight($fontSize);
            $x = ($size - $stringWidth) / 2;
            $y = ($size - $stringHeight) / 2;

            // Draw text
            imagestring($image, $fontSize, (int)$x, (int)$y, $text, $black);

            imagepng($image, $outputDir . "/icon-{$size}x{$size}.png");
            imagedestroy($image);

            $this->info("  ✓ icon-{$size}x{$size}.png");
        }
    }

    /**
     * Generate maskable icons (for Android adaptive icons)
     */
    private function generateMaskableIcons(string $outputDir, array $sizes): void
    {
        $this->line('Generating maskable icons...');

        foreach ($sizes as $size) {
            $this->line("Generating maskable {$size}x{$size}...");

            // Read the regular icon
            $source = imagecreatefrompng($outputDir . "/icon-{$size}x{$size}.png");

            if (!$source) {
                $this->warn("Could not read icon-{$size}x{$size}.png");
                continue;
            }

            $maskable = imagecreatetruecolor($size, $size);
            imagealphablending($maskable, false);
            imagesavealpha($maskable, true);

            // Create maskable version with padding
            $white = imagecolorallocate($maskable, 255, 255, 255);
            imagefill($maskable, 0, 0, $white);

            // Copy source with slight modifications for safe zone
            $padding = (int)($size * 0.1); // 10% padding
            imagecopyresampled(
                $maskable,
                $source,
                $padding,
                $padding,
                0,
                0,
                $size - ($padding * 2),
                $size - ($padding * 2),
                imagesx($source),
                imagesy($source)
            );

            imagepng($maskable, $outputDir . "/icon-{$size}x{$size}-maskable.png");
            imagedestroy($maskable);
            imagedestroy($source);

            $this->info("  ✓ icon-{$size}x{$size}-maskable.png");
        }
    }

    /**
     * Generate Apple touch icons
     */
    private function generateAppleTouchIcon(string $outputDir): void
    {
        $this->line('Generating Apple touch icon...');

        $sourceFile = $outputDir . '/icon-192x192.png';
        if (file_exists($sourceFile)) {
            $source = imagecreatefrompng($sourceFile);
            $apple = imagecreatetruecolor(180, 180);

            imagecopyresampled($apple, $source, 0, 0, 0, 0, 180, 180, 192, 192);
            imagepng($apple, $outputDir . '/apple-touch-icon-180x180.png');

            imagedestroy($apple);
            imagedestroy($source);

            $this->info('  ✓ apple-touch-icon-180x180.png');
        }
    }

    /**
     * Generate badge icons for notifications
     */
    private function generateBadges(string $outputDir): void
    {
        $this->line('Generating badge icons...');

        $sizes = [72, 96, 128];

        foreach ($sizes as $size) {
            $image = imagecreatetruecolor($size, $size);

            $black = imagecolorallocate($image, 10, 10, 10);
            $white = imagecolorallocate($image, 255, 255, 255);

            // White background with black border
            imagefilledrectangle($image, 0, 0, $size, $size, $white);
            imagerectangle($image, 0, 0, $size - 1, $size - 1, $black);

            // Simple circle in center
            $center = $size / 2;
            $radius = $size / 3;
            imagefilledarc($image, $center, $center, $radius * 2, $radius * 2, 0, 360, $black, IMG_ARC_PIE);

            imagepng($image, $outputDir . "/badge-{$size}x{$size}.png");
            imagedestroy($image);

            $this->info("  ✓ badge-{$size}x{$size}.png");
        }
    }
}
