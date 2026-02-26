@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Retour -->
    <a href="{{ route('client.dashboard') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold mb-6 inline-block">
        ← Retour
    </a>

    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Mes Commandes</h1>
        <p class="text-gray-600 mt-1">Suivi de vos achats et livraisons</p>
    </div>

    <!-- Contenu -->
    <div class="card overflow-hidden">
        @if($commandes->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-900">N°</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-900">Date</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-900">Statut</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-900">Montant</th>
                            <th class="text-left py-4 px-4 font-semibold text-gray-900">Paiement</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commandes as $commande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-semibold text-primary-600">#{{ $commande->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4">
                                    @switch($commande->statut)
                                        @case('en_attente')
                                            <span class="text-xs font-semibold text-warning-700">⏳ En attente</span>
                                        @break
                                        @case('confirmee')
                                            <span class="text-xs font-semibold text-primary-700">✓ Confirmée</span>
                                        @break
                                        @case('expediee')
                                            <span class="text-xs font-semibold text-accent-700">🚚 Expédiée</span>
                                        @break
                                        @case('livree')
                                            <span class="text-xs font-semibold text-success-700">✓ Livrée</span>
                                        @break
                                        @default
                                            <span class="text-xs font-semibold text-danger-700">✗ Annulée</span>
                                    @endswitch
                                </td>
                                <td class="py-4 px-4 font-semibold text-primary-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4 text-xs">
                                    @if($commande->paiement_confirme)
                                        <span class="text-success-600 font-semibold">✓ Payé</span>
                                    @else
                                        <span class="text-warning-600 font-semibold">⏳ En attente</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('client.commande-detail', $commande->id) }}" class="text-primary-600 hover:text-primary-700 font-semibold text-xs">
                                        Voir
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
                <p class="text-gray-600 mb-6 text-sm">Vous n'avez pas encore passé de commande</p>
                <a href="{{ route('produits.catalogue') }}" class="btn-primary inline-block">
                    Commencer à acheter
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
