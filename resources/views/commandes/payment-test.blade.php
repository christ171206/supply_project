@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5] py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('commandes.show', $commande->id) }}" class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-4">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Retour à la commande
            </a>
            <h1 class="font-serif text-[32px] text-[#0a0a0a] leading-none">Simulation de Paiement</h1>
            <p class="text-[13px] text-[#a0a09a] mt-2 font-light">Mode test — Aucune transaction réelle</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Récapitulatif -->
            <div class="md:col-span-1">
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 sticky top-8">
                    <span class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3 block">Commande</span>
                    <h3 class="font-mono text-[18px] font-bold text-[#0a0a0a] mb-6">{{ $commande->numero ?? 'CMD-' . $commande->id }}</h3>

                    <div class="space-y-3 pb-6 border-b border-[#e0e0dc]">
                        <div class="flex justify-between text-[13px]">
                            <span class="text-[#666660]">Articles</span>
                            <span class="font-mono text-[#0a0a0a]">{{ $commande->ligneCommandes()->count() }}</span>
                        </div>
                        <div class="flex justify-between text-[13px]">
                            <span class="text-[#666660]">Méthode</span>
                            <span class="font-mono text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-[#e0e0dc]">
                        <div class="flex justify-between items-baseline mb-4">
                            <span class="text-[13px] font-medium text-[#0a0a0a]">Total</span>
                            <span class="font-mono text-[24px] font-bold text-[#0a0a0a]">{{ number_format($commande->total, 0, ',', ' ') }}</span>
                        </div>
                        <span class="text-[11px] text-[#a0a09a] block">FCFA</span>
                    </div>

                    <div class="mt-6 pt-6 border-t border-[#e0e0dc]">
                        <p class="text-[11px] text-[#a0a09a] flex items-center gap-2">
                            <span class="w-5 h-5 bg-[#f0fdf4] text-[#15803d] rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                            <span>Ceci est un test — aucun paiement réel</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Simulation -->
            <div class="md:col-span-2 space-y-6">
                <!-- Carte de test -->
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-8">
                    <h2 class="font-serif text-[20px] text-[#0a0a0a] mb-6">Numéros de test</h2>

                    @if(in_array($commande->payment_method, ['wave', 'orange_money', 'mtn_money', 'moov_money']))
                        <div class="space-y-4">
                            <p class="text-[13px] text-[#666660] mb-6">
                                Pour tester le paiement mobile, utilisez l'un de ces numéros de test :
                            </p>

                            <div class="space-y-3">
                                <div class="bg-[#f7f7f5] rounded-lg p-4 border border-[#e0e0dc]">
                                    <p class="text-[11px] text-[#a0a09a] font-medium mb-2">Numéro de test (succès)</p>
                                    <p class="font-mono text-[14px] font-bold text-[#0a0a0a] mb-2">+225 0150000000</p>
                                    <p class="text-[11px] text-[#666660]">Ce numéro simulera un paiement réussi</p>
                                </div>

                                <div class="bg-[#f7f7f5] rounded-lg p-4 border border-[#e0e0dc]">
                                    <p class="text-[11px] text-[#a0a09a] font-medium mb-2">Numéro de test (échec)</p>
                                    <p class="font-mono text-[14px] font-bold text-[#0a0a0a] mb-2">+225 0160000000</p>
                                    <p class="text-[11px] text-[#666660]">Ce numéro simulera un paiement échoué</p>
                                </div>
                            </div>
                        </div>
                    @elseif($commande->payment_method === 'card')
                        <div class="space-y-4">
                            <p class="text-[13px] text-[#666660] mb-6">
                                Utilisez l'une de ces cartes de test Stripe :
                            </p>

                            <div class="space-y-3">
                                <div class="bg-[#f7f7f5] rounded-lg p-4 border border-[#e0e0dc]">
                                    <p class="text-[11px] text-[#a0a09a] font-medium mb-2">Paiement réussi</p>
                                    <p class="font-mono text-[14px] font-bold text-[#0a0a0a] mb-1">4242 4242 4242 4242</p>
                                    <p class="text-[11px] text-[#666660]">Exp: 12/34 | CVC: 123</p>
                                </div>

                                <div class="bg-[#f7f7f5] rounded-lg p-4 border border-[#e0e0dc]">
                                    <p class="text-[11px] text-[#a0a09a] font-medium mb-2">Paiement décliné</p>
                                    <p class="font-mono text-[14px] font-bold text-[#0a0a0a] mb-1">4000 0000 0000 0002</p>
                                    <p class="text-[11px] text-[#666660]">Exp: 12/34 | CVC: 123</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-[13px] text-[#666660] mb-6">
                                Paiement à la livraison
                            </p>

                            <div class="bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg p-4">
                                <p class="text-[13px] text-[#15803d] font-medium mb-2">✓ Aucun paiement maintenant</p>
                                <p class="text-[12px] text-[#15803d] font-light">
                                    Vous payerez lors de la réception de votre commande
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Simulation de résultat -->
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-8">
                    <h2 class="font-serif text-[20px] text-[#0a0a0a] mb-6">Simuler un résultat</h2>

                    <div class="space-y-3">
                        <!-- Succès -->
                        <form action="{{ route('commandes.payment-success', $commande->id) }}" method="GET" class="contents">
                            <button type="submit"
                                    class="w-full px-6 py-3 bg-[#15803d] text-white font-medium rounded-lg hover:bg-[#15803d]/90 transition-colors text-[13px]">
                                ✓ Simuler un paiement réussi
                            </button>
                        </form>

                        <!-- Annuler -->
                        <form action="{{ route('commandes.show', $commande->id) }}" method="GET" class="contents">
                            <button type="submit"
                                    class="w-full px-6 py-3 bg-[#dc2626] text-white font-medium rounded-lg hover:bg-[#dc2626]/90 transition-colors text-[13px]">
                                ✗ Simuler un paiement échoué
                            </button>
                        </form>
                    </div>

                    <p class="text-[11px] text-[#a0a09a] mt-4 pt-4 border-t border-[#e0e0dc]">
                        💡 En production, utilisez le formulaire de paiement réel avec les informations de votre carte ou téléphone
                    </p>
                </div>

                <!-- Instructions -->
                <div class="bg-[#fdf6ec] border border-[#fde68a] rounded-xl p-6">
                    <p class="text-[12px] text-[#b45309] font-medium mb-3">ℹ️ Mode test</p>
                    <ul class="text-[12px] text-[#92400e] space-y-2 font-light">
                        <li>✓ Aucune transaction réelle n'est effectuée</li>
                        <li>✓ Vos données sont sécurisées (test mode)</li>
                        <li>✓ Cliquez sur "Simuler un résultat" pour tester le flux</li>
                        <li>✓ Vérifiez que vous recevez les emails de confirmation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
