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
                                @if($commande->statut === 'livree')
                                    <span class="badge badge-ok">Livrée</span>
                                @elseif($commande->statut === 'expediee')
                                    <span class="badge badge-ok">Expédiée</span>
                                @elseif($commande->statut === 'confirmee')
                                    <span class="badge badge-ok">Confirmée</span>
                                @elseif($commande->statut === 'en_attente')
                                    <span class="badge badge-warn">En attente</span>
                                @elseif($commande->statut === 'annulee')
                                    <span class="badge badge-err">Annulée</span>
                                @else
                                    <span class="badge">{{ $commande->statut }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($commande->delivery_status === 'delivered')
                                    <span class="badge badge-ok">Livré</span>
                                @elseif($commande->delivery_status === 'in_transit')
                                    <span class="badge badge-warn">Transit</span>
                                @else
                                    <span class="badge badge-warn">Attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $commande->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.orders.show', $commande) }}" class="px-3 py-1 bg-black text-white rounded text-sm hover:opacity-85 transition">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.orders.tracking', $commande) }}" class="px-3 py-1 bg-black text-white rounded text-sm hover:opacity-85 transition">
                                        Suivi
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
