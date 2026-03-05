@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-banknotes class="w-8 h-8" /><span>Rapport Financier</span></h1>
        <p class="text-gray-600 mt-2">Analyse complète des revenus et transactions</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Début</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Fin</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Revenu Total</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ number_format($totalRevenue, 0, ',', ' ') }} XOF
                    </p>
                </div>
                <div class="text-4xl">💵</div>
            </div>
        </div>

        <!-- Orders Count -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Commandes Livrées</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $orderCount }}</p>
                </div>
                <div class="text-4xl"><x-heroicon-o-cube class="w-10 h-10" /></div>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Valeur Moyenne</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">
                        {{ number_format($averageOrderValue, 0, ',', ' ') }} XOF
                    </p>
                </div>
                <div class="text-4xl"><x-heroicon-o-chart-bar class="w-10 h-10" /></div>
            </div>
        </div>

        <!-- Commission (estimée) -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Commission (10%)</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">
                        {{ number_format($totalRevenue * 0.1, 0, ',', ' ') }} XOF
                    </p>
                </div>
                <div class="text-4xl">🎯</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-line class="w-5 h-5" /><span>Revenu Journalier</span></h2>
            
            @if($dailyRevenue->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    Aucune donnée disponible
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Commandes</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Revenu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyRevenue as $day)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                                    <td class="text-right py-3 px-4">{{ $day->orders }}</td>
                                    <td class="text-right py-3 px-4 font-semibold text-green-600">
                                        {{ number_format($day->revenue, 0, ',', ' ') }} XOF
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Top Vendors -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-star class="w-5 h-5" /><span>Top Vendeurs</span></h2>
            
            @if($vendorRevenue->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    ❌ Aucun vendeur actif
                </div>
            @else
                <div class="space-y-4">
                    @foreach($vendorRevenue->take(10) as $vendor)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $vendor->shop_name ?? $vendor->name }}</p>
                                <p class="text-xs text-gray-500">{{ $vendor->orders }} commande(s)</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">
                                    {{ number_format($vendor->revenue, 0, ',', ' ') }} XOF
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-6 flex justify-end gap-3">
        <form method="POST" action="{{ route('admin.reports.export') }}" class="inline">
            @csrf
            <input type="hidden" name="report_type" value="financial">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                📥 Exporter en CSV
            </button>
        </form>
    </div>
</div>
@endsection
