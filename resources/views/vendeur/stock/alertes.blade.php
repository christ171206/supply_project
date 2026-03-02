@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">🚨 Alertes Stock</h1>
        <p class="text-gray-600">Produits avec stock faible ou critique</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex gap-4 flex-wrap">
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Tous les niveaux</option>
                <option value="critique" {{ request('type') == 'critique' ? 'selected' : '' }}>Critique (Rupture)</option>
                <option value="faible" {{ request('type') == 'faible' ? 'selected' : '' }}>Faible</option>
            </select>
            <input type="text" name="search" placeholder="Rechercher un produit..." 
                   class="flex-1 min-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   value="{{ request('search') }}">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Stats Alertes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-red-50 rounded-xl shadow-lg border-l-4 border-red-500 p-6">
            <p class="text-red-700 font-bold text-sm mb-2">❌ Ruptures</p>
            <p class="text-3xl font-bold text-red-600">8</p>
            <p class="text-xs text-red-600 mt-2">À réapprovisionner d'urgence</p>
        </div>

        <div class="bg-yellow-50 rounded-xl shadow-lg border-l-4 border-yellow-500 p-6">
            <p class="text-yellow-700 font-bold text-sm mb-2">⚠️ Stock Faible</p>
            <p class="text-3xl font-bold text-yellow-600">23</p>
            <p class="text-xs text-yellow-600 mt-2">À surveiller</p>
        </div>

        <div class="bg-blue-50 rounded-xl shadow-lg border-l-4 border-blue-500 p-6">
            <p class="text-blue-700 font-bold text-sm mb-2">📋 Produits Alertes</p>
            <p class="text-3xl font-bold text-blue-600">31</p>
            <p class="text-xs text-blue-600 mt-2">Nécessitant action</p>
        </div>
    </div>

    <!-- Liste des alertes -->
    <div class="space-y-4">
        <!-- Ruptures de stock -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <h3 class="text-lg font-bold text-white bg-red-600 px-6 py-4 flex items-center gap-2">
                ❌ Produits en Rupture
            </h3>

            <div class="p-6 space-y-4">
                <!-- Items (exemples) -->
                <div class="flex items-center justify-between p-4 border-2 border-red-200 rounded-lg bg-red-50">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">Dell Inspiron 15</h4>
                        <p class="text-sm text-gray-600">Rupture de stock depuis 3 jours</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">Stock: 0/20</p>
                        <button class="mt-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                            Commander
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border-2 border-red-200 rounded-lg bg-red-50">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">Kingston SSD 256GB</h4>
                        <p class="text-sm text-gray-600">Rupture de stock depuis 1 jour</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">Stock: 0/15</p>
                        <button class="mt-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                            Commander
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock faible -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <h3 class="text-lg font-bold text-white bg-yellow-600 px-6 py-4 flex items-center gap-2">
                ⚠️ Produits Stock Faible
            </h3>

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 border-2 border-yellow-200 rounded-lg bg-yellow-50">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">HP Pavilion 13</h4>
                        <p class="text-sm text-gray-600">Stock en dessous du minimum</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-yellow-600">Stock: 3/10</p>
                        <button class="mt-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-semibold">
                            Approvisionner
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border-2 border-yellow-200 rounded-lg bg-yellow-50">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">USB-C Hub 5-in-1</h4>
                        <p class="text-sm text-gray-600">Stock limite dans 2-3 jours</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-yellow-600">Stock: 5/20</p>
                        <button class="mt-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-semibold">
                            Approvisionner
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div class="mt-8 bg-blue-50 rounded-xl shadow-lg border-l-4 border-blue-500 p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-4">💡 Recommandations</h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li>✓ Approvisionner d'urgence les 8 produits en rupture</li>
            <li>✓ Passer des commandes pour les 23 produits à stock faible</li>
            <li>✓ Ajuster les niveaux de stock minimum selon vos ventes</li>
            <li>✓ Mettre en place un système d'alerte automatique</li>
        </ul>
    </div>
</div>
@endsection
