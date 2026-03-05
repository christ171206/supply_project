@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-cube class="w-8 h-8" /><span>Audit du Stock</span></h1>
        <p class="text-gray-600 mt-2">Suivi complet du stock et des mouvements d'inventaire</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut Stock</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les produits</option>
                    <option value="critical" @selected(request('status') == 'critical')>Stock Critique</option>
                    <option value="low" @selected(request('status') == 'low')>Stock Faible</option>
                    <option value="normal" @selected(request('status') == 'normal')>Stock Normal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <input type="text" name="category" placeholder="Rechercher..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ request('category') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Stock Total</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalStock ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">unités</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Stock Faible</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">produits</p>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Stock Critique</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $criticalStockCount ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">produits</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Mouvements</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $movementsCount ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">ce mois</p>
        </div>
    </div>

    <!-- Stock Details Table -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Détails du Stock</span></h2>

        @if(isset($products) && $products->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Produit</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Stock</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Minimum</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Statut</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Mouvement (7j)</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $product->nom }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="text-lg font-bold text-gray-900">
                                        {{ $product->stock?->quantite ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    {{ $product->stock?->alerte_quantite ?? 10 }}
                                </td>
                                <td class="text-center py-4 px-4">
                                    @php
                                        $currentStock = $product->stock?->quantite ?? 0;
                                        $minStock = $product->stock?->alerte_quantite ?? 10;
                                    @endphp
                                    @if($currentStock === 0)
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">⚠️ Rupture</span>
                                    @elseif($currentStock <= $minStock)
                                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">⚠️ Critique</span>
                                    @elseif($currentStock <= $minStock * 1.5)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⚠️ Faible</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✅ Normal</span>
                                    @endif
                                </td>
                                <td class="text-center py-4 px-4">
                                    {{ $product->movements_7days ?? 0 }}
                                </td>
                                <td class="text-center py-4 px-4">
                                    <a href="{{ route('admin.products.stock-history', $product->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                        Historique →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <p>❌ Aucun produit trouvé</p>
            </div>
        @endif
    </div>

    <!-- Recent Movements -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🔄 Mouvements Récents</h2>

        @if(isset($recentMovements) && $recentMovements->isNotEmpty())
            <div class="space-y-3">
                @foreach($recentMovements as $movement)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $movement->produit->nom ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $movement->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm font-medium @if($movement->quantite > 0) text-green-600 @else text-red-600 @endif">
                                    @if($movement->quantite > 0) ↑ @else ↓ @endif {{ abs($movement->quantite) }}
                                </p>
                                <p class="text-xs text-gray-600">{{ $movement->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>❌ Aucun mouvement récent</p>
            </div>
        @endif
    </div>
</div>
@endsection
