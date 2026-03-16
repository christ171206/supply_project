@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('vendeur.bundles.index') }}" class="text-[12px] text-[#a0a09a] hover:underline mb-4 inline-block">
            ← Retour aux bundles
        </a>
        <h1 class="font-serif text-[32px] text-[#0a0a0a]">{{ $bundle->nom }}</h1>
        @if($bundle->description)
            <p class="text-[13px] text-[#666660] mt-2">{{ $bundle->description }}</p>
        @endif
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <!-- Statut -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Statut</p>
            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-medium
                {{ $bundle->statut === 'actif' && $bundle->isActive() ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $bundle->isActive() && $bundle->statut === 'actif' ? 'ACTIF' : 'INACTIF' }}
            </span>
        </div>

        <!-- Prix -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Prix bundle</p>
            <p class="font-mono text-[20px] font-bold text-[#0a0a0a]">
                {{ number_format($bundle->prix_bundle, 0, ',', ' ') }}
            </p>
            <p class="text-[11px] text-[#a0a09a] mt-1">FCFA</p>
        </div>

        <!-- Économies -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Économie</p>
            <p class="font-mono text-[24px] font-bold text-orange-600">
                -{{ $bundle->pourcentageEconomie() }}%
            </p>
        </div>
    </div>

    <!-- Disponibilité -->
    <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 mb-8">
        <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Disponibilité</p>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-[11px] text-[#666660]">Quantité disponible</p>
                <p class="font-mono text-[16px] font-bold text-[#0a0a0a] mt-1">
                    @if($bundle->quantite_disponible)
                        {{ $bundle->quantite_disponible }} stock{{ $bundle->quantite_disponible > 1 ? 's' : '' }}
                    @else
                        Illimité
                    @endif
                </p>
            </div>
            <div>
                <p class="text-[11px] text-[#666660]">Vendus</p>
                <p class="font-mono text-[16px] font-bold text-[#0a0a0a] mt-1">{{ $bundle->quantite_vendues ?? 0 }}</p>
            </div>
            <div>
                <p class="text-[11px] text-[#666660]">Disponible</p>
                <p class="font-mono text-[16px] font-bold text-[#0a0a0a] mt-1">
                    @if($bundle->quantite_disponible)
                        {{ $bundle->quantite_disponible - ($bundle->quantite_vendues ?? 0) }}
                    @else
                        ∞
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Période -->
    <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 mb-8">
        <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Période</p>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-[11px] text-[#a0a09a] mb-1">Début</p>
                <p class="font-mono text-[13px]">{{ $bundle->date_debut->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-[11px] text-[#a0a09a] mb-1">Fin</p>
                <p class="font-mono text-[13px]">{{ $bundle->date_fin->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        @if($bundle->isActive())
            <p class="text-[12px] text-orange-600 font-medium mt-3">
                ⏱ {{ $bundle->joursRestants() }} jour{{ $bundle->joursRestants() > 1 ? 's' : '' }} restant{{ $bundle->joursRestants() > 1 ? 's' : '' }}
            </p>
        @endif
    </div>

    <!-- Produits du Bundle -->
    <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 mb-8">
        <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Produits inclus ({{ $bundle->produits->count() }})</h2>

        <table class="w-full text-[12px]">
            <thead>
                <tr class="border-b border-[#e0e0dc]">
                    <th class="text-left py-3 px-3 font-medium text-[#a0a09a] uppercase text-[10px]">Nom</th>
                    <th class="text-right py-3 px-3 font-medium text-[#a0a09a] uppercase text-[10px]">Prix unit.</th>
                    <th class="text-center py-3 px-3 font-medium text-[#a0a09a] uppercase text-[10px]">Quantité</th>
                    <th class="text-right py-3 px-3 font-medium text-[#a0a09a] uppercase text-[10px]">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bundle->produits as $produit)
                    <tr class="border-b border-[#e0e0dc]">
                        <td class="py-3 px-3 text-[#0a0a0a]">{{ $produit->nom }}</td>
                        <td class="py-3 px-3 text-right font-mono text-[#0a0a0a]">
                            {{ number_format($produit->prix, 0, ',', ' ') }}
                        </td>
                        <td class="py-3 px-3 text-center font-mono text-[#0a0a0a]">
                            {{ $produit->pivot->quantite }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-[#0a0a0a] font-bold">
                            {{ number_format($produit->prix * $produit->pivot->quantite, 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-[#f7f7f5]">
                    <td colspan="3" class="py-3 px-3 text-right font-medium text-[#0a0a0a]">Total original:</td>
                    <td class="py-3 px-3 text-right font-mono text-[#0a0a0a] font-bold line-through text-[#a0a09a]">
                        {{ number_format($bundle->getPrixTotalOriginal(), 0, ',', ' ') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="py-3 px-3 text-right font-medium text-[#0a0a0a]">Prix proposé:</td>
                    <td class="py-3 px-3 text-right font-mono text-[20px] font-bold text-[#0a0a0a]">
                        {{ number_format($bundle->prix_bundle, 0, ',', ' ') }}
                    </td>
                </tr>
                <tr class="border-t-2 border-[#0a0a0a] bg-orange-50">
                    <td colspan="3" class="py-3 px-3 text-right font-serif text-[#0a0a0a] font-bold">Économie client:</td>
                    <td class="py-3 px-3 text-right font-mono text-[18px] font-bold text-orange-600">
                        -{{ number_format($bundle->getPrixTotalOriginal() - $bundle->prix_bundle, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Boutons d'actions -->
    <div class="flex gap-3">
        <a href="{{ route('vendeur.bundles.edit', $bundle->id) }}"
           class="flex-1 px-4 py-3 border border-[#0a0a0a] rounded-lg text-[13px] font-medium text-[#0a0a0a] hover:bg-[#f7f7f5] transition-colors text-center">
            Modifier
        </a>

        <form action="{{ route('vendeur.bundles.toggle', $bundle->id) }}" method="POST" class="flex-1">
            @csrf
            @method('PATCH')
            <button type="submit" class="w-full px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-orange-300 hover:text-orange-600 transition-colors">
                {{ $bundle->archive ? 'Réactiver' : 'Archiver' }}
            </button>
        </form>

        <form action="{{ route('vendeur.bundles.destroy', $bundle->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Supprimer ce bundle ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-4 py-3 border border-red-300 rounded-lg text-[13px] font-medium text-red-600 hover:bg-red-50 transition-colors">
                Supprimer
            </button>
        </form>
    </div>
</div>
@endsection
