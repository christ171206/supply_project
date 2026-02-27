@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
        <p class="text-gray-600 mt-1">Bienvenue, <span class="font-semibold text-primary-600">{{ Auth::user()->name }}</span></p>
    </div>

    <!-- Résumé Cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <!-- Total Commandes -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Commandes</p>
            <p class="text-3xl font-bold text-primary-600 mt-2">{{ $commandesTotal }}</p>
            <p class="text-xs text-gray-500 mt-2">Total depuis votre inscription</p>
        </div>

        <!-- En Cours -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">En Cours</p>
            <p class="text-3xl font-bold text-accent-600 mt-2">{{ $commandesEnCours }}</p>
            <p class="text-xs text-gray-500 mt-2">À livrer bientôt</p>
        </div>

        <!-- Total Dépensé -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Total Dépensé</p>
            <p class="text-3xl font-bold text-secondary-600 mt-2">{{ number_format($montantTotal, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-2">FCFA</p>
        </div>

        <!-- Dernier Achat -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Dernier Achat</p>
            <p class="text-3xl font-bold text-danger-600 mt-2">
                @if($commandesRecentes->first())
                    {{ $commandesRecentes->first()->created_at->format('d/m') }}
                @else
                    —
                @endif
            </p>
            <p class="text-xs text-gray-500 mt-2">
                @if($commandesRecentes->first())
                    {{ $commandesRecentes->first()->created_at->diffForHumans() }}
                @else
                    Pas d'achat
                @endif
            </p>
        </div>
    </div>

    <!-- Dernières Commandes -->
    <div class="card p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Dernières Commandes</h2>

        @if($commandesRecentes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">N°</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Montant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Paiement</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Statut</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commandesRecentes as $commande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-semibold text-gray-900">#{{ $commande->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4 font-semibold text-primary-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs font-semibold text-gray-700">
                                        @if($commande->mode_paiement == 'mobile_money') Mobile Money
                                        @elseif($commande->mode_paiement == 'carte_bancaire') Carte
                                        @else À la livraison @endif
                                    </span>
                                </td>
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
        @else
            <div class="text-center py-12">
                <p class="text-gray-600 mb-4">Vous n'avez pas encore de commandes</p>
                <a href="{{ route('produits.catalogue') }}" class="btn-primary inline-block">
                    Commencer à acheter
                </a>
            </div>
        @endif
    </div>

        <!-- Raccourcis Rapides - Améliorés -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Accès Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('produits.catalogue') }}"
                   class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-lg p-8 text-center font-bold transition transform hover:scale-105">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">🛍️</div>
                    <span>Continuer les Achats</span>
                </a>
                <a href="{{ route('panier.index') }}"
                   class="group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl shadow-lg p-8 text-center font-bold transition transform hover:scale-105">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">🛒</div>
                    <span>Voir Panier</span>
                </a>
                <a href="{{ route('client.messages') }}"
                   class="group bg-gradient-to-br from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white rounded-xl shadow-lg p-8 text-center font-bold transition transform hover:scale-105">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">💬</div>
                    <span>Messages</span>
                </a>
                <a href="{{ route('client.commandes') }}"
                   class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl shadow-lg p-8 text-center font-bold transition transform hover:scale-105">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">📦</div>
                    <span>Mes Commandes</span>
                </a>
                <a href="{{ route('client.profil') }}"
                   class="group bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl shadow-lg p-8 text-center font-bold transition transform hover:scale-105">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">👤</div>
                    <span>Mon Profil</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
