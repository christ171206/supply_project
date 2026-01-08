@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Retour -->
        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-8">
            ← Retour au tableau de bord
        </a>

        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-3xl shadow-lg">
                    📋
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Mes Commandes</h1>
                    <p class="text-gray-600 mt-2">Suivi de vos achats et livraisons</p>
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition">
            @if($commandes->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white border-b">
                                <th class="text-left py-4 px-6 font-bold">N° Commande</th>
                                <th class="text-left py-4 px-6 font-bold">Date</th>
                                <th class="text-left py-4 px-6 font-bold">Statut</th>
                                <th class="text-left py-4 px-6 font-bold">Montant</th>
                                <th class="text-left py-4 px-6 font-bold">Paiement</th>
                                <th class="text-left py-4 px-6 font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($commandes as $commande)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="py-4 px-6 font-bold text-blue-600">#{{ $commande->id }}</td>
                                    <td class="py-4 px-6 text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-4 py-2 rounded-full text-xs font-bold inline-block
                                            @if($commande->statut === 'livree') bg-green-100 text-green-700
                                            @elseif($commande->statut === 'expediee') bg-blue-100 text-blue-700
                                            @elseif($commande->statut === 'confirmee') bg-yellow-100 text-yellow-700
                                            @elseif($commande->statut === 'annulee') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            @switch($commande->statut)
                                                @case('en_attente') <x-icon name="clock" class="w-4 h-4 inline mr-1" /> En attente @break
                                                @case('confirmee') <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Confirmée @break
                                                @case('expediee') 🚚 Expédiée @break
                                                @case('livree') ✓ Livrée @break
                                                @case('annulee') ❌ Annulée @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                    <td class="py-4 px-6">
                                        @if($commande->paiement_confirme)
                                            <span class="text-green-600 font-bold flex items-center gap-1">
                                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                                Payé
                                            </span>
                                        @else
                                            <span class="text-orange-600 font-bold flex items-center gap-1">
                                                <span class="w-2 h-2 bg-orange-600 rounded-full animate-pulse"></span>
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('client.commande-detail', $commande->id) }}"
                                           class="text-blue-600 hover:text-blue-700 font-bold hover:underline transition flex items-center gap-1">
                                            Détails
                                            <span>→</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-200">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">😕</div>
                    <p class="text-gray-600 text-xl mb-8">Vous n'avez pas encore passé de commande</p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-bold transition transform hover:scale-105 shadow-lg flex items-center gap-2">
                        <span>🛒</span>
                        <span>Commencer à acheter</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
