@extends('vendeur.layout-dashboard')

@section('vendeur-content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header avec retour -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('vendeur.commandes') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition mb-4">
                    ← Retour aux commandes
                </a>
                <h1 class="text-3xl font-bold text-gray-900">📋 Commande #{{ $commande->id }}</h1>
                <p class="text-gray-600 mt-1">
                    Reçue le {{ $commande->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>

        <!-- Statut et info client -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <!-- Infos Commande -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Information Commande</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">ID Commande</dt>
                        <dd class="text-base font-bold text-gray-900">#{{ $commande->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Montant Total</dt>
                        <dd class="text-2xl font-bold text-blue-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Statut</dt>
                        <dd>
                            @php
                                $statusColor = match($commande->statut) {
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'acceptee' => 'bg-blue-100 text-blue-800',
                                    'expediee' => 'bg-green-100 text-green-800',
                                    'annulee' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusIcon = match($commande->statut) {
                                    'en_attente' => '📋',
                                    'acceptee' => '✅',
                                    'expediee' => '🚚',
                                    'annulee' => '❌',
                                    default => '❓'
                                };
                            @endphp
                            <span class="inline-block {{ $statusColor }} px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $statusIcon }} {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Date</dt>
                        <dd class="text-gray-900">{{ $commande->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Infos Client -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Information Client</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Nom Client</dt>
                        <dd class="text-base font-bold text-gray-900">{{ $commande->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Email</dt>
                        <dd class="text-gray-900 break-words">{{ $commande->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Adresse Livraison</dt>
                        <dd class="text-gray-900">{{ $commande->adresse_livraison ?? 'Non spécifiée' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Téléphone</dt>
                        <dd class="text-gray-900">{{ $commande->telephone ?? 'Non spécifié' }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        <!-- Articles commandés -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📦 Articles Commandés</h3>

            @if($lignes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Produit</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">Prix Unitaire</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">Quantité</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Sous-Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lignes as $ligne)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $ligne->produit->nom }}</div>
                                        <p class="text-xs text-gray-600">{{ $ligne->produit->categorie->nom ?? 'N/A' }}</p>
                                    </td>
                                    <td class="py-3 px-4 text-center text-gray-900">
                                        {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="py-3 px-4 text-center text-gray-900 font-medium">
                                        {{ $ligne->quantite }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-900">
                                        {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center py-4">Aucun article dans cette commande.</p>
            @endif
        </div>

        <!-- Paiement et Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Détails Paiement -->
            @if($payment)
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">💳 Paiement</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Méthode</dt>
                            <dd class="text-gray-900 font-medium">{{ ucfirst($payment->methode) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Montant Payé</dt>
                            <dd class="text-lg font-bold text-green-600">{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Statut Paiement</dt>
                            <dd>
                                @php
                                    $paymentStatusColor = match($payment->statut) {
                                        'completed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-block {{ $paymentStatusColor }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($payment->statut) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Date Paiement</dt>
                            <dd class="text-gray-900">{{ $payment->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <p class="text-yellow-700 font-medium flex items-center gap-2"><x-icon name="alert-circle" class="w-4 h-4" /> Aucune information de paiement</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-icon name="zap" class="w-5 h-5 text-yellow-500" /> Actions</h3>
                <div class="space-y-3">
                    @if($commande->statut === 'en_attente')
                        <form action="#" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm">
                                ✅ Accepter la Commande
                            </button>
                        </form>
                    @endif

                    @if(in_array($commande->statut, ['en_attente', 'acceptee']))
                        <form action="#" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm">
                                🚚 Marquer comme Expédiée
                            </button>
                        </form>
                    @endif

                    @if($commande->statut !== 'annulee')
                        <form action="#" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm">
                                ❌ Annuler la Commande
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('client.commande-detail', $commande->id) }}" class="block w-full text-center bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-lg font-medium transition duration-200 shadow-sm">
                        👁️ Voir comme Client
                    </a>
                </div>
            </div>

        </div>

        <!-- Conseil -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <p class="text-blue-700 text-sm">
                💡 <strong>Conseil :</strong> Acceptez rapidement les commandes et indiquez le statut d'expédition pour satisfaire vos clients et augmenter vos notes.
            </p>
        </div>

    </div>
</div>
@endsection
