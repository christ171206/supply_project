@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">⚙️ Paramètres</h1>
        <p class="text-gray-600">Configurez votre boutique</p>
    </div>

    <!-- Messages Flash -->
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
        <!-- Colonne principale: Formulaire -->
        <div class="lg:col-span-2">
            <!-- Paramètres Boutique -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">🏪 Paramètres Boutique</h2>

                <form method="POST" action="{{ route('vendeur.parametres.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nom Boutique -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom de la Boutique
                        </label>
                        <input type="text" name="shop_name" 
                               value="{{ auth()->user()->shop_name ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Entrez le nom de votre boutique">
                        @error('shop_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Boutique -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description Boutique
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                  placeholder="Décrivez votre boutique...">{{ auth()->user()->description ?? '' }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel" name="phone" 
                               value="{{ auth()->user()->phone ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="+225 XX XX XX XX XX">
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse
                        </label>
                        <input type="text" name="address" 
                               value="{{ auth()->user()->address ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Votre adresse complète">
                        @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Minimum Défaut -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Stock Minimum par Défaut
                        </label>
                        <input type="number" name="stock_minimum_defaut" 
                               value="{{ auth()->user()->stock_minimum_defaut ?? 10 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="10">
                        @error('stock_minimum_defaut')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton Sauvegarder -->
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                            💾 Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Colonne sidebar: Actions rapides -->
        <div class="space-y-6">
            <!-- Compte -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Compte</h3>
                
                <div class="space-y-3">
                    <div class="text-sm">
                        <p class="text-gray-600 text-xs">Email</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-600 text-xs">Statut</p>
                        <p class="font-semibold">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                ✓ Actif
                            </span>
                        </p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-600 text-xs">Membre depuis</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <a href="{{ route('vendeur.profil') }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm text-center">
                    👤 Voir Profil
                </a>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-bold text-red-900 mb-4">🚨 Zone Dangereuse</h3>
                
                <p class="text-sm text-red-800 mb-4">
                    Attention: Ces actions sont irréversibles!
                </p>

                <form method="POST" action="{{ route('vendeur.parametres.delete') }}" onsubmit="return confirm('Êtes-vous sûr? Cette action supprimera votre boutique et tous vos produits. Elle est IRRÉVERSIBLE!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-sm">
                        🗑️ Supprimer la Boutique
                    </button>
                </form>
            </div>

            <!-- Aide -->
            <div class="bg-blue-50 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-blue-900 mb-4">❓ Aide</h3>
                
                <ul class="space-y-2 text-sm text-blue-800">
                    <li>✓ Complétez votre profil pour plus de confianceClient</li>
                    <li>✓ Utilisez des photos de bonne qualité</li>
                    <li>✓ Répondez rapidement aux messages</li>
                    <li>✓ Maintenez un bon taux de complétion</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
