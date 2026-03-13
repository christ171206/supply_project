@extends('layouts.admin-layout')

@section('title', 'Historique stock — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#0a0a0a] transition-colors">Produits</a>
    &nbsp;/&nbsp;
    <a href="{{ route('admin.products.show', $produit->id) }}" class="hover:text-[#0a0a0a] transition-colors">{{ $produit->nom }}</a>
    &nbsp;/&nbsp; Historique
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.products.show', $produit->id) }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour au produit
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration · Produits</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Historique du stock</h1>
        <p class="text-[13px] text-white/40 font-light mt-1.5">{{ $produit->nom }}</p>

        {{-- KPIs --}}
        <div class="flex items-center gap-6 mt-5 pt-5 border-t border-white/10 flex-wrap">
            <div>
                <div class="font-mono text-[22px] font-medium text-[#22c55e] leading-none">+{{ $addedTotal ?? 0 }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Ajoutés</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-[#f87171] leading-none">−{{ $removedTotal ?? 0 }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Retirés</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ ($addedTotal ?? 0) - ($removedTotal ?? 0) >= 0 ? '+' : '' }}{{ ($addedTotal ?? 0) - ($removedTotal ?? 0) }}
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Solde net</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div>
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Date début</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div>
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Date fin</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Type</label>
            <select name="type"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="ajustement" {{ request('type') === 'ajustement' ? 'selected' : '' }}>Ajustement</option>
                <option value="vente"      {{ request('type') === 'vente'      ? 'selected' : '' }}>Vente</option>
                <option value="retour"     {{ request('type') === 'retour'     ? 'selected' : '' }}>Retour</option>
            </select>
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('start_date') || request('end_date') || request('type'))
            <a href="{{ route('admin.products.stock-history', $produit->id) }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Timeline --}}
    @if($mouvements->isEmpty())
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun mouvement</p>
            <p class="text-[12px] text-[#a0a09a] font-light">Aucun mouvement de stock enregistré</p>
        </div>
    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            @php
                $typeIcons = [
                    'ajustement' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
                    'vente'      => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
                    'retour'     => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.18"/>',
                ];
                $typeLabels = [
                    'ajustement' => 'Ajustement de stock',
                    'vente'      => 'Vente',
                    'retour'     => 'Retour client',
                ];
            @endphp
            <div class="px-6 py-5 space-y-0">
                @foreach($mouvements as $mouvement)
                    @php $icon = $typeIcons[$mouvement->type ?? ''] ?? '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'; @endphp
                    <div class="flex gap-4 {{ !$loop->last ? 'pb-5' : '' }}">
                        {{-- Icône + ligne --}}
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="w-8 h-8 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    {!! $icon !!}
                                </svg>
                            </div>
                            @if(!$loop->last)
                                <div class="w-px flex-1 bg-[#efefed] mt-2"></div>
                            @endif
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 pt-1 {{ !$loop->last ? 'pb-2' : '' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">
                                        {{ $typeLabels[$mouvement->type ?? ''] ?? 'Mouvement' }}
                                    </div>
                                    @if($mouvement->raison)
                                        <div class="text-[12px] text-[#666660] font-light mt-0.5">{{ $mouvement->raison }}</div>
                                    @endif
                                    <div class="font-mono text-[10px] text-[#a0a09a] mt-1">
                                        {{ $mouvement->created_at->format('d/m/Y · H:i') }}
                                    </div>
                                </div>
                                <span class="font-mono text-[15px] font-medium flex-shrink-0
                                             {{ $mouvement->quantite > 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                                    {{ $mouvement->quantite > 0 ? '+' : '' }}{{ $mouvement->quantite }}
                                </span>
                            </div>
                            @if($mouvement->notes)
                                <div class="mt-2 px-3 py-2 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-[11px] text-[#666660] font-light">
                                    {{ $mouvement->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if($mouvements->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $mouvements->firstItem() }}–{{ $mouvements->lastItem() }} / {{ $mouvements->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($mouvements->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $mouvements->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif
                    @foreach($mouvements->getUrlRange(max(1,$mouvements->currentPage()-2),min($mouvements->lastPage(),$mouvements->currentPage()+2)) as $page => $url)
                        @if($page == $mouvements->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($mouvements->hasMorePages())
                        <a href="{{ $mouvements->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                    @endif
                </div>
            </div>
        @endif
    @endif

    </div>
</div>
@endsection
