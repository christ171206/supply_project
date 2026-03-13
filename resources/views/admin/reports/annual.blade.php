@extends('layouts.admin-layout')

@section('title', 'Rapport Annuel - ' . $year)

@section('content')
<div class="p-6 space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-[#0a0a0a] flex items-center gap-2">
                <span>📊 Rapport Annuel {{ $year }}</span>
            </h1>
            <p class="text-[#a0a09a] mt-2">Vue d'ensemble complète des performances annuelles</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url()->current() }}?year={{ $year - 1 }}" class="px-4 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg hover:bg-[#f7f7f5] transition">
                ← {{ $year - 1 }}
            </a>
            @if($year < now()->year)
                <a href="{{ url()->current() }}?year={{ $year + 1 }}" class="px-4 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg hover:bg-[#f7f7f5] transition">
                    {{ $year + 1 }} →
                </a>
            @endif
        </div>
    </div>

    {{-- KPIs Principaux --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 bg-gradient-to-br from-[#f7f7f5] to-white rounded-lg p-6 border border-[#e0e0dc]">
        <div class="space-y-1">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Chiffre d'Affaires</p>
            <p class="text-3xl font-mono font-bold text-[#0a0a0a]">{{ number_format($totalAnnualRevenue, 0, ',', ' ') }} CFA</p>
            <p class="text-[12px] {{ $yearOverYearGrowth >= 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }} mt-1">
                {{ $yearOverYearGrowth >= 0 ? '+' : '' }}{{ $yearOverYearGrowth }}% vs {{ $year - 1 }}
            </p>
        </div>

        <div class="space-y-1">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Commandes</p>
            <p class="text-3xl font-mono font-bold text-[#0a0a0a]">{{ number_format($totalAnnualOrders, 0, ',', ' ') }}</p>
            <p class="text-[12px] text-[#a0a09a] mt-1">Panier moyen: {{ number_format($averageOrderValue, 0, ',', ' ') }} CFA</p>
        </div>

        <div class="space-y-1">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Moyenne Mensuelle</p>
            <p class="text-3xl font-mono font-bold text-[#0a0a0a]">{{ number_format($averageMonthlyRevenue, 0, ',', ' ') }} CFA</p>
            <p class="text-[12px] text-[#a0a09a] mt-1">Revenu par mois</p>
        </div>

        <div class="space-y-1">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Produits Vendus</p>
            <p class="text-3xl font-mono font-bold text-[#0a0a0a]">{{ number_format($totalProductsSold, 0, ',', ' ') }}</p>
            <p class="text-[12px] text-[#a0a09a] mt-1">Unités totales</p>
        </div>
    </div>

    {{-- Utilisateurs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-3">👥 Nouveaux Clients</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ number_format($newUsersCount, 0, ',', ' ') }}</p>
            <p class="text-[12px] text-[#a0a09a] mt-2">Inscrits en {{ $year }}</p>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-3">🏪 Nouveaux Vendeurs</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ number_format($newVendorsCount, 0, ',', ' ') }}</p>
            <p class="text-[12px] text-[#a0a09a] mt-2">Inscrits en {{ $year }}</p>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-3">📈 Croissance YoY</p>
            <p class="text-2xl font-mono font-bold {{ $yearOverYearGrowth >= 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                {{ $yearOverYearGrowth >= 0 ? '+' : '' }}{{ $yearOverYearGrowth }}%
            </p>
            <p class="text-[12px] text-[#a0a09a] mt-2">vs {{ $year - 1 }}</p>
        </div>
    </div>

    {{-- Graphique Évolution Annuelle --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
        <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-6">📊 Évolution du Chiffre d'Affaires - {{ $year }}</h3>
        <div class="h-96">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Graphique Croissance vs Année Précédente --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-6">📈 Croissance Mensuelle YoY</h3>
            <div class="h-80">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-6">📦 Commandes par Mois</h3>
            <div class="h-80">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Vendeurs --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
        <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-4">🏆 Top 10 Vendeurs</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-[#0a0a0a]">Rang</th>
                        <th class="px-4 py-3 text-left font-medium text-[#0a0a0a]">Boutique</th>
                        <th class="px-4 py-3 text-right font-medium text-[#0a0a0a]">Commandes</th>
                        <th class="px-4 py-3 text-right font-medium text-[#0a0a0a]">Chiffre d'Affaires</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($topVendors as $idx => $vendor)
                        <tr class="hover:bg-[#f7f7f5] transition">
                            <td class="px-4 py-3 font-mono font-bold text-[#0a0a0a]">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-[#0a0a0a]">{{ $vendor->shop_name }}</div>
                                <div class="text-[11px] text-[#a0a09a]">{{ $vendor->name }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#0a0a0a]">{{ $vendor->total_orders ?? 0 }}</td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-[#0a0a0a]">{{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }} CFA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-[#a0a09a]">Aucun vendeur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Produits --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
        <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-4">⭐ Top 10 Produits</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-[#0a0a0a]">Rang</th>
                        <th class="px-4 py-3 text-left font-medium text-[#0a0a0a]">Produit</th>
                        <th class="px-4 py-3 text-right font-medium text-[#0a0a0a]">Unités</th>
                        <th class="px-4 py-3 text-right font-medium text-[#0a0a0a]">Ventes</th>
                        <th class="px-4 py-3 text-right font-medium text-[#0a0a0a]">Chiffre d'Affaires</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($topProducts as $idx => $product)
                        <tr class="hover:bg-[#f7f7f5] transition">
                            <td class="px-4 py-3 font-mono font-bold text-[#0a0a0a]">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-[#0a0a0a] truncate">{{ $product->nom }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#0a0a0a]">{{ $product->total_quantity ?? 0 }}</td>
                            <td class="px-4 py-3 text-right font-mono text-[#0a0a0a]">{{ $product->times_sold ?? 0 }}</td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-[#0a0a0a]">{{ number_format($product->total_revenue ?? 0, 0, ',', ' ') }} CFA</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-[#a0a09a]">Aucun produit</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Scripts Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    const colors = {
        primary: '#0a0a0a',
        secondary: '#e0e0dc',
        success: '#15803d',
        warning: '#f59e0b',
        danger: '#dc2626',
        info: '#3b82f6'
    };

    // 1. Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Chiffre d\'Affaires (CFA)',
                data: {!! json_encode($monthlyRevenue) !!},
                borderColor: colors.primary,
                backgroundColor: 'rgba(10,10,10,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.45,
                pointRadius: 4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    grid: { color: '#efefed' },
                    ticks: {
                        callback: v => (v/1000).toFixed(0)+'k',
                        color: '#a0a09a',
                        font: { family: 'Geist Mono', size: 11 }
                    }
                },
                x: { grid: { display: false }, ticks: { color: '#a0a09a' } }
            }
        }
    });

    // 2. Growth Chart
    new Chart(document.getElementById('growthChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Croissance YoY (%)',
                data: {!! json_encode($monthlyGrowth) !!},
                backgroundColor: {!! json_encode(array_map(fn($g) => $g >= 0 ? colors.success : colors.danger, $monthlyGrowth)) !!},
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'x',
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: v => v+'%', color: '#a0a09a' },
                    grid: { color: '#efefed' }
                },
                x: { grid: { display: false }, ticks: { color: '#a0a09a' } }
            }
        }
    });

    // 3. Orders Chart
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Nombre de Commandes',
                data: {!! json_encode($monthlyOrders) !!},
                backgroundColor: colors.info,
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { color: '#a0a09a' }, grid: { color: '#efefed' } },
                x: { grid: { display: false }, ticks: { color: '#a0a09a' } }
            }
        }
    });
</script>
@endsection
