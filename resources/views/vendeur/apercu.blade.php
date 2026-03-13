@extends('vendeur.layout-dashboard')

@section('title', 'Aperçu Boutique — Supply')

@section('breadcrumb')
    Espace Vendeur &nbsp;/&nbsp; Aperçu Boutique
@endsection

@section('content')
<div class="pb-20">

    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Espace vendeur</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Aperçu Boutique</h1>
        <p class="text-[13px] text-white/50 font-light mt-2">Vue d'ensemble de vos performances</p>

        {{-- KPIs dans le header --}}
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ number_format($totalVentes, 0, ',', ' ') }}
                    <span class="text-[13px] text-white/40 font-sans font-light">FCFA</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Ventes totales</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $nombreCommandes }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Commandes</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $nombreProduits }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Produits en ligne</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ round($noteMoyenne, 1) }}<span class="text-[13px] text-white/40">/5</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Note moyenne</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ number_format($panierMoyen, 0, ',', ' ') }}
                    <span class="text-[13px] text-white/40 font-sans font-light">FCFA</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Panier moyen</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-8">

    {{-- ══════════════════════════════
         STATUT COMMANDES + COMPLÉTUDE
    ══════════════════════════════ --}}
    <div class="grid grid-cols-[1fr_320px] gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        {{-- Statut commandes --}}
        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Statut des commandes</span>
            </div>
            <div class="grid grid-cols-4 divide-x divide-[#efefed]">
                @php
                    $statuts = [
                        ['label' => 'En attente',  'value' => $commandesEnAttente,  'dot' => 'bg-[#f59e0b]', 'sub' => 'À traiter'],
                        ['label' => 'Confirmées',  'value' => $commandesConfirmees, 'dot' => 'bg-[#60a5fa]', 'sub' => 'Prêtes'],
                        ['label' => 'Expédiées',   'value' => $commandesExpediees,  'dot' => 'bg-[#a78bfa]', 'sub' => 'En route'],
                        ['label' => 'Livrées',     'value' => $commandeslivrees,    'dot' => 'bg-[#22c55e]', 'sub' => 'Complètes'],
                    ];
                @endphp
                @foreach($statuts as $s)
                    <div class="px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                        <div class="flex items-center gap-2 mb-5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">{{ $s['label'] }}</span>
                        </div>
                        <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">{{ $s['value'] }}</div>
                        <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">{{ $s['sub'] }}</div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-3.5 border-t border-[#efefed]">
                <a href="{{ route('vendeur.commandes') }}" class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir toutes les commandes →</a>
            </div>
        </div>

        {{-- Complétude profil --}}
        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed] flex items-center justify-between">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Profil boutique</span>
                <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $tauxCompletion }}%</span>
            </div>

            {{-- Barre de progression --}}
            <div class="px-5 pt-4 pb-2">
                <div class="w-full h-1 bg-[#efefed] rounded-full overflow-hidden">
                    <div class="h-full bg-[#0a0a0a] transition-all" style="width: {{ $tauxCompletion }}%"></div>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="px-5 py-3 space-y-0">
                @php
                    $user = auth()->user();
                    $items = [
                        ['label' => 'Nom de la boutique', 'ok' => (bool)$user->shop_name],
                        ['label' => 'Description',        'ok' => (bool)$user->description],
                        ['label' => 'Téléphone',          'ok' => (bool)$user->phone],
                        ['label' => 'Adresse',            'ok' => (bool)$user->address],
                        ['label' => 'Photo de profil',    'ok' => (bool)$user->profile_photo],
                    ];
                @endphp
                @foreach($items as $item)
                    <div class="flex items-center gap-3 py-2.5 border-b border-[#efefed] last:border-b-0">
                        @if($item['ok'])
                            <svg class="w-3.5 h-3.5 text-[#22c55e] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg class="w-3.5 h-3.5 text-[#e0e0dc] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                        @endif
                        <span class="text-[12px] {{ $item['ok'] ? 'text-[#666660]' : 'text-[#0a0a0a] font-medium' }}">{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>

            @if($tauxCompletion < 100)
                <div class="px-5 py-4 border-t border-[#efefed]">
                    <a href="{{ route('vendeur.profil') }}"
                       class="block text-center text-[12px] font-medium bg-[#0a0a0a] text-white py-2 rounded-lg hover:opacity-85 transition-opacity">
                        Compléter le profil
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════
         GRAPHIQUES
    ══════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Distribution des commandes</div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="chartStatut"></canvas>
            </div>
        </div>

        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Top 5 produits — ventes</div>
            <div class="h-64">
                <canvas id="chartProduits"></canvas>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════
         TOP PRODUITS + AVIS
    ══════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        {{-- Top produits --}}
        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Détail top 5 produits</span>
            </div>
            @forelse($topProduits as $idx => $produit)
                <div class="flex items-center gap-4 px-5 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <span class="w-5 text-[11px] font-mono text-[#a0a09a] flex-shrink-0">{{ $idx + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $produit->nom }}</div>
                        <div class="text-[11px] text-[#a0a09a] font-light">{{ $produit->ventes_nombre }} ventes</div>
                    </div>
                    <div class="font-mono text-[12px] font-medium text-[#0a0a0a] flex-shrink-0">
                        {{ number_format($produit->ventes_total, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun produit vendu</div>
            @endforelse
        </div>

        {{-- Avis récents --}}
        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed] flex items-center justify-between">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Avis récents</span>
                <span class="font-mono text-[11px] text-[#a0a09a]">{{ $nombreAvis }} avis</span>
            </div>
            @forelse($avisRecents as $avis)
                <div class="px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <div class="flex items-start justify-between mb-1.5">
                        <div>
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $avis->user->name }}</div>
                            <div class="text-[11px] text-[#a0a09a] font-light truncate max-w-[180px]">{{ $avis->produit->nom }}</div>
                        </div>
                        <div class="flex gap-0.5 flex-shrink-0">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-[11px] {{ $i <= $avis->note ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-[12px] text-[#666660] font-light leading-relaxed line-clamp-2">{{ $avis->commentaire }}</p>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun avis</div>
            @endforelse
        </div>

    </div>

    </div>{{-- /px-8 --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Palette monochrome + statuts
const COLORS = {
    attente:   '#f59e0b',
    confirmee: '#60a5fa',
    expediee:  '#a78bfa',
    livree:    '#22c55e',
};

// Chart doughnut — statut commandes
new Chart(document.getElementById('chartStatut').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['En attente', 'Confirmées', 'Expédiées', 'Livrées'],
        datasets: [{
            data: [{{ $commandesEnAttente }}, {{ $commandesConfirmees }}, {{ $commandesExpediees }}, {{ $commandeslivrees }}],
            backgroundColor: [COLORS.attente, COLORS.confirmee, COLORS.expediee, COLORS.livree],
            borderColor: '#ffffff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 16,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { size: 11, family: "'Geist', sans-serif" },
                    color: '#666660',
                }
            }
        }
    }
});

// Chart bar horizontal — top produits
const topData = {!! json_encode($topProduits->map(fn($p) => ['nom' => $p->nom, 'ventes' => $p->ventes_total])) !!};
new Chart(document.getElementById('chartProduits').getContext('2d'), {
    type: 'bar',
    data: {
        labels: topData.map(p => p.nom.length > 22 ? p.nom.substring(0, 22) + '…' : p.nom),
        datasets: [{
            data: topData.map(p => p.ventes),
            backgroundColor: '#0a0a0a',
            hoverBackgroundColor: '#2a2a28',
            borderRadius: 3,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { color: '#efefed' },
                ticks: {
                    font: { size: 10, family: "'Geist Mono', monospace" },
                    color: '#a0a09a',
                    callback: v => (v / 1000).toFixed(0) + 'k',
                }
            },
            y: {
                grid: { display: false },
                ticks: {
                    font: { size: 11, family: "'Geist', sans-serif" },
                    color: '#666660',
                }
            }
        }
    }
});
</script>
@endsection
