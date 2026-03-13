@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl px-4 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-serif text-gray-900 mb-2">Alertes Stock</h1>
        <p class="text-gray-600">Gérez les alertes de stock de vos produits</p>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="text-red-600 text-sm font-semibold mb-2">Alertes Critiques</div>
            <div class="text-4xl font-bold text-red-600">{{ $criticalAlerts }}</div>
            <p class="text-red-500 text-xs mt-2">Action immédiate requise</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="text-yellow-700 text-sm font-semibold mb-2">Alertes Bas Stock</div>
            <div class="text-4xl font-bold text-yellow-600">{{ $lowAlerts }}</div>
            <p class="text-yellow-600 text-xs mt-2">À surveiller</p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="text-green-700 text-sm font-semibold mb-2">Stock Normal</div>
            <div class="text-4xl font-bold text-green-600">{{ $normalStock }}</div>
            <p class="text-green-600 text-xs mt-2">Produits OK</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="mb-6 flex gap-2">
        <select onchange="this.form.submit()" class="px-4 py-2 rounded-lg bg-white border border-gray-300">
            <option value="">Tous les statuts</option>
            <option value="critical" {{ $filter === 'critical' ? 'selected' : '' }}>Critique seulement</option>
            <option value="low" {{ $filter === 'low' ? 'selected' : '' }}>Bas stock</option>
        </select>
    </div>

    {{-- Alertes --}}
    @if($alerts->isEmpty())
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-gray-500 text-lg">Aucune alerte pour le moment</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($alerts as $alert)
                <div class="bg-white border rounded-lg p-6 {{ $alert->alert_type === 'critical' ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $alert->produit->nom }}</h3>
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $alert->alert_type === 'critical' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800' }}">
                                    {{ $alert->alert_type === 'critical' ? '🚨 CRITIQUE' : '⚠️ BAS STOCK' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wide">Stock actuel</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $alert->current_stock }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wide">Stock minimum</p>
                                    <p class="text-2xl font-bold text-gray-700">{{ $alert->min_stock }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wide">À réapprovisionner</p>
                                    <p class="text-2xl font-bold text-red-600">{{ max(0, $alert->min_stock - $alert->current_stock) }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mt-3">
                                Alerte depuis {{ $alert->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex gap-2 flex-shrink-0">
                            <a href="{{ route('vendeur.produits.edit', $alert->produit->id) }}"
                               class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                Éditer
                            </a>
                            <button onclick="dismissAlert({{ $alert->id }})" class="px-3 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 text-sm">
                                Ignorer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function dismissAlert(id) {
        if (confirm('Ignorer cette alerte ?')) {
            // Implémentation API à ajouter
            location.reload();
        }
    }
</script>
@endsection
