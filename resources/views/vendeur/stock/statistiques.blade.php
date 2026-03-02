@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Statistiques Stock</h1>
        <p class="text-gray-600">Analyse et tendances de votre inventaire</p>
    </div>

    <!-- Stats Principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2">Valeur du Stock</p>
            <p class="text-3xl font-bold text-green-600">12,540,000 CFA</p>
            <p class="text-xs text-gray-500 mt-2">Valeur estimée</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2">Rotation Stock</p>
            <p class="text-3xl font-bold text-blue-600">4.2x</p>
            <p class="text-xs text-gray-500 mt-2">Par an</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2">Stock Moyen</p>
            <p class="text-3xl font-bold text-yellow-600">2,540 unités</p>
            <p class="text-xs text-gray-500 mt-2">En moyenne</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-purple-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2">Coût Stockage</p>
            <p class="text-3xl font-bold text-purple-600">125,400 CFA</p>
            <p class="text-xs text-gray-500 mt-2">Estimé</p>
        </div>
    </div>

    <!-- Graphiques (Placeholders) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Évolution du stock -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📈 Évolution du Stock</h3>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Graphique en cours de chargement...</p>
            </div>
        </div>

        <!-- Distribution par catégorie -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Répartition par Catégorie</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <p class="text-sm font-semibold text-gray-700">Informatique</p>
                        <p class="text-sm font-bold text-gray-900">45%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: 45%;"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <p class="text-sm font-semibold text-gray-700">Accessoires</p>
                        <p class="text-sm font-bold text-gray-900">30%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 30%;"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <p class="text-sm font-semibold text-gray-700">Logiciels</p>
                        <p class="text-sm font-bold text-gray-900">25%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques détaillées -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Métriques Détaillées</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-1">Produits en Stock</p>
                <p class="text-2xl font-bold text-gray-900">256</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-1">Stock Critique</p>
                <p class="text-2xl font-bold text-red-600">12</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-1">Stock Faible</p>
                <p class="text-2xl font-bold text-yellow-600">28</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-1">Stock Normal</p>
                <p class="text-2xl font-bold text-green-600">216</p>
            </div>
        </div>
    </div>
</div>
@endsection
