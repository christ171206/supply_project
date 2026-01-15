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
                    <x-icon name="status/out-of-stock" class="w-4 h-4" />
                    Rupture
                </span>
            @elseif($produit->stock < 5)
                <span class="inline-flex items-center gap-1.5 bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    <x-icon name="status/low-stock" class="w-4 h-4" />
                    Limité
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    <x-icon name="status/in-stock" class="w-4 h-4" />
                    {{ $produit->stock }} stock
                </span>
            @endif
        </div>

        <!-- Wishlist button -->
        <button class="absolute top-3 right-12 p-2 bg-white rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-primary-50">
            <x-icon name="user/wishlist" class="w-5 h-5 text-accent-500 hover:text-accent-600" />
        </button>
    </div>

    <!-- Contenu de la carte -->
    <div class="p-5 space-y-4">
        <!-- Nom du produit -->
        <h3 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-primary-600 transition-colors duration-300">
            {{ $produit->nom }}
        </h3>

        <!-- Description courte -->
        <p class="text-sm text-gray-600 line-clamp-2">
            {{ Str::limit($produit->description, 60) }}
        </p>

        <!-- Prix et Réduction -->
        <div class="space-y-3 pt-2 border-t border-gray-100">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                    {{ number_format($produit->prix, 2, ',', ' ') }} €
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
                    <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-primary-500/50 hover:scale-105 transition-all duration-300 text-sm">
                            <x-icon name="commerce/shopping-cart" class="w-4 h-4" />
                            Ajouter
                        </button>
                    </form>
                @else
                    <button disabled class="flex-1 px-3 py-2.5 bg-gray-200 text-gray-500 font-semibold rounded-xl cursor-not-allowed text-sm">
                        Indisponible
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
