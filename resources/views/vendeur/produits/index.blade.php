@extends('vendeur.layout-dashboard')

@section('title', 'Mes Produits — Supply')

@section('breadcrumb')
    Espace Vendeur &nbsp;/&nbsp; Mes Produits
@endsection

@section('content')
<div class="pb-20">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Gestion</div>
        <div class="flex items-end justify-between">
            <div>
                <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Mes Produits</h1>
                <p class="text-[13px] text-white/50 font-light mt-2">Gestion de votre catalogue</p>
            </div>
            <a href="{{ route('vendeur.produits.create') }}"
               class="flex items-center gap-2 bg-white text-[#0a0a0a] text-[12px] font-medium px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity flex-shrink-0">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                Ajouter un produit
            </a>
        </div>
    </div>

    <div class="px-8 space-y-6">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg text-[13px] text-[#15803d]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ══════════════════════════════
         FILTRES
    ══════════════════════════════ --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <form method="GET" class="flex items-center divide-x divide-[#efefed] flex-wrap md:flex-nowrap gap-0">
            <div class="flex-1 flex items-center gap-3 px-4 py-3.5 min-w-[250px]">
                <svg class="w-3.5 h-3.5 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Rechercher un produit…"
                       value="{{ request('search') }}"
                       class="flex-1 text-[13px] text-[#0a0a0a] placeholder-[#a0a09a] bg-transparent outline-none font-light">
            </div>
            <div class="px-4 py-3.5">
                <select name="categorie"
                        class="text-[13px] text-[#0a0a0a] bg-white outline-none font-light cursor-pointer">
                    <option value="">Toutes les catégories</option>
                    @forelse($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @empty
                        <option disabled>Aucune catégorie</option>
                    @endforelse
                </select>
            </div>
            <button type="submit"
                    class="px-5 py-3.5 text-[12px] font-medium text-[#0a0a0a] hover:bg-[#f7f7f5] transition-colors whitespace-nowrap">
                Filtrer →
            </button>
        </form>
    </div>

    {{-- ══════════════════════════════
         GRILLE PRODUITS
    ══════════════════════════════ --}}
    @if($produits->count() > 0)

        <div class="grid grid-cols-3 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
            @foreach($produits as $produit)
            <div class="bg-white flex flex-col hover:bg-[#f7f7f5] transition-colors group">

                {{-- Image --}}
                <div class="relative h-44 bg-[#f7f7f5] overflow-hidden border-b border-[#efefed]">
                    @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                        @php
                            $imgPath = $produit->images ? (is_array($produit->images) ? $produit->images[0] : $produit->images) : '';
                            $fullPath = $imgPath ? (strpos($imgPath, 'produits/') === 0 ? $imgPath : 'produits/' . $imgPath) : '';
                        @endphp
                        @if($fullPath)
                            <img src="{{ asset('storage/' . $fullPath) }}" loading="lazy"
                             alt="{{ $produit->nom }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        @endif
                    @elseif($produit->image)
                        <img src="{{ asset('storage/produits/' . $produit->image) }}"
                             alt="{{ $produit->nom }}"
                             class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-[#e0e0dc]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badges sur l'image --}}
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
                        @if($produit->stock == 0)
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono font-medium bg-white/95 text-[#dc2626] px-2 py-1 rounded border border-[#fecaca]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>Rupture
                            </span>
                        @elseif($produit->stock <= $produit->stock_minimum)
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono font-medium bg-white/95 text-[#b45309] px-2 py-1 rounded border border-[#fed7aa]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>Stock faible
                            </span>
                        @endif
                    </div>

                    <div class="absolute top-2.5 right-2.5">
                        @if($produit->est_actif)
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono font-medium bg-white/95 text-[#15803d] px-2 py-1 rounded border border-[#bbf7d0]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono font-medium bg-white/95 text-[#666660] px-2 py-1 rounded border border-[#e0e0dc]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-4 flex-1 flex flex-col">
                    <div class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-1.5">
                        {{ $produit->categorie->nom ?? '—' }}
                    </div>
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] leading-snug mb-1 line-clamp-1">{{ $produit->nom }}</h3>
                    <p class="text-[12px] text-[#666660] font-light leading-relaxed line-clamp-2 mb-4 flex-1">{{ $produit->description }}</p>

                    {{-- Prix + stock --}}
                    <div class="flex items-end justify-between mb-4 pt-3 border-t border-[#efefed]">
                        <div>
                            <div class="font-mono text-[16px] font-medium text-[#0a0a0a]">
                                {{ number_format($produit->prix, 0, ',', ' ') }}
                                <span class="text-[11px] text-[#a0a09a] font-sans font-light">FCFA</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-[14px] font-medium text-[#0a0a0a]">{{ $produit->stock }}</div>
                            <div class="text-[10px] text-[#a0a09a] font-light">min {{ $produit->stock_minimum }}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('vendeur.produits.edit', $produit->id) }}"
                           class="flex-1 flex items-center justify-center gap-1.5 text-[12px] font-medium bg-[#0a0a0a] text-white py-2 rounded-lg hover:opacity-85 transition-opacity">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Éditer
                        </a>
                        <form method="POST" action="{{ route('vendeur.produits.destroy', $produit->id) }}"
                              onsubmit="return confirm('Supprimer ce produit ? Cette action est définitive.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#a0a09a] hover:border-[#dc2626] hover:text-[#dc2626] transition-all"
                                    title="Supprimer">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($produits->hasPages())
            <div class="flex items-center justify-between pt-2">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $produits->firstItem() }}–{{ $produits->lastItem() }} / {{ $produits->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($produits->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $produits->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif

                    @foreach($produits->getUrlRange(max(1, $produits->currentPage()-2), min($produits->lastPage(), $produits->currentPage()+2)) as $page => $url)
                        @if($page == $produits->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($produits->hasMorePages())
                        <a href="{{ $produits->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                    @endif
                </div>
            </div>
        @endif

    @else
        {{-- Empty state --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-8 py-16 text-center">
            <div class="w-12 h-12 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
            <h3 class="text-[15px] font-medium text-[#0a0a0a] mb-1">Aucun produit trouvé</h3>
            <p class="text-[13px] text-[#a0a09a] font-light mb-6">Commencez par créer votre premier produit</p>
            <a href="{{ route('vendeur.produits.create') }}"
               class="inline-flex items-center gap-2 bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                Créer un produit
            </a>
        </div>
    @endif

    </div>{{-- /px-8 --}}
</div>
@endsection
