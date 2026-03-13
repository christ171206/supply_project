@extends('layouts.admin-layout')

@section('title', 'Commandes — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Commandes
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Administration</div>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Commandes</h1>
                <p class="text-[13px] text-white/40 font-light mt-2">Toutes les commandes de la plateforme</p>
            </div>
            <a href="{{ route('admin.orders.delivery-overview') }}"
               class="flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[12px] font-medium
                      px-4 py-2.5 rounded-lg hover:bg-white/20 transition-all mt-1">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Vue livraisons
            </a>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Client</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou email…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Statut</label>
            <select name="status"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="en_attente" {{ request('status') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="confirmee"  {{ request('status') === 'confirmee'  ? 'selected' : '' }}>Confirmée</option>
                <option value="expediee"   {{ request('status') === 'expediee'   ? 'selected' : '' }}>Expédiée</option>
                <option value="livree"     {{ request('status') === 'livree'     ? 'selected' : '' }}>Livrée</option>
                <option value="annulee"    {{ request('status') === 'annulee'    ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Livraison</label>
            <select name="delivery_status"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="pending"    {{ request('delivery_status') === 'pending'    ? 'selected' : '' }}>En attente</option>
                <option value="in_transit" {{ request('delivery_status') === 'in_transit' ? 'selected' : '' }}>En transit</option>
                <option value="delivered"  {{ request('delivery_status') === 'delivered'  ? 'selected' : '' }}>Livré</option>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Depuis le</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[12px] text-[#0a0a0a]
                          focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('search') || request('status') || request('delivery_status') || request('start_date'))
            <a href="{{ route('admin.orders.index') }}"
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
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">#</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Client</th>
                    <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Montant</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Commande</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Livraison</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                    @php
                        $b = match($commande->statut) {
                            'en_attente' => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]', 'En attente'],
                            'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]',  'bg-[#60a5fa]', 'Confirmée'],
                            'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]',  'bg-[#a78bfa]', 'Expédiée'],
                            'livree'     => ['bg-[#f0fdf4] text-[#15803d]',  'bg-[#22c55e]', 'Livrée'],
                            default      => ['bg-[#fef2f2] text-[#dc2626]',  'bg-[#f87171]', ucfirst(str_replace('_',' ',$commande->statut))],
                        };
                        $d = match($commande->delivery_status ?? '') {
                            'delivered'  => ['bg-[#f0fdf4] text-[#15803d]', 'bg-[#22c55e]', 'Livré'],
                            'in_transit' => ['bg-[#f5f3ff] text-[#7c3aed]', 'bg-[#a78bfa]', 'En transit'],
                            default      => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]', 'Attente'],
                        };
                    @endphp
                    <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <td class="px-5 py-3.5 font-mono text-[12px] text-[#666660]">#{{ $commande->id }}</td>
                        <td class="px-5 py-3.5">
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $commande->user?->name ?? 'Utilisateur inconnu' }}</div>
                            <div class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->user?->email ?? '—' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-[13px] font-medium text-[#0a0a0a]">
                            {{ number_format($commande->total, 0, ',', ' ') }}
                            <span class="text-[10px] text-[#a0a09a] font-sans">FCFA</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $b[0] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $b[1] }}"></span>{{ $b[2] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $d[0] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $d[1] }}"></span>{{ $d[2] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">
                            {{ $commande->created_at->format('d/m/Y · H:i') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <a href="{{ route('admin.orders.show', $commande) }}"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>
                                <a href="{{ route('admin.orders.tracking', $commande) }}"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Suivi
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucune commande trouvée</p>
                            <p class="text-[12px] text-[#a0a09a] font-light">Modifiez vos filtres</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                       class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                @endif
                @foreach($commandes->getUrlRange(max(1,$commandes->currentPage()-2), min($commandes->lastPage(),$commandes->currentPage()+2)) as $page => $url)
                    @if($page == $commandes->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                    @endif
                @endforeach
                @if($commandes->hasMorePages())
                    <a href="{{ $commandes->nextPageUrl() }}"
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
