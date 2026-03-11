@extends('layouts.admin-layout')

@section('title', 'Suivi #' . $commande->id . ' — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.orders.index') }}" class="hover:text-[#0a0a0a] transition-colors">Commandes</a>
    &nbsp;/&nbsp; Suivi #{{ $commande->id }}
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.orders.delivery-overview') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Vue livraisons
        </a>
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Suivi de commande</div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">#{{ $commande->id }}</h1>
                <div class="text-[13px] text-white/40 font-light mt-2">{{ $commande->user->name }}</div>
            </div>
            @php
                $sb = match($commande->statut) {
                    'en_attente' => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente'],
                    'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]', 'bg-[#60a5fa]','Confirmée'],
                    'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]', 'bg-[#a78bfa]','Expédiée'],
                    'livree'     => ['bg-[#f0fdf4] text-[#15803d]', 'bg-[#22c55e]','Livrée'],
                    default      => ['bg-[#fef2f2] text-[#dc2626]', 'bg-[#f87171]',ucfirst(str_replace('_',' ',$commande->statut))],
                };
                $db = match($commande->delivery_status ?? '') {
                    'picked_up'  => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','Enlevée'],
                    'in_transit' => ['bg-[#f5f3ff] text-[#7c3aed]','bg-[#a78bfa]','En transit'],
                    'delivered'  => ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Livrée'],
                    'failed'     => ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Échouée'],
                    default      => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente'],
                };
            @endphp
            <div class="flex flex-col gap-2 items-end mt-1">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2.5 py-1.5 rounded-md {{ $sb[0] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $sb[1] }}"></span>{{ $sb[2] }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2.5 py-1.5 rounded-md {{ $db[0] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $db[1] }}"></span>{{ $db[2] }}
                </span>
            </div>
        </div>
    </div>

    <div class="px-8">
        <div class="grid grid-cols-[300px_1fr] gap-5">

        {{-- SIDEBAR GAUCHE --}}
        <div class="space-y-5">

            {{-- Détails commande --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Détails</span>
                </div>
                <div class="divide-y divide-[#efefed]">

                    {{-- Client --}}
                    <div class="px-5 py-3.5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Client</div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $commande->user->name }}</div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->user->email }}</div>
                    </div>

                    {{-- Montant --}}
                    <div class="px-5 py-3.5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Montant</div>
                        <div class="font-mono text-[18px] font-medium text-[#0a0a0a] leading-none">
                            {{ number_format($commande->total, 0, ',', ' ') }}
                            <span class="text-[11px] text-[#a0a09a] font-sans font-light">FCFA</span>
                        </div>
                    </div>

                    {{-- Livraison prévue --}}
                    @if($commande->expected_delivery_date)
                        <div class="px-5 py-3.5">
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Livraison prévue</div>
                            <div class="font-mono text-[13px] font-medium text-[#0a0a0a]">
                                {{ \Carbon\Carbon::parse($commande->expected_delivery_date)->format('d/m/Y · H:i') }}
                            </div>
                        </div>
                    @endif

                    {{-- Adresse --}}
                    <div class="px-5 py-3.5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Adresse</div>
                        <div class="text-[12px] text-[#2a2a28] font-light leading-relaxed">
                            {{ $commande->adresse_detail ?? $commande->adresse_livraison ?? 'Non spécifiée' }}
                        </div>
                        @if($commande->telephone_livraison)
                            <div class="font-mono text-[11px] text-[#a0a09a] mt-1.5">{{ $commande->telephone_livraison }}</div>
                        @endif
                    </div>

                    {{-- Date commande --}}
                    <div class="px-5 py-3.5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Commandé le</div>
                        <div class="font-mono text-[12px] text-[#666660]">{{ $commande->created_at->format('d/m/Y · H:i') }}</div>
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-5 space-y-2">
                <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Actions</div>
                <a href="{{ route('admin.orders.show', $commande) }}"
                   class="flex items-center gap-2 w-full bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2.5 rounded-lg
                          hover:opacity-85 transition-opacity justify-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Voir détails complets
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-2 w-full text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2.5 rounded-lg
                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all justify-center">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Retour à la liste
                </a>
            </div>

        </div>

        {{-- TIMELINE --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Historique de suivi</span>
            </div>

            @if($tracking->isEmpty())
                <div class="px-5 py-16 text-center">
                    <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun événement enregistré</p>
                    <p class="text-[12px] text-[#a0a09a] font-light">Les informations s'afficheront une fois le colis en transit</p>

                    @if(!\Illuminate\Support\Facades\Schema::hasTable('delivery_trackings'))
                        <div class="mt-5 mx-auto max-w-sm bg-[#fdf6ec] border border-[#fde68a] rounded-lg px-4 py-3 text-left">
                            <div class="text-[11px] font-medium text-[#b45309] mb-1.5">Table de suivi manquante</div>
                            <code class="block text-[11px] font-mono text-[#92400e] bg-white/70 rounded px-2 py-1.5 border border-[#fde68a]">
                                php artisan migrate
                            </code>
                        </div>
                    @endif
                </div>
            @else
                <div class="px-5 py-6">
                    @foreach($tracking as $event)
                        @php
                            $label = match($event->status) {
                                'picked_up'  => 'Colis enlevé',
                                'in_transit' => 'En transit',
                                'delivered'  => 'Livré',
                                'failed'     => 'Livraison échouée',
                                default      => ucfirst(str_replace('_', ' ', $event->status)),
                            };
                            $dot = match($event->status) {
                                'delivered'  => 'bg-[#22c55e]',
                                'failed'     => 'bg-[#f87171]',
                                'in_transit' => 'bg-[#a78bfa]',
                                default      => 'bg-[#f59e0b]',
                            };
                            $icon = match($event->status) {
                                'picked_up'  => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
                                'in_transit' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                                'delivered'  => '<polyline points="20 6 9 17 4 12"/>',
                                'failed'     => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
                                default      => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
                            };
                        @endphp
                        <div class="flex gap-4 {{ !$loop->last ? 'mb-6' : '' }}">
                            {{-- Dot + ligne --}}
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-8 h-8 border border-[#e0e0dc] bg-[#f7f7f5] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        {!! $icon !!}
                                    </svg>
                                </div>
                                @if(!$loop->last)
                                    <div class="w-px flex-1 bg-[#e0e0dc] mt-2 min-h-[24px]"></div>
                                @endif
                            </div>

                            {{-- Contenu --}}
                            <div class="flex-1 pb-1">
                                <div class="flex items-start justify-between gap-4 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }} flex-shrink-0 mt-0.5"></span>
                                        <span class="text-[13px] font-medium text-[#0a0a0a]">{{ $label }}</span>
                                    </div>
                                    <span class="font-mono text-[10px] text-[#a0a09a] flex-shrink-0 whitespace-nowrap">
                                        {{ $event->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if($event->notes)
                                    <p class="text-[12px] text-[#666660] font-light ml-3.5">{{ $event->notes }}</p>
                                @endif

                                @if($event->latitude && $event->longitude)
                                    <div class="flex items-center gap-1 mt-1 ml-3.5">
                                        <svg class="w-3 h-3 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <span class="font-mono text-[10px] text-[#a0a09a]">{{ $event->latitude }}, {{ $event->longitude }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        </div>{{-- /grid --}}
    </div>
</div>
@endsection
