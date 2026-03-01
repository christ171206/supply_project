<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyConverterService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.exchangerate-api.com/v4/latest';
    protected string $cacheKey = 'currency_rates';
    protected int $cacheDuration = 3600; // 1 heure

    public function __construct()
    {
        $this->apiKey = config('services.exchangerate.api_key');
    }

    /**
     * Récupère les taux de change depuis l'API
     */
    public function fetchRates(string $from = 'XOF'): array
    {
        // Vérifier le cache d'abord
        $cached = Cache::get($this->cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $response = Http::get("{$this->baseUrl}/{$from}", [
                'apikey' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $rates = [
                    'base' => $data['base'] ?? $from,
                    'rates' => $data['rates'] ?? [],
                    'timestamp' => now()->timestamp,
                ];

                // Mettre en cache pour 1 heure
                Cache::put($this->cacheKey, $rates, $this->cacheDuration);

                return $rates;
            }

            return ['error' => 'API request failed'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Convertit un montant d'une devise à une autre
     */
    public function convert(float $amount, string $from = 'XOF', string $to = 'EUR'): float
    {
        $rates = $this->fetchRates($from);

        if (isset($rates['error'])) {
            return 0;
        }

        return $amount * ($rates['rates'][$to] ?? 1);
    }

    /**
     * Formate un prix avec la bonne devise
     */
    public function formatPrice(float $amount, string $currency = 'XOF'): string
    {
        $symbols = [
            'XOF' => 'FCFA',
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        $formatted = number_format($amount, 2, ',', ' ');

        return "{$formatted} {$symbol}";
    }

    /**
     * Retourne les devises populaires
     */
    public function getPopularCurrencies(): array
    {
        return [
            'XOF' => 'FCFA (Côte d\'Ivoire)',
            'EUR' => 'Euro',
            'USD' => 'Dollar US',
            'GBP' => 'Livre Sterling',
        ];
    }

    /**
     * Vide le cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
