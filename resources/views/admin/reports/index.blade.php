@extends('layouts.admin-layout')

@section('title', 'Rapports — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; <a href="{{ route('admin.reports.index') }}">Rapports</a>
@endsection

@section('content')
<div class="pb-16">
    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Supply</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">Rapports et Ventes</h1>
        <p class="text-[13px] text-white/40 font-light mt-2">Rapports détaillés et exportables</p>
    </div>

    <div class="px-8 space-y-8">
        {{-- FILTRES --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-4">Filtrer les rapports</h2>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[12px] text-[#a0a09a] font-medium mb-2 block">Date de début</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-[#e0e0dc] rounded">
                </div>
                <div>
                    <label class="text-[12px] text-[#a0a09a] font-medium mb-2 block">Date de fin</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-[#e0e0dc] rounded">
                </div>
                <div>
                    <label class="text-[12px] text-[#a0a09a] font-medium mb-2 block">&nbsp;</label>
                    <button type="submit" class="w-full px-4 py-2 bg-[#0a0a0a] text-white rounded hover:bg-[#2a2a28]">
                        Appliquer
                    </button>
                </div>
                <div>
                    <label class="text-[12px] text-[#a0a09a] font-medium mb-2 block">&nbsp;</label>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports.export.csv', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="flex-1 px-3 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded hover:bg-[#f7f7f5] text-sm text-center">
                            📥 CSV
                        </a>
                        <a href="{{ route('admin.reports.export.pdf', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="flex-1 px-3 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded hover:bg-[#f7f7f5] text-sm text-center">
                            📄 PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- KPIs --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Chiffre d'affaires</div>
                <div class="font-mono text-[28px] font-medium text-[#0a0a0a]">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1">FCFA</div>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Commandes</div>
                <div class="font-mono text-[28px] font-medium text-[#0a0a0a]">{{ $totalOrders }}</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1">Livraisons réussies</div>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-3">Panier moyen</div>
                <div class="font-mono text-[28px] font-medium text-[#0a0a0a]">{{ number_format($averageOrderValue, 0, ',', ' ') }}</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-1">FCFA</div>
            </div>
        </div>

        {{-- TOP PRODUITS --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[16px] font-serif text-[#0a0a0a]">Top 10 Produits</h2>
                <a href="{{ route('admin.reports.export.csv', ['type' => 'products', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="text-[12px] text-blue-600 hover:text-blue-800">
                    Exporter →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-[#e0e0dc]">
                            <th class="text-left py-3 px-4 text-[#a0a09a] font-medium">#</th>
                            <th class="text-left py-3 px-4 text-[#a0a09a] font-medium">Produit</th>
                            <th class="text-right py-3 px-4 text-[#a0a09a] font-medium">Quantités</th>
                            <th class="text-right py-3 px-4 text-[#a0a09a] font-medium">Chiffre d'affaires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $i => $product)
                            <tr class="border-b border-[#e0e0dc] hover:bg-[#f7f7f5]">
                                <td class="py-3 px-4 font-mono text-[#a0a09a]">{{ $i + 1 }}</td>
                                <td class="py-3 px-4 text-[#0a0a0a]">{{ $product->nom }}</td>
                                <td class="py-3 px-4 text-right font-mono text-[#0a0a0a]">{{ $product->sold }}</td>
                                <td class="py-3 px-4 text-right font-mono text-[#0a0a0a]">{{ number_format($product->revenue, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOP VENDEURS --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[16px] font-serif text-[#0a0a0a]">Top 10 Vendeurs</h2>
                <a href="{{ route('admin.reports.export.csv', ['type' => 'vendors', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="text-[12px] text-blue-600 hover:text-blue-800">
                    Exporter →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-[#e0e0dc]">
                            <th class="text-left py-3 px-4 text-[#a0a09a] font-medium">#</th>
                            <th class="text-left py-3 px-4 text-[#a0a09a] font-medium">Vendeur</th>
                            <th class="text-center py-3 px-4 text-[#a0a09a] font-medium">Commandes</th>
                            <th class="text-right py-3 px-4 text-[#a0a09a] font-medium">Chiffre d'affaires</th>
                            <th class="text-center py-3 px-4 text-[#a0a09a] font-medium">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topVendors as $i => $vendor)
                            <tr class="border-b border-[#e0e0dc] hover:bg-[#f7f7f5]">
                                <td class="py-3 px-4 font-mono text-[#a0a09a]">{{ $i + 1 }}</td>
                                <td class="py-3 px-4 text-[#0a0a0a]">{{ $vendor->shop_name }}</td>
                                <td class="py-3 px-4 text-center font-mono text-[#0a0a0a]">{{ $vendor->total_orders ?? 0 }}</td>
                                <td class="py-3 px-4 text-right font-mono text-[#0a0a0a]">{{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-[11px] font-medium">
                                        ⭐ {{ round($vendor->avg_rating ?? 0, 1) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- GRAPHIQUE CHIFFRE AFFAIRES --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="text-[16px] font-serif text-[#0a0a0a] mb-4">Évolution du Chiffre d'Affaires</h2>
            <canvas id="dailyRevenueChart" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Graphique du chiffre d'affaires quotidien
    const dates = @json($dailyRevenue->pluck('date'));
    const revenues = @json($dailyRevenue->pluck('revenue'));

    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Chiffre d\'affaires',
                data: revenues,
                borderColor: '#0a0a0a',
                backgroundColor: 'rgba(10, 10, 10, 0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    ticks: { callback: function(v) { return v.toLocaleString('fr-FR'); } }
                }
            }
        }
    });
</script>
@endsection
