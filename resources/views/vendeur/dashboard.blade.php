@extends('vendeur.layout-dashboard')

@section('vendeur-content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Tableau de Bord</h1>
        <p class="text-gray-500 mt-2">Gérez votre boutique en ligne</p>
    </div>

    <!-- Statistiques de Performance -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistiques de Performance</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Chiffre d'affaires total -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Chiffre d'affaires</p>
                        <p class="text-3xl font-bold text-green-600 mt-3">{{ number_format($totalVentes, 0, ',', ' ') }}</p>
                        <p class="text-gray-500 text-xs mt-1">FCFA</p>
                    </div>
                    <div class="opacity-20"><x-icon name="dollar-sign" class="w-12 h-12 text-green-600" /></div>
                </div>
            </div>

            <!-- Nombre de commandes -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Commandes</p>
                        <p class="text-3xl font-bold text-blue-600 mt-3">{{ $nombreCommandes }}</p>
                        <p class="text-gray-500 text-xs mt-1">Total reçues</p>
                    </div>
                    <div class="opacity-20"><x-icon name="package" class="w-12 h-12 text-blue-600" /></div>
                </div>
            </div>

            <!-- Panier moyen -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Panier Moyen</p>
                        <p class="text-3xl font-bold text-purple-600 mt-3">{{ $nombreCommandes > 0 ? number_format($totalVentes / $nombreCommandes, 0, ',', ' ') : '0' }}</p>
                        <p class="text-gray-500 text-xs mt-1">FCFA</p>
                    </div>
                    <div class="opacity-20"><x-icon name="shopping-cart" class="w-12 h-12 text-purple-600" /></div>
                </div>
            </div>

            <!-- Taux de complétion -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Taux Complétion</p>
                        <p class="text-3xl font-bold text-cyan-600 mt-3">{{ $tauxCompletion }}%</p>
                        <p class="text-gray-500 text-xs mt-1">Commandes livrées</p>
                    </div>
                    <div class="opacity-20"><x-icon name="check-circle" class="w-12 h-12 text-cyan-600" /></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statut des Commandes -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Statut des Commandes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- En attente -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                <div class="flex items-center justify-between mb-3">
                    <x-icon name="clock" class="w-8 h-8 text-yellow-600" />
                    <span class="text-xs font-bold text-yellow-700 uppercase">En attente</span>
                </div>
                <p class="text-4xl font-bold text-yellow-600">{{ $commandesEnAttente }}</p>
                <p class="text-sm text-yellow-700 mt-2">À traiter rapidement</p>
            </div>

            <!-- Confirmées -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-3">
                    <x-icon name="check-circle" class="w-8 h-8 text-blue-600" />
                    <span class="text-xs font-bold text-blue-700 uppercase">Confirmées</span>
                </div>
                <p class="text-4xl font-bold text-blue-600">{{ $commandesConfirmees }}</p>
                <p class="text-sm text-blue-700 mt-2">Prêtes à expédier</p>
            </div>

            <!-- Expédiées -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                <div class="flex items-center justify-between mb-3">
                    <x-icon name="package" class="w-8 h-8 text-indigo-600" />
                    <span class="text-xs font-bold text-indigo-700 uppercase">Expédiées</span>
                </div>
                <p class="text-4xl font-bold text-indigo-600">{{ $commandesExpediees }}</p>
                <p class="text-sm text-indigo-700 mt-2">En route vers clients</p>
            </div>

            <!-- Livrées -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between mb-3">
                    <x-icon name="award" class="w-8 h-8 text-green-600" />
                    <span class="text-xs font-bold text-green-700 uppercase">Livrées</span>
                </div>
                <p class="text-4xl font-bold text-green-600">{{ $commandeslivrees }}</p>
                <p class="text-sm text-green-700 mt-2">Clients satisfaits</p>
            </div>
        </div>
    </div>

    <!-- Alerte Stock Critique -->
    @if($produitsStockFaible->count() > 0)
        <div class="bg-red-50 border-l-4 border-red-600 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <x-icon name="alert-circle" class="w-8 h-8 text-red-600 flex-shrink-0 mt-1" />
                <div class="flex-1">
                    <h3 class="font-bold text-red-900 mb-4">Alerte : Stock Critique Détecté!</h3>
                    <p class="text-red-800 mb-4">{{ $produitsStockFaible->count() }} produit(s) nécessitent un réapprovisionnement immédiat</p>

                    <div class="space-y-3">
                        @foreach($produitsStockFaible as $produit)
                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-red-200">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $produit->nom }}</p>
                                    <p class="text-sm text-gray-600">Stock: {{ $produit->stock }} / Min: {{ $produit->stock_minimum }}</p>
                                </div>
                                <a href="{{ route('vendeur.produits.edit', $produit->id) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition text-sm">
                                    <x-icon name="zap" class="w-4 h-4" /> Modifier
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions Rapides -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-icon name="zap" class="w-6 h-6 text-yellow-500" /> Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('vendeur.produits.create') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <x-icon name="plus-circle" class="w-8 h-8 text-blue-600 flex-shrink-0" />
                <div>
                    <p class="font-bold text-gray-900">Ajouter Produit</p>
                    <p class="text-sm text-gray-600">Créer un nouveau produit</p>
                </div>
            </a>
            <a href="{{ route('vendeur.produits.index') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <x-icon name="edit-2" class="w-8 h-8 text-green-600 flex-shrink-0" />
                <div>
                    <p class="font-bold text-gray-900">Gérer Produits</p>
                    <p class="text-sm text-gray-600">Modifier vos produits</p>
                </div>
            </a>
            <a href="{{ route('vendeur.commandes') }}" class="flex items-center gap-3 p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="text-3xl">📦</div>
                <div>
                    <p class="font-bold text-gray-900">Voir Commandes</p>
                    <p class="text-sm text-gray-600">Gérer vos commandes</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Dernières Commandes -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dernières Commandes</h2>

        @if($derniereCommandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Client</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">N° Commande</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Date</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($derniereCommandes as $commande)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="py-4 px-4">
                                    <span class="font-semibold text-gray-900">{{ $commande->user->name }}</span>
                                </td>
                                <td class="py-4 px-4 font-bold text-blue-600">#{{ $commande->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-4 px-4 font-semibold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        @if($commande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($commande->statut === 'confirmee') bg-blue-100 text-blue-800
                                        @elseif($commande->statut === 'expediee') bg-indigo-100 text-indigo-800
                                        @elseif($commande->statut === 'livree') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('vendeur.commandes.show', $commande->id) }}" class="text-blue-600 hover:text-blue-700 font-bold">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 py-8">Aucune commande reçue pour l'instant</p>
        @endif
    </div>

    <!-- Top Produits -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Top Produits - Les 5 Plus Vendus</h2>
        @if($topProduits->count() > 0)
            <div class="space-y-3">
                @foreach($topProduits as $index => $produit)
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">{{ $produit->nom }}</p>
                            <p class="text-sm text-gray-600">{{ $produit->categorie->nom }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-lg">{{ number_format($produit->ventes_total ?? 0, 0, ',', ' ') }} FCFA</p>
                            <p class="text-xs text-gray-500">{{ $produit->ventes_nombre ?? 0 }} ventes</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucune vente enregistrée pour l'instant</p>
            </div>
        @endif
    </div>

    <!-- Avis Clients Récents -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Avis Clients Récents</h2>
        @if($avisRecents->count() > 0)
            <div class="space-y-4">
                @foreach($avisRecents as $avis)
                    <div class="p-6 border border-gray-200 rounded-xl hover:shadow-lg transition bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $avis->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">📦 {{ $avis->produit->nom }}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex gap-1 justify-end mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl">{{ $i <= $avis->note ? '⭐' : '☆' }}</span>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500">{{ $avis->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{{ $avis->commentaire }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucun avis pour l'instant</p>
            </div>
        @endif
    </div>

    <!-- Conseil -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <p class="flex items-start gap-3">
            <span class="text-2xl">💡</span>
            <span class="text-blue-900"><strong>Conseil du Jour :</strong> Vérifiez régulièrement votre stock, répondez rapidement aux messages des clients et maintenez une excellente note. C'est la clé du succès sur Supply !</span>
        </p>
    </div>
</div>
@endsection
