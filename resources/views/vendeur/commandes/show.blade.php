@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- Messages d'alerte -->
    @if($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded-lg mb-6" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-lg mb-6" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- En-tête avec retour -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('vendeur.commandes') }}" class="text-primary-600 hover:text-primary-700 font-semibold flex items-center gap-2">
            ← Retour aux commandes
        </a>
    </div>

    <!-- Titre et info principale -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Détail Commande #{{ $commande->id }}</h1>
        <p class="text-gray-600">Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale: Produits et détails -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Articles commandés -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">📦 Articles Commandés</h2>

                @if($commande->ligneCommandes->count() > 0)
                    <div class="space-y-4">
                        @foreach($commande->ligneCommandes as $ligne)
                            @php
                                $produit = $ligne->produit;
                                // Vérifier si ce produit appartient au vendeur actuel
                                if($produit && $produit->user_id == auth()->id()) {
                            @endphp
                            <div class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary-300 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-1">{{ $produit->nom }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Catégorie: <span class="font-semibold">{{ $produit->categorie->nom ?? 'N/A' }}</span>
                                        </p>
                                        <p class="text-sm text-gray-700 mb-3">
                                            {{ Str::limit($produit->description, 100) }}
                                        </p>

                                        <div class="grid grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-600">Quantité</p>
                                                <p class="text-lg font-bold text-primary-600">{{ $ligne->quantite }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600">Prix unitaire</p>
                                                <p class="text-lg font-bold text-gray-900">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} CFA</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600">Sous-total</p>
                                                <p class="text-lg font-bold text-green-600">
                                                    {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} CFA
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                }
                            @endphp
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6">Aucun article</p>
                @endif
            </div>

            <!-- Paiement -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">💳 Informations de Paiement</h2>

                @if($commande->payment)
                    <div class="space-y-4">
                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">Methode</p>
                            <p class="font-bold text-gray-900">{{ ucfirst($commande->payment->methode_paiement) }}</p>
                        </div>
                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">Statut Paiement</p>
                            <p class="font-bold">
                                @if($commande->payment->statut == 'complete')
                                    <span class="text-green-600">✓ Complété</span>
                                @else
                                    <span class="text-orange-600">⏳ En attente</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">Référence Paiement</p>
                            <p class="font-bold text-gray-900">{{ $commande->payment->reference ?? 'N/A' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6">Pas d'information de paiement</p>
                @endif
            </div>
        </div>

        <!-- Colonne sidebar: Résumé et actions -->
        <div class="space-y-6">
            <!-- Résumé commande -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">📊 Résumé</h2>

                <div class="space-y-4">
                    <div class="flex justify-between pb-4 border-b-2 border-gray-200">
                        <p class="text-gray-700">Sous-total</p>
                        <p class="font-bold text-gray-900">{{ number_format($commande->total, 0, ',', ' ') }} CFA</p>
                    </div>

                    <div class="pt-4 border-t-2 border-gray-200">
                        <div class="flex justify-between">
                            <p class="text-lg font-bold text-gray-900">Total</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} CFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut et Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">⚙️ Statut</h2>

                @php
                    $colors = [
                        'en_attente' => 'bg-red-100 text-red-700',
                        'confirmee' => 'bg-yellow-100 text-yellow-700',
                        'expediee' => 'bg-blue-100 text-blue-700',
                        'livree' => 'bg-green-100 text-green-700'
                    ];
                    $labels = [
                        'en_attente' => '⏳ En Attente',
                        'confirmee' => '✓ Confirmée',
                        'expediee' => '📦 Expédiée',
                        'livree' => '✓ Livrée'
                    ];
                @endphp

                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold mb-2">STATUT ACTUEL</p>
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold {{ $colors[$commande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $labels[$commande->statut] ?? $commande->statut }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-600 font-semibold mb-2">PROGRESSION</p>
                            <div class="flex gap-1">
                                <div class="w-8 h-8 rounded-full {{ in_array($commande->statut, ['en_attente','confirmee','expediee','livree']) ? 'bg-red-500' : 'bg-gray-300' }} text-white text-xs flex items-center justify-center font-bold">1</div>
                                <div class="w-8 h-8 rounded-full {{ in_array($commande->statut, ['confirmee','expediee','livree']) ? 'bg-yellow-500' : 'bg-gray-300' }} text-white text-xs flex items-center justify-center font-bold">2</div>
                                <div class="w-8 h-8 rounded-full {{ in_array($commande->statut, ['expediee','livree']) ? 'bg-blue-500' : 'bg-gray-300' }} text-white text-xs flex items-center justify-center font-bold">3</div>
                                <div class="w-8 h-8 rounded-full {{ $commande->statut === 'livree' ? 'bg-green-500' : 'bg-gray-300' }} text-white text-xs flex items-center justify-center font-bold">4</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action pour changer le statut -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200 mb-6">
                    <p class="text-sm font-bold text-gray-900 mb-4">🎯 Actions Disponibles</p>
                    <div class="space-y-3">
                        @if($commande->statut == 'en_attente')
                            <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}" style="display: block;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="confirmee">
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition font-bold text-base shadow-lg hover:shadow-xl transform hover:scale-105">
                                    ✓ CONFIRMER LA COMMANDE
                                </button>
                                <p class="text-xs text-gray-700 mt-2">La commande passe du statut "En Attente" à "Confirmée"</p>
                            </form>
                        @endif

                    @if(in_array($commande->statut, ['confirmee']))
                        <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}" style="display: block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="expediee">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-bold text-base shadow-lg hover:shadow-xl transform hover:scale-105">
                                📦 EXPÉDIER LA COMMANDE
                            </button>
                            <p class="text-xs text-gray-700 mt-2">La commande passe du statut "Confirmée" à "Expédiée"</p>
                        </form>
                    @endif

                    @if(in_array($commande->statut, ['expediee']))
                        <form method="POST" action="{{ route('vendeur.commandes.update-status', $commande->id) }}" style="display: block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="livree">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition font-bold text-base shadow-lg hover:shadow-xl transform hover:scale-105">
                                ✓ MARQUER COMME LIVRÉE
                            </button>
                            <p class="text-xs text-gray-700 mt-2">La commande passe du statut "Expédiée" à "Livrée"</p>
                        </form>
                    @endif
                    </div>

                    <!-- Boutons d'annulation et suppression -->
                    @if($commande->statut !== 'livree' && $commande->statut !== 'annulee')
                        <div class="border-t-2 border-gray-200 pt-4 mt-4">
                            <form method="POST" action="{{ route('vendeur.commandes.cancel', $commande->id) }}" onsubmit="return confirm('Êtes-vous sûr d\'annuler cette commande? Le stock sera rétabli.');">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition font-bold text-base shadow-lg hover:shadow-xl">
                                    ⛔ Annuler la Commande
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(in_array($commande->statut, ['en_attente', 'annulee']))
                        <div class="border-t-2 border-gray-200 pt-4 mt-4">
                            <form method="POST" action="{{ route('vendeur.commandes.delete', $commande->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition font-bold text-base shadow-lg hover:shadow-xl">
                                    🗑️ Supprimer la Commande
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Infos Client -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">👤 Infos Client</h2>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 text-xs">Nom</p>
                        <p class="font-semibold text-gray-900">{{ $commande->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Email</p>
                        <p class="font-semibold text-gray-900">{{ $commande->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Téléphone</p>
                        <p class="font-semibold text-gray-900">{{ $commande->user->phone ?? 'Non fourni' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Adresse</p>
                        <p class="font-semibold text-gray-900">{{ $commande->user->address ?? 'Non fournie' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
