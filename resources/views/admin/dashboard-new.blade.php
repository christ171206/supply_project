@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Tableau de Bord Admin</h1>
        <p class="text-gray-500 mt-2">Gérez la plateforme Supply</p>
    </div>

    <!-- KPI Cards -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistiques Clés</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Chiffre d'affaires -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Chiffre d'affaires</p>
                        <p class="text-3xl font-bold text-green-600 mt-3">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-gray-500 text-xs mt-1">FCFA Commission</p>
                    </div>
                    <div class="opacity-20 text-green-600">💰</div>
                </div>
            </div>

            <!-- Commission Rate -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Taux Commission</p>
                        <p class="text-3xl font-bold text-blue-600 mt-3">{{ $commissionRate ?? 10 }}%</p>
                        <p class="text-gray-500 text-xs mt-1">Sur chaque vente</p>
                    </div>
                    <div class="opacity-20 text-blue-600">📊</div>
                </div>
            </div>

            <!-- Vendors Pending -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Vendeurs à Valider</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-3">{{ $vendorsToApprove ?? 0 }}</p>
                        <p class="text-gray-500 text-xs mt-1">En attente de validation</p>
                    </div>
                    <div class="opacity-20 text-yellow-600">📋</div>
                </div>
            </div>

            <!-- Products Pending -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Produits à Valider</p>
                        <p class="text-3xl font-bold text-purple-600 mt-3">{{ $productsAwaitingValidation ?? 0 }}</p>
                        <p class="text-gray-500 text-xs mt-1">En attente de vérification</p>
                    </div>
                    <div class="opacity-20 text-purple-600">📦</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Status -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statut des Commandes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- En attente -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl">⏳</span>
                    <span class="text-xs font-bold text-yellow-700 uppercase">En attente</span>
                </div>
                <p class="text-4xl font-bold text-yellow-600">{{ $ordersAwaitingConfirmation ?? 0 }}</p>
                <p class="text-sm text-yellow-700 mt-2">À traiter rapidement</p>
            </div>

            <!-- Confirmées -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl">✅</span>
                    <span class="text-xs font-bold text-blue-700 uppercase">Confirmées</span>
                </div>
                <p class="text-4xl font-bold text-blue-600">{{ $ordersConfirmed ?? 0 }}</p>
                <p class="text-sm text-blue-700 mt-2">Prêtes à expédier</p>
            </div>

            <!-- Expédiées -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl">📮</span>
                    <span class="text-xs font-bold text-indigo-700 uppercase">Expédiées</span>
                </div>
                <p class="text-4xl font-bold text-indigo-600">{{ $ordersShipped ?? 0 }}</p>
                <p class="text-sm text-indigo-700 mt-2">En route vers clients</p>
            </div>

            <!-- Livrées -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl">🎉</span>
                    <span class="text-xs font-bold text-green-700 uppercase">Livrées</span>
                </div>
                <p class="text-4xl font-bold text-green-600">{{ $ordersDelivered ?? 0 }}</p>
                <p class="text-sm text-green-700 mt-2">Commandes réussies</p>
            </div>
        </div>
    </div>

    <!-- Top Vendors -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">🏆 Top 5 Vendeurs par Chiffre d'Affaires</h2>
        @if($topVendors && $topVendors->count() > 0)
            <div class="space-y-3">
                @foreach($topVendors as $index => $vendor)
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $vendor->name }}</p>
                            <p class="text-sm text-gray-600">{{ $vendor->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-lg">{{ number_format($vendor->total_revenue ?? 0, 0, ',', ' ') }} FCFA</p>
                            <p class="text-xs text-gray-500">Commission perçue</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Pas de vendeurs enregistrés</p>
            </div>
        @endif
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">🔥 Top 5 Produits en Validation</h2>
        @if($topProducts && $topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $product->nom }}</p>
                            <p class="text-sm text-gray-600">Soumis par {{ $product->vendeur->name ?? 'Inconnu' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">⏳ En attente</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Tous les produits sont validés</p>
            </div>
        @endif
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">📋 Dernières Commandes</h2>

        @if($recentOrders && $recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Client</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">N° Commande</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">{{ $order->user->name ?? 'Inconnu' }}</span>
                                </td>
                                <td class="py-4 px-4 font-bold text-blue-600">#{{ $order->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $order->vendeur->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 font-semibold text-green-600">{{ number_format($order->total ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        @if($order->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($order->statut === 'confirmee') bg-blue-100 text-blue-800
                                        @elseif($order->statut === 'expediee') bg-indigo-100 text-indigo-800
                                        @elseif($order->statut === 'livree') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-bold">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucune commande enregistrée</p>
            </div>
        @endif
    </div>

    <!-- Recent Disputes -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">⚠️ Litiges Récents</h2>

        @if($recentDisputes && $recentDisputes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Titre</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Client</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDisputes as $dispute)
                            <tr class="border-b hover:bg-red-50 transition">
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">{{ $dispute->titre }}</span>
                                </td>
                                <td class="py-4 px-4 text-gray-700">{{ $dispute->user->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $dispute->vendeur->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        @if($dispute->statut === 'ouvert') bg-red-100 text-red-800
                                        @elseif($dispute->statut === 'en_cours') bg-yellow-100 text-yellow-800
                                        @elseif($dispute->statut === 'resolu') bg-green-100 text-green-800
                                        @elseif($dispute->statut === 'ferme') bg-gray-100 text-gray-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $dispute->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="text-red-600 hover:text-red-700 font-bold">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucun litige actif</p>
            </div>
        @endif
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockProducts && $lowStockProducts->count() > 0)
        <div class="bg-red-50 border-l-4 border-red-600 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <span class="text-3xl">🚨</span>
                <div class="flex-1">
                    <h3 class="font-bold text-red-900 mb-4">Alerte : Stock Critique Détecté!</h3>
                    <p class="text-red-800 mb-4">{{ $lowStockProducts->count() }} produit(s) de vendeurs ont un stock critique</p>

                    <div class="space-y-2">
                        @foreach($lowStockProducts->take(5) as $product)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-red-200">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->nom }}</p>
                                    <p class="text-sm text-gray-600">Vendeur: {{ $product->vendeur->name }} | Stock: {{ $product->stock ?? 0 }} / Minimum: {{ $product->stock_minimum ?? 0 }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">⚡ Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl">👥</div>
                <div>
                    <p class="font-bold text-gray-900">Gérer Utilisateurs</p>
                    <p class="text-sm text-gray-600">Voir tous les utilisateurs</p>
                </div>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl">📦</div>
                <div>
                    <p class="font-bold text-gray-900">Valider Produits</p>
                    <p class="text-sm text-gray-600">Produits en attente</p>
                </div>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl">📋</div>
                <div>
                    <p class="font-bold text-gray-900">Voir Commandes</p>
                    <p class="text-sm text-gray-600">Gérer les commandes</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Conseil -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <p class="flex items-start gap-3">
            <span class="text-2xl">💡</span>
            <span class="text-blue-900"><strong>Conseil du Jour :</strong> Validez régulièrement les nouveaux vendeurs et produits, gérez les litiges rapidement et maintenez une plateforme saine. C'est la clé du succès de Supply !</span>
        </p>
    </div>
</div>
@endsection
