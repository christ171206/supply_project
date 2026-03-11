@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour aux produits
        </a>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-cube class="w-8 h-8" /><span>{{ $produit->nom }}</span></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Section - Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Informations Générales</span></h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">SKU</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $produit->sku ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Catégorie</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $produit->categorie->nom ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-medium">Description</p>
                        <p class="text-gray-900 mt-2 leading-relaxed">{{ $produit->description ?? 'Aucune description' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Prix</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($produit->prix, 0, ',', ' ') }} XOF</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Statut</p>
                            <span class="px-3 py-1 {{ $produit->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-sm font-semibold">
                                {{ $produit->actif ? '✅ Actif' : '❌ Inactif' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Vendeur</p>
                            <p class="text-gray-900 font-semibold">{{ $produit->vendeur?->shop_name ?? $produit->vendeur?->name ?? 'Vendeur supprimé' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Créé le</p>
                            <p class="text-gray-900">{{ $produit->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Management -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📦 Gestion du Stock</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Quantité</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $produit->stock }}</p>
                        </div>
                        <div class="p-4 @if($produit->stock <= $produit->stock_minimum) bg-red-50 @else bg-yellow-50 @endif rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Minimum</p>
                            <p class="text-3xl font-bold @if($produit->stock <= $produit->stock_minimum) text-red-600 @else text-yellow-600 @endif mt-2">
                                {{ $produit->stock_minimum }}
                            </p>
                        </div>
                    </div>

                    @if($produit->stock <= $produit->stock_minimum)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 text-sm">⚠️ <strong>Stock Critique:</strong> Le stock est en dessous du minimum recommandé</p>
                        </div>
                    @endif

                    <div class="p-3 bg-gray-50 rounded-lg border border-[#e0e0dc]">
                        <p class="text-[12px] text-[#a0a09a] font-light">ℹ️ Gestion du stock: Responsabilité du Vendeur</p>
                    </div>
                </div>
            </div>

            <!-- Stock History -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">🕐 Historique du Stock</h2>
                    <a href="{{ route('admin.products.stock-history', $produit->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        Voir tout →
                    </a>
                </div>

                @if(isset($stockHistory) && $stockHistory->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($stockHistory->take(5) as $history)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $history->type ?? 'Mouvement' }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="text-lg font-bold @if($history->quantite > 0) text-green-600 @else text-red-600 @endif">
                                    {{ $history->quantite > 0 ? '+' : '' }}{{ $history->quantite }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">❌ Aucun mouvement</p>
                @endif
            </div>
        </div>

        <!-- Right Section - Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">⚙️ Actions</h2>

                <div class="space-y-3">
                    @if($produit->actif)
                        <form method="POST" action="{{ route('admin.products.disable', $produit->id) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                🔒 Désactiver
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.products.enable', $produit->id) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                🔓 Activer
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.products.stock-history', $produit->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        📊 Historique Détaillé
                    </a>

                    <form method="DELETE" action="{{ route('admin.products.destroy', $produit->id) }}" class="w-full" onsubmit="return confirm('Êtes-vous sûr? Cette action est irréversible.')">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-purple-900 mb-3">📈 Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-purple-800">Ventes:</span>
                        <span class="font-bold text-purple-600">{{ $produit->sales_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-800">Revenus:</span>
                        <span class="font-bold text-purple-600">{{ number_format($produit->total_revenue ?? 0, 0, ',', ' ') }} XOF</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-800">Évaluation:</span>
                        <span class="font-bold text-purple-600">{{ round($produit->average_rating ?? 0, 1) }}/5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
