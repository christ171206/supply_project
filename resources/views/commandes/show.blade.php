@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('commandes.index') }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">← Retour aux commandes</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Détails de la Commande -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">Commande #{{ $commande->id }}</h1>
                        <p class="text-gray-600">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold
                        @if($commande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                        @elseif($commande->statut === 'payée') bg-green-100 text-green-800
                        @elseif($commande->statut === 'annulée') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800
                        @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                </div>

                <!-- Produits -->
                <div class="border-t-2 border-b-2 border-gray-200 py-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📦 Produits</h2>
                    <div class="space-y-4">
                        @forelse($lignes as $ligne)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $ligne->produit->nom }}</p>
                                    <p class="text-gray-600 text-sm">Vendeur: {{ $ligne->produit->vendor->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-700">Quantité: <span class="font-bold">{{ $ligne->quantite }}</span></p>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, '', ' ') }} F CFA</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 text-center py-4">Aucun produit dans cette commande</p>
                        @endforelse
                    </div>
                </div>

                <!-- Adresse de Livraison -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">📍 Adresse de Livraison</h3>
                    <p class="text-gray-800 whitespace-pre-line">{{ $commande->adresse_livraison }}</p>
                </div>
            </div>

            <!-- Paiement Info -->
            @if($payment)
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">💳 Informations de Paiement</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Référence de paiement:</span>
                            <span class="font-semibold text-gray-900">{{ $payment->reference ?? 'En attente' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Statut du paiement:</span>
                            <span class="font-semibold
                                @if($payment->statut === 'confirmé') text-green-600
                                @elseif($payment->statut === 'en_attente') text-yellow-600
                                @else text-red-600
                                @endif
                            ">
                                {{ ucfirst($payment->statut) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Date du paiement:</span>
                            <span class="font-semibold text-gray-900">{{ $payment->date_paiement?->format('d/m/Y H:i') ?? 'En attente' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Résumé et Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📊 Résumé</h2>

                <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between text-gray-700">
                        <span>Sous-total</span>
                        <span class="font-semibold">
                            @php
                                $sousTotal = $lignes->sum(fn($l) => $l->quantite * $l->prix_unitaire);
                            @endphp
                            {{ number_format($sousTotal, 0, '', ' ') }} F CFA
                        </span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Frais de livraison</span>
                        <span class="font-semibold">
                            @php
                                $frais = $sousTotal > 100000 ? 0 : 2500;
                            @endphp
                            @if($frais == 0)
                                Gratuit
                            @else
                                {{ number_format($frais, 0, '', ' ') }} F CFA
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-between text-xl font-bold text-gray-900 mb-6 pb-6 border-b border-gray-200">
                    <span>Total TTC</span>
                    <span>{{ number_format($commande->montant_total, 0, '', ' ') }} F CFA</span>
                </div>

                <!-- Informations Commande -->
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Méthode de paiement</p>
                        <p class="text-gray-900 font-semibold">{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Date de commande</p>
                        <p class="text-gray-900 font-semibold">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-3">
                    <a href="{{ route('commandes.download-pdf', $commande->id) }}" class="w-full block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-center">
                        � Voir la Facture
                    </a>
                    <a href="{{ route('commandes.index') }}" class="w-full block px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-bold text-center">
                        Mes Commandes
                    </a>
                    <a href="{{ route('produits.catalogue') }}" class="w-full block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-center">
                        Continuer les achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
