@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Message de succès --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-[#f7f7f5] border-l-4 border-[#0a0a0a] rounded-lg">
            <div class="flex items-center">
                <span class="text-3xl mr-3">✓</span>
                <div>
                    <h3 class="font-bold text-lg text-[#0a0a0a]">Commande créée avec succès !</h3>
                    <p class="text-[#666660]">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('commandes.index') }}" class="text-[#0a0a0a] hover:text-[#2a2a28] mb-6 inline-block font-medium">← Retour aux commandes</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Détails de la Commande -->
        <div class="lg:col-span-2">
            <!-- En-tête -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-8 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold mb-2 text-[#0a0a0a]">✓ Commande confirmée</h1>
                        <p class="text-[#666660]">Commande #{{ $commande->id }} — {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <span class="inline-block px-6 py-3 rounded-full text-lg font-bold bg-[#0a0a0a] text-white
                        @if($commande->statut === 'en_attente') bg-[#f7f7f5] text-[#0a0a0a]
                        @elseif($commande->statut === 'confirmee') bg-[#0a0a0a] text-white
                        @elseif($commande->statut === 'expediee') bg-[#2a2a28] text-white
                        @elseif($commande->statut === 'livree') bg-[#0a0a0a] text-white
                        @elseif($commande->statut === 'annulee') bg-[#dc2626] text-white
                        @else bg-[#a0a09a] text-white
                        @endif
                    ">
                        {{ match($commande->statut) {
                            'en_attente' => 'En attente',
                            'confirmee' => 'Confirmée',
                            'expediee' => 'Expédiée',
                            'livree' => 'Livrée',
                            'annulee' => 'Annulée',
                            default => ucfirst(str_replace('_', ' ', $commande->statut))
                        } }}
                    </span>
                </div>
            </div>

            <!-- Produits -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6 flex items-center">
                    <span class="text-3xl mr-3">📦</span>
                    Produits Commandés
                </h2>
                <div class="space-y-4">
                    @forelse($lignes as $ligne)
                        <div class="flex justify-between items-center p-4 bg-[#f7f7f5] rounded-lg hover:bg-[#efefed] transition">
                            <div>
                                <p class="font-bold text-[#0a0a0a] text-lg">{{ $ligne->produit->nom }}</p>
                                <p class="text-[#666660] text-sm">Vendeur: <span class="font-semibold">{{ $ligne->produit->user->name ?? 'N/A' }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[#666660] mb-1">Quantité: <span class="font-bold text-lg">{{ $ligne->quantite }}</span></p>
                                <p class="text-lg font-bold text-[#0a0a0a]">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, '', ' ') }} F CFA</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[#666660] text-center py-4">Aucun produit dans cette commande</p>
                    @endforelse
                </div>
            </div>

            <!-- Adresse de Livraison -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-8 mb-6">
                <h3 class="text-xl font-bold text-[#0a0a0a] mb-4 flex items-center">
                    <span class="text-2xl mr-3">📍</span>
                    Adresse de Livraison
                </h3>
                <div class="bg-[#f7f7f5] p-4 rounded-lg">
                    <p class="text-[#2a2a28] font-semibold text-lg">{{ $commande->adresse_livraison }}</p>
                    <p class="text-[#666660] mt-2">{{ $commande->adresse_detail }}</p>
                    <p class="text-[#a0a09a] mt-3">📞 {{ $commande->telephone_livraison }}</p>
                </div>
            </div>

            <!-- Paiement Info -->
            @if($payment)
                <div class="bg-white border border-[#e0e0dc] rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6 flex items-center">
                        <span class="text-3xl mr-3">💳</span>
                        Informations de Paiement
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between p-4 bg-[#f7f7f5] rounded-lg">
                            <span class="text-[#2a2a28] font-semibold">Référence de paiement:</span>
                            <span class="font-mono font-bold text-[#0a0a0a]">{{ $payment->payment_code }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-[#f7f7f5] rounded-lg">
                            <span class="text-[#2a2a28] font-semibold">Statut du paiement:</span>
                            <span class="font-bold
                                @if($payment->payment_status === 'confirmee') text-[#0a0a0a]
                                @elseif($payment->payment_status === 'en_attente') text-[#a0a09a]
                                @elseif($payment->payment_status === 'echouee') text-[#dc2626]
                                @else text-[#666660]
                                @endif
                            ">
                                {{ match($payment->payment_status) {
                                    'initialisee' => 'Initialisée',
                                    'en_attente' => 'En attente',
                                    'confirmee' => 'Confirmée',
                                    'echouee' => 'Échouée',
                                    'annulee' => 'Annulée',
                                    default => ucfirst(str_replace('_', ' ', $payment->payment_status))
                                } }}
                            </span>
                        </div>
                        <div class="flex justify-between p-4 bg-[#f7f7f5] rounded-lg">
                            <span class="text-[#2a2a28] font-semibold">Type de paiement:</span>
                            <span class="font-bold text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $payment->typePayement)) }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-[#f7f7f5] rounded-lg">
                            <span class="text-[#2a2a28] font-semibold">Montant:</span>
                            <span class="font-bold text-lg text-[#0a0a0a]">{{ number_format($payment->montant, 0, '', ' ') }} F CFA</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Résumé et Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-[#0a0a0a] mb-6">📊 Résumé</h2>

                <div class="space-y-3 mb-6 pb-6 border-b border-[#e0e0dc]">
                    <div class="flex justify-between text-[#666660]">
                        <span>Montant:</span>
                        <span class="font-bold text-lg text-[#0a0a0a]">
                            {{ number_format($commande->total, 0, '', ' ') }} F CFA
                        </span>
                    </div>
                </div>

                <!-- Informations Commande -->
                <div class="space-y-6 mb-8">
                    <div class="bg-[#f7f7f5] p-4 rounded-lg">
                        <p class="text-[#a0a09a] text-sm font-medium mb-1">Méthode de paiement</p>
                        <p class="text-[#0a0a0a] font-bold text-lg">{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</p>
                    </div>
                    <div class="bg-[#f7f7f5] p-4 rounded-lg">
                        <p class="text-[#a0a09a] text-sm font-medium mb-1">État de la commande</p>
                        <p class="text-[#0a0a0a] font-bold text-lg">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('commandes.download-pdf', $commande->id) }}" class="w-full block px-4 py-3 bg-[#0a0a0a] text-white rounded-lg hover:bg-[#2a2a28] transition font-bold text-center shadow-sm">
                        📄 Télécharger la Facture
                    </a>
                    <a href="{{ route('produits.catalogue') }}" class="w-full block px-4 py-3 bg-[#f7f7f5] text-[#0a0a0a] border border-[#e0e0dc] rounded-lg hover:bg-[#efefed] transition font-bold text-center">
                        🛍️ Continuer les achats
                    </a>
                    <a href="{{ route('commandes.index') }}" class="w-full block px-4 py-3 bg-[#f7f7f5] text-[#0a0a0a] border border-[#e0e0dc] rounded-lg hover:bg-[#efefed] transition font-bold text-center">
                        📋 Mes Commandes
                    </a>
                </div>

                <!-- Info Important -->
                <div class="mt-8 p-4 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg">
                    <p class="text-sm text-[#2a2a28]">
                        <span class="font-bold">✓ Confirmation :</span> Votre commande a été créée avec succès.
                        @if($commande->payment_method !== 'cash')
                            Veuillez confirmer votre paiement via {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}.
                        @else
                            Vous paierez à la livraison.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
