<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;
use Stripe\Exception\InvalidRequestException;

class StripePaymentService
{
    private $secretKey;
    private $publicKey;
    private $baseUrl = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->publicKey = config('services.stripe.public');

        if (!$this->secretKey) {
            throw new Exception('Stripe secret key not configured');
        }
    }

    /**
     * Créer un PaymentIntent
     * https://stripe.com/docs/api/payment_intents/create
     */
    public function createPaymentIntent(array $data)
    {
        try {
            // Idempotency key pour éviter les doublons
            $idempotencyKey = $data['idempotency_key'] ?? Str::uuid();

            $payload = [
                'amount' => (int)($data['amount'] * 100), // Centimes
                'currency' => $data['currency'] ?? 'eur',
                'payment_method_types[]' => 'card',
                'description' => $data['description'] ?? 'Commande Supply',
                'metadata[order_id]' => $data['order_id'] ?? '',
                'metadata[user_id]' => $data['user_id'] ?? '',
                'metadata[customer_email]' => $data['customer_email'] ?? '',
                'customer' => $data['customer_id'] ?? '', // Optionnel
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'Idempotency-Key' => $idempotencyKey,
                ])
                ->post($this->baseUrl . '/payment_intents', $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Unknown error',
                    'status' => $response->status(),
                ];
            }

            return [
                'success' => true,
                'payment_intent' => $response->json(),
                'client_secret' => $response->json()['client_secret'],
                'intent_id' => $response->json()['id'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirmer un PaymentIntent
     * Généralement fait côté frontend avec Stripe.js
     */
    public function confirmPaymentIntent($intentId, $paymentMethodId)
    {
        try {
            $payload = [
                'payment_method' => $paymentMethodId,
                'use_stripe_sdk' => 'true',
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents/{$intentId}/confirm", $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Confirmation failed',
                ];
            }

            return [
                'success' => true,
                'status' => $response->json()['status'],
                'payment_intent' => $response->json(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Récupérer le statut d'un PaymentIntent
     */
    public function getPaymentIntentStatus($intentId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/payment_intents/{$intentId}");

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => 'Failed to fetch payment intent',
                ];
            }

            $data = $response->json();
            return [
                'success' => true,
                'status' => $data['status'],
                'charge_id' => $data['charges']['data'][0]['id'] ?? null,
                'payment_intent' => $data,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Valider la signature d'un webhook Stripe
     * https://stripe.com/docs/webhooks/signatures
     */
    public function verifyWebhookSignature($payload, $signature, $secret)
    {
        try {
            // Créer le signed content
            [$timestamp, $version, $hash] = explode(',', str_replace('t=', '', str_replace('v1,', '', $signature)));

            // Créer le message signé
            $signed_content = "{$timestamp}.{$payload}";

            // Générer le hash HMAC
            $computed_hash = hash_hmac('sha256', $signed_content, $secret);

            // Comparer (timing-safe)
            return hash_equals($hash, $computed_hash);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Annuler un PaymentIntent
     */
    public function cancelPaymentIntent($intentId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents/{$intentId}/cancel", []);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Cancellation failed',
                ];
            }

            return [
                'success' => true,
                'status' => $response->json()['status'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Récupérer la clé publique (pour le frontend)
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
}
