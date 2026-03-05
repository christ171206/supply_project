@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.products.show', $produit->id) }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour au produit
        </a>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-8 h-8" /><span>Historique du Stock - {{ $produit->nom }}</span></h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Début</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Fin</label>
                <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="ajustement">Ajustement</option>
                    <option value="vente">Vente</option>
                    <option value="retour">Retour</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($mouvements->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">❌ Aucun mouvement enregistré</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($mouvements as $mouvement)
                    <div class="flex gap-4">
                        <!-- Timeline dot -->
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full @if($mouvement->quantite > 0) bg-green-100 @else bg-red-100 @endif flex items-center justify-center">
                                @if($mouvement->type === 'ajustement')
                                    <span class="text-lg">⚙️</span>
                                @elseif($mouvement->type === 'vente')
                                    <span class="text-lg">🛒</span>
                                @elseif($mouvement->type === 'retour')
                                    <span class="text-lg">↩️</span>
                                @else
                                    <span class="text-lg">📦</span>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="w-1 h-16 bg-gray-200 my-2"></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="pb-2 flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-gray-900">
                                        @if($mouvement->type === 'ajustement')
                                            Ajustement de Stock
                                        @elseif($mouvement->type === 'vente')
                                            Vente
                                        @elseif($mouvement->type === 'retour')
                                            Retour Client
                                        @else
                                            Mouvement
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $mouvement->raison ?? 'Aucune raison spécifiée' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold @if($mouvement->quantite > 0) text-green-600 @else text-red-600 @endif">
                                        {{ $mouvement->quantite > 0 ? '+' : '' }}{{ $mouvement->quantite }}
                                    </span>
                                    <p class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($mouvement->notes)
                                <p class="text-sm text-gray-600 mt-2 p-3 bg-gray-50 rounded">
                                    📝 {{ $mouvement->notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($mouvements->total() > 15)
                <div class="mt-6 flex justify-center">
                    {{ $mouvements->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Total Ajoutés</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $addedTotal ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Total Retirés</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $removedTotal ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Solde Net</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ ($addedTotal ?? 0) - ($removedTotal ?? 0) }}</p>
        </div>
    </div>
</div>
@endsection
