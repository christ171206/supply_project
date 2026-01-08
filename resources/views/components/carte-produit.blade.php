<div class="group relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 overflow-hidden border border-slate-700/50 hover:border-indigo-500/50">
    <!-- Image du produit -->
    <div class="relative h-56 bg-gradient-to-br from-slate-700 to-slate-800 overflow-hidden">
        @if($produit->image)
            <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500/20 to-violet-500/20">
                <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
        
        <!-- Overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent opacity-0 group-hover:opacity-80 transition-opacity duration-300"></div>
        
        <!-- Badge de catégorie -->
        @if($produit->categorie)
            <div class="absolute top-3 right-3 bg-gradient-to-r from-indigo-500 to-violet-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-indigo-500/50 backdrop-blur-sm">
                {{ $produit->categorie->nom }}
            </div>
        @endif

        <!-- Badge de stock -->
        <div class="absolute top-3 left-3">
            @if($produit->stock <= 0)
                <span class="inline-block bg-gradient-to-r from-red-600 to-pink-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/50">
                    ❌ Rupture
                </span>
            @elseif($produit->stock < 5)
                <span class="inline-block bg-gradient-to-r from-amber-500 to-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-amber-500/50">
                    ⚠️ Limité
                </span>
            @else
                <span class="inline-block bg-gradient-to-r from-emerald-500 to-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-emerald-500/50">
                    ✓ {{ $produit->stock }} en stock
                </span>
            @endif
        </div>
    </div>

    <!-- Contenu de la carte -->
    <div class="p-5 space-y-4">
        <!-- Nom du produit -->
        <h3 class="text-lg font-bold text-slate-100 line-clamp-2 group-hover:text-indigo-300 transition-colors duration-300">
            {{ $produit->nom }}
        </h3>

        <!-- Description courte -->
        <p class="text-sm text-slate-400 line-clamp-2">
            {{ Str::limit($produit->description, 60) }}
        </p>

        <!-- Prix et stock -->
        <div class="space-y-3 pt-2 border-t border-slate-700/50">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                    {{ number_format($produit->prix, 2, ',', ' ') }} €
                </span>
                @if($produit->prix_original && $produit->prix_original > $produit->prix)
                    <span class="text-xs text-slate-500 line-through">
                        {{ number_format($produit->prix_original, 2, ',', ' ') }} €
                    </span>
                @endif
            </div>

            <!-- Boutons d'action -->
            <div class="flex gap-2 pt-2">
                <a href="{{ route('produits.show', $produit->id) }}" class="flex-1 px-3 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-slate-200 font-semibold rounded-lg hover:from-indigo-600 hover:to-violet-600 hover:text-white transition-all duration-300 text-center text-sm">
                    👁️ Voir
                </a>
                @if($produit->stock > 0)
                    <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="w-full px-3 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-indigo-500/50 transition-all duration-300 text-sm">
                            🛒 Ajouter
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
