@extends('layouts.admin-layout')

@section('title', 'Statistiques — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; <a href="{{ route('admin.statistics.index') }}">Statistiques</a>
@endsection

@section('content')
<div class="pb-16">
    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Supply</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Statistiques</h1>
        <p class="text-[13px] text-white/40 font-light mt-2">Analyse complète de la plateforme avec graphiques interactifs</p>

        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            @php
                $kpis = [
                    ['value' => number_format($totalRevenue ?? 0, 0, ',', ' '), 'unit' => 'FCFA', 'label' => "Chiffre d'affaires total"],
                    ['value' => $totalOrders,  'unit' => '', 'label' => 'Commandes totales'],
                    ['value' => $totalClients, 'unit' => '', 'label' => 'Clients'],
                    ['value' => $totalVendors, 'unit' => '', 'label' => 'Vendeurs'],
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

    {{-- FILTRES ET EXPORTS --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Période</div>
            <div class="flex gap-2">
                <input type="date" id="startDate"
                       class="px-3 py-2 border border-[#e0e0dc] rounded-lg bg-white text-[12px] text-[#0a0a0a]
                              focus:border-[#0a0a0a] outline-none transition-all">
                <input type="date" id="endDate"
                       class="px-3 py-2 border border-[#e0e0dc] rounded-lg bg-white text-[12px] text-[#0a0a0a]
                              focus:border-[#0a0a0a] outline-none transition-all">
                <button onclick="applyDateFilter()"
                        class="px-4 py-2 bg-[#0a0a0a] text-white text-[12px] font-medium rounded-lg hover:opacity-85 transition-opacity">
                    Appliquer
                </button>
            </div>
        </div>

        <div class="flex gap-2">
            <button onclick="exportStatistics('csv')"
                    class="flex items-center gap-1.5 px-4 py-2 border border-[#e0e0dc] text-[#0a0a0a] text-[12px] font-medium
                           rounded-lg hover:bg-[#f7f7f5] transition-colors">
                <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Exporter CSV
            </button>
            <button onclick="exportStatistics('pdf')"
                    class="flex items-center gap-1.5 px-4 py-2 bg-[#0a0a0a] text-white text-[12px] font-medium
                           rounded-lg hover:opacity-85 transition-opacity">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Exporter PDF
            </button>
        </div>
    </div>

    {{-- GRAPHIQUES --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Revenu quotidien --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Revenu quotidien</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Derniers 30 jours</div>
            </div>
            <canvas id="dailyRevenueChart" height="180"></canvas>
        </div>

        {{-- Croissance mensuelle --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Croissance mensuelle</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Derniers 12 mois</div>
            </div>
            <canvas id="monthlyGrowthChart" height="180"></canvas>
        </div>

        {{-- Top 10 vendeurs --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Top 10 vendeurs</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Par chiffre d'affaires</div>
            </div>
            <canvas id="topVendorsChart" height="180"></canvas>
        </div>

        {{-- Statut des commandes --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Statut des commandes</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Distribution actuelle</div>
            </div>
            <canvas id="orderStatusChart" height="180"></canvas>
        </div>

        {{-- Croissance utilisateurs --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Croissance utilisateurs</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Clients vs Vendeurs</div>
            </div>
            <canvas id="userGrowthChart" height="180"></canvas>
        </div>

        {{-- Catégories populaires --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Catégories populaires</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Top 8 catégories</div>
            </div>
            <canvas id="categoriesChart" height="180"></canvas>
        </div>

        {{-- Produits populaires — pleine largeur --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 lg:col-span-2">
            <div class="mb-4">
                <div class="text-[14px] font-serif text-[#0a0a0a] leading-none">Produits populaires</div>
                <div class="text-[11px] text-[#a0a09a] mt-1">Top 10 produits vendus</div>
            </div>
            <canvas id="topProductsChart" height="120"></canvas>

            <div class="mt-5 border-t border-[#efefed] pt-4">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Détails</div>
                <div id="productsList" class="space-y-1.5 max-h-64 overflow-y-auto">
                    <div class="text-[12px] text-[#a0a09a] py-6 text-center">Chargement…</div>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIQUES DÉTAILLÉES --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">
            Statistiques détaillées
            <span id="statsDateRange" class="text-[10px] text-[#666660] ml-2 normal-case tracking-normal">(Chargement…)</span>
        </div>
        <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden md:grid-cols-4">
            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Revenu</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-revenue">–</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">FCFA</div>
            </div>
            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Commandes</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-orders">–</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">total</div>
            </div>
            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Panier moyen</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-average">–</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">FCFA</div>
            </div>
            <div class="bg-white px-4 py-5 hover:bg-[#f7f7f5] transition-colors cursor-default">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Note moyenne</div>
                <div class="font-mono text-[24px] font-medium leading-none text-[#0a0a0a]" id="stat-rating">–</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1.5">/ 5</div>
            </div>
        </div>
    </div>

    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
const colors = {
    primary:   '#0a0a0a',
    secondary: '#a0a09a',
    success:   '#22c55e',
    warning:   '#f59e0b',
    danger:    '#dc2626',
    info:      '#60a5fa',
    purple:    '#a78bfa',
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true,
            labels: {
                font: { family: 'Geist, sans-serif', size: 11 },
                color: colors.primary,
                padding: 12,
                boxWidth: 10,
                boxHeight: 10,
            }
        }
    },
    scales: {
        x: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 10 }, color: colors.secondary } },
        y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 10 }, color: colors.secondary } }
    }
};

function initializeDateFilters() {
    const today    = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay  = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    const fmt = (d) => d.toISOString().split('T')[0];
    document.getElementById('startDate').value = fmt(firstDay);
    document.getElementById('endDate').value   = fmt(lastDay);
}

function loadCharts() {
    initializeDateFilters();
    loadDailyRevenueChart();
    loadMonthlyGrowthChart();
    loadTopVendorsChart();
    loadTopProductsChart();
    loadOrderStatusChart();
    loadUserGrowthChart();
    loadCategoriesChart();
    loadDetailedStats();
}

function loadDailyRevenueChart() {
    fetch('{{ route("admin.statistics.api.daily-revenue") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('dailyRevenueChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{ label: 'Revenu (FCFA)', data: data.revenue,
                    borderColor: colors.primary, backgroundColor: 'rgba(10,10,10,0.06)',
                    tension: 0.4, fill: true, pointRadius: 2 }]
            },
            options: chartOptions
        }));
}

function loadMonthlyGrowthChart() {
    fetch('{{ route("admin.statistics.api.monthly-growth") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('monthlyGrowthChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{ label: "Chiffre d'affaires", data: data.revenue,
                    backgroundColor: colors.primary, borderRadius: 3 }]
            },
            options: chartOptions
        }));
}

function loadTopVendorsChart() {
    fetch('{{ route("admin.statistics.api.top-vendors") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('topVendorsChart'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{ data: data.data, backgroundColor: [
                    colors.primary, colors.info, colors.warning,
                    colors.purple, colors.success, colors.secondary,
                    '#f0ad4e', '#5bc0de', '#d9534f', '#5cb85c'
                ]}]
            },
            options: { ...chartOptions, plugins: { ...chartOptions.plugins,
                legend: { ...chartOptions.plugins.legend, position: 'right' } } }
        }));
}

