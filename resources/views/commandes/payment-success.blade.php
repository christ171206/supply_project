@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-12 text-center">
                <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" fill-rule="evenodd" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-[28px] font-serif leading-tight mb-1">Paiement réussi!</h1>
                <p class="text-[13px] text-white/80 font-light">Merci pour votre commande</p>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-6">

                <!-- Order Summary -->
                <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                    <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-green-600 mb-3">Résumé de la commande</div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#0a0a0a] font-medium">Commande #{{ $commande->id }}</span>
                            <span class="text-[12px] font-mono text-green-600 font-medium">✓ Confirmée</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-green-200">
                            <span class="text-[12px] text-[#a0a09a]">Montant total :</span>
                            <span class="text-[14px] font-mono font-medium text-[#0a0a0a]">
                                {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="bg-[#f7f7f5] rounded-xl p-5">
                    <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Adresse de livraison</div>
                    <div class="space-y-1">
                        <p class="text-[13px] font-medium text-[#0a0a0a]">{{ $commande->adresse_detail }}</p>
                        <p class="text-[12px] text-[#666660]">{{ $commande->pays }}</p>
                        <p class="text-[12px] text-[#a0a09a] font-light">📞 {{ $commande->telephone_livraison }}</p>
                    </div>
                </div>

                <!-- What's Next -->
                <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                    <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-blue-600 mb-3">Prochaines étapes</div>
                    <div class="space-y-2 text-[12px] text-blue-900 font-light">
                        <div class="flex gap-2">
                            <span class="font-medium">1.</span>
                            <span>Confirmation du paiement en cours</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-medium">2.</span>
                            <span>Préparation de votre commande</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-medium">3.</span>
                            <span>Livraison à votre adresse</span>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('client.commandes') }}"
                       class="block w-full text-center px-4 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity">
                        Voir mes commandes
                    </a>
                    <a href="{{ route('accueil') }}"
                       class="block w-full text-center px-4 py-3 border border-[#e0e0dc] text-[#0a0a0a] text-[13px] font-medium rounded-lg hover:bg-[#f7f7f5] transition-colors">
                        Continuer les achats
                    </a>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-[#f7f7f5] px-6 py-4 text-center border-t border-[#efefed]">
                <div class="text-[11px] text-[#a0a09a] font-light">
                    Un email de confirmation a été envoyé à <br>
                    <span class="font-medium text-[#0a0a0a]">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>

        <!-- Help Link -->
        <div class="mt-6 text-center">
            <a href="#" class="text-[12px] text-[#0a0a0a] border-b border-[#e0e0dc] pb-px hover:border-[#0a0a0a] transition-colors">
                Besoin d'aide ?
            </a>
        </div>
    </div>
</div>

<style>
@keyframes checkmark {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

/* Success animation (optional) */
</style>

@endsection
