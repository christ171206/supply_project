@extends('layouts.app')

@section('content')
<div class="max-w-[1100px] mx-auto px-8 py-10 pb-20">

    {{-- ── HEADER ── --}}
    <div class="flex items-center gap-3 mb-8">
        <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <h1 class="font-serif text-[28px] tracking-tight text-[#0a0a0a] leading-none">Mon Panier</h1>
    </div>

    {{-- ── FLASH MESSAGES ── --}}
    @if(session('success'))
        <div class="flex items-center gap-2 mb-6 px-4 py-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg text-[13px] text-[#15803d]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 mb-6 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-[1fr_300px] gap-6 items-start">

        {{-- ══════════════════════════════
             ARTICLES
        ══════════════════════════════ --}}
        <div>
            @if($items && count($items) > 0)

                {{-- Liste --}}
                <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden mb-4">
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#efefed]">
                        <span class="text-[13px] font-medium text-[#0a0a0a]">Articles</span>
                        <span class="text-[11px] font-mono text-[#a0a09a]">{{ count($items) }} article{{ count($items) > 1 ? 's' : '' }}</span>
                    </div>

                    @foreach($items as $item)
                        <div class="flex gap-4 px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">

                            {{-- Thumb --}}
                            <div class="w-16 h-16 rounded-lg border border-[#e0e0dc] bg-[#f7f7f5] overflow-hidden flex items-center justify-center flex-shrink-0">
                                @if($item->produit->images && is_array($item->produit->images) && count($item->produit->images) > 0)
                                    <img src="{{ asset('storage/produits/' . $item->produit->images[0]) }}" alt="{{ $item->produit->nom }}" class="w-full h-full object-cover">
                                @elseif($item->produit->image)
                                    <img src="{{ asset('storage/produits/' . $item->produit->image) }}" alt="{{ $item->produit->nom }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-[#e0e0dc]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                @endif
                            </div>

                            {{-- Infos --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('produits.show', $item->produit->id) }}"
                                   class="text-[13px] font-medium text-[#0a0a0a] hover:text-[#666660] transition-colors line-clamp-1">
                                    {{ $item->produit->nom }}
                                </a>
                                <div class="text-[11px] text-[#a0a09a] font-light mt-0.5 mb-3">
                                    {{ Str::limit($item->produit->description, 60) }}
                                </div>

                                {{-- Qty control --}}
                                <form action="{{ route('panier.modifier', $item->id) }}" method="POST" class="inline-flex items-center">
                                    @csrf @method('PATCH')
                                    <div class="flex items-center border border-[#e0e0dc] rounded-md overflow-hidden">
                                        <button type="button" onclick="stepQty('qty_{{ $item->id }}', -1, this.closest('form'))"
                                            class="w-7 h-7 flex items-center justify-center text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-colors text-sm">−</button>
                                        <div class="w-px h-4 bg-[#e0e0dc]"></div>
                                        <input type="number" id="qty_{{ $item->id }}" name="quantite"
                                            value="{{ $item->quantite }}" min="1" max="{{ $item->produit->stock }}"
                                            class="w-9 text-center text-[12px] font-mono font-medium text-[#0a0a0a] border-none outline-none bg-transparent">
                                        <div class="w-px h-4 bg-[#e0e0dc]"></div>
                                        <button type="button" onclick="stepQty('qty_{{ $item->id }}', 1, this.closest('form'))"
                                            class="w-7 h-7 flex items-center justify-center text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-colors text-sm">+</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Prix + suppr --}}
                            <div class="flex flex-col items-end justify-between flex-shrink-0">
                                <div class="text-right">
                                    <div class="text-[14px] font-mono font-medium text-[#0a0a0a]">
                                        {{ number_format($item->quantite * $item->prix_unitaire, 0, ',', ' ') }}
                                    </div>
                                    <div class="text-[10px] text-[#a0a09a] mt-0.5">
                                        FCFA × {{ $item->quantite }}
                                    </div>
                                </div>
                                <form action="{{ route('panier.supprimer', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] text-[#a0a09a] hover:text-[#dc2626] transition-colors">
                                        Supprimer
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Actions bas --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('produits.catalogue') }}"
                       class="flex items-center gap-1.5 text-[12px] text-[#666660] hover:text-[#0a0a0a] transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                        Continuer les achats
                    </a>
                    <form action="{{ route('panier.vider') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-[12px] text-[#a0a09a] hover:text-[#dc2626] transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                            Vider le panier
                        </button>
                    </form>
                </div>

            @else
                {{-- ── PANIER VIDE ── --}}
                <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-16 text-center">
                    <svg class="w-10 h-10 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Votre panier est vide</h2>
                    <p class="text-[13px] text-[#a0a09a] font-light mb-6">Commencez vos achats en explorant notre catalogue</p>
                    <a href="{{ route('produits.catalogue') }}"
                       class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-6 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                        Découvrir nos produits
                    </a>
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════
             RÉSUMÉ
        ══════════════════════════════ --}}
        <div class="sticky top-[72px]">
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">

                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Résumé</span>
                </div>

                <div class="px-5 py-4 border-b border-[#efefed] space-y-3">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-[#666660] font-light">Nombre d'articles</span>
                        <span class="font-mono font-medium text-[#0a0a0a]">{{ $items ? count($items) : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-[#666660] font-light">Sous-total</span>
                        <span class="font-mono font-medium text-[#0a0a0a]">{{ number_format($total ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($items && count($items) > 0)
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-[#666660] font-light">Livraison</span>
                            <span class="font-mono font-medium {{ ($total ?? 0) > 100 ? 'text-[#15803d]' : 'text-[#0a0a0a]' }}">
                                {{ ($total ?? 0) > 100 ? 'Gratuite' : '2 500 FCFA' }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="px-5 py-4 border-b border-[#efefed] flex items-baseline justify-between">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Total</span>
                    <div class="text-right">
                        <div class="font-mono text-[18px] font-medium text-[#0a0a0a] tracking-tight">
                            @if($items && count($items) > 0)
                                {{ number_format(($total ?? 0) + (($total ?? 0) > 100 ? 0 : 2500), 0, ',', ' ') }}
                            @else
                                0
                            @endif
                        </div>
                        <div class="text-[10px] text-[#a0a09a]">FCFA</div>
                    </div>
                </div>

                @if($items && count($items) > 0)
                    <div class="px-5 py-4 space-y-2 border-b border-[#efefed]">
                        @auth
                            <a href="{{ route('commandes.create') }}"
                               class="block w-full py-3 bg-[#0a0a0a] text-white text-[13px] font-medium text-center rounded-lg hover:opacity-85 transition-opacity">
                                Commander →
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="block w-full py-3 bg-[#0a0a0a] text-white text-[13px] font-medium text-center rounded-lg hover:opacity-85 transition-opacity">
                                Se connecter pour commander
                            </a>
                        @endauth
                        <a href="{{ route('produits.catalogue') }}"
                           class="block w-full py-2.5 text-[12px] text-[#666660] text-center border border-[#e0e0dc] rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                            ← Continuer les achats
                        </a>
                    </div>
                @endif

                {{-- Trust badges --}}
                <div class="px-5 py-4 space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 border border-[#e0e0dc] rounded-md bg-[#f7f7f5] flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">Livraison rapide</div>
                            <div class="text-[11px] text-[#a0a09a] font-light">2 à 5 jours ouvrables</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 border border-[#e0e0dc] rounded-md bg-[#f7f7f5] flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">Paiement sécurisé</div>
                            <div class="text-[11px] text-[#a0a09a] font-light">Vos données sont protégées</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 border border-[#e0e0dc] rounded-md bg-[#f7f7f5] flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.92"/></svg>
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">Retours gratuits</div>
                            <div class="text-[11px] text-[#a0a09a] font-light">30 jours pour changer d'avis</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
function stepQty(inputId, delta, form) {
    const input = document.getElementById(inputId);
    const newVal = Math.max(1, Math.min(parseInt(input.max) || 99, parseInt(input.value) + delta));
    input.value = newVal;
    form.submit();
}
</script>

@endsection
