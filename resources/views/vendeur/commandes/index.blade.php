@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-serif text-[#0a0a0a] mb-2">Commandes Reçues</h1>
        <p class="text-[13px] text-[#666660] font-light">Gestion et suivi de vos commandes clients</p>
    </div>

    <!-- Filtres et Recherche -->
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-4 mb-8">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher par n° commande ou client..."
                   class="flex-1 min-w-xs px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] transition text-[13px]"
                   value="{{ request('search') }}">

            <select name="statut" class="px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] transition text-[13px] text-[#0a0a0a]">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                <option value="expediee" {{ request('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-medium text-[13px]">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau des commandes -->
    @if(isset($derniereCommandes) && $derniereCommandes->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">N° Commande</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Client</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Produits</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Montant</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Date</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Statut</th>
                        <th class="px-6 py-4 text-left text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @foreach($derniereCommandes as $commande)
                        <tr class="hover:bg-[#f7f7f5] transition">
                            <td class="px-6 py-4 text-[13px] font-mono font-bold text-[#0a0a0a]">#{{ $commande->id }}</td>
                            <td class="px-6 py-4 text-[13px]">
                                <div>
                                    <p class="font-medium text-[#0a0a0a]">{{ $commande->user->name ?? 'N/A' }}</p>
                                    <p class="text-[11px] text-[#a0a09a]">{{ $commande->user->email ?? '' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#0a0a0a]">
                                {{ $commande->ligneCommandes->count() }} article(s)
                            </td>
                            <td class="px-6 py-4 text-[13px] font-mono font-bold text-[#0a0a0a]">
                                {{ number_format($commande->total, 0, ',', ' ') }} CFA
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#666660]">
                                {{ $commande->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-[13px]">
                                @php
                                    $colors = [
                                        'en_attente' => 'bg-[#fef2f2] text-[#dc2626]',
                                        'confirmee' => 'bg-[#fef3c7] text-[#92400e]',
                                        'expediee' => 'bg-[#f0fdf4] text-[#15803d]',
                                        'livree' => 'bg-[#f0fdf4] text-[#15803d]'
                                    ];
                                    $labels = [
                                        'en_attente' => 'En Attente',
                                        'confirmee' => 'Confirmée',
                                        'expediee' => 'Expédiée',
                                        'livree' => 'Livrée'
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 rounded text-xs font-medium {{ $colors[$commande->statut] ?? 'bg-[#f7f7f5] text-[#a0a09a]' }}">
                                    {{ $labels[$commande->statut] ?? $commande->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[13px]">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('vendeur.commandes.show', $commande->id) }}"
                                       class="inline-block px-3 py-2 bg-[#0a0a0a] text-white rounded hover:opacity-85 transition font-medium text-xs whitespace-nowrap">
                                        Voir
                                    </a>

                                    @if(in_array($commande->statut, ['en_attente', 'annulee']))
                                        <form action="{{ route('vendeur.commandes.delete', $commande->id) }}" method="POST" class="inline"
                                              data-confirm="Êtes-vous sûr de vouloir supprimer cette commande? Cette action est irréversible."
                                              data-confirm-title="Supprimer la commande"
                                              data-confirm-type="danger"
                                              data-confirm-button="Supprimer">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 border border-[#dc2626] text-[#dc2626] rounded hover:bg-[#fef2f2] transition font-medium text-xs whitespace-nowrap">
                                                Supprimer
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
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-12 text-center">
            <h3 class="text-2xl font-serif text-[#0a0a0a] mb-2">Aucune commande trouvée</h3>
            <p class="text-[13px] text-[#666660]">Vous n'avez pas encore reçu de commandes</p>
        </div>
    @endif
</div>
@endsection
