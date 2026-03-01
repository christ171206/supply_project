<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class PaymentService
{
    private $apiKey;
    private $merchantId;
    private $baseUrl = 'https://api.cinetpay.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.cinetpay.api_key');
        $this->merchantId = config('services.cinetpay.merchant_id');
    }

    /**
     * Créer une transaction de paiement
     */
    public function createPayment(array $data)
    {
        try {
            $payload = [
                'apikey' => $this->apiKey,
                'site_id' => $this->merchantId,
                'transaction_id' => $data['transaction_id'] ?? uniqid('ORDER_', true),
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'XOF',
                'description' => $data['description'],
                'return_url' => $data['return_url'] ?? route('commandes.payment-success'),
                'notify_url' => $data['notify_url'] ?? route('api.payment-webhook'),
                'customer_name' => $data['customer_name'] ?? '',
                'customer_email' => $data['customer_email'] ?? '',
                'customer_phone_number' => $data['customer_phone'] ?? '',
                'channels' => 'ALL', // Permet tous les canaux (Wave, Orange Money, MTN, Moov, etc.)
            ];

            $response = Http::post($this->baseUrl . '/payment', $payload);

            return $response->json();
        } catch (Exception $e) {
            return [
                'code' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $response = Http::post($this->baseUrl . '/payment/check', [
                'apikey' => $this->apiKey,
                'site_id' => $this->merchantId,
                'transaction_id' => $transactionId,
            ]);

            return $response->json();
        } catch (Exception $e) {
            return [
                'code' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtenir l'URL de paiement
     */
    public function getPaymentUrl($transactionId)
    {
        return $this->baseUrl . '/payment?' . http_build_query([
            'transaction_id' => $transactionId,
            'apikey' => $this->apiKey,
        ]);
    }

    /**
     * Wave API Integration
     */
    public function createWavePayment(array $data)
    {
        try {
            // Wave n'a pas d'API de paiement directe, utiliser Wave via CinetPay
            return $this->createPayment(array_merge($data, [
                'channel' => 'WAVE',
            ]));
        } catch (Exception $e) {
            return [
                'code' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Orange Money API Integration
     */
    public function createOrangeMoneyPayment(array $data)
    {
        try {
            return $this->createPayment(array_merge($data, [
                'channel' => 'ORANGE_MONEY',
            ]));
        } catch (Exception $e) {
            return [
                'code' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Mobile Money Wallet Payment (MTN, Moov, etc.)
     */
    public function createMobileMoneyPayment(array $data)
    {
        try {
            return $this->createPayment(array_merge($data, [
                'channel' => 'MOMO',
            ]));
        } catch (Exception $e) {
            return [
                'code' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
