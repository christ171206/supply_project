@extends('layouts.admin-layout')

@section('title', 'Dashboard — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Tableau de bord
@endsection

@section('content')
<div class="pb-16">

    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Supply</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Tableau de bord</h1>
        <p class="text-[13px] text-white/40 font-light mt-2">Vue d'ensemble de la plateforme</p>

        {{-- KPIs inline --}}
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            @php
                $kpis = [
                    ['value' => number_format($totalRevenue ?? 0, 0, ',', ' '), 'unit' => 'FCFA', 'label' => "Chiffre d'affaires"],
                    ['value' => number_format($totalCommission ?? 0, 0, ',', ' '), 'unit' => 'FCFA', 'label' => "Commission {$commissionRate}%"],
                    ['value' => $activeVendors, 'unit' => '',     'label' => 'Vendeurs actifs'],
                    ['value' => $satisfactionRate.'%', 'unit' => '', 'label' => 'Satisfaction'],
                    ['value' => $totalOrders,    'unit' => '',     'label' => 'Commandes'],
                    ['value' => $totalClients,   'unit' => '',     'label' => 'Clients'],
                ];
            @endphp
            @foreach($kpis as $i => $k)
                @if($i > 0)<div class="w-px h-8 bg-white/10"></div>@endif
                <div>
                    <div class="font-mono text-[22px] font-medium text-white leading-none">
                        {{ $k['value'] }}
                        @if($k['unit'])<span class="text-[12px] text-white/35 font-sans font-light ml-0.5">{{ $k['unit'] }}</span>@endif
                    </div>
                    <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">{{ $k['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="px-8 space-y-8">

    {{-- ══════════════════════════════
         STATS SECONDAIRES
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Indicateurs</div>
        <div class="grid grid-cols-6 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
            @php
                $stats = [
                    ['label'=>'Commandes / mois', 'value'=>$thisMonthOrders,   'sub'=>($orderGrowth >= 0 ? '+' : '').$orderGrowth.'%'],
                    ['label'=>'Clients',           'value'=>$totalClients,      'sub'=>$newClientsThisMonth.' nouveaux'],
                    ['label'=>'Produits actifs',   'value'=>$totalProducts,     'sub'=>'En ligne'],
                    ['label'=>'Utilisateurs',      'value'=>$totalUsers,        'sub'=>'Enregistrés'],
                    ['label'=>'Bloqués',           'value'=>$bannedUsers,       'sub'=>'Suspendus', 'alert'=>true],
                    ['label'=>'Litiges ouverts',   'value'=>$pendingDisputes,   'sub'=>'En cours',  'alert'=>$pendingDisputes > 0],
                ];
            @endphp
            @foreach($stats as $s)
                <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                    <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">{{ $s['label'] }}</div>
                    <div class="font-mono text-[28px] font-medium leading-none {{ ($s['alert'] ?? false) ? 'text-[#dc2626]' : 'text-[#0a0a0a]' }}">
                        {{ $s['value'] }}
                    </div>
                    <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">{{ $s['sub'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════
         STATUT COMMANDES — hover inversé
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Statut des commandes</div>
        <div class="grid grid-cols-4 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
            @php
                $statuts = [
                    ['label'=>'En attente', 'value'=>$ordersAwaitingConfirmation, 'dot'=>'bg-[#f59e0b]', 'sub'=>'À traiter'],
                    ['label'=>'Confirmées', 'value'=>$ordersConfirmed,            'dot'=>'bg-[#60a5fa]', 'sub'=>'Prêtes'],
                    ['label'=>'Expédiées',  'value'=>$ordersShipped,              'dot'=>'bg-[#a78bfa]', 'sub'=>'En route'],
                    ['label'=>'Livrées',    'value'=>$ordersDelivered,            'dot'=>'bg-[#22c55e]', 'sub'=>'Complètes'],
                ];
            @endphp
            @foreach($statuts as $s)
                <div class="bg-white px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                        <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">
                        {{ $s['value'] }}
                    </div>
                    <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">
                        {{ $s['sub'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════
         GRAPHIQUES ligne 1
    ══════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Revenus — 7 derniers jours</div>
            <div class="h-56"><canvas id="revenueChart"></canvas></div>
        </div>
        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Distribution des commandes</div>
            <div class="h-56 flex items-center justify-center"><canvas id="statusChart"></canvas></div>
        </div>
    </div>

    {{-- ══════════════════════════════
         GRAPHIQUES ligne 2
    ══════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Revenus — 30 derniers jours</div>
            <div class="h-56"><canvas id="revenue30Chart"></canvas></div>
        </div>
        <div class="bg-white px-5 py-5">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-5">Croissance — 7 jours</div>
            <div class="h-56"><canvas id="growthChart"></canvas></div>
        </div>
    </div>

    {{-- ══════════════════════════════
         TOP VENDEURS + DERNIÈRES COMMANDES
    ══════════════════════════════ --}}
    <div class="grid grid-cols-[280px_1fr] gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        {{-- Top vendeurs --}}
        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Top 5 vendeurs</span>
            </div>
            @forelse($topVendors as $i => $vendor)
                <div class="flex items-center gap-3 px-5 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <span class="w-5 text-[11px] font-mono text-[#a0a09a] flex-shrink-0 text-right">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $vendor->name }}</div>
                        <div class="text-[11px] text-[#a0a09a] font-light">{{ $vendor->total_orders ?? 0 }} commandes</div>
                    </div>
                    <div class="font-mono text-[12px] font-medium text-[#0a0a0a] flex-shrink-0 text-right">
                        {{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }}
                        <span class="text-[10px] text-[#a0a09a] font-sans">F</span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun vendeur</div>
            @endforelse
        </div>

        {{-- Dernières commandes --}}
        <div class="bg-white">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Dernières commandes</span>
                <a href="{{ route('admin.orders.index') }}"
                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px">
                    Voir tout →
                </a>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Client</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">N°</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Montant</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        @php
                            $b = match($order->statut) {
                                'en_attente' => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]', 'En attente'],
                                'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]',  'bg-[#60a5fa]', 'Confirmée'],
                                'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]',  'bg-[#a78bfa]', 'Expédiée'],
                                'livree'     => ['bg-[#f0fdf4] text-[#15803d]',  'bg-[#22c55e]', 'Livrée'],
                                default      => ['bg-[#fef2f2] text-[#dc2626]',  'bg-[#f87171]', ucfirst(str_replace('_',' ',$order->statut))],
                            };
                        @endphp
                        <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-5 py-3.5 text-[13px] font-medium text-[#0a0a0a]">{{ $order->user->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 font-mono text-[12px] text-[#666660]">#{{ $order->id }}</td>
                            <td class="px-5 py-3.5 font-mono text-[12px] font-medium text-[#0a0a0a]">
                                {{ number_format($order->total ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $b[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $b[1] }}"></span>
                                    {{ $b[2] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">
                                Aucune commande
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- ══════════════════════════════
         ACTIONS RAPIDES — hover inversé
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Actions rapides</div>
        <div class="grid grid-cols-3 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

            <a href="{{ route('admin.users.index') }}"
               class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0
                            group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Gérer utilisateurs</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/50 transition-colors">{{ $totalUsers }} enregistrés</div>
                </div>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0
                            group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Valider produits</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/50 transition-colors">{{ $productsAwaitingValidation->count() }} en attente</div>
                </div>
            </a>

            <a href="{{ route('admin.disputes.index') }}"
               class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0
                            group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Litiges</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/50 transition-colors">{{ $pendingDisputes }} ouverts</div>
                </div>
            </a>

        </div>
    </div>

    </div>{{-- /px-8 --}}
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
const MONO = "'Geist Mono', monospace";
const SANS = "'Geist', sans-serif";
const tick = { font: { size: 10, family: MONO }, color: '#a0a09a' };
const grid = { color: '#efefed' };

// 1 — Revenus 7 jours
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: @json($revenueDayLabels),
        datasets: [{
            data: @json($revenueData),
            borderColor: '#0a0a0a',
            backgroundColor: 'rgba(10,10,10,0.04)',
            borderWidth: 1.5,
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: '#0a0a0a',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: tick },
            y: { grid, ticks: { ...tick, callback: v => (v/1000).toFixed(0)+'k' } }
        }
    }
});

// 2 — Doughnut statut
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['En attente','Confirmées','Expédiées','Livrées','Annulées'],
        datasets: [{
            data: [
                @json($ordersByStatus['en_attente'] ?? 0),
                @json($ordersByStatus['confirmee']  ?? 0),
                @json($ordersByStatus['expediee']   ?? 0),
                @json($ordersByStatus['livree']     ?? 0),
                @json($ordersByStatus['annulee']    ?? 0),
            ],
            backgroundColor: ['#f59e0b','#60a5fa','#a78bfa','#22c55e','#f87171'],
            borderColor: '#fff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 14, usePointStyle: true, pointStyle: 'circle', font: { size: 11, family: SANS }, color: '#666660' }
            }
        }
    }
});

// 3 — Bar 30 jours
new Chart(document.getElementById('revenue30Chart'), {
    type: 'bar',
    data: {
        labels: @json($revenueLast30DaysLabels),
        datasets: [{
            data: @json($revenueLast30Days),
            backgroundColor: '#0a0a0a',
            hoverBackgroundColor: '#2a2a28',
            borderRadius: 2,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { ...tick, maxRotation: 0 } },
            y: { grid, ticks: { ...tick, callback: v => (v/1000).toFixed(0)+'k' } }
        }
    }
});

// 4 — Croissance
new Chart(document.getElementById('growthChart'), {
    type: 'line',
    data: {
        labels: @json($growthChartLabels),
        datasets: [
            {
                label: 'Commandes',
                data: @json($ordersData),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.06)',
                borderWidth: 1.5, tension: 0.4, pointRadius: 3,
            },
            {
                label: 'Vendeurs',
                data: @json($vendorsData),
                borderColor: '#a78bfa',
                backgroundColor: 'rgba(167,139,250,0.06)',
                borderWidth: 1.5, tension: 0.4, pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 11, family: SANS }, color: '#666660' }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: tick },
            y: { grid, ticks: tick }
        }
    }
});
</script>
@endsection
