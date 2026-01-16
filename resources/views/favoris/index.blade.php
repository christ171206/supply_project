@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">❤️ Mes Favoris</h1>
        @auth
            <p class="text-gray-600">{{ $favoris->total() }} produit(s) en favoris</p>
        @else
            <p class="text-gray-600" id="favorite-count">Chargement des favoris...</p>
        @endauth
    </div>

    @auth
        @if($favoris->count() > 0)
            <!-- Grille de produits (Utilisateur connecté) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach($favoris as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $favoris->links() }}
            </div>
        @else
            <!-- Message vide (Utilisateur connecté) -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">💔</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucun favori pour le moment</h2>
                <p class="text-gray-600 mb-8">Commencez à ajouter vos produits préférés en cliquant sur le cœur!</p>
                <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                    🔍 Parcourir le catalogue
                </a>
            </div>
        @endif
    @else
        <!-- Favoris localStorage (Utilisateur non connecté) -->
        <div id="favorites-container">
            <div class="text-center py-16">
                <p class="text-gray-600 mb-8">Chargement de vos favoris...</p>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-gray-600 mb-4">💡 Connecte-toi pour sauvegarder tes favoris définitivement!</p>
            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:shadow-lg transition mr-4">
                🔐 Se connecter
            </a>
            <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-secondary-500 to-secondary-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                📝 S'inscrire
            </a>
        </div>
    @endauth
</div>

@guest
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const favoriteIds = JSON.parse(localStorage.getItem('favorites') || '[]');
        const container = document.getElementById('favorites-container');

        // Update count
        document.getElementById('favorite-count').textContent = `${favoriteIds.length} produit(s) en favoris`;

        if (favoriteIds.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">💔</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucun favori pour le moment</h2>
                    <p class="text-gray-600 mb-8">Commencez à ajouter vos produits préférés en cliquant sur le cœur!</p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                        🔍 Parcourir le catalogue
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
                html += `
                    <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-primary-200">
                        <div class="relative h-56 bg-gray-200">
                            <img src="/storage/produits/${produit.image}" alt="${produit.nom}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">${produit.nom}</h3>
                            <p class="text-3xl font-bold text-primary-600">${produit.prix} FCFA</p>
                            <div class="flex gap-2">
                                <a href="/produits/${produit.id}" class="flex-1 px-3 py-2.5 bg-gray-100 text-gray-900 font-semibold rounded-xl hover:bg-primary-50 text-sm text-center">
                                    Voir
                                </a>
                                <button onclick="toggleFavorite(${produit.id}, event)" data-favorite-btn="${produit.id}" class="text-2xl">❤️</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        } catch (error) {
            console.error('Error loading favorites:', error);
            container.innerHTML = `
                <div class="text-center py-16 text-red-600">
                    <p>Erreur lors du chargement des favoris</p>
                </div>
            `;
        }
    });
</script>
@endguest

@endsection

