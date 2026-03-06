@extends('layouts.admin-layout')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Gestion des Commandes</h1>
            <p class="text-gray-600 mt-2">Gérez toutes les commandes de la plateforme</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.orders.delivery-overview') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                📦 Vue Livraisons
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher par client</label>
                <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nom ou email..." value="{{ request('search') }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('status') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmee" {{ request('status') === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                    <option value="expediee" {{ request('status') === 'expediee' ? 'selected' : '' }}>🚚 Expédiée</option>
                    <option value="livree" {{ request('status') === 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ request('status') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Livraison</label>
                <select name="delivery_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les états</option>
                    <option value="pending" {{ request('delivery_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="in_transit" {{ request('delivery_status') === 'in_transit' ? 'selected' : '' }}>En transit</option>
                    <option value="delivered" {{ request('delivery_status') === 'delivered' ? 'selected' : '' }}>Livré</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ request('start_date') }}">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    ↺
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des commandes -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">#ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Client</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Montant</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut Commande</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Livraison</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($commandes as $commande)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $commande->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $commande->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $commande->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ number_format($commande->total, 0, ',', ' ') }} XOF
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $commande->statut === 'expediee' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $commande->statut === 'confirmee' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $commande->statut === 'en_attente' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $commande->statut === 'annulee' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ match($commande->statut) {
                                        'en_attente' => 'En attente',
                                        'confirmee' => '✅ Confirmée',
                                        'expediee' => '🚚 Expédiée',
                                        'livree' => 'Livrée',
                                        'annulee' => 'Annulée',
                                        default => ucfirst($commande->statut),
                                    } }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $commande->delivery_status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $commande->delivery_status === 'in_transit' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $commande->delivery_status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ match($commande->delivery_status ?? 'pending') {
                                        'pending' => 'Attente',
                                        'in_transit' => '📍 Transit',
                                        'delivered' => 'Livré',
                                        default => 'N/A',
                                    } }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $commande->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.orders.show', $commande) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200 transition">
                                        👁️ Voir
                                    </a>
                                    <a href="{{ route('admin.orders.tracking', $commande) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200 transition">
                                        📍 Suivi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <p class="text-lg">📭 Aucune commande trouvée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $commandes->links() }}
    </div>
</div>
@endsection
