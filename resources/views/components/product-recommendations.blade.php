@props(['title' => 'Recommandations', 'products' => [], 'type' => 'default'])

@if($products && count($products) > 0)
<section class="mt-16 pt-12 border-t border-[#e0e0dc]">
    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 md:px-8">
        {{-- Titre --}}
        <h2 class="text-2xl sm:text-3xl font-serif text-[#0a0a0a] mb-8">
            {{ $title }}
        </h2>

        {{-- Grille de produits --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
            @forelse($products as $product)
                @if($product)
                <a href="{{ route('produits.show', $product->id) }}" 
                   class="group block bg-white border border-[#e0e0dc] rounded-lg overflow-hidden hover:border-[#0a0a0a] hover:shadow-md transition-all duration-200">
                    
                    {{-- Image --}}
                    <div class="aspect-square overflow-hidden bg-[#f7f7f5] flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/produits/' . $product->image) }}" 
                                 alt="{{ $product->nom }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @elseif($product->images && is_array($product->images) && count($product->images) > 0)
                            @php
                                $firstImage = $product->images[0];
                                $path = strpos($firstImage, 'produits/') === 0 ? $firstImage : 'produits/' . $firstImage;
                            @endphp
                            <img src="{{ asset('storage/' . $path) }}" 
                                 alt="{{ $product->nom }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">📦</div>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="p-3">
                        {{-- Nom (tronqué) --}}
                        <h3 class="text-[13px] font-medium text-[#0a0a0a] mb-2 line-clamp-2 h-8">
                            {{ Str::limit($product->nom, 40) }}
                        </h3>

                        {{-- Prix --}}
                        <p class="font-mono font-bold text-[14px] text-[#0a0a0a] mb-3">
                            {{ number_format($product->prix, 0, ',', ' ') }} <span class="text-[10px] text-[#a0a09a]">FCFA</span>
                        </p>

                        {{-- Notation (si disponible) --}}
                        @if($product->avis && $product->avis->count() > 0)
                            @php
                                $avgRating = number_format($product->avis->avg('note'), 1);
                                $reviewCount = $product->avis->count();
                            @endphp
                            <div class="flex items-center gap-1 text-[11px] text-[#a0a09a] mb-2">
                                <span class="text-[#fbbf24]">★</span>
                                <span>{{ $avgRating }} ({{ $reviewCount }})</span>
                            </div>
                        @endif

                        {{-- Disponibilité --}}
                        @if($product->stock > 0)
                            <span class="text-[10px] text-[#15803d] font-medium">
                                ✓ En stock
                            </span>
                        @else
                            <span class="text-[10px] text-[#dc2626] font-medium">
                                Rupture
                            </span>
                        @endif
                    </div>
                </a>
                @endif
            @empty
                <div class="col-span-full text-center py-8 text-[#a0a09a]">
                    <p class="text-[13px]">Aucun produit recommandé pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endif
