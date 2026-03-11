@extends('layouts.app')

@section('title', 'Paiement Sécurisé - Supply')

@section('content')
<div class="min-h-screen bg-[#f7f7f5] py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#0a0a0a] font-serif">Paiement Sécurisé</h1>
            <p class="text-[#666660] mt-2">Powered by Stripe - Vos données sont protégées</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Récapitulatif -->
            <div class="md:col-span-1">
                <div class="bg-white border border-[#e0e0dc] rounded-lg p-6 sticky top-8">
                    <h2 class="font-serif text-lg font-bold text-[#0a0a0a] mb-6">Récapitulatif</h2>

                    <!-- Détails commande -->
                    <div class="space-y-4 mb-6 pb-6 border-b border-[#e0e0dc]">
                        <div class="flex justify-between">
                            <span class="text-[#666660]">Commande #{{ $commande->id }}</span>
                            <span class="text-[#0a0a0a] font-mono font-bold">{{ now()->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-sm text-[#a0a09a]">
                            {{ $commande->ligneCommandes()->count() }} article(s)
                        </div>
                    </div>

                    <!-- Montant -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#666660]">Sous-total</span>
                            <span class="text-[#0a0a0a] font-mono">{{ number_format($commande->total, 2) }} €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#666660]">Frais de livraison</span>
                            <span class="text-[#0a0a0a] font-mono">Gratuit</span>
                        </div>
                        <div class="h-px bg-[#e0e0dc] my-3"></div>
                        <div class="flex justify-between">
                            <span class="font-bold text-[#0a0a0a]">Total</span>
                            <span class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ number_format($commande->total, 2) }} €</span>
                        </div>
                    </div>

                    <!-- Adresse livraison -->
                    <div class="mt-6 pt-6 border-t border-[#e0e0dc]">
                        <h3 class="text-sm font-bold text-[#0a0a0a] mb-2">Livraison à</h3>
                        <p class="text-sm text-[#666660]">
                            {{ $commande->adresse_detail }}<br>
                            @if($commande->quartier_id)
                                {{ $commande->quartier?->nom ?? 'Sierra Leone' }}
                            @endif
                        </p>
                    </div>

                    <!-- Infos Stripe -->
                    <div class="mt-6 pt-6 border-t border-[#e0e0dc]">
                        <p class="text-xs text-[#a0a09a] flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Paiement sécurisé par Stripe
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="md:col-span-2">
                <div class="bg-white border border-[#e0e0dc] rounded-lg p-8">
                    <form id="payment-form">
                        @csrf

                        <!-- Email -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-[#0a0a0a] mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                   value="{{ auth()->user()->email }}"
                                   class="w-full px-4 py-3 border border-[#e0e0dc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0a0a0a] focus:border-transparent"
                                   placeholder="votre@email.com">
                        </div>

                        <!-- Stripe Card Element -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-[#0a0a0a] mb-2">Informations Carte</label>
                            <div id="card-element"
                                 class="w-full px-4 py-3 border border-[#e0e0dc] rounded-lg"
                                 style="padding: 12px; background: white;">
                                <!-- Stripe.js remplira ça -->
                            </div>
                            <div id="card-errors" class="text-[#dc2626] text-sm mt-2" role="alert"></div>
                        </div>

                        <!-- Cardholder Name -->
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-[#0a0a0a] mb-2">Nom du titulaire</label>
                            <input type="text" id="cardholder-name" required
                                   value="{{ auth()->user()->name }}"
                                   class="w-full px-4 py-3 border border-[#e0e0dc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0a0a0a] focus:border-transparent"
                                   placeholder=" Kouassi jean">
                        </div>

                        <!-- Message d'erreur général -->
                        <div id="payment-error-message" class="bg-[#fee] border border-[#dc2626] text-[#dc2626] px-4 py-3 rounded-lg mb-6 hidden" role="alert"></div>

                        <!-- Message de traitement -->
                        <div id="payment-processing" class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6 hidden">
                            ⏳ Traitement en cours...
                        </div>

                        <!-- Bouton de paiement -->
                        <button type="submit" id="submit-btn"
                                class="w-full py-4 px-6 bg-[#0a0a0a] text-white rounded-lg font-bold hover:bg-[#2a2a28] transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            💳 Payer {{ number_format($commande->total, 2) }} €
                        </button>

                        <!-- Texte sécurité -->
                        <p class="text-center text-xs text-[#a0a09a] mt-4">
                            Vos informations de paiement sont traitées de manière sécurisée.<br>
                            Aucun stockage local de données de carte.
                        </p>
                    </form>
                </div>

                <!-- Tester avec Stripe -->
                <div class="mt-6 bg-[#efefed] rounded-lg p-4">
                    <p class="text-xs text-[#666660] font-bold mb-2">📌 CARTE DE TEST (Mode Demo)</p>
                    <div class="text-xs text-[#a0a09a] space-y-1 font-mono">
                        <p>Numéro: <code>4242 4242 4242 4242</code></p>
                        <p>Expiration: <code>12/25</code></p>
                        <p>CVC: <code>123</code></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
const stripe = Stripe('{{ $stripePublicKey }}', {
    locale: 'fr'
});

const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontFamily: '"Geist", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            fontSize: '16px',
            color: '#0a0a0a',
            '::placeholder': {
                color: '#a0a09a',
            },
        },
        invalid: {
            color: '#dc2626',
        },
    },
});

cardElement.mount('#card-element');

// Afficher les erreurs carte en temps réel
cardElement.addEventListener('change', (event) => {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Soumettre le formulaire
document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = document.getElementById('submit-btn');
    const processingDiv = document.getElementById('payment-processing');
    const errorDiv = document.getElementById('payment-error-message');

    // Désactiver le bouton
    submitBtn.disabled = true;
    processingDiv.classList.remove('hidden');
    errorDiv.classList.add('hidden');

    try {
        // 1️⃣ Créer le PaymentIntent
        const createIntentResponse = await fetch('{{ route('payment.create-intent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                commande_id: {{ $commande->id }},
                email: document.getElementById('email').value,
            }),
        });

        const intentData = await createIntentResponse.json();

        if (!intentData.success) {
            throw new Error(intentData.error);
        }

        // 2️⃣ Confirmer la carte avec Stripe.js
        const confirmResult = await stripe.confirmCardPayment(intentData.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: document.getElementById('cardholder-name').value,
                    email: document.getElementById('email').value,
                },
            },
        });

        if (confirmResult.error) {
            throw new Error(confirmResult.error.message);
        }

        // 3️⃣ Confirmer le statut côté serveur
        const confirmResponse = await fetch('{{ route('payment.confirm') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                commande_id: {{ $commande->id }},
                stripe_payment_intent: confirmResult.paymentIntent.id,
            }),
        });

        const result = await confirmResponse.json();

        if (result.success) {
            // ✅ Paiement réussi !
            window.location.href = result.redirect;
        } else if (result.status === 'processing') {
            errorDiv.textContent = result.message;
            errorDiv.classList.remove('hidden');
        } else {
            throw new Error(result.message || 'Erreur inconnue');
        }
    } catch (error) {
        // ❌ Erreur
        errorDiv.textContent = error.message;
        errorDiv.classList.remove('hidden');
        submitBtn.disabled = false;
        processingDiv.classList.add('hidden');
    }
});
</script>
@endsection
