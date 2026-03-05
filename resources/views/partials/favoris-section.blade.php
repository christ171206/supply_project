<!-- Section Favoris -->
<div class="relative py-20 bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <!-- Éléments décoratifs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 right-40 w-96 h-96 bg-accent-100/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 left-40 w-96 h-96 bg-primary-100/20 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-6 px-6 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-bold">
                💝 VOS SÉLECTIONS
            </div>
            <h2 class="text-5xl font-bold text-gray-900 mb-4">Produits que Vous Aimez</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Consultez vos articles favoris et découvrez vos futures acquisitions
            </p>
        </div>

        @auth
            <!-- Utilisateur connecté -->
            @php
                $userFavorites = auth()->user()->produitsFavoris()->limit(8)->with('categorie', 'vendeur')->get();
            @endphp

            @if($userFavorites->count() > 0)
                <!-- Afficher les favoris -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    @foreach($userFavorites as $produit)
                        @include('components.carte-produit', ['produit' => $produit])
                    @endforeach
                </div>

                <!-- CTA vers la page complète -->
                <div class="text-center">
                    <a href="{{ route('favoris.index') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 text-lg">
                        ❤️ Voir tous mes {{ auth()->user()->produitsFavoris()->count() }} favoris
                    </a>
                </div>
            @else
                <!-- Pas de favoris - Invitation -->
                <div class="bg-white rounded-2xl shadow-xl p-12 border-2 border-dashed border-accent-300 text-center">
                    <div class="text-7xl mb-6">🤍</div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Commencez vos favoris!</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Cliquez sur le cœur de vos produits préférés pour les retrouver facilement à tout moment.
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 text-lg">
                        🔍 Parcourir le catalogue
                    </a>
                </div>
            @endif
        @else
            <!-- Utilisateur non connecté - localStorage -->
            <div id="favorites-home-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Chargement dynamique via JavaScript -->
            </div>

            <!-- Section incitation authentification -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl shadow-2xl p-12 text-white text-center mt-12">
                <div class="text-5xl mb-6">💝</div>
                <h3 class="text-3xl font-bold mb-4">Sauvegarde tes favoris définitivement</h3>
                <p class="text-primary-100 text-lg mb-8 max-w-md mx-auto">
                    Crée un compte pour accéder à tes favoris depuis n'importe quel appareil et bénéficier d'une meilleure expérience.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-white text-primary-600 font-bold rounded-lg hover:shadow-lg transition duration-200">
                        <x-heroicon-o-lock-closed class=\"w-5 h-5\" /><span>Se connecter</span>
                    </a>
                    <a href="{{ route('register') }}" class="inline-block px-10 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition duration-200">
                        📝 Créer un compte
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>

@guest
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const favoriteIds = JSON.parse(localStorage.getItem('favorites') || '[]');
        const container = document.getElementById('favorites-home-container');

        if (favoriteIds.length === 0) {
            // Pas de favoris - afficher le message
            container.parentElement.innerHTML = `
                <div class="bg-white rounded-2xl shadow-xl p-12 border-2 border-dashed border-accent-300 text-center">
                    <div class="text-7xl mb-6">🤍</div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Commencez vos favoris!</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Cliquez sur le cœur de vos produits préférés pour les retrouver facilement à tout moment.
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:shadow-lg transition duration-200 text-lg">
                        🔍 Parcourir le catalogue
                    </a>
                </div>
            `;
            return;
        }

        try {
            // Charger les détails des produits
            const response = await fetch(`/api/produits/${favoriteIds.slice(0, 8).join(',')}`);
            if (!response.ok) throw new Error('Failed to load products');

            const produits = await response.json();

            let html = '';

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
                                >
                                    ❤️
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            if (html) {
                container.innerHTML = html;
            }
        } catch (error) {
            console.error('Error loading favorites:', error);
        }
    });
</script>
@endguest
