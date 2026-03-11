@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-4xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] rounded-xl px-8 pt-10 pb-8 mb-6">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Supply</div>

        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none mb-2">
                    Commande confirmée
                </h1>
                <div class="flex items-center gap-3 mt-3">
                    <span class="font-mono text-[13px] text-white/50">{{ $commande->numero ?? 'CMD-' . $commande->id }}</span>
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                    <span class="font-mono text-[13px] text-white/50">{{ $commande->created_at->format('d/m/Y · H:i') }}</span>
                </div>
            </div>

            {{-- Badge statut --}}
            @php
                $badge = match($commande->statut) {
                    'en_attente' => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]', 'En attente'],
                    'confirmee'  => ['bg-[#f0fdf4] text-[#15803d]', 'bg-[#22c55e]', 'Confirmée'],
                    'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]', 'bg-[#a78bfa]', 'Expédiée'],
                    'livree'     => ['bg-[#f0fdf4] text-[#15803d]', 'bg-[#22c55e]', 'Livrée'],
                    'annulee'    => ['bg-[#fef2f2] text-[#dc2626]', 'bg-[#f87171]', 'Annulée'],
                    default      => ['bg-[#f7f7f5] text-[#666660]',  'bg-[#a0a09a]', ucfirst(str_replace('_',' ',$commande->statut))],
                };
            @endphp
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium px-3 py-1.5 rounded-md {{ $badge[0] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $badge[1] }}"></span>
                {{ $badge[2] }}
            </span>
        </div>

        {{-- Flash success --}}
        @if(session('success'))
            <div class="mt-5 pt-5 border-t border-white/10 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#22c55e] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[12px] text-white/60 font-light">{{ session('success') }}</span>
            </div>
        @endif
    </div>

    {{-- Retour --}}
    <a href="{{ route('commandes.index') }}"
       class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-6">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Mes commandes
    </a>

    <div class="grid grid-cols-[1fr_280px] gap-5 items-start">

        {{-- ══ COLONNE PRINCIPALE ══ --}}
        <div class="space-y-4">

            {{-- Produits commandés --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Produits commandés</span>
                </div>
                @forelse($lignes as $ligne)
                    <div class="flex items-center justify-between px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $ligne->produit->nom }}</div>
                            <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">
                                Vendeur : {{ $ligne->produit->user->name ?? '—' }}
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <div class="font-mono text-[12px] text-[#a0a09a]">{{ $ligne->quantite }} × {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</div>
                            <div class="font-mono text-[14px] font-medium text-[#0a0a0a] mt-0.5">
                                {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun produit</div>
                @endforelse
            </div>

            {{-- Adresse de livraison --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Adresse de livraison</span>
                </div>
                <div class="px-5 py-4 space-y-1.5">
                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $commande->adresse_livraison }}</div>
                    @if($commande->adresse_detail)
                        <div class="text-[12px] text-[#666660] font-light">{{ $commande->adresse_detail }}</div>
                    @endif
                    <div class="flex items-center gap-1.5 pt-1">
                        <svg class="w-3 h-3 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.4 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.34 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.4a16 16 0 0 0 6.29 6.29l.76-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->telephone_livraison }}</span>
                    </div>
                </div>
            </div>

            {{-- Paiement --}}
            @if($payment)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Informations de paiement</span>
                </div>
                @php
                    $pRows = [
                        ['label' => 'Référence',       'value' => $payment->payment_code,                                           'mono' => true],
                        ['label' => 'Type',             'value' => ucfirst(str_replace('_', ' ', $payment->typePayement)),           'mono' => false],
                        ['label' => 'Statut paiement', 'value' => match($payment->payment_status) {
                            'initialisee' => 'Initialisée',
                            'en_attente'  => 'En attente',
                            'confirmee'   => 'Confirmée',
                            'echouee'     => 'Échouée',
                            'annulee'     => 'Annulée',
                            default       => ucfirst(str_replace('_', ' ', $payment->payment_status))
                        }, 'mono' => false],
                        ['label' => 'Montant',          'value' => number_format($payment->montant, 0, ',', ' ') . ' FCFA',          'mono' => true],
                    ];
                @endphp
                @foreach($pRows as $row)
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#efefed] last:border-b-0">
                        <span class="text-[12px] text-[#a0a09a] font-light">{{ $row['label'] }}</span>
                        <span class="{{ $row['mono'] ? 'font-mono text-[12px]' : 'text-[13px]' }} font-medium text-[#0a0a0a]">
                            {{ $row['value'] }}
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ══ SIDEBAR STICKY ══ --}}
        <div class="sticky top-6 space-y-4">

            {{-- Résumé --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Résumé</span>
                </div>

                <div class="px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] text-[#666660] font-light">Méthode</span>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">
                            {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] text-[#666660] font-light">Statut</span>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $badge[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $badge[1] }}"></span>
                            {{ $badge[2] }}
                        </span>
                    </div>
                </div>

                <div class="mx-4 mb-4 bg-[#0a0a0a] rounded-lg px-4 py-4">
                    <div class="text-[10px] font-medium tracking-[0.08em] uppercase text-white/50 mb-1">Total TTC</div>
                    <div class="font-mono text-[20px] font-medium text-white leading-none">
                        {{ number_format($commande->total, 0, ',', ' ') }}
                        <span class="text-[11px] text-white/40 font-sans font-light">FCFA</span>
                    </div>
                </div>

                {{-- Note paiement --}}
                @if($commande->payment_method !== 'cash')
                    <div class="mx-4 mb-4 px-3 py-2.5 bg-[#fdf6ec] border border-[#fde68a] rounded-lg">
                        <p class="text-[11px] text-[#b45309] font-light leading-relaxed">
                            Confirmez votre paiement via {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}.
                        </p>
                    </div>
                @else
                    <div class="mx-4 mb-4 px-3 py-2.5 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg">
                        <p class="text-[11px] text-[#15803d] font-light leading-relaxed">Paiement à la livraison.</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="space-y-2">
                <a href="{{ route('commandes.download-pdf', $commande->id) }}"
                   class="flex items-center justify-center gap-2 w-full bg-[#0a0a0a] text-white text-[12px] font-medium py-3 rounded-lg hover:opacity-85 transition-opacity">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Télécharger la facture
                </a>
                <a href="{{ route('produits.catalogue') }}"
                   class="flex items-center justify-center gap-2 w-full border border-[#e0e0dc] text-[#0a0a0a] text-[12px] font-medium py-3 rounded-lg hover:border-[#2a2a28] transition-all">
                    Continuer les achats
                </a>
                <a href="{{ route('commandes.index') }}"
                   class="flex items-center justify-center gap-2 w-full border border-[#e0e0dc] text-[#666660] text-[12px] font-light py-3 rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                    Voir mes commandes
                </a>
            </div>

        </div>

    </div>
</div>
</div>
@endsection
