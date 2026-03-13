@extends('layouts.admin-layout')

@section('title', 'Produits Vedettes — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#0a0a0a] transition-colors">Produits</a>
    &nbsp;/&nbsp; Vedettes
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux produits
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration · Produits</div>
        <div class="flex items-start justify-between">
            <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Produits vedettes</h1>
            <div class="flex items-center gap-1.5 bg-white/10 border border-white/20 px-3 py-1.5 rounded-lg mt-1">
                <svg class="w-3 h-3 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span class="font-mono text-[12px] font-medium text-white">{{ $produits->total() }}</span>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Rechercher</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nom du produit…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('search'))
            <a href="{{ route('admin.products.featured') }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Produit</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Catégorie</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Vendeur</th>
                    <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Prix</th>
                    <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Stock</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                    <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">

                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <a href="{{ route('admin.products.show', $produit) }}"
                                   class="text-[13px] font-medium text-[#0a0a0a] hover:text-[#666660] transition-colors">
                                    {{ $produit->nom }}
                                </a>
                            </div>
                            <div class="font-mono text-[10px] text-[#a0a09a] mt-0.5 pl-5">#{{ $produit->id }}</div>
                        </td>

                        <td class="px-5 py-3.5 text-[13px] text-[#666660]">
                            {{ $produit->categorie?->nom ?? '—' }}
                        </td>

                        <td class="px-5 py-3.5 text-[13px] text-[#666660]">
                            {{ $produit->vendeur?->shop_name ?? $produit->vendeur?->name ?? '—' }}
                        </td>

                        <td class="px-5 py-3.5 text-right font-mono text-[13px] font-medium text-[#0a0a0a]">
                            {{ number_format($produit->prix, 0, ',', ' ') }}
                            <span class="text-[10px] text-[#a0a09a] font-sans">FCFA</span>
                        </td>

                        <td class="px-5 py-3.5 text-center">
                            @php
                                $dot  = $produit->stock > 0 ? 'bg-[#22c55e]' : 'bg-[#f87171]';
                                $text = $produit->stock > 0 ? 'text-[#15803d]' : 'text-[#dc2626]';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-medium {{ $text }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                {{ $produit->stock }}
                            </span>
                        </td>

                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <a href="{{ route('admin.products.show', $produit) }}"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>
                                <form method="POST" action="{{ route('admin.products.toggle-featured', $produit) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                                   hover:bg-[#fef2f2] transition-all flex items-center gap-1"
                                            title="Retirer des vedettes">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        Retirer
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </div>
                            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun produit vedette</p>
                            <p class="text-[12px] text-[#a0a09a] font-light">Marquez des produits comme vedettes depuis la liste</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($produits->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-[11px] font-mono text-[#a0a09a]">
                {{ $produits->firstItem() }}–{{ $produits->lastItem() }} / {{ $produits->total() }}
            </div>
            <div class="flex items-center gap-1">
                @if($produits->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                @else
                    <a href="{{ $produits->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                @endif
                @foreach($produits->getUrlRange(max(1,$produits->currentPage()-2),min($produits->lastPage(),$produits->currentPage()+2)) as $page => $url)
                    @if($page == $produits->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                    @endif
                @endforeach
                @if($produits->hasMorePages())
                    <a href="{{ $produits->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                @endif
            </div>
        </div>
    @endif

    </div>
</div>
@endsection
