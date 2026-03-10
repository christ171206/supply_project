@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto px-8 py-10 pb-20">

    {{-- ── HEADER ── --}}
    <div class="mb-8">
        <h1 class="font-serif text-[28px] tracking-tight text-[#0a0a0a] leading-tight">
            Catalogue <em class="italic text-[#666660]">Produits</em>
        </h1>
        <p class="text-[13px] text-[#a0a09a] font-light mt-1">{{ count($produits) ?? 0 }} produits disponibles</p>
    </div>

    <div class="grid grid-cols-[220px_1fr] gap-7 items-start">

        {{-- ══════════════════════════════
             SIDEBAR FILTRES
        ══════════════════════════════ --}}
        <aside class="sticky top-[72px]">
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">

                <form method="GET" action="{{ route('produits.catalogue') }}">

                    {{-- Recherche --}}
                    <div class="p-4 border-b border-[#efefed]">
                        <div class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-3 py-2 focus-within:border-[#0a0a0a] transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input
                                type="text"
                                name="recherche"
                                value="{{ request('recherche') }}"
                                placeholder="Chercher un produit…"
                                class="bg-transparent outline-none text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] placeholder:font-light w-full"
                            >
                        </div>
                    </div>

                    {{-- Catégories --}}
                    <div class="p-4 border-b border-[#efefed]">
                        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Catégories</div>
                        <div class="flex flex-col gap-0.5">
                            <label class="flex items-center gap-2 px-2 py-1.5 rounded-md cursor-pointer transition-colors hover:bg-[#f7f7f5] {{ !request('categorie') ? 'bg-[#0a0a0a]' : '' }} group">
                                <div class="w-3.5 h-3.5 rounded-[3px] border flex-shrink-0 flex items-center justify-center transition-all
                                    {{ !request('categorie') ? 'border-transparent bg-white' : 'border-[#e0e0dc]' }}">
                                    @if(!request('categorie'))
                                        <svg class="w-2.5 h-2.5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    @endif
                                </div>
                                <input type="checkbox" value="" class="hidden" {{ !request('categorie') ? 'checked' : '' }}>
                                <span class="text-[12px] flex-1 {{ !request('categorie') ? 'text-white font-medium' : 'text-[#666660]' }}">Toutes</span>
                                <span class="text-[10px] font-mono {{ !request('categorie') ? 'text-white/60' : 'text-[#a0a09a]' }}">{{ $produits->total() ?? count($produits) }}</span>
                            </label>

                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 px-2 py-1.5 rounded-md cursor-pointer transition-colors hover:bg-[#f7f7f5] {{ request('categorie') == $cat->id ? 'bg-[#0a0a0a]' : '' }}">
                                    <div class="w-3.5 h-3.5 rounded-[3px] border flex-shrink-0 flex items-center justify-center transition-all
                                        {{ request('categorie') == $cat->id ? 'border-transparent bg-white' : 'border-[#e0e0dc]' }}">
                                        @if(request('categorie') == $cat->id)
                                            <svg class="w-2.5 h-2.5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        @endif
                                    </div>
                                    <input type="checkbox" name="categorie" value="{{ $cat->id }}" class="hidden" {{ request('categorie') == $cat->id ? 'checked' : '' }}>
                                    <span class="text-[12px] flex-1 {{ request('categorie') == $cat->id ? 'text-white font-medium' : 'text-[#666660]' }}">{{ $cat->nom }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Prix --}}
                    <div class="p-4 border-b border-[#efefed]">
                        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Prix (FCFA)</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-[#a0a09a]">Min</span>
                                <input
                                    type="number"
                                    name="prix_min"
                                    value="{{ request('prix_min') }}"
                                    placeholder="0"
                                    class="border border-[#e0e0dc] rounded-md px-2.5 py-1.5 text-[12px] font-mono text-[#0a0a0a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors w-full bg-white placeholder:text-[#a0a09a]"
                                >
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-[#a0a09a]">Max</span>
                                <input
                                    type="number"
                                    name="prix_max"
                                    value="{{ request('prix_max') }}"
                                    placeholder="—"
                                    class="border border-[#e0e0dc] rounded-md px-2.5 py-1.5 text-[12px] font-mono text-[#0a0a0a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors w-full bg-white placeholder:text-[#a0a09a]"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Tri --}}
                    <div class="p-4 border-b border-[#efefed]">
                        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Tri</div>
                        <select
                            name="tri"
                            class="w-full border border-[#e0e0dc] rounded-md px-2.5 py-1.5 text-[12px] text-[#0a0a0a] bg-white outline-none cursor-pointer focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors appearance-none"
                            style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a0a09a' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;padding-right:28px"
                        >
                            <option value="latest"   {{ request('tri') === 'latest'   ? 'selected' : '' }}>Plus récents</option>
                            <option value="prix_asc"  {{ request('tri') === 'prix_asc'  ? 'selected' : '' }}>Prix croissant</option>
                            <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="nom"       {{ request('tri') === 'nom'       ? 'selected' : '' }}>Nom (A–Z)</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="p-4 flex gap-2">
                        <button
                            type="submit"
                            class="flex-1 bg-[#0a0a0a] text-white text-[12px] font-medium py-2 rounded-lg hover:opacity-85 transition-opacity"
                        >
                            Appliquer
                        </button>
                        <a
                            href="{{ route('produits.catalogue') }}"
                            class="flex-1 text-center text-[12px] text-[#666660] border border-[#e0e0dc] py-2 rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all"
                        >
                            Réinit
                        </a>
                    </div>

                </form>
            </div>
        </aside>

        {{-- ══════════════════════════════
             GRILLE PRODUITS
        ══════════════════════════════ --}}
        <div>

            @if($produits && count($produits) > 0)

                {{-- Grille --}}
                <div class="grid grid-cols-3 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden mb-8">
                    @foreach($produits as $produit)
                        @include('components.carte-produit', ['produit' => $produit])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if(method_exists($produits, 'links'))
                <div class="flex items-center justify-center gap-1.5 mt-8">
                    {{-- Previous --}}
                    @if($produits->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-sm cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $produits->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-sm hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">‹</a>
                    @endif

                    {{-- Pages --}}
                    @foreach($produits->getUrlRange(max(1, $produits->currentPage()-2), min($produits->lastPage(), $produits->currentPage()+2)) as $page => $url)
                        @if($page == $produits->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[13px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-[13px] font-mono hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($produits->hasMorePages())
                        <a href="{{ $produits->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-sm hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">›</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-sm cursor-not-allowed">›</span>
                    @endif

                    <span class="ml-3 text-[11px] text-[#a0a09a] font-mono">
                        {{ $produits->firstItem() }}–{{ $produits->lastItem() }} / {{ $produits->total() }}
                    </span>
                </div>
                @endif

            @else
                {{-- Empty state --}}
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-16 text-center">
                    <svg class="w-10 h-10 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Aucun produit trouvé</h2>
                    <p class="text-[13px] text-[#a0a09a] font-light mb-6">
                        @if(request('recherche'))
                            Aucun résultat pour « {{ request('recherche') }} »
                        @elseif(request('categorie'))
                            Aucun produit dans cette catégorie
                        @else
                            Aucun produit disponible pour le moment
                        @endif
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                        Voir tous les produits
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
