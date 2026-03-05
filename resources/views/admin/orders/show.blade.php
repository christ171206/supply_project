@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour aux commandes
        </a>
        <h1 class="text-3xl font-bold text-gray-900">🛍️ Commande #{{ $commande->id }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Section - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📝 Résumé de la Commande</h2>
                
                <div class="space-y-4">
                    <!-- Client Info -->
                    <div>
                        <p class="text-sm text-gray-600 font-medium">CLIENT</p>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <p class="font-semibold text-gray-900">{{ $commande->user->name }}</p>
                            <p class="text-sm text-gray-600">📧 {{ $commande->user->email }}</p>
                            @if($commande->user->phone)
                                <p class="text-sm text-gray-600">📞 {{ $commande->user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">STATUT COMMANDE</p>
                            <span class="px-3 py-1 mt-2 @if($commande->statut === 'en_attente') bg-yellow-100 text-yellow-800 @elseif($commande->statut === 'en_cours') bg-blue-100 text-blue-800 @elseif($commande->statut === 'prete') bg-purple-100 text-purple-800 @else bg-red-100 text-red-800 @endif rounded-full text-xs font-semibold inline-block">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">STATUT LIVRAISON</p>
                            <span class="px-3 py-1 mt-2 @if($commande->delivery_status === 'pending') bg-gray-100 text-gray-800 @elseif($commande->delivery_status === 'in_transit') bg-blue-100 text-blue-800 @elseif($commande->delivery_status === 'delivered') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif rounded-full text-xs font-semibold inline-block">
                                {{ ucfirst(str_replace('_', ' ', $commande->delivery_status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">MODE DE PAIEMENT</p>
                            <p class="text-gray-900 font-semibold mt-1">{{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">PAIEMENT CONFIRMÉ</p>
                            <span class="px-2 py-1 {{ $commande->paiement_confirme ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded text-xs font-semibold inline-block">
                                {{ $commande->paiement_confirme ? '✅ Oui' : '❌ Non' }}
                            </span>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4 p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-xs text-gray-600">Date de Commande</p>
                            <p class="font-semibold text-gray-900">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Dernière Modification</p>
                            <p class="font-semibold text-gray-900">{{ $commande->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Articles de la Commande</span></h2>
                
                @if($commande->ligneCommandes->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Produit</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Quantité</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Prix Unitaire</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commande->ligneCommandes as $ligne)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $ligne->produit->nom ?? 'Produit supprimé' }}</p>
                                                <p class="text-xs text-gray-500">{{ $ligne->produit->user->shop_name ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center py-3 px-4 font-semibold">{{ $ligne->quantite }}</td>
                                        <td class="text-right py-3 px-4">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} XOF</td>
                                        <td class="text-right py-3 px-4 font-bold text-green-600">
                                            {{ number_format($ligne->prix_unitaire * $ligne->quantite, 0, ',', ' ') }} XOF
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6">❌ Aucun article</p>
                @endif
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📍 Adresse de Livraison</h2>
                
                <div class="p-4 bg-gray-50 rounded-lg space-y-2">
                    <p class="text-gray-900">{{ $commande->adresse_detail ?? $commande->adresse_livraison }}</p>
                    @if($commande->telephone_livraison)
                        <p class="text-gray-600">📞 {{ $commande->telephone_livraison }}</p>
                    @endif
                    @if($commande->deliveryZone)
                        <p class="text-sm text-gray-600 mt-2">Zone: <strong>{{ $commande->deliveryZone->nom }}</strong></p>
                    @endif
                </div>

                @if($commande->notes)
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800"><strong>Notes:</strong> {{ $commande->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Section - Totals & Actions -->
        <div class="space-y-6">
            <!-- Totals Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-green-900 mb-4 flex items-center gap-2"><x-heroicon-o-banknotes class="w-6 h-6" /><span>Totaux</span></h3>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-green-800">Sous-total:</span>
                        <span class="font-bold text-green-900">{{ number_format($commande->total, 0, ',', ' ') }} XOF</span>
                    </div>
                    @if($commande->frais_livraison)
                        <div class="flex justify-between items-center border-t border-green-200 pt-3">
                            <span class="text-green-800">Frais Livraison:</span>
                            <span class="font-bold text-green-900">{{ number_format($commande->frais_livraison, 0, ',', ' ') }} XOF</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-lg border-t-2 border-green-200 pt-3">
                        <span class="font-bold text-green-900">TOTAL:</span>
                        <span class="text-2xl font-bold text-green-600">{{ number_format($commande->total + ($commande->frais_livraison ?? 0), 0, ',', ' ') }} XOF</span>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Changer le Statut</h3>
                
                <form method="POST" action="{{ route('admin.orders.update-status', $commande->id) }}" class="space-y-3">
                    @csrf
                    <select name="statut" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="en_attente" @selected($commande->statut === 'en_attente')>⏳ En attente</option>
                        <option value="en_cours" @selected($commande->statut === 'en_cours')>🔄 En cours</option>
                        <option value="prete" @selected($commande->statut === 'prete')>✅ Prête</option>
                        <option value="cancelled" @selected($commande->statut === 'cancelled')>❌ Annulée</option>
                    </select>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Delivery Status Update -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Statut Livraison</span></h3>
                
                <form method="POST" action="{{ route('admin.orders.update-delivery-status', $commande->id) }}" class="space-y-3">
                    @csrf
                    <select name="delivery_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="pending" @selected($commande->delivery_status === 'pending')>⏳ En attente</option>
                        <option value="picked_up" @selected($commande->delivery_status === 'picked_up')>📦 Enlevée</option>
                        <option value="in_transit" @selected($commande->delivery_status === 'in_transit')>🚚 En transit</option>
                        <option value="delivered" @selected($commande->delivery_status === 'delivered')>✅ Livrée</option>
                        <option value="failed" @selected($commande->delivery_status === 'failed')>❌ Échouée</option>
                    </select>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Actions</span></h3>
                
                <div class="space-y-2">
                    <a href="{{ route('admin.orders.tracking', $commande->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        👁️ Suivi Détaillé
                    </a>
                    
                    @if($commande->statut !== 'cancelled')
                        <form method="POST" action="{{ route('admin.orders.cancel', $commande->id) }}">
                            @csrf
                            <input type="hidden" name="reason" value="Annulation administrative">
                            <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                ❌ Annuler
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