function loadTopProductsChart() {
    fetch('{{ route("admin.statistics.api.top-products") }}')
        .then(r => { if (!r.ok) throw new Error('Erreur'); return r.json(); })
        .then(data => {
            if (!data.labels || !data.data) return;

            const productsList = data.labels.map((name, idx) => ({
                nom: name, vendus: data.data[idx] || 0,
                revenu: data.revenue ? data.revenue[idx] || 0 : 0
            }));

            const listHtml = productsList
                .filter(p => p.vendus > 0 || p.revenu > 0)
                .map((p, idx) => `
                    <div class="flex items-center justify-between px-3 py-2.5 bg-[#f7f7f5] rounded-lg border border-[#e0e0dc] hover:border-[#a0a09a] transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="text-[12px] font-medium text-[#0a0a0a] truncate">${idx + 1}. ${p.nom}</div>
                            <div class="text-[10px] text-[#a0a09a] mt-0.5 font-mono">
                                ${p.vendus} vente${p.vendus > 1 ? 's' : ''}
                                ${p.revenu > 0 ? ' · ' + Number(p.revenu).toLocaleString('fr-FR', {maximumFractionDigits: 0}) + ' FCFA' : ''}
                            </div>
                        </div>
                        <div class="font-mono text-[12px] font-medium text-[#0a0a0a] ml-4 flex-shrink-0">${p.vendus}</div>
                    </div>
                `).join('');

            document.getElementById('productsList').innerHTML =
                listHtml || '<div class="text-[12px] text-[#a0a09a] py-6 text-center">Aucun produit vendu</div>';

            const ctx = document.getElementById('topProductsChart');
            if (ctx) new Chart(ctx, {
                type: 'bar',
                data: { labels: data.labels, datasets: [{
                    label: 'Quantités vendues', data: data.data,
                    backgroundColor: colors.primary, borderRadius: 3,
                }]},
                options: { ...chartOptions, indexAxis: 'y' }
            });
        })
        .catch(() => {
            document.getElementById('productsList').innerHTML =
                '<div class="text-[12px] text-[#dc2626] py-4 text-center">Erreur de chargement</div>';
        });
}

