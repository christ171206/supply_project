@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">{{ $flashSale->categorie->nom }}</h1>
            <p class="text-[13px] text-[#a0a09a]">Vente Flash à -{{ $flashSale->pourcentage_reduction }}%</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('vendeur.flash-sales.edit', $flashSale->id) }}"
               class="px-4 py-2.5 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity">
                Éditer
            </a>
            <form action="{{ route('vendeur.flash-sales.destroy', $flashSale->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2.5 border border-red-200 text-red-600 text-[13px] font-medium rounded-lg hover:bg-red-50 transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Réduction</p>
            <p class="font-serif text-[28px] text-[#0a0a0a]">-{{ $flashSale->pourcentage_reduction }}%</p>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Statut</p>
            <p class="text-[13px] font-medium">
                <span class="inline-block px-2 py-1 rounded text-[11px]
                    {{ $flashSale->isActive() ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $flashSale->isActive() ? 'ACTIF' : 'INACTIF' }}
                </span>
            </p>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <p class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Temps restant</p>
            <p class="text-[13px] font-medium">
                @if($flashSale->isActive())
                    {{ $flashSale->joursRestants() }} jours
                @else
                    Expiré
                @endif
            </p>
        </div>
    </div>

    <!-- Produits affectés -->
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#efefed]">
            <h2 class="text-[13px] font-medium text-[#0a0a0a]">Produits affectés</h2>
        </div>

        @if($produits->count() > 0)
            <div class="divide-y divide-[#efefed]">
                @foreach($produits as $produit)
                    <div class="px-5 py-3.5 hover:bg-[#f7f7f5] transition-colors flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</p>
                            <p class="text-[11px] text-[#a0a09a] mt-1">
                                <span class="font-mono">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                                →
                                <span class="font-mono font-medium text-orange-600">
                                    {{ number_format($flashSale->prixReduit($produit->prix), 0, ',', ' ') }} FCFA
                                </span>
                            </p>
                        </div>
                        <span class="text-[11px] text-[#a0a09a]">Stock: {{ $produit->stock }}</span>
                    </div>
                @endforeach
            </div>

            {{ $produits->links() }}
        @else
            <div class="px-5 py-8 text-center text-[13px] text-[#a0a09a]">
                Aucun produit dans cette catégorie
            </div>
        @endif
    </div>
</div>
@endsection
