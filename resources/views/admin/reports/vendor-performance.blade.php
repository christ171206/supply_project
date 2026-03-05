@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-star class="w-8 h-8" /><span>Performance des Vendeurs</span></h1>
        <p class="text-gray-600 mt-2">Analyse détaillée de la performance de chaque vendeur</p>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($vendors->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Aucun vendeur trouvé</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Boutique</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Commandes</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Livrées</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Annulées</th>
                            <th class="text-right py-4 px-4 font-semibold text-gray-700">Revenu</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $vendor->shop_name ?? $vendor->name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $vendor->id }}</p>
                                    </div>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $vendor->total_orders ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        {{ $vendor->delivered_orders ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                        {{ $vendor->cancelled_orders ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-right py-4 px-4 font-bold text-green-600">
                                    {{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }} XOF
                                </td>
                                <td class="text-center py-4 px-4">
                                    {{-- Score basé sur taux de livraison --}}
                                    @php
                                        $score = ($vendor->total_orders && $vendor->total_orders > 0) 
                                            ? round(($vendor->delivered_orders / $vendor->total_orders) * 100)
                                            : 0;
                                    @endphp
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full @if($score >= 80) bg-green-500 @elseif($score >= 60) bg-yellow-500 @else bg-red-500 @endif" style="width: {{ $score }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $score }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($vendors->total() > 15)
                <div class="mt-6 flex justify-center">
                    {{ $vendors->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2"><x-heroicon-o-clipboard class="w-4 h-4" /><span>Légende</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-blue-900"><strong>Score:</strong> Basé sur le taux de livraison (commandes livrées / total)</p>
            </div>
            <div>
                <p class="text-blue-900"><strong>Vert (80%+):</strong> Excellent | <strong>Jaune (60-79%):</strong> Bon | <strong>Rouge (-60%):</strong> À améliorer</p>
            </div>
        </div>
    </div>
</div>
@endsection
