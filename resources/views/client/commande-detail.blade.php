@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Retour -->
        <a href="{{ route('client.commandes') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-8">
            ← Retour aux commandes
        </a>

        <!-- Header -->
        <div class="mb-12 bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">COMMANDE</p>
                    <h1 class="text-4xl font-bold text-gray-900 mt-2">N°{{ $commande->id }}</h1>
                    <p class="text-gray-600 mt-2">{{ $commande->created_at->format('d M Y à H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-2 rounded-full text-sm font-bold inline-block
                        @if($commande->statut === 'livree') bg-green-100 text-green-800
                        @elseif($commande->statut === 'expediee') bg-blue-100 text-blue-800
                        @elseif($commande->statut === 'confirmee') bg-yellow-100 text-yellow-800
                        @elseif($commande->statut === 'annulee') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @switch($commande->statut)
                            @case('en_attente') <x-icon name="clock" class="w-4 h-4 inline mr-1" /> En attente @break
                            @case('confirmee') <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Confirmée @break
                            @case('expediee') 🚚 Expédiée @break
                            @case('livree') ✓ Livrée @break
                            @case('annulee') ❌ Annulée @break
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Grille Principale -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Colonne Gauche - Infos et Articles -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Infos Commande -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-lg">
                            📅
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Informations de la Commande</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Date de Commande</p>
                            <p class="text-lg font-bold text-gray-900 mt-2">{{ $commande->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Mode de Paiement</p>
                            <p class="text-lg font-bold text-gray-900 mt-2">
                                @if($commande->mode_paiement == 'mobile_money') <x-icon name="smartphone" class="w-4 h-4 inline mr-1" /> Mobile Money
                                @elseif($commande->mode_paiement == 'carte_bancaire') 💳 Carte Bancaire
                                @else 🚚 À la livraison @endif
                            </p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Statut Paiement</p>
                            <p class="text-lg font-bold mt-2 flex items-center gap-2">
                                @if($commande->paiement_confirme)
                                    <span class="text-green-600">✓ Payé</span>
                                @else
                                    <span class="text-orange-600 flex items-center gap-1"><x-icon name="clock" class="w-4 h-4" /> En attente</span>
                                @endif
                            </p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Montant Total</p>
                            <p class="text-lg font-bold text-green-600 mt-2">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Articles -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-lg">
                            📦
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Articles Commandés</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Produit</th>
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                                    <th class="text-center py-3 px-4 font-bold text-gray-700">Quantité</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">P.U.</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commande->ligneCommandes as $ligne)
                                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition">
                                        <td class="py-4 px-4">
                                            <a href="{{ route('produits.show', $ligne->produit_id) }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                                                {{ $ligne->produit->nom ?? 'Produit supprimé' }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($ligne->produit && $ligne->produit->vendeur)
                                                <span class="text-sm text-gray-600 flex items-center gap-1">
                                                    <span>🏪</span>
                                                    {{ $ligne->produit->vendeur->name }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center font-semibold">{{ $ligne->quantite }}</td>
                                        <td class="py-4 px-4 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                        <td class="py-4 px-4 text-right font-bold text-green-600">{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">Aucun article</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Adresse Livraison -->
                @if($commande->adresse_livraison)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                                🏠
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Adresse de Livraison</h3>
                        </div>
                        <p class="text-gray-700 whitespace-pre-wrap bg-orange-50 p-4 rounded-lg">{{ $commande->adresse_livraison }}</p>
                    </div>
                @endif
            </div>

            <!-- Colonne Droite - Résumé -->
            <div class="lg:col-span-1">
                <!-- Résumé Paiement -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Résumé Financier</h3>

                    <div class="space-y-4 pb-6 border-b border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($commande->total * 0.85, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de port:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($commande->total * 0.15, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div class="pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-3xl font-bold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <p class="text-blue-900 text-sm font-semibold">💡 Besoin d'aide?</p>
                    <a href="{{ route('client.messages') }}" class="mt-4 block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-bold transition">
                        💬 Contacter le vendeur
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
