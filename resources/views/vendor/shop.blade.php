@extends('layouts.app')

@section('content')
<div class="bg-[#0a0a0a] text-white mb-12">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 py-12 sm:py-16">
        <div class="grid grid-cols-1 md:grid-cols-[140px_1fr_auto] gap-8 items-start">

            {{-- Avatar --}}
            <div class="flex justify-center md:justify-start">
                <div class="w-32 h-32 rounded-full bg-white/10 border border-white/20 flex items-center justify-center overflow-hidden">
                    @if($vendor->profile_photo)
                        <img src="{{ asset('storage/' . $vendor->profile_photo) }}"
                             alt="{{ $vendor->shop_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-[48px]">🏪</span>
                    @endif
                </div>
            </div>

            {{-- Info Vendeur --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="font-serif text-[32px] leading-tight">{{ $vendor->shop_name ?? $vendor->name }}</h1>
                    @if($badge)
                        <div class="px-3 py-1.5 bg-white/10 border border-white/20 rounded-full text-[13px] font-medium flex-shrink-0">
                            {{ $badge['name'] }}
                        </div>
                    @endif
                </div>

                <p class="text-white/60 text-[14px] mb-4">
                    {{ $vendor->email ?? 'Boutique en ligne' }}
                </p>

                {{-- Stats --}}
                <div class="flex flex-wrap gap-6 text-[13px]">
                    <div>
                        <div class="font-mono font-bold text-[18px] leading-none">{{ $stats['total_products'] }}</div>
                        <div class="text-white/50 text-[11px] tracking-widest uppercase mt-1">Produits</div>
                    </div>
                    <div>
                        <div class="font-mono font-bold text-[18px] leading-none">{{ round($stats['avg_rating'], 1) }}/5</div>
                        <div class="text-white/50 text-[11px] tracking-widest uppercase mt-1">Note moyenne</div>
                    </div>
                    <div>
                        <div class="font-mono font-bold text-[18px] leading-none">{{ $stats['review_count'] }}</div>
                        <div class="text-white/50 text-[11px] tracking-widest uppercase mt-1">Avis clients</div>
                    </div>
                    <div>
                        <div class="font-mono font-bold text-[18px] leading-none">{{ $stats['total_sales'] }}</div>
                        <div class="text-white/50 text-[11px] tracking-widest uppercase mt-1">Commandes</div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex flex-col gap-2">
                <button onclick="followVendor({{ $vendor->id }})"
                        class="px-6 py-2.5 bg-white text-[#0a0a0a] rounded-lg font-medium text-[13px] hover:opacity-85 transition-opacity">
                    👤 Suivre
                </button>
                <a href="javascript:location.href='mailto:{{ $vendor->email }}'"
                   class="px-6 py-2.5 border border-white/30 text-white rounded-lg font-medium text-[13px] hover:bg-white/10 transition-colors text-center">
                    💬 Contacter
                </a>
            </div>

        </div>

        {{-- Description (si disponible) --}}
        @if($vendor->address)
            <div class="mt-8 pt-8 border-t border-white/10">
                <p class="text-white/60 text-[14px] leading-relaxed">
                    {{ $vendor->address }}
                </p>
            </div>
        @endif
    </div>
</div>

{{-- Contenu Principal --}}
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 pb-20">

    {{-- Recherche dans la boutique --}}
    <div class="mb-12 sticky top-20 z-30 bg-white py-4">
        <form method="GET" action="{{ route('vendor.search', $vendor->id) }}" class="flex gap-3">
            <div class="flex-1 flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-4 py-2.5">
                <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="q" value="{{ $query ?? '' }}"
                       placeholder="Chercher dans cette boutique…"
                       class="flex-1 outline-none text-[13px] text-[#0a0a0a]">
            </div>
            <select name="sort" class="px-4 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] outline-none">
                <option value="relevance">Pertinence</option>
                <option value="newest">Nouveauté</option>
                <option value="price_asc">Prix ↑</option>
                <option value="price_desc">Prix ↓</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg font-medium text-[13px] hover:opacity-85 transition-opacity">
                Chercher
            </button>
        </form>
    </div>

    {{-- Grille Produits --}}
    @if($products->count() > 0)
        <div>
            <h2 class="text-[20px] font-serif text-[#0a0a0a] mb-6">
                @if(isset($query))
                    Résultats pour "<strong>{{ $query }}</strong>"
                @else
                    Tous les produits de cette boutique
                @endif
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
                @foreach($products as $product)
                    <a href="{{ route('produits.show', $product->id) }}" class="group">
                        <div class="border border-[#e0e0dc] rounded-lg overflow-hidden hover:border-[#0a0a0a] transition-all hover:shadow-sm">
                            {{-- Image --}}
                            <div class="aspect-square bg-[#f7f7f5] overflow-hidden flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/produits/' . $product->image) }}"
                                         alt="{{ $product->nom }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                         loading="lazy">
                                @else
                                    <span class="text-[48px]">📦</span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <h3 class="text-[13px] font-medium text-[#0a0a0a] line-clamp-2 mb-2">
                                    {{ $product->nom }}
                                </h3>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-mono font-bold text-[14px] text-[#0a0a0a]">
                                        {{ number_format($product->prix, 0, ',', ' ') }} F
                                    </span>
                                </div>

                                {{-- Rating --}}
                                @if($product->review_count > 0)
                                    <div class="flex items-center gap-1 mb-3">
                                        <div class="flex gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-[11px] {{ $i <= round($product->avg_rating) ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-[#a0a09a]">({{ $product->review_count }})</span>
                                    </div>
                                @endif

                                {{-- Stock --}}
                                <div class="text-[11px] {{ $product->stock > 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                                    {{ $product->stock > 0 ? '✓ ' . $product->stock . ' en stock' : '❌ Rupture' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="flex items-center justify-center gap-2">
                    @if($products->onFirstPage())
                        <button disabled class="px-3 py-2 text-[#a0a09a] cursor-not-allowed">← Précédent</button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] transition-colors">← Précédent</a>
                    @endif

                    @for($i = 1; $i <= min($products->lastPage(), 5); $i++)
                        @if($i === $products->currentPage())
                            <span class="px-3 py-2 bg-[#0a0a0a] text-white rounded-lg">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg hover:bg-[#f7f7f5] transition-colors">{{ $i }}</a>
                        @endif
                    @endfor

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] transition-colors">Suivant →</a>
                    @else
                        <button disabled class="px-3 py-2 text-[#a0a09a] cursor-not-allowed">Suivant →</button>
                    @endif
                </div>
            @endif

        </div>
    @else
        <div class="text-center py-16">
            <div class="text-[64px] mb-4">📭</div>
            <h2 class="text-[24px] font-serif text-[#0a0a0a] mb-2">Aucun produit</h2>
            <p class="text-[14px] text-[#666660]">
                Cette boutique n'a pas encore de produits.
            </p>
        </div>
    @endif

    {{-- Avis Récents --}}
    @if($recentReviews->count() > 0)
        <div class="mt-16 pt-12 border-t border-[#e0e0dc]">
            <h2 class="text-[20px] font-serif text-[#0a0a0a] mb-6">Avis des clients</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentReviews as $review)
                    <div class="bg-white border border-[#e0e0dc] rounded-lg p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <div class="font-medium text-[13px] text-[#0a0a0a]">{{ $review->user->name }}</div>
                                <div class="text-[11px] text-[#a0a09a] mt-0.5 truncate">{{ $review->produit->nom }}</div>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-[12px] {{ $i <= $review->note ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[13px] text-[#666660] font-light leading-relaxed line-clamp-3">
                            {{ $review->commentaire }}
                        </p>
                        <div class="text-[10px] text-[#a0a09a] mt-3">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
async function followVendor(vendorId) {
    @if(!auth()->check())
        window.location.href = '{{ route("login") }}';
        return;
    @endif

    try {
        const response = await fetch(`/vendor/${vendorId}/follow`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        const data = await response.json();
        if (data.success) {
            alert('✓ ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

@endsection
