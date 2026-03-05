@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">⚙️ Configuration Système</h1>
        <p class="text-gray-600 mt-2">Gérez les paramètres généraux de la plateforme</p>
    </div>

    <!-- Configuration Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">⚙️ Paramètres Globaux</h2>
        
        <form method="POST" action="{{ route('admin.configuration.update') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Delivery Base Fee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frais de Livraison de Base (XOF)</label>
                    <input type="number" name="delivery_base_fee" step="0.01" value="{{ $configurations['delivery_base_fee'] ?? 2000 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Frais standard de livraison appliqués à chaque commande</p>
                </div>

                <!-- Default Delivery Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Délai de Livraison par Défaut (jours)</label>
                    <input type="number" name="default_delivery_days" min="1" value="{{ $configurations['default_delivery_days'] ?? 3 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nombre de jours estimés pour livrer une commande</p>
                </div>

                <!-- Currency Exchange Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Taux de Change (1 USD = ? XOF)</label>
                    <input type="number" name="currency_exchange_rate" step="0.01" value="{{ $configurations['currency_exchange_rate'] ?? 610 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Pour la conversion de devises</p>
                </div>

                <!-- Platform Commission Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Taux de Commission (%)</label>
                    <input type="number" name="platform_commission_rate" step="0.01" min="0" max="100" value="{{ $configurations['platform_commission_rate'] ?? 10 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Pourcentage prélevé sur chaque vente</p>
                </div>
            </div>

            <div class="border-t pt-6 flex justify-end gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('admin.configuration.delivery-zones') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-blue-900 mb-2">🗺️ Zones de Livraison</h3>
            <p class="text-blue-800 text-sm">Gérez les zones de livraison disponibles et les frais associés</p>
        </a>

        <a href="{{ route('admin.products.critical-stock') }}" class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-red-900 mb-2 flex items-center gap-2"><x-heroicon-o-exclamation-triangle class="w-5 h-5" /><span>Stock Critique</span></h3>
            <p class="text-red-800 text-sm">Produits nécessitant une action urgente sur le stock</p>
        </a>

        <a href="{{ route('admin.audit.stats') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-purple-900 mb-2 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-5 h-5" /><span>Statistiques</span></h3>
            <p class="text-purple-800 text-sm">Statistiques détaillées des activités du système</p>
        </a>
    </div>

    <!-- Information Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="font-bold text-blue-900 mb-2">💡 Conseil</h3>
        <ul class="text-blue-800 text-sm space-y-1">
            <li>• Ajustez les frais de livraison en fonction de vos coûts réels</li>
            <li>• Le taux de commission doit couvrir vos frais d'exploitation</li>
            <li>• Les délais de livraison influencent la satisfaction client</li>
        </ul>
    </div>
</div>
@endsection
