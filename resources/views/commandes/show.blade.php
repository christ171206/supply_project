@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Message de succès --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
            <div class="flex items-center">
                <span class="text-3xl mr-3">✓</span>
                <div>
                    <h3 class="font-bold text-lg">Commande créée avec succès !</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('commandes.index') }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">← Retour aux commandes</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Détails de la Commande -->
        <div class="lg:col-span-2">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-8 mb-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Commande #{{ $commande->id }}</h1>
                        <p class="text-blue-100">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <span class="inline-block px-6 py-3 rounded-full text-lg font-bold bg-white
                        @if($commande->statut === 'en_attente') text-yellow-700
                        @elseif($commande->statut === 'confirmee') text-blue-700
                        @elseif($commande->statut === 'expediee') text-purple-700
                        @elseif($commande->statut === 'livree') text-green-700
                        @elseif($commande->statut === 'annulee') text-red-700
                        @else text-blue-700
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
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="text-3xl mr-3">📦</span>
                    Produits Commandés
                </h2>
                <div class="space-y-4">
                    @forelse($lignes as $ligne)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $ligne->produit->nom }}</p>
                                <p class="text-gray-600 text-sm">Vendeur: <span class="font-semibold">{{ $ligne->produit->vendor->name ?? 'N/A' }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-700 mb-1">Quantité: <span class="font-bold text-lg">{{ $ligne->quantite }}</span></p>
                                <p class="text-lg font-bold text-blue-600">{{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, '', ' ') }} F CFA</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-4">Aucun produit dans cette commande</p>
                    @endforelse
                </div>
            </div>

            <!-- Adresse de Livraison -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6 border-2 border-green-200">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="text-2xl mr-3">📍</span>
                    Adresse de Livraison
                </h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-800 font-semibold text-lg">{{ $commande->adresse_livraison }}</p>
                    <p class="text-gray-700 mt-2">{{ $commande->adresse_detail }}</p>
                    <p class="text-gray-600 mt-3">📞 {{ $commande->telephone_livraison }}</p>
                </div>
            </div>

            <!-- Paiement Info -->
            @if($payment)
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="text-3xl mr-3">💳</span>
                        Informations de Paiement
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-700 font-semibold">Référence de paiement:</span>
                            <span class="font-mono font-bold text-gray-900">{{ $payment->payment_code }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-700 font-semibold">Statut du paiement:</span>
                            <span class="font-bold
                                @if($payment->payment_status === 'confirmee') text-green-600
                                @elseif($payment->payment_status === 'en_attente') text-yellow-600
                                @elseif($payment->payment_status === 'echouee') text-red-600
                                @else text-gray-600
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
                        <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-700 font-semibold">Type de paiement:</span>
                            <span class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->typePayement)) }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-gray-700 font-semibold">Montant:</span>
                            <span class="font-bold text-lg text-green-600">{{ number_format($payment->montant, 0, '', ' ') }} F CFA</span>
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
                        <span>Montant:</span>
                        <span class="font-bold text-lg">
                            {{ number_format($commande->total, 0, '', ' ') }} F CFA
                        </span>
                    </div>
                </div>

                <!-- Informations Commande -->
                <div class="space-y-6 mb-8">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium mb-1">Méthode de paiement</p>
                        <p class="text-gray-900 font-bold text-lg">{{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-gray-600 text-sm font-medium mb-1">État de la commande</p>
                        <p class="text-gray-900 font-bold text-lg">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('commandes.download-pdf', $commande->id) }}" class="w-full block px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-center shadow-md hover:shadow-lg">
                        📄 Télécharger la Facture
                    </a>
                    <a href="{{ route('produits.catalogue') }}" class="w-full block px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-center shadow-md hover:shadow-lg">
                        🛍️ Continuer les achats
                    </a>
                    <a href="{{ route('commandes.index') }}" class="w-full block px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-bold text-center shadow-md hover:shadow-lg">
                        📋 Mes Commandes
                    </a>
                </div>

                <!-- Info Important -->
                <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-900">
                        <span class="font-bold">⚠️ Important :</span> Votre commande a été créée.
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
