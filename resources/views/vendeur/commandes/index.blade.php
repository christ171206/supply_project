@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">🛒 Commandes Reçues</h1>
        <p class="text-gray-600">Gestion et suivi de vos commandes clients</p>
    </div>

    <!-- Filtres et Recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher par n° commande ou client..." 
                   class="flex-1 min-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   value="{{ request('search') }}">
            
            <select name="statut" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                <option value="expediee" {{ request('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau des commandes -->
    @if(isset($derniereCommandes) && $derniereCommandes->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">N° Commande</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Client</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Produits</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Montant</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Statut</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($derniereCommandes as $commande)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-bold text-primary-600">#{{ $commande->id }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $commande->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600">{{ $commande->user->email ?? '' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $commande->ligneCommandes->count() }} article(s)
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-green-600">
                                {{ number_format($commande->total, 0, ',', ' ') }} CFA
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $commande->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
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
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $colors[$commande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $labels[$commande->statut] ?? $commande->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('vendeur.commandes.show', $commande->id) }}" 
                                       class="inline-block px-3 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold text-xs whitespace-nowrap">
                                        👁️ Détails
                                    </a>

                                    @if($commande->statut !== 'livree' && $commande->statut !== 'annulee')
                                        <form action="{{ route('vendeur.commandes.cancel', $commande->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr d\'annuler cette commande? Le stock sera rétabli.');">
                                            @csrf
                                            <button type="submit" class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold text-xs whitespace-nowrap">
                                                ⛔ Annuler
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($commande->statut, ['en_attente', 'annulee']))
                                        <form action="{{ route('vendeur.commandes.delete', $commande->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande? Cette action est irréversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-xs whitespace-nowrap">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Pas de commandes -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <p class="text-6xl mb-4">🛒</p>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune commande trouvée</h3>
            <p class="text-gray-600">Vous n'avez pas encore reçu de commandes</p>
        </div>
    @endif
</div>
@endsection
