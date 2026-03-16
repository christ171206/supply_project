@extends('vendeur.layout-dashboard')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-8">
            <div>
                <a href="{{ route('vendeur.promo-codes.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-4">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Retour
                </a>
                <h1 class="font-serif text-[32px] text-[#0a0a0a]">{{ $promoCode->code }}</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('vendeur.promo-codes.edit', $promoCode->id) }}"
                   class="flex items-center gap-2 px-3.5 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[12px] font-medium hover:opacity-85 transition-opacity">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Modifier
                </a>
                <form action="{{ route('vendeur.promo-codes.duplicate', $promoCode->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3.5 py-2.5 bg-white border border-[#e0e0dc] text-[#666660] rounded-lg text-[12px] font-medium hover:border-[#0a0a0a] transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 4v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4m-6 15H6a2 2 0 0 1-2-2V9m0-3h6V3h6"/></svg>
                        Dupliquer
                    </button>
                </form>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[13px] text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-3 gap-4 mb-8">
            {{-- Carte réduction --}}
            <div class="bg-gradient-to-br from-[#0a0a0a] to-[#2a2a28] text-white rounded-xl p-6">
                <div class="text-[11px] text-white/60 font-light mb-2">Réduction</div>
                <div class="text-[36px] font-mono font-medium mb-1">
                    @if($promoCode->type_reduction === 'pourcentage')
                        {{ $promoCode->taux_reduction }}%
                    @else
                        {{ number_format($promoCode->taux_reduction, 0, ',', ' ') }} FCFA
                    @endif
                </div>
                @if($promoCode->montant_maximum)
                    <div class="text-[11px] text-white/60 font-light">Max: {{ number_format($promoCode->montant_maximum, 0, ',', ' ') }} FCFA</div>
                @endif
            </div>

            {{-- Carte utilisations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6">
                <div class="text-[11px] text-[#a0a09a] font-light mb-2">Utilisations</div>
                @if($promoCode->max_utilisations)
                    <div class="text-[28px] font-mono font-medium text-[#0a0a0a] mb-2">
                        {{ $promoCode->utilisations }}<span class="text-[14px] font-light text-[#a0a09a]">/{{ $promoCode->max_utilisations }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-[#efefed] rounded-full overflow-hidden mb-1">
                        <div class="bg-[#0a0a0a] h-full" style="width: {{ $promoCode->pourcentageUtilisation() }}%"></div>
                    </div>
                    <div class="text-[11px] text-[#a0a09a] font-light">{{ $promoCode->pourcentageUtilisation() }}% utilisé</div>
                @else
                    <div class="text-[28px] font-mono font-medium text-[#0a0a0a] mb-2">{{ $promoCode->utilisations }}</div>
                    <div class="text-[11px] text-[#a0a09a] font-light">Illimité</div>
                @endif
            </div>

            {{-- Carte validité --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6">
                <div class="text-[11px] text-[#a0a09a] font-light mb-2">Validité</div>
                <div class="space-y-1">
                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $promoCode->date_debut->format('d/m/Y') }}</div>
                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $promoCode->date_fin->format('d/m/Y') }}</div>
                    @if($promoCode->joursRestants() > 0)
                        <div class="text-[11px] text-green-600 font-light">{{ $promoCode->joursRestants() }} jours restants</div>
                    @else
                        <div class="text-[11px] text-red-600 font-light">Expiré</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="grid grid-cols-2 gap-4 mb-8">
            {{-- Infos principales --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6">
                <div class="border-b border-[#efefed] pb-4 mb-4">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Informations</h3>
                </div>

                <div class="space-y-4">
                    @if($promoCode->description)
                        <div>
                            <div class="text-[11px] text-[#a0a09a] mb-1">Description</div>
                            <div class="text-[13px] text-[#0a0a0a]">{{ $promoCode->description }}</div>
                        </div>
                    @endif

                    <div>
                        <div class="text-[11px] text-[#a0a09a] mb-1">Type de réduction</div>
                        <div class="text-[13px] text-[#0a0a0a]">
                            {{ $promoCode->type_reduction === 'pourcentage' ? 'Pourcentage (%)' : 'Montant fixe (FCFA)' }}
                        </div>
                    </div>

                    @if($promoCode->montant_minimum)
                        <div>
                            <div class="text-[11px] text-[#a0a09a] mb-1">Montant minimum d'achat</div>
                            <div class="text-[13px] font-mono text-[#0a0a0a]">{{ number_format($promoCode->montant_minimum, 0, ',', ' ') }} FCFA</div>
                        </div>
                    @endif

                    <div>
                        <div class="text-[11px] text-[#a0a09a] mb-1">Statut</div>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2 py-1 rounded {{ $promoCode->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($promoCode->statut) }}
                        </span>
                        @if($promoCode->archive)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2 py-1 rounded bg-gray-100 text-gray-800 ml-2">
                                📦 Archivé
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Produits ciblés --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6">
                <div class="border-b border-[#efefed] pb-4 mb-4">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Produits ciblés</h3>
                </div>

                @if($promoCode->produits()->count() > 0)
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($promoCode->produits as $produit)
                            <div class="p-2.5 bg-[#f7f7f5] rounded flex items-start justify-between">
                                <div>
                                    <div class="text-[12px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                                    <div class="text-[11px] text-[#a0a09a]">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[13px] text-[#a0a09a]">S'applique à tous les achats</p>
                @endif
            </div>
        </div>

        {{-- Utilisations --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#efefed]">
                <h3 class="text-[14px] font-medium text-[#0a0a0a]">Historique d'utilisations</h3>
            </div>

            @if($utilisations->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#efefed]">
                            <th class="text-left px-6 py-3 text-[11px] font-medium text-[#a0a09a] uppercase tracking-[0.08em]">Client</th>
                            <th class="text-left px-6 py-3 text-[11px] font-medium text-[#a0a09a] uppercase tracking-[0.08em]">Commande</th>
                            <th class="text-left px-6 py-3 text-[11px] font-medium text-[#a0a09a] uppercase tracking-[0.08em]">Réduction</th>
                            <th class="text-left px-6 py-3 text-[11px] font-medium text-[#a0a09a] uppercase tracking-[0.08em]">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($utilisations as $util)
                            <tr class="border-b border-[#efefed] hover:bg-[#f7f7f5] transition-colors">
                                <td class="px-6 py-3">
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $util->user->name }}</div>
                                    <div class="text-[11px] text-[#a0a09a]">{{ $util->user->email }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('commandes.show', $util->commande->id) }}" class="text-[13px] font-mono text-blue-600 hover:underline">
                                        #{{ $util->commande->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="font-mono text-[13px] font-medium text-[#0a0a0a]">
                                        {{ number_format($util->montant_reduction, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="text-[12px] text-[#a0a09a]">{{ $util->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-[#efefed]">
                    {{ $utilisations->links() }}
                </div>
            @else
                <div class="px-6 py-10 text-center">
                    <p class="text-[13px] text-[#a0a09a]">Aucune utilisation enregistrée</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
