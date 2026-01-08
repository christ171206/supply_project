@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">🛒 Mon Panier</h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panier Items -->
        <div class="lg:col-span-2">
            @if($items && count($items) > 0)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="divide-y">
                        @foreach($items as $item)
                            <div class="p-6 flex gap-4 hover:bg-gray-50 transition">
                                <!-- Image Produit -->
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->produit->image)
                                        <img src="{{ asset('storage/produits/' . $item->produit->image) }}" alt="{{ $item->produit->nom }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                            <span class="text-2xl">📦</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Détails Produit -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('produits.show', $item->produit->id) }}" class="hover:text-blue-600">
                                            {{ $item->produit->nom }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($item->produit->description, 100) }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($item->prix_unitaire, 2, ',', ' ') }} €</p>
                                </div>

                                <!-- Quantité et Actions -->
                                <div class="flex flex-col items-end gap-4">
                                    <form action="{{ route('panier.modifier', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantite_{{ $item->id }}" class="text-sm font-medium text-gray-700">Qté:</label>
                                        <input type="number" 
                                               id="quantite_{{ $item->id }}"
                                               name="quantite" 
                                               value="{{ $item->quantite }}" 
                                               min="1" 
                                               max="{{ $item->produit->stock }}"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded text-center"
                                               onchange="this.form.submit()">
                                    </form>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Sous-total</p>
                                        <p class="text-lg font-bold text-gray-900">{{ number_format($item->quantite * $item->prix_unitaire, 2, ',', ' ') }} €</p>
                                    </div>

                                    <form action="{{ route('panier.supprimer', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 p-6 flex gap-4">
                        <a href="{{ route('produits.catalogue') }}" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center font-medium">
                            ← Continuer les achats
                        </a>
                        <form action="{{ route('panier.vider') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                                🗑️ Vider le panier
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Panier Vide -->
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🛒</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Votre panier est vide</h2>
                    <p class="text-gray-600 mb-8">Commencez vos achats en explorant notre catalogue</p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Découvrir nos produits
                    </a>
                </div>
            @endif
        </div>

        <!-- Résumé Panier -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Résumé</h2>

                <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between text-gray-700">
                        <span>Nombre d'articles</span>
                        <span class="font-semibold">{{ $items ? count($items) : 0 }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Sous-total</span>
                        <span class="font-semibold">{{ number_format($total ?? 0, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Frais de livraison</span>
                        <span class="font-semibold">
                            @if(($total ?? 0) > 100)
                                Gratuit
                            @else
                                {{ number_format($fraisLivraison ?? 9.99, 2, ',', ' ') }} €
                            @endif
                        </span>
                    </div>
                    @if($total > 0 && ($total ?? 0) <= 100)
                        <p class="text-xs text-gray-500">Livraison gratuite à partir de 100 €</p>
                    @endif
                </div>

                <div class="flex justify-between text-xl font-bold text-gray-900 mb-6">
                    <span>Total</span>
                    <span>{{ number_format(($total ?? 0) + (($total ?? 0) > 100 ? 0 : ($fraisLivraison ?? 9.99)), 2, ',', ' ') }} €</span>
                </div>

                @if($items && count($items) > 0)
                    @auth
                        <a href="{{ route('commandes.create') }}" class="w-full block px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-center mb-3">
                            Procéder au paiement
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full block px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-center mb-3">
                            Se connecter pour commander
                        </a>
                    @endauth

                    <button type="button" class="w-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 transition font-medium">
                        Continuer les achats
                    </button>
                @endif

                <!-- Informations Supplémentaires -->
                <div class="mt-8 pt-6 border-t border-gray-200 space-y-3 text-sm text-gray-600">
                    <div class="flex items-start gap-2">
                        <span>🚚</span>
                        <p><strong>Livraison rapide</strong> - 2 à 5 jours ouvrables</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span>🔒</span>
                        <p><strong>Paiement sécurisé</strong> - Vos données sont protégées</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span>↩️</span>
                        <p><strong>Retours gratuits</strong> - 30 jours pour changer d'avis</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
