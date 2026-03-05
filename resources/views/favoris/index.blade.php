@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('accueil') }}" class="hover:text-primary-600 transition">Accueil</a>
        <span>/</span>
        <span class="text-gray-900 font-semibold">Mes Favoris</span>
    </nav>

    <!-- Header Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                    <span class="text-4xl">❤️</span>
                    Mes Favoris
                </h1>
                @auth
                    <p class="text-lg text-gray-600">{{ $favoris->total() }} produit(s) sauvegardé(s)</p>
                @else
                    <p class="text-lg text-gray-600" id="favorite-count">Chargement de vos favoris...</p>
                @endauth
            </div>
            <div class="hidden md:block text-6xl">💝</div>
        </div>
    </div>

    @auth
        @if($favoris->count() > 0)
            <!-- Grille de produits -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach($favoris as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @endforeach
            </div>

            <!-- Pagination -->
            @if($favoris->hasPages())
                <div class="flex justify-center mb-12">
                    {{ $favoris->links() }}
                </div>
            @endif

            <!-- Actionner Section -->
            <div class="bg-gradient-to-r from-primary-50 to-accent-50 rounded-2xl p-8 border border-primary-100 text-center">
                <p class="text-gray-700 mb-6 text-lg">Prêt à commander? Consultez vos articles favoris et ajoutez-les au panier.</p>
                <a href="{{ route('panier.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 flex items-center gap-2">
                    <x-heroicon-o-shopping-cart class="w-5 h-5" /><span>Voir mon panier</span>
                </a>
            </div>
        @else
            <!-- Message d'état vide -->
            <div class="text-center py-20">
                <div class="text-7xl mb-6 animate-bounce">
                    <x-heroicon-o-heart class="w-16 h-16 inline text-red-500" />
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Aucun favori pour le moment</h2>
                <p class="text-gray-600 mb-10 text-lg max-w-md mx-auto">
                    Explorez notre catalogue et ajoutez vos produits préférés en cliquant sur le cœur!
                </p>
                <a href="{{ route('produits.catalogue') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-xl transition duration-200 text-lg flex items-center gap-2">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" /><span>Découvrir des produits</span>
                </a>
            </div>
        @endif
    @else
        <!-- Favoris localStorage (Non connecté) -->
        <div id="favorites-container">
            <div class="text-center py-20">
                <div class="inline-block mb-6 p-4 bg-primary-50 rounded-full">
                    <svg class="w-12 h-12 text-primary-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600 mb-8 text-lg">Chargement de vos favoris...</p>
            </div>
        </div>

        <!-- Section authentification -->
        <div class="text-center mt-16 bg-gradient-to-r from-primary-50 to-accent-50 rounded-2xl p-12 border border-primary-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center justify-center gap-2"><x-heroicon-o-light-bulb class="w-6 h-6" /><span>Stocke tes favoris définitivement</span></h2>
            <p class="text-gray-600 mb-8 text-lg max-w-md mx-auto">
                Connecte-toi pour sauvegarder tous tes produits préférés et y accéder depuis n'importe quel appareil.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 flex items-center gap-2">
                    <x-heroicon-o-lock-closed class="w-5 h-5" /><span>Se connecter</span>
                </a>
                <a href="{{ route('register') }}" class="inline-block px-8 py-3 border-2 border-accent-500 text-accent-600 font-bold rounded-lg hover:bg-accent-50 transition duration-200 flex items-center gap-2">
                    <x-heroicon-o-pencil-square class="w-5 h-5" /><span>Créer un compte</span>
                </a>
            </div>
        </div>
    @endguest
</div>

@guest
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const favoriteIds = JSON.parse(localStorage.getItem('favorites') || '[]');
        const container = document.getElementById('favorites-container');

        // Mise à jour du compteur
        document.getElementById('favorite-count').textContent = `${favoriteIds.length} produit(s) sauvegardé(s)`;

        if (favoriteIds.length === 0) {
            container.innerHTML = `
                <div class="text-center py-20">
                    <div class="text-7xl mb-6">💔</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Aucun favori pour le moment</h2>
                    <p class="text-gray-600 mb-10 text-lg max-w-md mx-auto">
                        Explorez notre catalogue et ajoutez vos produits préférés en cliquant sur le cœur!
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-xl transition duration-200 text-lg">
                        🔍 Découvrir des produits
                    </a>
                </div>
            `;
            return;
        }

        try {
            // Charger les détails des produits favorisés
            const response = await fetch(`/api/produits/${favoriteIds.join(',')}`);
            if (!response.ok) throw new Error('Failed to load products');

            const produits = await response.json();

            let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">';

            produits.forEach(produit => {
                const productUrl = `/produits/${produit.id}`;
                const imageUrl = produit.image ? `/storage/produits/${produit.image}` : null;

                html += `
                    <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-primary-200 h-full flex flex-col">
                        <!-- Image -->
                        <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            ${imageUrl
                                ? `<img src="${imageUrl}" alt="${produit.nom}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">`
                                : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                                </div>`
                            }
                        </div>

                        <!-- Contenu -->
                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition">${produit.nom}</h3>

                            <!-- Prix -->
                            <div class="mb-4 mt-auto">
                                <p class="text-3xl font-black text-primary-600">${parseFloat(produit.prix).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0})} FCFA</p>
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-2">
                                <a href="${productUrl}" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-900 font-semibold rounded-xl hover:bg-primary-50 text-sm text-center transition duration-200">
                                    Détails
                                </a>
                                <button
                                    onclick="toggleFavorite(${produit.id}, event)"
                                    data-favorite-btn="${produit.id}"
                                    class="px-4 py-2.5 text-xl border-2 border-gray-300 rounded-xl hover:border-primary-400 transition duration-200"
                                    title="Retirer des favoris"
                                    aria-label="Retirer ce produit des favoris"
                                >
                                    ❤️
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            html += `
                <div class="bg-gradient-to-r from-primary-50 to-accent-50 rounded-2xl p-8 border border-primary-100 text-center">
                    <p class="text-gray-700 mb-6 text-lg">💡 <strong>Stocke tes favoris définitivement</strong> en te connectant à ton compte!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200">
                            🔐 Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 border-2 border-accent-500 text-accent-600 font-bold rounded-lg hover:bg-accent-50 transition duration-200">
                            📝 Créer un compte
                        </a>
                    </div>
                </div>
            `;
            container.innerHTML = html;
        } catch (error) {
            console.error('Error loading favorites:', error);
            container.innerHTML = `
                <div class="text-center py-16">
                    <div class="text-6xl mb-4"><x-heroicon-o-exclamation-triangle class="w-16 h-16" /></div>
                    <p class="text-gray-600 text-lg">Erreur lors du chargement des favoris</p>
                    <p class="text-gray-500 mt-2 text-sm">Veuillez rafraîchir la page</p>
                </div>
            `;
        }
    });
</script>
@endguest

@endsection

