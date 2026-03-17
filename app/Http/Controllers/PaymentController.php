<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Payment;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
        $this->middleware('auth');
    }

    /**
     * Afficher la page de paiement Stripe
     * GET /commandes/{id}/payment
     */
    public function show($commandeId)
    {
        $commande = Commande::findOrFail($commandeId);

        // Vérifier l'accès
        if (auth()->user()->id !== $commande->user_id) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la commande n'est pas encore payée
        if ($commande->paiement_confirme) {
            return redirect()->route('commandes.show', $commande)->with('message', 'Cette commande est déjà payée');
        }

        // Récupérer le payment existant ou en créer un
        $payment = $commande->payment;
        if (!$payment) {
            $payment = Payment::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'payment_type' => 'stripe',
                'statut' => 'pending',
                'idempotency_key' => Str::uuid(),
            ]);
        }

        $stripePublicKey = $this->stripeService->getPublicKey();

        return view('commandes.payment', [
            'commande' => $commande,
            'payment' => $payment,
            'stripePublicKey' => $stripePublicKey,
            'clientSecret' => $payment->stripe_payment_intent_id ? null : session('stripe_client_secret'),
        ]);
    }

    /**
     * Créer un PaymentIntent
     * POST /payment/create-intent
     */
    public function createIntent(Request $request)
    {
        try {
            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'email' => 'required|email',
            ]);

            $commande = Commande::findOrFail($request->commande_id);

            // Vérifier l'accès
            if (auth()->user()->id !== $commande->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Utiliser la devise EUR pour Stripe (peut être configurée)
            $amount = $commande->total; // Montant en euros
            $currency = config('payment.stripe_currency', 'eur');

            $result = $this->stripeService->createPaymentIntent([
                'amount' => $amount,
                'currency' => $currency,
                'description' => "Commande #{$commande->id} - Supply Marketplace",
                'order_id' => $commande->id,
                'user_id' => auth()->id(),
                'customer_email' => $request->email,
                'idempotency_key' => $commande->payment?->idempotency_key ?? Str::uuid(),
            ]);

            if (!$result['success']) {
                Log::error('Stripe createPaymentIntent failed', $result);
                return response()->json(['error' => $result['error']], 400);
            }

            // Sauvegarder le PaymentIntent dans la base
            $payment = $commande->payment ?: Payment::create([
                'commande_id' => $commande->id,
                'montant' => $commande->total,
                'payment_type' => 'stripe',
                'statut' => 'pending',
            ]);

            $payment->update([
                'stripe_payment_intent_id' => $result['intent_id'],
                'stripe_response' => $result['payment_intent'],
                'stripe_status' => 'requires_payment_method',
            ]);

            Log::info('Stripe Payment Intent created', [
                'commande_id' => $commande->id,
                'intent_id' => $result['intent_id'],
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $result['client_secret'],
                'intentId' => $result['intent_id'],
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Intent creation error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirmer le paiement (appelé après Stripe.js confirmation)
     * POST /payment/confirm
     */
    public function confirm(Request $request)
    {
        try {
            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'stripe_payment_intent' => 'required',
            ]);

            $commande = Commande::findOrFail($request->commande_id);

            // Vérifier l'accès
            if (auth()->user()->id !== $commande->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Récupérer le statut du PaymentIntent depuis Stripe
            $result = $this->stripeService->getPaymentIntentStatus(
                $request->stripe_payment_intent
            );

            if (!$result['success']) {
                return response()->json(['error' => $result['error']], 400);
            }

            $payment = $commande->payment;
            $intentStatus = $result['status']; // 'succeeded', 'processing', etc.

            // Mettre à jour la base avec la réponse Stripe
            $payment->update([
                'stripe_status' => $intentStatus,
                'stripe_response' => $result['payment_intent'],
                'stripe_charge_id' => $result['charge_id'],
            ]);

            if ($intentStatus === 'succeeded') {
                // ✅ Paiement confirmé !
                $commande->update([
                    'paiement_confirme' => true,
                    'statut' => 'pending', // En attente de traitement vendeur
                ]);

                $payment->update([
                    'statut' => 'completed',
                ]);

                Log::info('Stripe payment succeeded', [
                    'commande_id' => $commande->id,
                    'payment_id' => $payment->idPayment,
                ]);

                return response()->json([
                    'success' => true,
                    'redirect' => route('commandes.payment-success', $commande),
                    'message' => 'Paiement confirmé',
                ]);
            } elseif ($intentStatus === 'processing') {
                // Paiement en cours de traitement
                return response()->json([
                    'success' => false,
                    'status' => 'processing',
                    'message' => 'Votre paiement est en cours de traitement',
                ]);
            } else {
                // Erreur ou action requise
                return response()->json([
                    'success' => false,
                    'status' => $intentStatus,
                    'message' => 'Une action supplémentaire est requise. Veuillez réessayer.',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Payment confirmation error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook Stripe
     * POST /webhooks/stripe
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('stripe-signature');
        $secret = config('services.stripe.webhook_secret');

        // Valider la signature
        if (!$this->stripeService->verifyWebhookSignature($payload, $signature, $secret)) {
            Log::warning('Invalid Stripe webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);
        Log::info('Stripe webhook received', ['type' => $event['type']]);

        try {
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event['data']['object']);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event['data']['object']);
                    break;

                case 'charge.refunded':
                    $this->handleChargeRefunded($event['data']['object']);
                    break;

                case 'charge.dispute.created':
                    $this->handleDisputeCreated($event['data']['object']);
                    break;

                default:
                    Log::info('Unhandled webhook type', ['type' => $event['type']]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing error', ['error' => $e->getMessage()]);
        }

        // Toujours retourner 200 pour Stripe
        return response()->json(['success' => true]);
    }

    /**
     * Gérer payment_intent.succeeded
     */
    protected function handlePaymentIntentSucceeded($intentData)
    {
        // Utiliser l'idempotency key ou l'intent ID pour trouver le payment
        $payment = Payment::where(
            'stripe_payment_intent_id',
            $intentData['id']
        )->first();

        if (!$payment) {
            Log::warning('Payment not found for intent', ['intent_id' => $intentData['id']]);
            return;
        }

        // Mettre à jour le payment et la commande
        $payment->update([
            'stripe_status' => 'succeeded',
            'stripe_charge_id' => $intentData['charges']['data'][0]['id'] ?? null,
            'stripe_webhook_received_at' => now(),
            'statut' => 'completed',
        ]);

        // Mettre à jour la commande si pas déjà faite
        if (!$payment->commande->paiement_confirme) {
            $payment->commande->update(['paiement_confirme' => true]);
        }

        Log::info('Webhook: Payment marked as succeeded', [
            'payment_id' => $payment->idPayment,
            'commande_id' => $payment->commande_id,
        ]);
    }

    /**
     * Gérer payment_intent.payment_failed
     */
    protected function handlePaymentIntentFailed($intentData)
    {
        $payment = Payment::where(
            'stripe_payment_intent_id',
            $intentData['id']
        )->first();

        if ($payment) {
            $payment->update([
                'stripe_status' => 'failed',
                'stripe_webhook_received_at' => now(),
                'statut' => 'failed',
            ]);

            Log::warning('Webhook: Payment failed', [
                'payment_id' => $payment->idPayment,
                'error' => $intentData['last_payment_error']['message'] ?? 'Unknown error',
            ]);
        }
    }

    /**
     * Gérer charge.refunded
     */
    protected function handleChargeRefunded($chargeData)
    {
        $payment = Payment::where('stripe_charge_id', $chargeData['id'])->first();

        if ($payment) {
            $payment->update([
                'statut' => 'refunded',
                'stripe_webhook_received_at' => now(),
            ]);

            Log::info('Webhook: Charge refunded', [
                'payment_id' => $payment->idPayment,
                'amount_refunded' => $chargeData['amount_refunded'],
            ]);
        }
    }

    /**
     * Gérer charge.dispute.created
     */
    protected function handleDisputeCreated($disputeData)
    {
        $payment = Payment::where('stripe_charge_id', $disputeData['charge'])->first();

        if ($payment) {
            Log::warning('Webhook: Dispute created', [
                'payment_id' => $payment->idPayment,
                'dispute_id' => $disputeData['id'],
                'reason' => $disputeData['reason'],
            ]);
        }
    }
}
