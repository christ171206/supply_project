@extends('layouts.admin-layout')

@section('title', 'Vue Livraisons')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Vue Livraisons</h1>
            <p class="text-gray-600 mt-2">Suivi des livraisons en cours</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Retour aux commandes
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut de livraison</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="in_transit" {{ request('status') === 'in_transit' ? 'selected' : '' }}>📍 En transit</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livré</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.orders.delivery-overview') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    ↺
                </a>
            </div>
        </form>
    </div>

    <!-- Carte des commandes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($commandes as $commande)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition">
                <!-- Header de la carte -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm opacity-90">Commande #{{ $commande->id }}</p>
                            <h3 class="text-lg font-bold">{{ $commande->user->name }}</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white 
                            {{ $commande->delivery_status === 'delivered' ? 'text-green-600' : '' }}
                            {{ $commande->delivery_status === 'in_transit' ? 'text-yellow-600' : '' }}
                            {{ $commande->delivery_status === 'pending' ? 'text-gray-600' : '' }}
                        ">
                            {{ match($commande->delivery_status ?? 'pending') {
                                'pending' => '⏳ Attente',
                                'in_transit' => '📍 Transit',
                                'delivered' => '✅ Livré',
                                default => 'N/A',
                            } }}
                        </span>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="p-4 space-y-3">
                    <!-- Info client -->
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Client</p>
                        <p class="text-sm text-gray-900">{{ $commande->user->email }}</p>
                    </div>

                    <!-- Montant -->
                    <div class="flex justify-between items-center py-2 border-t border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Montant</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($commande->total, 0, ',', ' ') }} XOF</span>
                    </div>

                    <!-- Date -->
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Commandée le</p>
                        <p class="text-sm text-gray-900">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Adresse de livraison</p>
                        <p class="text-sm text-gray-900">{{ $commande->adresse_detaillee ?? 'N/A' }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-2">
                        <a href="{{ route('admin.orders.show', $commande) }}" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200 transition text-center font-semibold">
                            👁️ Détails
                        </a>
                        <a href="{{ route('admin.orders.tracking', $commande) }}" class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200 transition text-center font-semibold">
                            📍 Suivi
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-xl shadow-md p-12 text-center">
                <p class="text-2xl">📭</p>
                <p class="text-gray-500 text-lg mt-2">Aucune commande en attente de livraison</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $commandes->links() }}
    </div>
</div>
@endsection
