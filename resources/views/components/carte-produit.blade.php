<div class="group relative bg-white flex flex-col cursor-pointer transition-colors duration-150 hover:bg-[#f7f7f5]">

    {{-- ── IMAGE ── --}}
    <div class="relative">
        <div class="w-full aspect-square bg-[#f7f7f5] border border-[#efefed] overflow-hidden flex items-center justify-center transition-colors duration-150 group-hover:border-[#e0e0dc]">

            @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                <img
                    src="{{ asset('storage/produits/' . $produit->images[0]) }}"
                    alt="{{ $produit->nom }}"
                    class="w-full h-full object-cover"
                    onerror="this.parentElement.querySelector('.img-placeholder').style.display='flex'; this.style.display='none';"
                >
            @elseif($produit->image)
                <img
                    src="{{ asset('storage/produits/' . $produit->image) }}"
                    alt="{{ $produit->nom }}"
                    class="w-full h-full object-cover"
                    onerror="this.parentElement.querySelector('.img-placeholder').style.display='flex'; this.style.display='none';"
                >
            @endif

            {{-- Placeholder --}}
            <div class="img-placeholder absolute inset-0 flex items-center justify-center {{ ($produit->images && is_array($produit->images) && count($produit->images) > 0) || $produit->image ? 'hidden' : 'flex' }}">
                <svg class="w-10 h-10 text-[#e0e0dc]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if($produit->stock > 0)
                <span class="inline-flex items-center gap-1 bg-white border border-[#e0e0dc] px-2 py-0.5 rounded text-[10px] font-medium text-[#2a2a28] font-mono">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                    {{ $produit->stock }} en stock
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-white border border-[#e0e0dc] px-2 py-0.5 rounded text-[10px] font-medium text-[#666660] font-mono">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
                    Rupture
                </span>
            @endif

            @if($produit->categorie)
                <span class="bg-[#0a0a0a] text-white px-2 py-0.5 rounded text-[10px] font-medium truncate max-w-[120px]">
                    {{ $produit->categorie->nom }}
                </span>
            @endif
        </div>

        {{-- Favori --}}
        <button
            onclick="toggleFavorite({{ $produit->id }}, event)"
            data-favorite-btn="{{ $produit->id }}"
            class="absolute top-2 right-2 w-7 h-7 bg-white border border-[#e0e0dc] rounded-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-150 hover:border-[#2a2a28]"
            title="Ajouter aux favoris"
        >
            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </div>

    {{-- ── INFOS ── --}}
    <div class="flex-1 flex flex-col p-4">

        {{-- Nom --}}
        <h3 class="text-[13px] font-medium text-[#0a0a0a] tracking-tight leading-snug mb-1 line-clamp-1">
            {{ $produit->nom }}
        </h3>

        {{-- Description --}}
        <p class="text-[12px] text-[#a0a09a] font-light leading-relaxed flex-1 line-clamp-2">
            {{ Str::limit($produit->description, 80) }}
        </p>

        {{-- Vendeur --}}
        @if($produit->vendeur)
            <div class="flex items-center gap-1.5 mt-3 text-[11px] text-[#a0a09a]">
                <span class="w-3.5 h-3.5 rounded-sm bg-[#e0e0dc] flex-shrink-0"></span>
                {{ Str::limit($produit->vendeur->shop_name ?? $produit->vendeur->name, 24) }}
            </div>
        @endif
    </div>

    {{-- ── FOOTER PRIX + ACTIONS ── --}}
    <div class="flex items-center justify-between px-4 pb-4 pt-3 border-t border-[#efefed] gap-2">

        {{-- Prix --}}
        <div>
            <div class="text-[15px] font-medium text-[#0a0a0a] font-mono tracking-tight leading-none">
                {{ number_format($produit->prix, 0, ',', ' ') }}
            </div>
            <div class="text-[10px] text-[#a0a09a] mt-0.5">FCFA</div>
        </div>

        {{-- Boutons --}}
        <div class="flex items-center gap-1.5">
            <a
                href="{{ route('produits.show', $produit->id) }}"
                class="text-[11px] text-[#666660] px-2.5 py-1.5 border border-[#e0e0dc] rounded-md hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all duration-150"
            >
                Détails
            </a>

            @if($produit->stock > 0)
                <button
                    type="button"
                    onclick="openQuantityModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->stock }}, {{ $produit->prix }})"
                    class="text-[11px] font-medium bg-[#0a0a0a] text-white px-3.5 py-1.5 rounded-md hover:opacity-80 transition-opacity duration-150"
                >
                    Ajouter
                </button>
            @else
                <button
                    disabled
                    class="text-[11px] font-medium text-[#a0a09a] px-3.5 py-1.5 rounded-md border border-[#e0e0dc] cursor-not-allowed opacity-50"
                >
                    Indisponible
                </button>
            @endif
        </div>
    </div>
</div>
