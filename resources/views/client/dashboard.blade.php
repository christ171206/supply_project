@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Moderne -->
        <div class="mb-12">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-3xl shadow-lg">
                    <x-icon name="bar-chart-2" class="w-8 h-8 text-blue-600" />
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Tableau de Bord</h1>
                    <p class="text-gray-600 mt-2">Bienvenue, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span> 👋</p>
                </div>
            </div>
        </div>

        <!-- Bloc Résumé - Amélioré -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Commandes -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">📦 Total Commandes</p>
                        <p class="text-4xl font-bold text-blue-600 mt-3">{{ $commandesTotal }}</p>
                        <p class="text-xs text-gray-500 mt-2">Au total depuis votre inscription</p>
                    </div>
                </div>
            </div>

            <!-- Commandes en Cours -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium flex items-center gap-1"><x-icon name="clock" class="w-4 h-4" /> En Cours</p>
                        <p class="text-4xl font-bold text-yellow-500 mt-3">{{ $commandesEnCours }}</p>
                        <p class="text-xs text-gray-500 mt-2">À livrer bientôt</p>
                    </div>
                </div>
            </div>

            <!-- Total Dépensé -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">💰 Total Dépensé</p>
                        <p class="text-3xl font-bold text-green-600 mt-3">{{ number_format($montantTotal, 0, ',', ' ') }}</p>
                        <p class="text-xs text-gray-500 mt-2">FCFA</p>
                    </div>
                </div>
            </div>

            <!-- Dernier Achat -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">📅 Dernier Achat</p>
                        <p class="text-2xl font-bold text-purple-600 mt-3">
                            @if($commandesRecentes->first())
                                {{ $commandesRecentes->first()->created_at->format('d/m') }}
                            @else
                                —
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($commandesRecentes->first())
                                il y a {{ $commandesRecentes->first()->created_at->diffForHumans() }}
                            @else
                                Pas encore d'achat
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes 5 Dernières Commandes - Amélioré -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-12 hover:shadow-xl transition">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-lg">
                    📦
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Mes 5 Dernières Commandes</h2>
            </div>

            @if($commandesRecentes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">N° Commande</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Montant</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Paiement</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($commandesRecentes as $commande)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900">#{{ $commande->id }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 font-bold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($commande->mode_paiement == 'mobile_money') bg-blue-100 text-blue-800
                                            @elseif($commande->mode_paiement == 'carte_bancaire') bg-green-100 text-green-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            @if($commande->mode_paiement == 'mobile_money') <x-icon name="smartphone" class="w-4 h-4 inline mr-1" /> Mobile Money
                                            @elseif($commande->mode_paiement == 'carte_bancaire') 💳 Carte
                                            @else 🚚 À la livraison @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            @if($commande->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                            @elseif($commande->statut == 'confirmee') bg-blue-100 text-blue-800
                                            @elseif($commande->statut == 'expediee') bg-indigo-100 text-indigo-800
                                            @elseif($commande->statut == 'livree') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @switch($commande->statut)
                                                @case('en_attente') <x-icon name="clock" class="w-4 h-4 inline mr-1" /> En attente @break
                                                @case('confirmee') <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Confirmée @break
                                                @case('expediee') 🚚 Expédiée @break
                                                @case('livree') ✓ Livrée @break
                                                @case('annulee') ❌ Annulée @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('client.commande-detail', $commande->id) }}" class="text-blue-600 hover:text-blue-700 font-bold hover:underline transition">
                                            Détails →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">Vous n'avez pas encore de commandes</p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition transform hover:scale-105">
                        🛍️ Commencer à acheter
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
