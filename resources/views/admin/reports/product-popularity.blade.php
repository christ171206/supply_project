@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-fire class="w-8 h-8" /><span>Produits Populaires</span></h1>
        <p class="text-gray-600 mt-2">Analyse des produits les plus vendus</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de Produits</label>
                <select name="limit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="10" @selected(request('limit') == 10)>Top 10</option>
                    <option value="20" @selected(request('limit') == 20)>Top 20</option>
                    <option value="50" @selected(request('limit') == 50)>Top 50</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($products->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Aucun produit trouvé</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Produit</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Prix Unitaire</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Fois Vendu</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Quantité Totale</th>
                            <th class="text-right py-4 px-4 font-semibold text-gray-700">Revenu</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-xl font-bold text-gray-400">{{ $index + 1 }}</div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $product->nom }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $product->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-4 px-4">
                                    {{ number_format($product->prix, 0, ',', ' ') }} XOF
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $product->times_sold ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                        {{ $product->total_quantity ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-right py-4 px-4 font-bold text-green-600">
                                    {{ number_format($product->total_revenue ?? 0, 0, ',', ' ') }} XOF
                                </td>
                                <td class="text-center py-4 px-4">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->total() > 20)
                <div class="mt-6 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Insights -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-green-900 mb-2">💡 Insight</h3>
            <p class="text-green-800 text-sm">Les produits les plus vendus génèrent la majorité des revenus. Utilisez ces données pour optimiser votre stratégie commercial.</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-4 h-4" /><span>Conseil</span></h3>
            <p class="text-blue-800 text-sm">Promouvoir les produits populaires et améliorer le stock des articles à haute demande peut augmenter les ventes globales.</p>
        </div>
    </div>
</div>
@endsection
