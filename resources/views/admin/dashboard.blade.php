@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="p-6 space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-8 h-8" /><span>Tableau de Bord</span></h1>
            <p class="text-gray-600 mt-1">Bienvenue sur la plateforme Supply</p>
        </div>
        <div class="flex gap-4">
            <!-- Mode Client Toggle -->
            <form method="POST" action="{{ route('admin.mode.client-enter') }}" class="inline">
                @csrf
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-semibold transition shadow-lg flex items-center gap-2">
                    <x-heroicon-o-eye class="w-5 h-5" /><span>Mode Visualisation Client</span>
                </button>
            </form>
        </div>
    </div>

    <!-- KPI Cards -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <x-heroicon-o-bolt class="w-6 h-6" />
            <span>Key Performance Indicators</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Chiffre d'affaires -->
            <div class="stat-card bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-green-600 text-xs font-bold uppercase tracking-wider">Chiffre d'Affaires</p>
                        <p class="text-3xl font-black text-green-700 mt-2">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-xs text-green-600 mt-1">Total FCFA</p>
                    </div>
                    <div class="flex-shrink-0">
                        <x-heroicon-o-banknotes class="w-8 h-8 text-green-600 opacity-30" />
                    </div>
                </div>
                <div class="pt-3 border-t border-green-200">
                    <p class="text-xs text-green-700"><span class="font-bold {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $revenueGrowth >= 0 ? '↗' : '↘' }} {{ abs($revenueGrowth) }}%</span> vs mois dernier</p>
                </div>
            </div>

            <!-- Commission -->
            <div class="stat-card bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-blue-600 text-xs font-bold uppercase tracking-wider">Commission</p>
                        <p class="text-3xl font-black text-blue-700 mt-2">{{ number_format($totalCommission ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ $commissionRate }}% collectés</p>
                    </div>
                    <div class="flex-shrink-0">
                        <x-heroicon-o-chart-pie class="w-8 h-8 text-blue-600 opacity-30" />
                    </div>
                </div>
                <div class="pt-3 border-t border-blue-200">
                    <p class="text-xs text-blue-700 font-semibold">Bénéfice net de la plateforme</p>
                </div>
            </div>

            <!-- Vendeurs -->
            <div class="stat-card bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-purple-600 text-xs font-bold uppercase tracking-wider">Vendeurs</p>
                        <p class="text-3xl font-black text-purple-700 mt-2">{{ $totalVendors }}</p>
                        <p class="text-xs text-purple-600 mt-1">Actifs</p>
                    </div>
                    <x-heroicon-o-user-group class="w-8 h-8 text-purple-600 opacity-30" />
                </div>
                <div class="pt-3 border-t border-purple-200">
                    <p class="text-xs text-purple-700"><span class="font-bold text-yellow-600">{{ $vendorsToApprove }}</span> en attente</p>
                </div>
            </div>

            <!-- Taux de Satisfaction -->
            <div class="stat-card bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-amber-600 text-xs font-bold uppercase tracking-wider">Satisfaction</p>
                        <p class="text-3xl font-black text-amber-700 mt-2">{{ $satisfactionRate }}%</p>
                        <p class="text-xs text-amber-600 mt-1">Sans litige</p>
                    </div>
                    <x-heroicon-o-face-smile class="w-8 h-8 text-amber-600 opacity-30" />
                </div>
                <div class="pt-3 border-t border-amber-200">
                    <p class="text-xs text-amber-700"><span class="font-bold">{{ $pendingDisputes }}</span> litiges ouverts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Commandes ce mois</p>
            <p class="text-2xl font-black text-gray-900 mt-2">{{ $thisMonthOrders }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $orderGrowth >= 0 ? '↗' : '↘' }} {{ abs($orderGrowth) }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Clients</p>
            <p class="text-2xl font-black text-gray-900 mt-2">{{ $totalClients }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $newClientsThisMonth }} nouveaux</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Produits</p>
            <p class="text-2xl font-black text-gray-900 mt-2">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-500 mt-1">Actifs</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Commandes</p>
            <p class="text-2xl font-black text-gray-900 mt-2">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-500 mt-1">Au total</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Utilisateurs</p>
            <p class="text-2xl font-black text-gray-900 mt-2">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 mt-1">Enregistrés</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border border-gray-100">
            <p class="text-gray-600 text-xs font-bold uppercase">Utilisateurs Bloqués</p>
            <p class="text-2xl font-black text-red-900 mt-2">{{ $bannedUsers }}</p>
            <p class="text-xs text-red-500 mt-1">Suspendus</p>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart (7 jours) -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">💹 Revenus (7 derniers jours)</h3>
            <div class="relative h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Orders Status Pie -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Distribution des Commandes par Statut</span></h3>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 30 Days Revenue -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-line class="w-5 h-5" /><span>Revenus (30 derniers jours)</span></h3>
            <div class="relative h-64">
                <canvas id="revenue30Chart"></canvas>
            </div>
        </div>

        <!-- Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-rocket-launch class="w-5 h-5" /><span>Croissance (7 jours)</span></h3>
            <div class="relative h-64">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Overview -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-6 h-6" /><span>Vue d'ensemble des Commandes</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border-2 border-yellow-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-700 text-sm font-bold uppercase"><x-heroicon-o-clock class="w-4 h-4 inline" /> Attente</p>
                        <p class="text-4xl font-black text-yellow-700 mt-2">{{ $ordersAwaitingConfirmation }}</p>
                    </div>
                    <div class="text-5xl opacity-20"><x-heroicon-o-clock class="w-12 h-12" /></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-300">
                <div class="flex items-center justify-between">
                    <div>
                <p class="text-blue-700 text-sm font-bold uppercase flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Confirmées</span></p>
                        <p class="text-4xl font-black text-blue-700 mt-2">{{ $ordersConfirmed }}</p>
                    </div>
                    <div class="text-5xl opacity-20"><x-heroicon-o-check-circle class="w-12 h-12" /></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border-2 border-indigo-300">
                <div class="flex items-center justify-between">
                    <div>
                <p class="text-indigo-700 text-sm font-bold uppercase flex items-center gap-1"><x-heroicon-o-envelope class="w-4 h-4" /><span>Expédiées</span></p>
                        <p class="text-4xl font-black text-indigo-700 mt-2">{{ $ordersShipped }}</p>
                    </div>
                    <div class="text-5xl opacity-20"><x-heroicon-o-envelope class="w-12 h-12" /></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-300">
                <div class="flex items-center justify-between">
                    <div>
                <p class="text-green-700 text-sm font-bold uppercase flex items-center gap-1"><x-heroicon-o-check class="w-4 h-4" /><span>Livrées</span></p>
                        <p class="text-4xl font-black text-green-700 mt-2">{{ $ordersDelivered }}</p>
                    </div>
                    <div class="text-5xl opacity-20"><x-heroicon-o-check class="w-12 h-12" /></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Vendors -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-star class="w-6 h-6 text-yellow-500" /><span>Top 5 Vendeurs</span></h3>
        <div class="space-y-3">
            @forelse($topVendors as $index => $vendor)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">{{ $index + 1 }}</div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-900">{{ $vendor->name }}</p>
                        <p class="text-sm text-gray-600">{{ $vendor->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-green-600 text-lg">{{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500">{{ $vendor->total_orders ?? 0 }} commandes</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">Aucun vendeur enregistré</div>
            @endforelse
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-8 border-b">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-clipboard class="w-6 h-6" /><span>Dernières Commandes</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">N° Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Statut</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->user->name ?? 'Inconnu' }}</td>
                            <td class="px-6 py-4 font-bold text-blue-600">#{{ $order->id }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">{{ number_format($order->total ?? 0, 0, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->statut === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->statut === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->statut === 'shipped') bg-indigo-100 text-indigo-800
                                    @elseif($order->statut === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-bold">Voir →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucune commande</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
            <div class="text-5xl mb-3"><x-heroicon-o-user-group class="w-12 h-12" /></div>
            <h4 class="font-bold text-gray-900 group-hover:text-blue-600">Gérer Utilisateurs</h4>
            <p class="text-sm text-gray-600">{{ $totalUsers }} utilisateurs</p>
        </a>
        <a href="{{ route('admin.products.index') }}" class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
            <div class="text-5xl mb-3"><x-heroicon-o-cube class="w-12 h-12" /></div>
            <h4 class="font-bold text-gray-900 group-hover:text-purple-600">Valider Produits</h4>
            <p class="text-sm text-gray-600">{{ $productsAwaitingValidation->count() }} en attente</p>
        </a>
        <a href="{{ route('admin.disputes.index') }}" class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition">
            <div class="text-5xl mb-3">⚖️</div>
            <h4 class="font-bold text-gray-900 group-hover:text-red-600">Litiges</h4>
            <p class="text-sm text-gray-600">{{ $pendingDisputes }} ouverts</p>
        </a>
    </div>

    <!-- Tip Box -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-xl p-6">
        <p class="flex items-start gap-3 text-gray-900">
            <span class="text-2xl">💡</span>
            <span><strong>Conseil :</strong> Maintenez une supervision régulière de la plateforme, validez rapidement les nouveaux vendeurs, et traitez les litiges pour assurer la satisfaction des clients et la croissance de Supply.</span>
        </p>
    </div>
</div>

<script>
    // Chart Colors
    const chartColors = {
        primary: '#3b82f6',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        purple: '#8b5cf6',
        cyan: '#06b6d4'
    };

    // 1. Revenue Chart (7 Days)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueDayLabels),
            datasets: [{
                label: 'Revenus (FCFA)',
                data: @json($revenueData),
                borderColor: chartColors.success,
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: chartColors.success,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, labels: { font: { weight: 'bold' } } } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } } }
        }
    });

    // 2. Status Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Attente', 'Confirmées', 'Expédiées', 'Livrées', 'Annulées'],
            datasets: [{
                data: [@json($ordersByStatus['pending'] ?? 0), @json($ordersByStatus['confirmed'] ?? 0), @json($ordersByStatus['shipped'] ?? 0), @json($ordersByStatus['delivered'] ?? 0), @json($ordersByStatus['cancelled'] ?? 0)],
                backgroundColor: ['#fbbf24', '#60a5fa', '#818cf8', '#34d399', '#f87171'],
                borderColor: '#fff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 12 } } } }
        }
    });

    // 3. 30 Days Revenue
    const revenue30Ctx = document.getElementById('revenue30Chart').getContext('2d');
    new Chart(revenue30Ctx, {
        type: 'bar',
        data: {
            labels: @json($revenueLast30DaysLabels),
            datasets: [{
                label: 'Revenus (FCFA)',
                data: @json($revenueLast30Days),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: chartColors.primary,
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });

    // 4. Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: @json($growthChartLabels),
            datasets: [
                {
                    label: 'Commandes',
                    data: @json($ordersData),
                    borderColor: chartColors.warning,
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    tension: 0.4
                },
                {
                    label: 'Vendeurs',
                    data: @json($vendorsData),
                    borderColor: chartColors.purple,
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true, labels: { font: { weight: 'bold' } } } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class=\"text-4xl font-bold text-gray-900 flex items-center gap-2\"><x-heroicon-o-chart-bar class=\"w-8 h-8\" /><span>Tableau de Bord</span></h1>
            <p class=\"text-gray-600 mt-1\">Bienvenue sur la plateforme Supply</p>
        </div>
        <div class=\"flex gap-4\">
            <!-- Mode Client Toggle -->
            <form method=\"POST\" action=\"{{ route('admin.mode.client-enter') }}\" class=\"inline\">
                @csrf
                <button type=\"submit\" class=\"px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-semibold transition shadow-lg flex items-center gap-2\">
                    <x-heroicon-o-eye class=\"w-5 h-5\" /><span>Mode Visualisation Client</span>
                </button>
            </form>
        </div>
    </div>

    <!-- KPI Cards -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistiques Clés</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Chiffre d'affaires -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Chiffre d'affaires</p>
                        <p class="text-3xl font-bold text-green-600 mt-3">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-gray-500 text-xs mt-1">FCFA Commission</p>
                    </div>
                    <div class="opacity-20 text-green-600"><x-heroicon-o-banknotes class="w-8 h-8" /></div>
                </div>
            </div>

            <!-- Commission Rate -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Taux Commission</p>
                        <p class="text-3xl font-bold text-blue-600 mt-3">{{ $commissionRate ?? 10 }}%</p>
                        <p class="text-gray-500 text-xs mt-1">Sur chaque vente</p>
                    </div>
                    <div class="opacity-20 text-blue-600"><x-heroicon-o-chart-bar class="w-8 h-8" /></div>
                </div>
            </div>

            <!-- Vendors Pending -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Vendeurs à Valider</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-3">{{ $vendorsToApprove ?? 0 }}</p>
                        <p class="text-gray-500 text-xs mt-1">En attente de validation</p>
                    </div>
                    <div class="opacity-20 text-yellow-600"><x-heroicon-o-clipboard class="w-8 h-8" /></div>
                </div>
            </div>

            <!-- Products Pending -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Produits à Valider</p>
                        <p class="text-3xl font-bold text-purple-600 mt-3">{{ $productsAwaitingValidation->count() ?? 0 }}</p>
                        <p class="text-gray-500 text-xs mt-1">En attente de vérification</p>
                    </div>
                    <div class="opacity-20 text-purple-600"><x-heroicon-o-cube class="w-8 h-8" /></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Status -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statut des Commandes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- En attente -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl"><x-heroicon-o-clock class="w-6 h-6" /></span>
                    <span class="text-xs font-bold text-yellow-700 uppercase">En attente</span>
                </div>
                <p class="text-4xl font-bold text-yellow-600">{{ $ordersAwaitingConfirmation ?? 0 }}</p>
                <p class="text-sm text-yellow-700 mt-2">À traiter rapidement</p>
            </div>

            <!-- Confirmées -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl"><x-heroicon-o-check-circle class="w-6 h-6" /></span>
                    <span class="text-xs font-bold text-blue-700 uppercase">Confirmées</span>
                </div>
                <p class="text-4xl font-bold text-blue-600">{{ $ordersConfirmed ?? 0 }}</p>
                <p class="text-sm text-blue-700 mt-2">Prêtes à expédier</p>
            </div>

            <!-- Expédiées -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl"><x-heroicon-o-envelope class="w-6 h-6" /></span>
                    <span class="text-xs font-bold text-indigo-700 uppercase">Expédiées</span>
                </div>
                <p class="text-4xl font-bold text-indigo-600">{{ $ordersShipped ?? 0 }}</p>
                <p class="text-sm text-indigo-700 mt-2">En route vers clients</p>
            </div>

            <!-- Livrées -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl"><x-heroicon-o-check class="w-6 h-6" /></span>
                    <span class="text-xs font-bold text-green-700 uppercase">Livrées</span>
                </div>
                <p class="text-4xl font-bold text-green-600">{{ $ordersDelivered ?? 0 }}</p>
                <p class="text-sm text-green-700 mt-2">Commandes réussies</p>
            </div>
        </div>
    </div>

    <!-- Top Vendors -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-star class="w-6 h-6 text-yellow-500" /><span>Top 5 Vendeurs par Chiffre d'Affaires</span></h2>
        @if($topVendors && $topVendors->count() > 0)
            <div class="space-y-3">
                @foreach($topVendors as $index => $vendor)
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $vendor->name }}</p>
                            <p class="text-sm text-gray-600">{{ $vendor->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-lg">{{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }} FCFA</p>
                            <p class="text-xs text-gray-500">Commission perçue</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Pas de vendeurs enregistrés</p>
            </div>
        @endif
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-fire class="w-6 h-6 text-red-500" /><span>Top 5 Produits en Validation</span></h2>
        @if($topProducts && $topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $product->nom }}</p>
                            <p class="text-sm text-gray-600">Soumis par {{ $product->vendeur->name ?? 'Inconnu' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 flex items-center gap-1"><x-heroicon-o-clock class="w-3 h-3" /><span>En attente</span></span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Tous les produits sont validés</p>
            </div>
        @endif
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-clipboard class="w-6 h-6" /><span>Dernières Commandes</span></h2>

        @if($recentOrders && $recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Client</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">N° Commande</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">{{ $order->user->name ?? 'Inconnu' }}</span>
                                </td>
                                <td class="py-4 px-4 font-bold text-blue-600">#{{ $order->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $order->vendeur->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 font-semibold text-green-600">{{ number_format($order->total ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($order->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($order->statut === 'confirmee') bg-blue-100 text-blue-800
                                        @elseif($order->statut === 'expediee') bg-indigo-100 text-indigo-800
                                        @elseif($order->statut === 'livree') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-bold">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucune commande enregistrée</p>
            </div>
        @endif
    </div>

    <!-- Recent Disputes -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-500" /><span>Litiges Récents</span></h2>

        @if($openDisputes && $openDisputes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Titre</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Client</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($openDisputes as $dispute)
                            <tr class="border-b hover:bg-red-50 transition">
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">{{ $dispute->titre }}</span>
                                </td>
                                <td class="py-4 px-4 text-gray-700">{{ $dispute->user->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $dispute->vendeur->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($dispute->statut === 'ouvert') bg-red-100 text-red-800
                                        @elseif($dispute->statut === 'en_cours') bg-yellow-100 text-yellow-800
                                        @elseif($dispute->statut === 'resolu') bg-green-100 text-green-800
                                        @elseif($dispute->statut === 'ferme') bg-gray-100 text-gray-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $dispute->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="text-red-600 hover:text-red-700 font-bold">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucun litige actif</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-bolt class="w-6 h-6 text-yellow-500" /><span>Actions Rapides</span></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl"><x-heroicon-o-user-group class="w-8 h-8" /></div>
                <div>
                    <p class="font-bold text-gray-900">Gérer Utilisateurs</p>
                    <p class="text-sm text-gray-600">Voir tous les utilisateurs</p>
                </div>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl"><x-heroicon-o-cube class="w-8 h-8" /></div>
                <div>
                    <p class="font-bold text-gray-900">Valider Produits</p>
                    <p class="text-sm text-gray-600">Produits en attente</p>
                </div>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl"><x-heroicon-o-clipboard class="w-8 h-8" /></div>
                <div>
                    <p class="font-bold text-gray-900">Voir Commandes</p>
                    <p class="text-sm text-gray-600">Gérer les commandes</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Conseil -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <p class="flex items-start gap-3">
            <span class="text-2xl">💡</span>
            <span class="text-blue-900"><strong>Conseil du Jour :</strong> Validez régulièrement les nouveaux vendeurs et produits, gérez les litiges rapidement et maintenez une plateforme saine. C'est la clé du succès de Supply !</span>
        </p>
    </div>
</div>
@endsection
