<div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-primary-200 animate-fade-in-up">
    <!-- Image du produit -->
    <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
        @if($produit->image)
            <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-accent-50">
                <x-icon name="electronics/desktop-computer" class="w-16 h-16 text-primary-400" />
            </div>
        @endif

        <!-- Overlay on hover -->
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-0 group-hover:opacity-40 transition-opacity duration-300"></div>

        <!-- Badge de catégorie -->
        @if($produit->categorie)
            <div class="absolute top-3 right-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-primary-500/50 backdrop-blur-sm">
                {{ $produit->categorie->nom }}
            </div>
        @endif

        <!-- Badge de stock -->
        <div class="absolute top-3 left-3">
            @if($produit->stock <= 0)
                <span class="inline-flex items-center gap-1.5 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    🔴 Rupture
                </span>
            @elseif($produit->stock < 5)
                <span class="inline-flex items-center gap-1.5 bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    🟡 Stock faible
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    🟢 {{ $produit->stock }} stock
                </span>
            @endif
        </div>

        <!-- Wishlist button -->
        <!-- Wishlist button -->
        <button
            onclick="toggleFavorite({{ $produit->id }}, event)"
            data-favorite-btn="{{ $produit->id }}"
            class="absolute top-3 right-12 p-2 bg-white rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-primary-50 text-2xl"
        >
            🤍
        </button>
    </div>

    <!-- Contenu de la carte -->
    <div class="p-5 space-y-3">
        <!-- Nom du produit -->
        <h3 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-primary-600 transition-colors duration-300">
            {{ $produit->nom }}
        </h3>

        <!-- Description courte -->
        <p class="text-sm text-gray-600 line-clamp-2">
            {{ Str::limit($produit->description, 60) }}
        </p>

        <!-- Vendeur -->
        @if($produit->vendeur)
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg">
                <span class="text-sm">🏪</span>
                <span class="text-xs text-gray-700">
                    <span class="font-semibold">{{ $produit->vendeur->shop_name ?? $produit->vendeur->name }}</span>
                </span>
            </div>
        @endif

        <!-- Note et Avis -->
        <div class="flex items-center gap-2">
            <div class="flex gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-sm">{{ $i <= round($produit->note_moyenne ?? 4.5) ? '⭐' : '☆' }}</span>
                @endfor
            </div>
            <span class="text-xs text-gray-600">({{ $produit->nombre_avis ?? 0 }} avis)</span>
        </div>

        <!-- Prix et Réduction -->
        <div class="space-y-3 pt-2 border-t border-gray-100">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                    {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                </span>
                @if($produit->prix_original && $produit->prix_original > $produit->prix)
                    <span class="text-xs text-gray-500 line-through">
                        {{ number_format($produit->prix_original, 2, ',', ' ') }} €
                    </span>
                    <span class="text-xs font-bold text-red-500">
                        -{{ round(((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100)) }}%
                    </span>
                @endif
            </div>

            <!-- Boutons d'action -->
            <div class="flex gap-2 pt-2">
                <a href="{{ route('produits.show', $produit->id) }}" class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-100 text-gray-900 font-semibold rounded-xl hover:bg-primary-50 hover:text-primary-600 transition-all duration-300 text-sm group/btn">
                    <x-icon name="navigation/forward" class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" />
                    Voir
                </a>
                @if($produit->stock > 0)
                    <button
                        type="button"
                        onclick="openQuantityModal({{ $produit->id }}, '{{ $produit->nom }}', {{ $produit->stock }}, {{ $produit->prix }})"
                        class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-primary-500/50 hover:scale-105 transition-all duration-300 text-sm cursor-pointer"
                    >
                        <x-icon name="commerce/shopping-cart" class="w-4 h-4" />
                        Ajouter
                    </button>
                @else
                    <button disabled class="flex-1 px-3 py-2.5 bg-gray-200 text-gray-500 font-semibold rounded-xl cursor-not-allowed text-sm">
                        Indisponible
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Check favorite status for this product when component loads
    document.addEventListener('DOMContentLoaded', function() {
        checkFavoriteStatus({{ $produit->id }});
    }, { once: true });
</script>
