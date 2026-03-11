@extends('layouts.admin-layout')

@section('title', 'Produits — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Produits
@endsection

@section('content')
<div class="pb-16">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Administration</div>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Produits</h1>
                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-white/10">
                    <div>
                        <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $produits->total() }}</div>
                        <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Au total</div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.products.featured') }}"
                   class="flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[12px] font-medium
                          px-4 py-2.5 rounded-lg hover:bg-white/20 transition-all">
                    <span class="text-lg">⭐</span>
                    Vedettes
                </a>
                <a href="{{ route('admin.products.critical-stock') }}"
                   class="flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[12px] font-medium
                          px-4 py-2.5 rounded-lg hover:bg-white/20 transition-all">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>
                    Stock critique
                </a>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                Rechercher
            </label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nom du produit…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div class="w-44">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Statut</label>
            <select name="status"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous les statuts</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.products.index') }}"
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
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Vendeur</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Catégorie</th>
                    <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Prix</th>
                    <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Stock</th>
                    <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                    @php
                        $stockDot = $produit->stock <= 5
                            ? 'bg-[#f87171]'
                            : ($produit->stock <= 10 ? 'bg-[#f59e0b]' : 'bg-[#22c55e]');
                        $stockText = $produit->stock <= 5
                            ? 'text-[#dc2626]'
                            : ($produit->stock <= 10 ? 'text-[#b45309]' : 'text-[#15803d]');
                    @endphp
                    <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">

                        {{-- Produit --}}
                        <td class="px-5 py-3.5">
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                            <div class="font-mono text-[10px] text-[#a0a09a] mt-0.5">#{{ $produit->id }}</div>
                        </td>

                        {{-- Vendeur --}}
                        <td class="px-5 py-3.5 text-[13px] text-[#2a2a28]">
                            {{ $produit->vendeur?->shop_name ?? $produit->vendeur?->name ?? '—' }}
                        </td>

                        {{-- Catégorie --}}
                        <td class="px-5 py-3.5 text-[13px] text-[#666660]">
                            {{ $produit->categorie?->nom ?? '—' }}
                        </td>

                        {{-- Prix --}}
                        <td class="px-5 py-3.5 text-right font-mono text-[13px] font-medium text-[#0a0a0a]">
                            {{ number_format($produit->prix, 0, ',', ' ') }}
                            <span class="text-[10px] text-[#a0a09a] font-sans">FCFA</span>
                        </td>

                        {{-- Stock --}}
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-medium {{ $stockText }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $stockDot }}"></span>
                                {{ $produit->stock }}
                            </span>
                        </td>

                        {{-- Statut --}}
                        <td class="px-5 py-3.5 text-center">
                            @if($produit->est_actif)
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f7f7f5] text-[#a0a09a]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <a href="{{ route('admin.products.show', $produit) }}"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>

                                @if($produit->est_actif)
                                    <form method="POST" action="{{ route('admin.products.disable', $produit) }}" class="inline"
                                          data-confirm="Désactiver ce produit ?"
                                          data-confirm-title="Désactiver le produit"
                                          data-confirm-type="warning"
                                          data-confirm-button="Désactiver">
                                        @csrf
                                        <button type="submit"
                                                class="text-[11px] font-medium text-[#b45309] border border-[#fde68a] px-2.5 py-1.5 rounded-lg
                                                       hover:bg-[#fdf6ec] transition-all">
                                            Désactiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.products.enable', $produit) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                                       hover:bg-[#f0fdf4] transition-all">
                                            Activer
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.products.toggle-featured', $produit) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-[11px] font-medium {{ $produit->featured ? 'text-[#dc2626] border border-[#fecaca]' : 'text-[#7c3aed] border border-[#ddd6fe]' }} px-2.5 py-1.5 rounded-lg
                                                   {{ $produit->featured ? 'hover:bg-[#fef2f2]' : 'hover:bg-[#f5f3ff]' }} transition-all"
                                            title="{{ $produit->featured ? 'Retirer de vedettes' : 'Ajouter aux vedettes' }}">
                                        {{ $produit->featured ? 'Retirer ⭐' : 'Vedette ⭐' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.products.destroy', $produit) }}" class="inline"
                                      data-confirm="Supprimer ce produit ? Cette action est irréversible."
                                      data-confirm-title="Supprimer le produit"
                                      data-confirm-type="danger"
                                      data-confirm-button="Supprimer">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                                   hover:bg-[#fef2f2] transition-all">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                            </div>
                            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun produit trouvé</p>
                            <p class="text-[12px] text-[#a0a09a] font-light">Ajustez vos filtres pour voir les produits</p>
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

                @foreach($produits->getUrlRange(max(1, $produits->currentPage()-2), min($produits->lastPage(), $produits->currentPage()+2)) as $page => $url)
                    @if($page == $produits->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
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

    </div>{{-- /px-8 --}}
</div>
@endsection
