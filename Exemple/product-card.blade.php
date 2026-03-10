{{-- resources/views/components/product-card.blade.php --}}
{{-- Usage: <x-product-card :product="$product" /> --}}

@props(['product'])

<div class="group bg-white p-5 flex flex-col cursor-pointer hover:bg-[#f7f7f5] transition-colors duration-150">

    {{-- Image + badges --}}
    <div class="relative mb-4">
        <div class="w-full aspect-square rounded-lg bg-[#f7f7f5] border border-[#efefed] flex items-center justify-center overflow-hidden group-hover:border-[#e0e0dc] transition-colors">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            @else
                <span class="text-5xl">📦</span>
            @endif
        </div>

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if($product->stock > 0)
                <span class="inline-flex items-center gap-1 bg-white border border-[#e0e0dc] px-2 py-0.5 rounded text-[10px] font-medium text-[#2a2a28] font-mono">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                    {{ $product->stock }} en stock
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-white border border-[#e0e0dc] px-2 py-0.5 rounded text-[10px] font-medium text-[#666660] font-mono">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
                    Rupture
                </span>
            @endif

            @if($product->category)
                <span class="bg-[#0a0a0a] text-white px-2 py-0.5 rounded text-[10px] font-medium">
                    {{ $product->category->name }}
                </span>
            @endif
        </div>

        {{-- Favori --}}
        <button
            class="absolute top-2 right-2 w-7 h-7 bg-white border border-[#e0e0dc] rounded-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-150"
            title="Ajouter aux favoris"
        >
            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </div>

    {{-- Infos produit --}}
    <div class="flex-1 flex flex-col">
        <h3 class="text-[13px] font-medium text-[#0a0a0a] tracking-tight leading-snug mb-1">
            {{ $product->name }}
        </h3>
        <p class="text-[12px] text-[#a0a09a] font-light leading-relaxed flex-1">
            {{ Str::limit($product->description, 80) }}
        </p>

        {{-- Vendeur --}}
        @if($product->vendor)
            <div class="flex items-center gap-1.5 mt-3 text-[11px] text-[#a0a09a]">
                <span class="w-3.5 h-3.5 rounded-sm bg-[#e0e0dc] flex-shrink-0"></span>
                {{ $product->vendor->shop_name ?? $product->vendor->name }}
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between mt-4 pt-3.5 border-t border-[#efefed] gap-2">
        <div>
            <div class="text-[15px] font-medium text-[#0a0a0a] font-mono tracking-tight">
                {{ number_format($product->price, 0, ',', ' ') }}
            </div>
            <div class="text-[10px] text-[#a0a09a] mt-0.5">FCFA</div>
        </div>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('products.show', $product) }}"
               class="text-[11px] text-[#666660] px-2.5 py-1.5 border border-[#e0e0dc] rounded-md hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all duration-150">
                Détails
            </a>
            <button
                @if($product->stock === 0) disabled @endif
                class="text-[11px] font-medium bg-[#0a0a0a] text-white px-3.5 py-1.5 rounded-md hover:opacity-80 transition-opacity duration-150 disabled:opacity-30 disabled:cursor-not-allowed"
            >
                Ajouter
            </button>
        </div>
    </div>
</div>