function loadOrderStatusChart() {
    fetch('{{ route("admin.statistics.api.order-status") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('orderStatusChart'), {
            type: 'pie',
            data: {
                labels: data.labels.map(l => capitalizeFirst(l)),
                datasets: [{ data: data.data, backgroundColor: [
                    '#f59e0b', '#60a5fa', '#a78bfa', '#22c55e', '#dc2626', '#6b7280'
                ]}]
            },
            options: chartOptions
        }));
}

function loadUserGrowthChart() {
    fetch('{{ route("admin.statistics.api.user-growth") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('userGrowthChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    { label: 'Clients', data: data.clients,
                      borderColor: colors.success, backgroundColor: 'rgba(34,197,94,0.08)',
                      tension: 0.4, fill: true, pointRadius: 2 },
                    { label: 'Vendeurs', data: data.vendors,
                      borderColor: colors.info, backgroundColor: 'rgba(96,165,250,0.08)',
                      tension: 0.4, fill: true, pointRadius: 2 }
                ]
            },
            options: chartOptions
        }));
}

function loadCategoriesChart() {
    fetch('{{ route("admin.statistics.api.categories") }}')
        .then(r => r.json())
        .then(data => new Chart(document.getElementById('categoriesChart'), {
            type: 'bar',
            data: { labels: data.labels, datasets: [{
                label: 'Produits vendus', data: data.data,
                backgroundColor: colors.primary, borderRadius: 3,
            }]},
            options: chartOptions
        }));
}

function loadDetailedStats() {
    let start = document.getElementById('startDate').value;
    let end   = document.getElementById('endDate').value;

    if (!start || !end) {
        const today = new Date();
        const fmt = (d) => d.toISOString().split('T')[0];
        start = fmt(new Date(today.getFullYear(), today.getMonth(), 1));
        end   = fmt(new Date(today.getFullYear(), today.getMonth() + 1, 0));
    }

    const fmtDisplay = (s) => new Date(s + 'T00:00:00').toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
    const endDisplay = new Date(end + 'T00:00:00').toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
    document.getElementById('statsDateRange').textContent = `(${fmtDisplay(start)} au ${endDisplay})`;

    fetch(`{{ route("admin.statistics.api.detailed-stats") }}?start_date=${start}&end_date=${end}`)
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            const fmt = (n) => n ? Math.round(n).toLocaleString('fr-FR') : '0';
            const rev    = data.totalRevenue || 0;
            const orders = data.totalOrders  || 0;
            document.getElementById('stat-revenue').textContent = fmt(rev);
            document.getElementById('stat-orders').textContent  = orders;
            document.getElementById('stat-average').textContent = fmt(orders > 0 ? rev / orders : 0);
            document.getElementById('stat-rating').textContent  = data.averageRating
                ? parseFloat(data.averageRating).toFixed(2) : '–';
        })
        .catch(() => {
            ['stat-revenue','stat-orders','stat-average','stat-rating']
                .forEach(id => document.getElementById(id).textContent = '–');
        });
}

function exportStatistics(format) {
    const start = document.getElementById('startDate').value;
    const end   = document.getElementById('endDate').value;
    const params = new URLSearchParams({ type: format });
    if (start) params.append('start_date', start);
    if (end)   params.append('end_date', end);

    if (format === 'csv') {
        params.set('type', 'daily');
        window.location.href = `{{ route("admin.statistics.export.csv") }}?${params}`;
    } else {
        fetch(`{{ route("admin.statistics.export.pdf") }}?${params}`)
            .then(r => r.json())
            .then(data => alert(data.message));
    }
}

function applyDateFilter() { loadDetailedStats(); }

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
}

document.addEventListener('DOMContentLoaded', loadCharts);
</script>
@endsection

@endsection