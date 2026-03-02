@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📜 Historique Stock</h1>
        <p class="text-gray-600">Suivi des mouvements de stock</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="produit_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Tous les produits</option>
                @foreach($produits as $produit)
                    <option value="{{ $produit->id }}" {{ request('produit_id') == $produit->id ? 'selected' : '' }}>
                        {{ $produit->nom }}
                    </option>
                @endforeach
            </select>

            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Tous les types</option>
                <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                <option value="ajustement" {{ request('type') == 'ajustement' ? 'selected' : '' }}>Ajustement</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau d'historique -->
    @if(isset($mouvements) && $mouvements->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Produit</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Quantité</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Motif</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Utilisateur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($mouvements as $mouvement)
                        @php
                            $typeColors = [
                                'entree' => 'bg-green-100 text-green-700',
                                'sortie' => 'bg-red-100 text-red-700',
                                'ajustement' => 'bg-blue-100 text-blue-700'
                            ];
                            $typeIcons = [
                                'entree' => '📥',
                                'sortie' => '📤',
                                'ajustement' => '🔧'
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $mouvement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $mouvement->produit->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $typeColors[$mouvement->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $typeIcons[$mouvement->type] ?? '' }} {{ ucfirst(str_replace('_', ' ', $mouvement->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold">
                                @if($mouvement->type == 'entree')
                                    <span class="text-green-600">+{{ $mouvement->quantite }}</span>
                                @else
                                    <span class="text-red-600">-{{ $mouvement->quantite }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $mouvement->motif ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $mouvement->user->name ?? 'Système' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mouvements->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $mouvements->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <p class="text-6xl mb-4">📜</p>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun mouvement de stock</h3>
            <p class="text-gray-600">Il n'y a pas de mouvements de stock à afficher</p>
        </div>
    @endif
</div>
@endsection
