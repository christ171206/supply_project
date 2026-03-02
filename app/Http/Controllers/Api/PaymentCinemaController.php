<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentCinemaController extends Controller
{
    /**
     * Initialize a payment
     */
    public function initiate(Request $request): JsonResponse
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'payment_method' => 'required|in:wave,orange_money,mtn_money,moov_money,cash',
            'phone' => 'required_if:payment_method,wave,orange_money,mtn_money,moov_money|string',
            'quartier_id' => 'required|exists:quartiers,id',
            'adresse_livraison' => 'required|string|min:5',
            'telephone_livraison' => 'required|string|regex:/^[0-9]{10}$/',
        ]);

        $commande = Commande::findOrFail($request->commande_id);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Mettre à jour les informations de livraison
        $commande->update([
            'quartier_id' => $request->quartier_id,
            'adresse_livraison' => $request->adresse_livraison,
            'telephone_livraison' => $request->telephone_livraison,
            'adresse_detail' => $request->input('adresse_detail', null),
        ]);

        // Vérifier qu'il n'y a pas de paiement en cours
        $existingPayment = Payment::where('commande_id', $commande->id)
            ->whereIn('payment_status', ['initialisee', 'en_attente'])
            ->first();

        if ($existingPayment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Un paiement est déjà en cours pour cette commande',
                'payment_code' => $existingPayment->payment_code,
            ]);
        }

        // Créer ou mettre à jour le paiement
        $payment = Payment::updateOrCreate(
            ['commande_id' => $commande->id],
            [
                'montant' => $commande->total,
                'typePayement' => $request->payment_method,
                'payment_code' => 'PAY-' . Str::upper(Str::random(12)),
                'payment_status' => 'initialisee',
                'payment_initiated_at' => now(),
            ]
        );

        // Simuler l'appel au provider de paiement
        $paymentData = $this->initializePaymentWithProvider(
            $payment,
            $request->payment_method,
            $request->phone ?? null,
            $commande
        );

        if (!$paymentData['success']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'initialisation du paiement',
                'error' => $paymentData['error'],
            ], 400);
        }

        // Mettre à jour le paiement avec les données du provider
        $payment->update([
            'provider_transaction_id' => $paymentData['transaction_id'],
            'response_data' => json_encode($paymentData),
            'payment_status' => 'en_attente',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Paiement initialisé avec succès',
            'payment_code' => $payment->payment_code,
            'data' => [
                'payment_id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'amount' => $payment->montant,
                'currency' => 'XOF',
                'payment_method' => $request->payment_method,
                'status' => 'en_attente',
                'provider_reference' => $paymentData['transaction_id'],
                'redirect_url' => $paymentData['redirect_url'] ?? null,
                'polling_required' => true,
                'polling_interval' => 5000, // 5 secondes
                'max_polling_attempts' => 120, // 10 minutes max
            ],
        ]);
    }

    /**
     * Confirm a payment
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_code' => 'required|string|exists:payments,payment_code',
        ]);

        $payment = Payment::where('payment_code', $request->payment_code)->firstOrFail();

        // Vérifier que l'utilisateur est propriétaire
        $commande = $payment->commande;
        if ($commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Vérifier le statut du paiement avec le provider
        $providerStatus = $this->checkPaymentStatusWithProvider($payment);

        if ($providerStatus['success']) {
            // Mettre à jour le paiement
            $payment->update([
                'payment_status' => 'confirmee',
                'payment_confirmed_at' => now(),
            ]);

            // Mettre à jour la commande
            $commande->update([
                'paiement_confirme' => true,
                'statut' => 'confirmee',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Paiement confirmé avec succès',
                'data' => [
                    'payment_code' => $payment->payment_code,
                    'status' => 'confirmee',
                    'commande_id' => $commande->id,
                    'amount' => $payment->montant,
                    'confirmed_at' => $payment->payment_confirmed_at,
                ],
            ]);
        } else {
            $payment->update([
                'payment_status' => 'echouee',
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Paiement échoué',
                'error' => $providerStatus['error'],
            ], 400);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'payment_code' => 'required|string|exists:payments,payment_code',
        ]);

        $payment = Payment::where('payment_code', $request->payment_code)->firstOrFail();

        // Vérifier que l'utilisateur est propriétaire
        if ($payment->commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Vérifier le statut avec le provider
        $providerStatus = $this->checkPaymentStatusWithProvider($payment);

        // Mettre à jour le statut si nécessaire
        if ($providerStatus['success'] && $payment->payment_status !== 'confirmee') {
            $payment->update([
                'payment_status' => 'confirmee',
                'payment_confirmed_at' => now(),
            ]);

            $payment->commande->update([
                'paiement_confirme' => true,
                'statut' => 'confirmee',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'payment_code' => $payment->payment_code,
                'status' => $payment->payment_status,
                'amount' => $payment->montant,
                'payment_method' => $payment->typePayement,
                'initiated_at' => $payment->payment_initiated_at,
                'confirmed_at' => $payment->payment_confirmed_at,
                'is_confirmed' => $payment->payment_status === 'confirmee',
            ],
        ]);
    }

    /**
     * Cancel a payment
     */
    public function cancel(Request $request): JsonResponse
    {
        $request->validate([
            'payment_code' => 'required|string|exists:payments,payment_code',
        ]);

        $payment = Payment::where('payment_code', $request->payment_code)->firstOrFail();

        // Vérifier que l'utilisateur est propriétaire
        if ($payment->commande->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 403);
        }

        // Vérifier que le paiement peut être annulé
        if (!in_array($payment->payment_status, ['initialisee', 'en_attente'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce paiement ne peut pas être annulé',
            ], 400);
        }

        // Annuler auprès du provider
        $this->cancelPaymentWithProvider($payment);

        // Mettre à jour le paiement
        $payment->update([
            'payment_status' => 'annulee',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Paiement annulé avec succès',
            'data' => [
                'payment_code' => $payment->payment_code,
                'status' => 'annulee',
            ],
        ]);
    }

    /**
     * Get payment history for a user
     */
    public function history(): JsonResponse
    {
        $payments = Payment::whereHas('commande', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('commande')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $payments->map(fn($p) => [
                'payment_code' => $p->payment_code,
                'amount' => $p->montant,
                'payment_method' => $p->typePayement,
                'status' => $p->payment_status,
                'commande_id' => $p->commande_id,
                'initiated_at' => $p->payment_initiated_at,
                'confirmed_at' => $p->payment_confirmed_at,
            ]),
        ]);
    }

    // ==================== Private Methods ====================

    /**
     * Initialize payment with the actual provider
     */
    private function initializePaymentWithProvider($payment, $method, $phone = null, $commande = null): array
    {
        // Ici vous intégrerez avec les vrais providers:
        // - Wave (https://wave.co/docs)
        // - Orange Money (API Orange Money CI)
        // - MTN Money (API MTN CI)
        // - Moov Money (API Moov Money CI)

        // Pour maintenant, nous simulerons une réponse de succès
        return [
            'success' => true,
            'transaction_id' => 'TRANS-' . Str::upper(Str::random(16)),
            'provider_name' => $method,
            'redirect_url' => null, // À ajouter selon le provider
            'status' => 'pending',
        ];
    }

    /**
     * Check payment status with the provider
     */
    private function checkPaymentStatusWithProvider($payment): array
    {
        // Ici vous intégrerez avec les vrais providers pour vérifier le statut

        // Pour la démo, simuler une confirmation après un délai
        if ($payment->payment_status === 'en_attente') {
            // Vous pouvez ajouter de la logique pour simuler les confirmations
            return [
                'success' => false, // Pas encore confirmé
                'error' => 'Le paiement est en attente',
            ];
        }

        return [
            'success' => true,
        ];
    }

    /**
     * Cancel payment with the provider
     */
    private function cancelPaymentWithProvider($payment): void
    {
        // Ici vous intégrerez avec les vrais providers pour annuler le paiement
    }

    /**
     * Webhook de notification de paiement CinetPay
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook paiement reçu', $request->all());

        try {
            $transaction_id = $request->input('transaction_id');
            $status = $request->input('status');

            // Trouver le paiement correspondant
            $payment = Payment::where('payment_code', $transaction_id)->first();

            if (!$payment) {
                Log::warning('Paiement non trouvé pour transaction_id: ' . $transaction_id);
                return response()->json(['status' => 'error', 'message' => 'Paiement non trouvé'], 404);
            }

            // Mettre à jour le statut du paiement
            if ($status === 'completed' || $status === 'SUCCESS') {
                $payment->update([
                    'payment_status' => 'CONFIRMÉE',
                    'payment_confirmed_at' => now(),
                ]);

                // Mettre à jour le statut de la commande
                $payment->commande->update(['statut' => 'confirmée']);

                Log::info('Paiement confirmé', ['payment_id' => $payment->id, 'commande_id' => $payment->commande_id]);
            } else {
                $payment->update(['payment_status' => 'ÉCHOUÉE']);
                $payment->commande->update(['statut' => 'paiement_échoué']);

                Log::warning('Paiement échoué', ['payment_id' => $payment->id, 'status' => $status]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Erreur webhook paiement: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
