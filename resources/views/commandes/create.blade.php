@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">💳 Paiement de la Commande</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire de Paiement -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="{{ route('commandes.store') }}" method="POST" id="payment-form">
                    @csrf

                    <!-- Adresse de Livraison -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">📍 Adresse de Livraison</h2>

                        <div class="mb-6">
                            <label for="adresse_livraison" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète</label>
                            <textarea
                                id="adresse_livraison"
                                name="adresse_livraison"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Rue, numéro, ville, code postal..."
                                required>{{ old('adresse_livraison', auth()->user()->adresse ?? '') }}</textarea>
                            @error('adresse_livraison')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone de livraison</label>
                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="+225 XX XX XX XX"
                                value="{{ old('telephone', auth()->user()->telephone ?? '') }}"
                                required>
                            @error('telephone')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Méthode de Paiement -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">💰 Méthode de Paiement</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Wave -->
                            <label class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition" id="wave-option">
                                <div class="flex items-center gap-3 mb-3">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="wave"
                                        class="w-4 h-4"
                                        required>
                                    <img src="{{ asset('images/payments/wave.png') }}" alt="Wave" class="h-8 object-contain">
                                </div>
                                <p class="font-semibold text-gray-900">Wave</p>
                                <p class="text-gray-600 text-xs mt-1">Paiement mobile rapide</p>
                            </label>

                            <!-- Orange Money -->
                            <label class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition">
                                <div class="flex items-center gap-3 mb-3">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="orange_money"
                                        class="w-4 h-4"
                                        required>
                                    <img src="{{ asset('images/payments/orange money.png') }}" alt="Orange Money" class="h-8 object-contain">
                                </div>
                                <p class="font-semibold text-gray-900">Orange Money</p>
                                <p class="text-gray-600 text-xs mt-1">Service de paiement Orange</p>
                            </label>

                            <!-- MTN Money -->
                            <label class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition">
                                <div class="flex items-center gap-3 mb-3">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="mtn_money"
                                        class="w-4 h-4"
                                        required>
                                    <img src="{{ asset('images/payments/mtn money.png') }}" alt="MTN Money" class="h-8 object-contain">
                                </div>
                                <p class="font-semibold text-gray-900">MTN Money</p>
                                <p class="text-gray-600 text-xs mt-1">Porte-monnaie mobile MTN</p>
                            </label>

                            <!-- Moov Money -->
                            <label class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition">
                                <div class="flex items-center gap-3 mb-3">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="moov_money"
                                        class="w-4 h-4"
                                        required>
                                    <img src="{{ asset('images/payments/moov money.png') }}" alt="Moov Money" class="h-8 object-contain">
                                </div>
                                <p class="font-semibold text-gray-900">Moov Money</p>
                                <p class="text-gray-600 text-xs mt-1">Service monétique Moov Africa</p>
                            </label>

                            <!-- À la Livraison -->
                            <label class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition md:col-span-2">
                                <div class="flex items-center gap-3 mb-3">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="cash"
                                        class="w-4 h-4"
                                        required>
                                    <img src="{{ asset('images/payments/a la livraison.jfif') }}" alt="À la Livraison" class="h-8 object-contain">
                                </div>
                                <p class="font-semibold text-gray-900">À la Livraison</p>
                                <p class="text-gray-600 text-xs mt-1">Paiement en espèces à la livraison</p>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Conditions -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="accept_conditions"
                                required
                                class="mt-1">
                            <span class="text-sm text-gray-700">
                                J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> et les <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                            </span>
                        </label>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4">
                        <a href="{{ route('panier.index') }}" class="flex-1 px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-bold text-center">
                            ← Retour au panier
                        </a>
                        <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold">
                            Confirmer le Paiement →
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résumé de la Commande -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📦 Résumé</h2>

                <!-- Produits -->
                <div class="space-y-4 mb-6 pb-6 border-b border-gray-200 max-h-96 overflow-y-auto">
                    @foreach($items as $item)
                        <div class="flex justify-between text-sm">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->produit->nom }}</p>
                                <p class="text-gray-600">x {{ $item->quantite }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">{{ number_format($item->quantite * $item->prix_unitaire, 0, '', ' ') }} F CFA</p>
                        </div>
                    @endforeach
                </div>

                <!-- Totaux -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Sous-total</span>
                        <span class="font-semibold">{{ number_format($total, 0, '', ' ') }} F CFA</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Livraison</span>
                        <span class="font-semibold" id="shipping-cost">
                            @if($total > 100)
                                Gratuit
                            @else
                                2 500 F CFA
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-between text-xl font-bold text-gray-900 pt-6 border-t-2 border-gray-200">
                    <span>Total TTC</span>
                    <span id="total-amount">
                        @if($total > 100)
                            {{ number_format($total, 0, '', ' ') }} F CFA
                        @else
                            {{ number_format($total + 2500, 0, '', ' ') }} F CFA
                        @endif
                    </span>
                </div>

                <!-- Informations Client -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-3">👤 Vos Informations</h3>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-700"><strong>Nom:</strong> {{ auth()->user()->name }}</p>
                        <p class="text-gray-700"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    input[type="radio"]:checked + span {
        color: #2563eb;
    }

    label input[type="radio"]:checked {
        accent-color: #2563eb;
    }
</style>
@endsection
