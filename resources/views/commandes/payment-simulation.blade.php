@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#f7f7f5] to-[#efefed] flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <!-- Header -->
            <div class="bg-[#0a0a0a] text-white px-6 py-8">
                <h1 class="text-[24px] font-serif leading-tight mb-1">Paiement en cours</h1>
                <p class="text-[13px] text-white/60 font-light">Commande #{{ $commande->id }}</p>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">

                <!-- Amount Card -->
                <div class="bg-gradient-to-br from-[#0a0a0a] to-[#2a2a28] text-white rounded-xl p-6">
                    <div class="text-[13px] text-white/60 font-light mb-2">Montant à payer</div>
                    <div class="text-[40px] font-mono font-medium">
                        {{ number_format($commande->total, 0, ',', ' ') }}
                        <span class="text-[20px] font-light ml-1">FCFA</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-[#f7f7f5] rounded-xl p-4">
                    <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Méthode de paiement</div>
                    <div class="flex items-center gap-3">
                        <div id="payment-icon" class="w-10 h-10 bg-[#0a0a0a] rounded-lg flex items-center justify-center text-white text-[18px]">
                            💳
                        </div>
                        <div>
                            <div class="text-[13px] font-medium text-[#0a0a0a]" id="payment-method-name">
                                {{ ucfirst(str_replace('_', ' ', $commande->payment->typePayement ?? $commande->payment_method ?? 'N/A')) }}
                            </div>
                            <div class="text-[11px] text-[#a0a09a] font-light">Sécurisé et chiffré</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="space-y-4">
                    <div class="text-[12px] font-medium text-[#0a0a0a] mb-3">Traitement du paiement</div>

                    <!-- Step Indicators -->
                    <div class="space-y-3">
                        <div class="step-item flex items-start gap-4" data-step="1">
                            <div class="step-icon w-8 h-8 rounded-full bg-[#efefed] flex items-center justify-center text-[12px] font-medium text-[#a0a09a] flex-shrink-0">
                                ✓
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">Commande créée</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">Données validées et enregistrées</div>
                            </div>
                        </div>

                        <div class="step-item flex items-start gap-4" data-step="2">
                            <div class="step-icon w-8 h-8 rounded-full bg-[#0a0a0a] flex items-center justify-center text-white text-[12px] font-medium flex-shrink-0 animate-pulse">
                                ⏳
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">Vérification des données</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">Validation de l'adresse et montant</div>
                            </div>
                        </div>

                        <div class="step-item flex items-start gap-4" data-step="3">
                            <div class="step-icon w-8 h-8 rounded-full bg-[#efefed] flex items-center justify-center text-[12px] font-medium text-[#a0a09a] flex-shrink-0">
                                1
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">Seconde étape</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">En attente...</div>
                            </div>
                        </div>

                        <div class="step-item flex items-start gap-4" data-step="4">
                            <div class="step-icon w-8 h-8 rounded-full bg-[#efefed] flex items-center justify-center text-[12px] font-medium text-[#a0a09a] flex-shrink-0">
                                2
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">Paiement traité</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">Confirmation finale</div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Progress -->
                    <div class="mt-6">
                        <div class="h-1.5 bg-[#efefed] rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-[#0a0a0a] transition-all duration-700" style="width: 25%"></div>
                        </div>
                        <div class="text-[11px] text-[#a0a09a] font-light mt-2 text-center">
                            <span id="progress-text">1/4 étapes</span>
                        </div>
                    </div>
                </div>

                <!-- Animated Card (Visa/MasterCard simulation) -->
                <div class="relative h-40 mb-4">
                    <div id="card" class="absolute inset-0 bg-gradient-to-br from-[#0a0a0a] to-[#2a2a28] rounded-xl p-6 text-white shadow-lg transform transition-all duration-500"
                         style="transform-style: preserve-3d;">

                        <div class="flex justify-between items-start mb-8">
                            <div class="text-[28px] font-bold">●●●●</div>
                            <div class="text-[12px] font-light text-white/60">SUPPLY</div>
                        </div>

                        <div class="flex justify-between items-end">
                            <div>
                                <div class="text-[10px] text-white/40 font-light mb-1">CARD NUMBER</div>
                                <div class="text-[16px] font-mono font-medium">
                                    <span id="card-number" class="blur-sm">4532 •••• •••• 9876</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-white/40 font-light">VALID THRU</div>
                                <div class="text-[14px] font-mono font-medium">12/25</div>
                            </div>
                        </div>

                        <!-- Security status indicator -->
                        <div class="absolute top-3 right-3">
                            <svg class="w-6 h-6 text-green-400 opacity-0 animate-fadeIn" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                        </div>

                        <!-- Flip effect for back -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#2a2a28] to-[#0a0a0a] rounded-xl p-6 text-white hidden" id="card-back">
                            <div class="h-12 bg-[#333] my-4"></div>
                            <div class="text-right">
                                <div class="text-[10px] text-white/40 mb-1">CVV</div>
                                <div class="bg-white/20 rounded w-16 h-8 flex items-center justify-center text-[13px] font-mono">***</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Message -->
                <div id="status-message" class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 hidden">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-400 flex items-center justify-center text-white text-[10px] flex-shrink-0 mt-0.5">
                            ℹ
                        </div>
                        <div class="text-[12px] text-blue-900" id="status-text">
                            Vérification en cours...
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-[#f7f7f5] px-6 py-4 text-center border-t border-[#efefed]">
                <div class="text-[11px] text-[#a0a09a] font-light">
                    🔒 Votre paiement est sécurisé avec chiffrement SSL
                </div>
            </div>
        </div>

        <!-- Success or Redirect Info -->
        <div id="success-info" class="mt-6 text-center hidden">
            <p class="text-[13px] text-[#0a0a0a] font-medium">
                ✓ Paiement réussi! Redirection en cours...
            </p>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-in forwards;
}

.step-item.completed .step-icon {
    @apply bg-green-100 text-green-600;
}

.step-item.active .step-icon {
    @apply bg-[#0a0a0a] text-white animate-pulse;
}

.step-item.active > div:last-child > div:first-child {
    @apply text-[#0a0a0a] font-medium;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    simulatePayment();
});

function simulatePayment() {
    const steps = [
        {
            duration: 1500,
            text: 'Initialisation du paiement...',
            stepNum: 1,
            iconText: '✓'
        },
        {
            duration: 2000,
            text: 'Connexion au serveur de paiement...',
            stepNum: 2,
            iconText: '2'
        },
        {
            duration: 2000,
            text: 'Traitement de la transaction...',
            stepNum: 3,
            iconText: '3'
        },
        {
            duration: 1500,
            text: 'Confirmation et finalisation...',
            stepNum: 4,
            iconText: '✓'
        }
    ];

    let currentStepIndex = 1; // Step 1 is already "completed"
    let totalDuration = 0;

    function processStep(stepIndex) {
        const step = steps[stepIndex];
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const statusMessage = document.getElementById('status-message');
        const statusText = document.getElementById('status-text');

        // Update current step UI
        const stepItem = document.querySelector(`.step-item[data-step="${stepIndex + 1}"]`);
        if (stepItem) {
            stepItem.classList.add('active');
            stepItem.classList.remove('completed');
        }

        // Update previous step as completed
        if (stepIndex > 0) {
            const prevItem = document.querySelector(`.step-item[data-step="${stepIndex}"]`);
            if (prevItem) {
                prevItem.classList.remove('active');
                prevItem.classList.add('completed');
                const prevIcon = prevItem.querySelector('.step-icon');
                if (prevIcon) prevIcon.textContent = '✓';
            }
        }

        // Update progress
        const progress = ((stepIndex + 1) / steps.length) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = `${stepIndex + 1}/${steps.length} étapes`;

        // Update status message
        statusMessage.classList.remove('hidden');
        statusText.textContent = step.text;

        totalDuration += step.duration;

        if (stepIndex < steps.length - 1) {
            // Continue to next step
            setTimeout(() => {
                processStep(stepIndex + 1);
            }, step.duration);
        } else {
            // Final step completed
            setTimeout(() => {
                completePayment();
            }, step.duration);
        }
    }

    // Start processing from step 2 (index 1)
    processStep(1);
}

function completePayment() {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const statusMessage = document.getElementById('status-message');
    const statusText = document.getElementById('status-text');
    const successInfo = document.getElementById('success-info');
    const lastStep = document.querySelector(`.step-item[data-step="4"]`);

    // Mark last step as completed
    if (lastStep) {
        lastStep.classList.remove('active');
        lastStep.classList.add('completed');
        const icon = lastStep.querySelector('.step-icon');
        if (icon) icon.textContent = '✓';
    }

    // Update progress to 100%
    progressBar.style.width = '100%';
    progressText.textContent = '4/4 étapes';

    // Update status
    statusMessage.classList.add('hidden');
    successInfo.classList.remove('hidden');

    // Redirect after 2 seconds
    setTimeout(() => {
        window.location.href = '{{ route("commandes.payment-success", ["id" => $commande->id]) }}';
    }, 2000);
}
</script>

@endsection
