@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] rounded-xl px-8 pt-10 pb-8 mb-6 animate-slideDown">
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
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium px-3 py-1.5 rounded-md bg-[#f0fdf4] text-[#15803d]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                Confirmée
            </span>
        </div>
    </div>

    {{-- Retour --}}
    <a href="{{ route('commandes.index') }}"
       class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-6">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Mes commandes
    </a>

    <div class="grid grid-cols-[1fr_320px] gap-6 items-start animate-fadeInUp">

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
                                Vendeur : {{ $ligne->produit->vendeur->name ?? '—' }}
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
                    <div class="text-[12px] text-[#a0a09a] font-light">{{ $commande->pays }}</div>
                    <div class="flex items-center gap-1.5 pt-1.5">
                        <svg class="w-3.5 h-3.5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.4 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.34 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.4a16 16 0 0 0 6.29 6.29l.76-.77a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="font-mono text-[11px] text-[#a0a09a]">{{ $commande->telephone_livraison }}</span>
                    </div>
                </div>
            </div>

            {{-- Informations de paiement --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Informations de paiement</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-[11px] text-[#a0a09a] font-light">Référence</span>
                        <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">
                            {{ $commande->payment?->payment_code ?? 'PAY-' . strtoupper(substr($commande->id, 0, 8)) }}
                        </span>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-[11px] text-[#a0a09a] font-light">Type</span>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">
                            {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}
                        </span>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-[11px] text-[#a0a09a] font-light">Statut paiement</span>
                        <span class="text-[12px] font-medium text-[#15803d] inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                            Confirmée
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ COLONNE DROITE: RÉSUMÉ ══ --}}
        <div class="space-y-4" style="animation-delay: 150ms;">

            {{-- Résumé card --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Récapitulatif</span>
                </div>

                <div class="divide-y divide-[#efefed]">
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-[11px] text-[#a0a09a] font-light">Méthode</span>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">
                            {{ ucfirst(str_replace('_', ' ', $commande->payment_method)) }}
                        </span>
                    </div>
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <span class="text-[11px] text-[#a0a09a] font-light">Statut</span>
                        <span class="inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                            <span class="text-[12px] font-medium text-[#15803d]">Confirmée</span>
                        </span>
                    </div>
                </div>

                {{-- Total --}}
                <div class="bg-[#0a0a0a] px-5 py-4">
                    <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-white/50 mb-2">TOTAL TTC</div>
                    <div class="text-[24px] font-mono font-medium text-white">
                        {{ number_format($commande->total, 0, ',', ' ') }} <span class="text-[14px] font-light">FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Download facture --}}
            <a href="{{ route('commandes.download-pdf', $commande->id) }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Télécharger la facture
            </a>

            {{-- Continue shopping --}}
            <a href="{{ route('accueil') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-[#e0e0dc] text-[#0a0a0a] text-[13px] font-medium rounded-lg hover:bg-[#f7f7f5] transition-colors">
                Continuer les achats
            </a>

            {{-- My orders --}}
            <a href="{{ route('client.commandes') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-[#e0e0dc] text-[#666660] text-[13px] font-medium rounded-lg hover:text-[#0a0a0a] hover:border-[#0a0a0a] transition-colors">
                Voir mes commandes
            </a>

        </div>
    </div>

</div>
</div>

<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideDown {
    animation: slideDown 0.5s ease-out;
}

.animate-fadeInUp {
    animation: fadeInUp 0.5s ease-out 0.1s both;
}
</style>

@endsection
