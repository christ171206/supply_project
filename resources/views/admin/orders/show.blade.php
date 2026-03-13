@extends('layouts.admin-layout')

@section('title', 'Commande #' . $commande->id . ' — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.orders.index') }}" class="hover:text-[#0a0a0a] transition-colors">Commandes</a>
    &nbsp;/&nbsp; #{{ $commande->id }}
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux commandes
        </a>
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Commande</div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">#{{ $commande->id }}</h1>
                <div class="font-mono text-[12px] text-white/40 mt-2">{{ $commande->created_at->format('d/m/Y · H:i') }}</div>
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
                    <span class="w-1.5 h-1.5 rounded-full {{ $db[1] }}"></span>Livraison · {{ $db[2] }}
                </span>
            </div>
        </div>
    </div>

    <div class="px-8">
        <div class="grid grid-cols-[1fr_280px] gap-5">

        {{-- COLONNE PRINCIPALE --}}
        <div class="space-y-5">

            {{-- Client --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Client</span>
                </div>
                <div class="px-5 py-4 flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#0a0a0a] rounded-md flex items-center justify-center text-white text-[11px] font-medium flex-shrink-0">
                        {{ strtoupper(substr($commande->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $commande->user?->name ?? 'Utilisateur inconnu' }}</div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->user?->email ?? '—' }}</div>
                        @if($commande->user?->phone)
                            <div class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->user?->phone }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Articles --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Articles ({{ $commande->ligneCommandes->count() }})</span>
                </div>
                @if($commande->ligneCommandes->isNotEmpty())
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                                <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Produit</th>
                                <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Qté</th>
                                <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">P.U.</th>
                                <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commande->ligneCommandes as $ligne)
                                <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $ligne->produit->nom ?? 'Produit supprimé' }}</div>
                                        <div class="text-[11px] text-[#a0a09a] font-light">{{ $ligne->produit?->user?->shop_name ?? '—' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-mono text-[12px] text-[#666660]">{{ $ligne->quantite }}</td>
                                    <td class="px-5 py-3.5 text-right font-mono text-[12px] text-[#666660]">
                                        {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-[13px] font-medium text-[#0a0a0a]">
                                        {{ number_format($ligne->prix_unitaire * $ligne->quantite, 0, ',', ' ') }}
                                        <span class="text-[10px] text-[#a0a09a] font-sans">F</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Totaux --}}
                    <div class="border-t border-[#efefed] px-5 py-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[12px] text-[#666660]">Sous-total</span>
                            <span class="font-mono text-[12px] text-[#0a0a0a]">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @if($commande->frais_livraison)
                            <div class="flex items-center justify-between">
                                <span class="text-[12px] text-[#666660]">Frais de livraison</span>
                                <span class="font-mono text-[12px] text-[#0a0a0a]">{{ number_format($commande->frais_livraison, 0, ',', ' ') }} FCFA</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t border-[#efefed]">
                            <span class="text-[12px] font-medium text-[#0a0a0a]">Total</span>
                            <span class="font-mono text-[16px] font-medium text-[#0a0a0a]">
                                {{ number_format($commande->total + ($commande->frais_livraison ?? 0), 0, ',', ' ') }}
                                <span class="text-[11px] text-[#a0a09a] font-sans">FCFA</span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun article</div>
                @endif
            </div>

            {{-- Adresse de livraison --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Adresse de livraison</span>
                </div>
                <div class="px-5 py-4 space-y-2">
                    <div class="text-[13px] text-[#2a2a28] font-light leading-relaxed">
                        {{ $commande->adresse_detail ?? $commande->adresse_livraison ?? '—' }}
                    </div>
                    @if($commande->telephone_livraison)
                        <div class="font-mono text-[12px] text-[#a0a09a]">{{ $commande->telephone_livraison }}</div>
                    @endif
                    @if($commande->deliveryZone)
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-[11px] text-[#a0a09a]">Zone</span>
                            <span class="text-[11px] font-medium text-[#0a0a0a]">{{ $commande->deliveryZone->nom }}</span>
                        </div>
                    @endif
                </div>
                @if($commande->notes)
                    <div class="mx-5 mb-4 px-3.5 py-3 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Notes</div>
                        <div class="text-[12px] text-[#2a2a28] font-light">{{ $commande->notes }}</div>
                    </div>
                @endif
            </div>

        </div>

        {{-- SIDEBAR DROITE --}}
        <div class="space-y-5">

            {{-- Paiement --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Paiement</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Mode</div>
                        <div class="text-[13px] text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}</div>
                    </div>
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Confirmé</div>
                        @if($commande->paiement_confirme)
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#fef2f2] text-[#dc2626]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>Non
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Statuts --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Statuts</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Commande</div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $sb[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sb[1] }}"></span>{{ $sb[2] }}
                        </span>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1.5">Géré par le vendeur</div>
                    </div>
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Livraison</div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $db[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $db[1] }}"></span>{{ $db[2] }}
                        </span>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1.5">Géré par le livreur</div>
                    </div>
                </div>
            </div>

            {{-- Dates --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="divide-y divide-[#efefed]">
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Commandé</div>
                        <div class="font-mono text-[12px] text-[#0a0a0a]">{{ $commande->created_at->format('d/m/Y · H:i') }}</div>
                    </div>
                    <div class="px-5 py-3">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Modifié</div>
                        <div class="font-mono text-[12px] text-[#a0a09a]">{{ $commande->updated_at->format('d/m/Y · H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-5 space-y-2">
                <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Actions</div>
                <a href="{{ route('admin.orders.tracking', $commande->id) }}"
                   class="flex items-center justify-center gap-2 w-full bg-[#0a0a0a] text-white text-[12px] font-medium
                          px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Suivi détaillé
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center justify-center gap-2 w-full text-[12px] font-medium text-[#666660]
                          border border-[#e0e0dc] px-4 py-2.5 rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Retour à la liste
                </a>
                <div class="px-3.5 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg">
                    <div class="text-[10px] text-[#a0a09a] font-light leading-relaxed">
                        Annulation gérée par le client ou le vendeur
                    </div>
                </div>
            </div>

        </div>
        </div>{{-- /grid --}}
    </div>
</div>
@endsection
