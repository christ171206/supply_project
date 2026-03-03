<div class="group relative bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
    <!-- Image du produit - Optimisée -->
    <div class="relative h-72 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden flex items-center justify-center">
        @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
            <img src="{{ asset('storage/produits/' . $produit->images[0]) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @elseif($produit->image)
            <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @endif

        <!-- Placeholder par défaut (SVG Supply Discret) -->
        <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 group-hover:from-gray-100 group-hover:to-gray-150 transition-colors duration-300" id="placeholder-{{ $produit->id }}" @if($produit->images && is_array($produit->images) && count($produit->images) > 0 || $produit->image) style="display: none;" @endif>
            <svg class="w-32 h-32 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-400">Image indisponible</p>
        </div>

        <!-- Overlay sombre au hover -->
        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>

        <!-- Badge de catégorie - Optimisé pour lisibilité -->
        @if($produit->categorie)
            <div class="absolute top-3 right-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg hover:shadow-xl transition-all whitespace-nowrap max-w-xs truncate">
                {{ $produit->categorie->nom }}
            </div>
        @endif

        <!-- Indicateur de stock - Amélioré -->
        <div class="absolute top-3 left-3">
            @if($produit->stock > 0)
                <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg hover:shadow-xl transition-all">
                    <span class="text-sm">✓</span>
                    {{ $produit->stock }} en stock
                </div>
            @else
                <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    <span class="text-sm">✗</span>
                    Rupture de stock
                </div>
            @endif
        </div>

        <!-- Wishlist button - Plus visible et accessible -->
        <button
            onclick="toggleFavorite({{ $produit->id }}, event)"
            data-favorite-btn="{{ $produit->id }}"
            class="absolute bottom-3 right-3 p-2.5 bg-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transform scale-0 group-hover:scale-100 transition-all duration-300 hover:bg-primary-50 text-2xl hover:shadow-xl active:scale-95"
            title="Ajouter aux favoris"
        >
            🤍
        </button>
    </div>

    <!-- Contenu de la carte - Structure robuste et flexible -->
    <div class="p-5 flex flex-col h-full">
        <!-- Nom du produit - Rigoreux et lisible -->
        <h3 class="text-base font-bold text-gray-900 line-clamp-1 mb-2 group-hover:text-primary-600 transition-colors">
            {{ $produit->nom }}
        </h3>

        <!-- Description courte + Vendeur - Zone flexible -->
        <div class="flex-grow flex flex-col mb-4">
            <!-- Description -->
            <p class="text-sm text-gray-700 line-clamp-2 leading-relaxed font-medium">
                {{ Str::limit($produit->description, 60) }}
            </p>

            <!-- Vendeur - Mieux visible -->
            @if($produit->vendeur)
                <div class="mt-auto pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-600 font-medium">
                        🏪 <span class="text-gray-800 font-semibold">{{ Str::limit($produit->vendeur->shop_name ?? $produit->vendeur->name, 20) }}</span>
                    </p>
                </div>
            @else
                <div class="mt-auto pt-3 border-t border-gray-200"></div>
            @endif
        </div>

        <!-- Section Prix + Actions (toujours en bas) -->
        <div class="mt-auto pt-4 space-y-3">
            <!-- Prix - Ultra-visible et attirant -->
            <div class="bg-gradient-to-br from-primary-50 via-primary-25 to-accent-50 rounded-xl p-4 border-2 border-primary-200 hover:border-primary-300 transition-colors">
                <span class="block text-3xl font-black text-primary-700 leading-tight">
                    {{ number_format($produit->prix, 0, ',', ' ') }}
                </span>
                <span class="text-sm font-bold text-primary-600 uppercase tracking-widest">FCFA</span>
            </div>

            <!-- Boutons d'action - Ultra-visible et séducteur -->
            <div class="flex gap-2.5 w-full">
                <a href="{{ route('produits.show', $produit->id) }}"
                   class="flex-1 px-4 py-3 bg-gray-100 text-gray-900 font-semibold rounded-lg hover:bg-gray-200 active:bg-gray-300 transition-all duration-150 text-center text-sm border border-gray-300 hover:border-gray-400"
                   title="Voir les détails du produit"
                   aria-label="Afficher tous les détails du produit {{ $produit->nom }}">
                    👁️ Détails
                </a>
                @if($produit->stock > 0)
                    <button
                        type="button"
                        onclick="openQuantityModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->stock }}, {{ $produit->prix }})"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-lg hover:from-primary-700 hover:to-primary-800 active:scale-95 shadow-md hover:shadow-lg transition-all duration-150 text-sm"
                        aria-label="Ajouter {{ $produit->nom }} au panier ({{ $produit->stock }} en stock)"
                        title="Ajouter {{ $produit->nom }} au panier"
                    >
                        🛒 Ajouter
                    </button>
                @else
                    <button disabled class="flex-1 px-4 py-3 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed text-sm opacity-60" aria-disabled="true" title="Ce produit est actuellement indisponible">
                        Indisponible
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Hide placeholder when image loads successfully
    document.addEventListener('DOMContentLoaded', function() {
        const placeholderId = `placeholder-{{ $produit->id }}`;
        const imgs = document.querySelectorAll(`img[alt="{{ addslashes($produit->nom) }}"]`);

        imgs.forEach(img => {
            if (img.complete && img.naturalHeight !== 0) {
                // Image already loaded
                const placeholder = document.getElementById(placeholderId);
                if (placeholder) placeholder.style.display = 'none';
            } else {
                // Wait for load event
                img.addEventListener('load', function() {
                    const placeholder = document.getElementById(placeholderId);
                    if (placeholder) placeholder.style.display = 'none';
                });
                img.addEventListener('error', function() {
                    // Keep placeholder visible on error
                    const placeholder = document.getElementById(placeholderId);
                    if (placeholder) placeholder.style.display = 'flex';
                });
            }
        });

        checkFavoriteStatus({{ $produit->id }});
    });
</script>
