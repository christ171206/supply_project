@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 py-8 pb-20">

    {{-- Header avec retour à la boutique --}}
    <div class="mb-8">
        <a href="{{ route('vendor.show', $vendor->id) }}"
           class="text-[13px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors flex items-center gap-1 mb-4">
            ← Boutique de {{ $vendor->shop_name ?? $vendor->name }}
        </a>

        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">
            Résultats de recherche
        </h1>
        <p class="text-[14px] text-[#666660]">
            Pour "<strong>{{ $query }}</strong>" — {{ $products->total() }} produit(s) trouvé(s)
        </p>
    </div>

    {{-- Barre de recherche --}}
    <form method="GET" action="{{ route('vendor.search', $vendor->id) }}"
          class="flex gap-3 mb-8 sticky top-20 z-30 bg-white py-4">
        <div class="flex-1 flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-4 py-2.5">
            <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Chercher dans cette boutique…"
                   class="flex-1 outline-none text-[13px] text-[#0a0a0a]">
        </div>
        <select name="sort" class="px-4 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]">
            <option value="relevance" {{ $sort === 'relevance' ? 'selected' : '' }}>Pertinence</option>
            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Nouveauté</option>
            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Prix ↑</option>
            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Prix ↓</option>
        </select>
        <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg font-medium text-[13px] hover:opacity-85">
            Chercher
        </button>
    </form>

    {{-- Grille Produits --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
            @foreach($products as $product)
                <a href="{{ route('produits.show', $product->id) }}" class="group">
                    <div class="border border-[#e0e0dc] rounded-lg overflow-hidden hover:border-[#0a0a0a] transition-all">
                        <div class="aspect-square bg-[#f7f7f5] flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/produits/' . $product->image) }}"
                                     alt="{{ $product->nom }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <span class="text-[48px]">📦</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-[13px] font-medium text-[#0a0a0a] line-clamp-2 mb-2">
                                {{ $product->nom }}
                            </h3>
                            <div class="font-mono font-bold text-[14px] text-[#0a0a0a] mb-2">
                                {{ number_format($product->prix, 0, ',', ' ') }} F
                            </div>
                            @if($product->review_count > 0)
                                <div class="flex items-center gap-1">
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-[11px] {{ $i <= round($product->avg_rating) ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-[#a0a09a]">({{ $product->review_count }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="flex items-center justify-center gap-2">
                @if(!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}&sort={{ $sort }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg">← Précédent</a>
                @endif

                @for($i = 1; $i <= min($products->lastPage(), 5); $i++)
                    @if($i === $products->currentPage())
                        <span class="px-3 py-2 bg-[#0a0a0a] text-white rounded-lg">{{ $i }}</span>
                    @else
                        <a href="{{ $products->url($i) }}&sort={{ $sort }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg">{{ $i }}</a>
                    @endif
                @endfor

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}&sort={{ $sort }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg">Suivant →</a>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-16">
            <p class="text-[14px] text-[#a0a09a]">Aucun produit trouvé</p>
        </div>
    @endif

</div>
@endsection
