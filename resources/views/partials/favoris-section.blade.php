{{-- Section Favoris Minimaliste --}}
<div class="max-w-7xl mx-auto px-8 py-16 border-b border-gray-200">
    <h2 class="text-2xl font-display font-bold text-black mb-2">Vos Favoris</h2>
    <p class="text-gray-600 text-sm mb-8">Produits que vous aimez</p>

    @auth
        @php
            $userFavorites = auth()->user()->produitsFavoris()->limit(8)->with('categorie', 'vendeur')->get();
        @endphp

        @if($userFavorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                @foreach($userFavorites as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('favoris.index') }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg hover:opacity-85 transition-opacity duration-150">
                    Voir tous mes {{ auth()->user()->produitsFavoris()->count() }} favoris
                </a>
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
                <p class="text-gray-600 mb-6">Vous n'avez pas encore de favoris</p>
                <a href="{{ route('produits.catalogue') }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg hover:opacity-85 transition-opacity duration-150">
                    Parcourir le catalogue
                </a>
            </div>
        @endif
    @else
        <div id="favorites-home-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        </div>

        <div class="bg-black text-white rounded-lg p-8 text-center mt-8">
            <h3 class="text-lg font-display font-bold mb-2">Sauvegarde tes favoris</h3>
            <p class="text-gray-300 text-sm mb-6">Crée un compte pour accéder à tes favoris depuis n'importe quel appareil</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('login') }}" class="px-6 py-2 bg-white text-black rounded-lg hover:opacity-85 transition-opacity duration-150 text-sm font-medium">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2 border border-white text-white rounded-lg hover:bg-white/10 transition-colors duration-150 text-sm font-medium">
                    Créer un compte
                </a>
            </div>
        </div>
    @endauth
</div>

@guest
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const favoriteIds = JSON.parse(localStorage.getItem('favorites') || '[]');
        const container = document.getElementById('favorites-home-container');

        if (favoriteIds.length === 0) {
            container.parentElement.innerHTML = `
                <div class="bg-white border border-gray-200 rounded-lg p-12 text-center col-span-full">
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore de favoris</p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block bg-black text-white px-6 py-3 rounded-lg hover:opacity-85 transition-opacity duration-150">
                        Parcourir le catalogue
                    </a>
                </div>
            `;
            return;
        }

        try {
            const response = await fetch(`/api/produits/${favoriteIds.slice(0, 8).join(',')}`);
            if (!response.ok) throw new Error('Failed to load products');

            const produits = await response.json();

            let html = '';

            produits.forEach(produit => {
                const productUrl = `/produits/${produit.id}`;
                const imageUrl = produit.image ? `/storage/produits/${produit.image}` : null;

                html += `
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col h-full">
                        <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                            ${imageUrl
                                ? `<img src="${imageUrl}" alt="${produit.nom}" class="w-full h-full object-cover">`
                                : `<svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>`
                            }
                        </div>

                        <div class="p-4 flex-grow flex flex-col">
                            <h3 class="text-sm font-medium text-black mb-2 line-clamp-2">${produit.nom}</h3>

                            <div class="mb-4 mt-auto">
                                <p class="text-lg font-mono font-bold text-black">${parseFloat(produit.prix).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0})} FCFA</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="${productUrl}" class="flex-1 text-center px-3 py-2 bg-white border border-gray-200 text-black rounded-lg hover:border-black transition-colors duration-150 text-xs font-medium">
                                    Détails
                                </a>
                                <button
                                    onclick="toggleFavorite(${produit.id}, event)"
                                    data-favorite-btn="${produit.id}"
                                    class="px-3 py-2 border border-gray-200 rounded-lg hover:border-black transition-colors duration-150"
                                    title="Retirer des favoris"
                                >
                                    ♡
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
