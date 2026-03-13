@extends('layouts.admin-layout')

@section('title', 'Statistiques — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; <a href="{{ route('admin.statistics.index') }}">Statistiques</a>
@endsection

@section('content')
<div class="pb-16">
    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Supply</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Statistiques</h1>
        <p class="text-[13px] text-white/40 font-light mt-2">Analyse complète de la plateforme avec graphiques interactifs</p>

        {{-- KPIs inline --}}
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            @php
                $kpis = [
                    ['value' => number_format($totalRevenue ?? 0, 0, ',', ' '), 'unit' => 'FCFA', 'label' => "Chiffre d'affaires total"],
                    ['value' => $totalOrders,          'unit' => '',     'label' => 'Commandes totales'],
                    ['value' => $totalClients,          'unit' => '',     'label' => 'Clients'],
                    ['value' => $totalVendors,          'unit' => '',     'label' => 'Vendeurs'],
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
         FILTRES ET EXPORTS
    ══════════════════════════════ --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Période</div>
            <div class="flex gap-2">
                <input type="date" id="startDate" class="px-3 py-2 border border-[#e0e0dc] rounded bg-white text-sm">
                <input type="date" id="endDate" class="px-3 py-2 border border-[#e0e0dc] rounded bg-white text-sm">
                <button onclick="applyDateFilter()" class="px-4 py-2 bg-[#0a0a0a] text-white text-sm rounded hover:bg-[#2a2a28]">Appliquer</button>
            </div>
        </div>

        {{-- Boutons d'export --}}
        <div class="flex gap-2">
            <button onclick="exportStatistics('csv')" class="px-4 py-2 border border-[#e0e0dc] text-[#0a0a0a] text-sm rounded hover:bg-[#f7f7f5] transition-colors">
                📥 Exporter CSV
            </button>
            <button onclick="exportStatistics('pdf')" class="px-4 py-2 bg-[#0a0a0a] text-white text-sm rounded hover:bg-[#2a2a28] transition-colors">
                📄 Exporter PDF
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════
         GRAPHIQUES PRINCIPAUX
    ══════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Revenu quotidien --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Revenu quotidien</h2>
                <p class="text-[12px] text-[#a0a09a]">Derniers 30 jours</p>
            </div>
            <canvas id="dailyRevenueChart" height="300"></canvas>
        </div>

        {{-- Croissance mensuelle --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Croissance mensuelle</h2>
                <p class="text-[12px] text-[#a0a09a]">Derniers 12 mois</p>
            </div>
            <canvas id="monthlyGrowthChart" height="300"></canvas>
        </div>

        {{-- Top 10 vendeurs --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Top 10 vendeurs</h2>
                <p class="text-[12px] text-[#a0a09a]">Par chiffre d'affaires</p>
            </div>
            <canvas id="topVendorsChart" height="300"></canvas>
        </div>

        {{-- Produits populaires --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Produits populaires</h2>
                <p class="text-[12px] text-[#a0a09a]">Top 10 produits vendus</p>
            </div>
            <canvas id="topProductsChart" height="300"></canvas>
        </div>

        {{-- Statut des commandes --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Statut des commandes</h2>
                <p class="text-[12px] text-[#a0a09a]">Distribution actuelle</p>
            </div>
            <canvas id="orderStatusChart" height="300"></canvas>
        </div>

        {{-- Croissance utilisateurs --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Croissance utilisateurs</h2>
                <p class="text-[12px] text-[#a0a09a]">Clients vs Vendeurs</p>
            </div>
            <canvas id="userGrowthChart" height="300"></canvas>
        </div>

        {{-- Catégories populaires --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6 lg:col-span-2">
            <div class="mb-4">
                <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-1">Catégories populaires</h2>
                <p class="text-[12px] text-[#a0a09a]">Top 8 catégories</p>
            </div>
            <canvas id="categoriesChart" height="200"></canvas>
        </div>
    </div>

    {{-- ══════════════════════════════
         STATISTIQUES DÉTAILLÉES
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Statistiques détaillées</div>
        <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden md:grid-cols-4">
            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Revenu (30j)</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-revenue">0</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">FCFA</div>
            </div>

            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Commandes (30j)</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-orders">0</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">Commandes</div>
            </div>

            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Panier moyen</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-average">0</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">FCFA</div>
            </div>

            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Note moyenne</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-rating">0</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">Avis clients</div>
            </div>
        </div>
    </div>

    </div> {{-- end px-8 --}}
</div> {{-- end pb-16 --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Configuration des graphiques
    const colors = {
        primary: '#0a0a0a',
        secondary: '#a0a09a',
        success: '#22c55e',
        warning: '#f59e0b',
        danger: '#dc2626',
        info: '#60a5fa',
        purple: '#a78bfa',
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                labels: {
                    font: { family: 'Geist, sans-serif', size: 12 },
                    color: colors.primary,
                    padding: 15,
                }
            }
        },
        scales: {
            x: {
                grid: { color: '#f0f0f0' },
                ticks: { font: { size: 11 }, color: colors.secondary }
            },
            y: {
                grid: { color: '#f0f0f0' },
                ticks: { font: { size: 11 }, color: colors.secondary }
            }
        }
    };

    // Charger les graphiques
    function loadCharts() {
        loadDailyRevenueChart();
        loadMonthlyGrowthChart();
        loadTopVendorsChart();
        loadTopProductsChart();
        loadOrderStatusChart();
        loadUserGrowthChart();
        loadCategoriesChart();
        loadDetailedStats();
    }

    // Revenu quotidien
    function loadDailyRevenueChart() {
        fetch('{{ route("admin.statistics.api.daily-revenue") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('dailyRevenueChart'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Revenu (FCFA)',
                            data: data.revenue,
                            borderColor: colors.primary,
                            backgroundColor: 'rgba(10, 10, 10, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: chartOptions
                });
            });
    }

    // Croissance mensuelle
    function loadMonthlyGrowthChart() {
        fetch('{{ route("admin.statistics.api.monthly-growth") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('monthlyGrowthChart'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Chiffre d\'affaires',
                            data: data.revenue,
                            backgroundColor: colors.primary,
                        }]
                    },
                    options: chartOptions
                });
            });
    }

    // Top vendeurs
    function loadTopVendorsChart() {
        fetch('{{ route("admin.statistics.api.top-vendors") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('topVendorsChart'), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: [
                                colors.primary, colors.info, colors.warning,
                                colors.purple, colors.success, colors.secondary,
                                '#f0ad4e', '#5bc0de', '#d9534f', '#5cb85c'
                            ],
                        }]
                    },
                    options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { ...chartOptions.plugins.legend, position: 'right' } } }
                });
            });
    }

    // Produits populaires
    function loadTopProductsChart() {
        fetch('{{ route("admin.statistics.api.top-products") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('topProductsChart'), {
                    type: 'horizontalBar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Quantités vendues',
                            data: data.data,
                            backgroundColor: colors.primary,
                        }]
                    },
                    options: {
                        ...chartOptions,
                        indexAxis: 'y',
                    }
                });
            });
    }

    // Statut des commandes
    function loadOrderStatusChart() {
        fetch('{{ route("admin.statistics.api.order-status") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('orderStatusChart'), {
                    type: 'pie',
                    data: {
                        labels: data.labels.map(l => capitalizeFirst(l)),
                        datasets: [{
                            data: data.data,
                            backgroundColor: [
                                '#f59e0b', '#60a5fa', '#a78bfa', '#22c55e', '#dc2626', '#6b7280'
                            ],
                        }]
                    },
                    options: chartOptions
                });
            });
    }

    // Croissance utilisateurs
    function loadUserGrowthChart() {
        fetch('{{ route("admin.statistics.api.user-growth") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('userGrowthChart'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Clients',
                                data: data.clients,
                                borderColor: colors.success,
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                            },
                            {
                                label: 'Vendeurs',
                                data: data.vendors,
                                borderColor: colors.info,
                                backgroundColor: 'rgba(96, 165, 250, 0.1)',
                                tension: 0.4,
                                fill: true,
                            }
                        ]
                    },
                    options: chartOptions
                });
            });
    }

    // Catégories populaires
    function loadCategoriesChart() {
        fetch('{{ route("admin.statistics.api.categories") }}')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('categoriesChart'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Produits vendus',
                            data: data.data,
                            backgroundColor: colors.primary,
                        }]
                    },
                    options: chartOptions
                });
            });
    }

    // Statistiques détaillées
    function loadDetailedStats() {
        const startDate = document.getElementById('startDate').value || new Date().toISOString().split('T')[0];
        const endDate = document.getElementById('endDate').value || new Date().toISOString().split('T')[0];

        fetch(`{{ route("admin.statistics.api.detailed-stats") }}?start_date=${startDate}&end_date=${endDate}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-revenue').textContent = data.totalRevenue.toLocaleString('fr-FR');
                document.getElementById('stat-orders').textContent = data.totalOrders;
                document.getElementById('stat-average').textContent = Math.round(data.totalOrders > 0 ? data.totalRevenue / data.totalOrders : 0).toLocaleString('fr-FR');
                document.getElementById('stat-rating').textContent = data.averageRating.toFixed(2);
            });
    }

    // Export
    function exportStatistics(format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const params = new URLSearchParams();

        if (format === 'csv') {
            params.append('type', 'daily');
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            window.location.href = `{{ route("admin.statistics.export.csv") }}?${params}`;
        } else if (format === 'pdf') {
            params.append('type', 'pdf');
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            fetch(`{{ route("admin.statistics.export.pdf") }}?${params}`)
                .then(r => r.json())
                .then(data => alert(data.message));
        }
    }

    function applyDateFilter() {
        loadDetailedStats();
    }

    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
    }

    // Charger au démarrage
    document.addEventListener('DOMContentLoaded', loadCharts);
</script>

<style>
    .horizontalBar {
        indexAxis: y;
    }
</style>
@endsection
