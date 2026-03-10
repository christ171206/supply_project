@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="mb-6">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-[#a0a09a] mb-2">Mon compte</div>
        <h1 class="font-serif text-[32px] tracking-tight text-[#0a0a0a] leading-none">Mes Commandes</h1>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 mb-5 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg text-[13px] text-[#15803d]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 mb-5 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if($commandes && count($commandes) > 0)

        {{-- ══════════════════════════════
             LISTE COMMANDES
        ══════════════════════════════ --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden mb-5">
            @foreach($commandes as $commande)
            @php
                $badge = match($commande->statut) {
                    'en_attente' => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]', 'En attente'],
                    'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]',  'bg-[#60a5fa]', 'Confirmée'],
                    'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]',  'bg-[#a78bfa]', 'Expédiée'],
                    'livree'     => ['bg-[#f0fdf4] text-[#15803d]',  'bg-[#22c55e]', 'Livrée'],
                    'annulee'    => ['bg-[#fef2f2] text-[#dc2626]',  'bg-[#f87171]', 'Annulée'],
                    default      => ['bg-[#f7f7f5] text-[#666660]',  'bg-[#a0a09a]', ucfirst(str_replace('_',' ',$commande->statut))],
                };
            @endphp
            <div class="px-5 py-5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">

                {{-- Ligne principale --}}
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2.5 mb-1">
                            <span class="font-mono text-[13px] font-medium text-[#0a0a0a]">#{{ $commande->id }}</span>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $badge[0] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $badge[1] }}"></span>
                                {{ $badge[2] }}
                            </span>
                        </div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->created_at->format('d/m/Y · H:i') }}</div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-4">
                        <div class="font-mono text-[16px] font-medium text-[#0a0a0a]">
                            {{ number_format($commande->total, 0, ',', ' ') }}
                            <span class="text-[11px] text-[#a0a09a] font-sans font-light">FCFA</span>
                        </div>
                    </div>
                </div>

                {{-- Infos secondaires --}}
                <div class="flex items-center gap-6 mb-3">
                    <div>
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Paiement</div>
                        <div class="text-[12px] text-[#2a2a28]">{{ ucfirst(str_replace('_', ' ', $commande->payment_method ?? '—')) }}</div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Livraison</div>
                        <div class="text-[12px] text-[#2a2a28] truncate">{{ Str::limit($commande->adresse_livraison, 45) }}</div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="flex justify-end">
                    <a href="{{ route('commandes.show', $commande->id) }}"
                       class="text-[11px] font-medium text-[#a0a09a] border-b border-[#e0e0dc] pb-px hover:text-[#0a0a0a] hover:border-[#0a0a0a] transition-all">
                        Voir les détails →
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($commandes->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $commandes->firstItem() }}–{{ $commandes->lastItem() }} / {{ $commandes->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($commandes->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $commandes->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif

                    @foreach($commandes->getUrlRange(max(1, $commandes->currentPage()-2), min($commandes->lastPage(), $commandes->currentPage()+2)) as $page => $url)
                        @if($page == $commandes->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($commandes->hasMorePages())
                        <a href="{{ $commandes->nextPageUrl() }}"
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
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-1">Aucune commande</h2>
            <p class="text-[13px] text-[#a0a09a] font-light mb-6">Explorez notre catalogue pour passer votre première commande</p>
            <a href="{{ route('produits.catalogue') }}"
               class="inline-flex items-center gap-2 bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                Découvrir le catalogue
            </a>
        </div>

    @endif

</div>
</div>
@endsection

