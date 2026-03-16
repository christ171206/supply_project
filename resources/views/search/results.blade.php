@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 md:px-8 py-8 pb-20">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif text-[32px] sm:text-[40px] text-[#0a0a0a] mb-2">
            Résultats de recherche
        </h1>
        <p class="text-[14px] text-[#666660]">
            @if(request('q'))
                Résultats pour "<strong>{{ request('q') }}</strong>"
                <span class="text-[#a0a09a]">— {{ $products->total() }} produit(s) trouvé(s)</span>
            @else
                Parcourez nos produits
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">

        {{-- Sidebar: Filtres --}}
        <aside class="h-fit sticky top-20">
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">

                {{-- Catégories --}}
                <div>
                    <h3 class="text-[13px] font-medium tracking-[0.1em] uppercase text-[#0a0a0a] mb-4">Catégories</h3>
                    <div class="space-y-2">
                        @forelse(\App\Models\Categorie::all() as $cat)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="category" value="{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'checked' : '' }}
                                    class="w-4 h-4 border border-[#e0e0dc] rounded accent-[#0a0a0a]">
                                <span class="text-[13px] text-[#666660] group-hover:text-[#0a0a0a] transition-colors">
                                    {{ $cat->nom }}
                                </span>
                            </label>
                        @empty
                            <p class="text-[12px] text-[#a0a09a]">Aucune catégorie</p>
                        @endforelse
                    </div>
                </div>

                {{-- Tri --}}
                <div>
                    <h3 class="text-[13px] font-medium tracking-[0.1em] uppercase text-[#0a0a0a] mb-4">Trier par</h3>
                    <select id="sort-select" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] outline-none focus:border-[#0a0a0a]">
                        <option value="relevance" {{ $sort === 'relevance' ? 'selected' : '' }}>Pertinence</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Nouveauté</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Prix: bas → haut</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Prix: haut → bas</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Populaire</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Meilleur avis</option>
                    </select>
                </div>

                {{-- Appliquer --}}
                <button type="button" onclick="applyFilters()"
                        class="w-full bg-[#0a0a0a] text-white text-[13px] font-medium py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    Appliquer les filtres
                </button>

            </div>
        </aside>

        {{-- Main: Products Grid --}}
        <main>
            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach($products as $produit)
                        <a href="{{ route('produits.show', $produit->id) }}" class="group">
                            <div class="border border-[#e0e0dc] rounded-lg overflow-hidden hover:border-[#0a0a0a] transition-all hover:shadow-sm">
                                {{-- Image --}}
                                <div class="aspect-square bg-[#f7f7f5] overflow-hidden flex items-center justify-center">
                                    @if($produit->image)
                                        <img src="{{ asset('storage/produits/' . $produit->image) }}"
                                             alt="{{ $produit->nom }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <span class="text-[48px]">📦</span>
                                    @endif
                                </div>
                                {{-- Info --}}
                                <div class="p-4">
                                    <h3 class="text-[13px] font-medium text-[#0a0a0a] line-clamp-2 mb-2 group-hover:text-[#2a2a28]">
                                        {{ $produit->nom }}</h3>

                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-mono font-bold text-[14px] text-[#0a0a0a]">
                                            {{ number_format($produit->prix, 0, ',', ' ') }} F
                                        </span>
                                        @if(isset($produit->badge) && $produit->badge)
                                            <span class="text-[10px] font-medium text-[#f59e0b]">{{ $produit->badge }}</span>
                                        @endif
                                    </div>

                                    {{-- Avis --}}
                                    @if(isset($produit->avg_rating) && $produit->avg_rating > 0)
                                        <div class="flex items-center gap-1 mb-3">
                                            <div class="flex gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-[11px] {{ $i <= round($produit->avg_rating) ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="text-[10px] text-[#a0a09a]">({{ isset($produit->review_count) ? $produit->review_count : 0 }})</span>
                                        </div>
                                    @endif

                                    {{-- Stock --}}
                                    <div class="text-[11px] {{ $produit->stock > 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                                        {{ $produit->stock > 0 ? '✓ ' . $produit->stock . ' en stock' : '✗ Rupture' }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="flex items-center justify-center gap-2 mt-12">
                        @if($products->onFirstPage())
                            <button disabled class="px-3 py-2 rounded-lg text-[#a0a09a] cursor-not-allowed">← Précédent</button>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg text-[#0a0a0a] hover:border-[#0a0a0a] transition-colors">← Précédent</a>
                        @endif

                        <div class="flex gap-1">
                            @for($i = 1; $i <= $products->lastPage(); $i++)
                                @if($i === $products->currentPage())
                                    <span class="px-3 py-2 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium">{{ $i }}</span>
                                @else
                                    <a href="{{ $products->url($i) }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg text-[#0a0a0a] hover:bg-[#f7f7f5] transition-colors">{{ $i }}</a>
                                @endif
                            @endfor
                        </div>

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg text-[#0a0a0a] hover:border-[#0a0a0a] transition-colors">Suivant →</a>
                        @else
                            <button disabled class="px-3 py-2 rounded-lg text-[#a0a09a] cursor-not-allowed">Suivant →</button>
                        @endif
                    </div>
                @endif

            @else
                <div class="text-center py-16">
                    <div class="text-[64px] mb-4">🔍</div>
                    <h2 class="text-[24px] font-serif text-[#0a0a0a] mb-2">Aucun résultat</h2>
                    <p class="text-[14px] text-[#666660] mb-8">
                        Aucun produit n'a été trouvé pour votre recherche.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('produits.catalogue') }}" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                            Parcourir le catalogue
                        </a>
                        <a href="{{ route('accueil') }}" class="px-6 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#0a0a0a] hover:border-[#0a0a0a] transition-colors">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            @endif
        </main>

    </div>

</div>

<script>
function applyFilters() {
    const category = document.querySelector('input[name="category"]:checked')?.value || '';
    const sort = document.getElementById('sort-select').value;
    const q = new URLSearchParams(window.location.search).get('q') || '';

    const params = new URLSearchParams();
    if (q) params.append('q', q);
    if (category) params.append('category', category);
    if (sort) params.append('sort', sort);

    window.location.href = `/search/results?${params.toString()}`;
}

// Appliquer les filtres au changement du tri
document.getElementById('sort-select').addEventListener('change', applyFilters);

// Appliquer au clic sur checkbox
document.querySelectorAll('input[name="category"]').forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
});
</script>

@endsection
