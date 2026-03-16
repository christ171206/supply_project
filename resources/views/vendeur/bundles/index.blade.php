@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Bundles</h1>
                <p class="text-[13px] text-[#a0a09a]">Créez des offres groupées pour booser vos ventes</p>
            </div>
            <a href="{{ route('vendeur.bundles.create') }}"
               class="flex items-center gap-2 bg-[#0a0a0a] text-white px-4 py-2.5 rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau Bundle
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="text-[13px] text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if($bundles->count() > 0)
        <div class="space-y-4">
            @foreach($bundles as $bundle)
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 hover:border-[#0a0a0a] transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-medium text-[#0a0a0a] mb-1">{{ $bundle->nom }}</h3>
                            <div class="flex items-center gap-3 text-[12px] mb-2">
                                <span class="font-mono text-[#0a0a0a] text-[14px]">{{ number_format($bundle->prix_bundle, 0, ',', ' ') }} FCFA</span>
                                @if($bundle->prix_original)
                                    <span class="text-[#a0a09a] line-through">{{ number_format($bundle->prix_original, 0, ',', ' ') }} FCFA</span>
                                    <span class="font-medium text-orange-600">-{{ $bundle->pourcentageEconomie() }}%</span>
                                @endif
                                <span class="text-[11px] font-mono px-2 py-1 rounded bg-gray-100 text-gray-600">
                                    {{ $bundle->produits->count() }} produits
                                </span>
                            </div>
                            <p class="text-[11px] text-[#a0a09a] mt-2">
                                Du {{ $bundle->date_debut->format('d M') }} au {{ $bundle->date_fin->format('d M Y') }}
                                @if($bundle->isActive())
                                    <span class="font-medium text-orange-600">• {{ $bundle->joursRestants() }} jours</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('vendeur.bundles.show', $bundle->id) }}"
                               class="px-3 py-1.5 text-[11px] text-[#666660] border border-[#e0e0dc] rounded-md hover:border-[#0a0a0a] transition-colors">
                                Détails
                            </a>
                            <a href="{{ route('vendeur.bundles.edit', $bundle->id) }}"
                               class="px-3 py-1.5 text-[11px] text-[#666660] border border-[#e0e0dc] rounded-md hover:border-[#0a0a0a] transition-colors">
                                Éditer
                            </a>
                            <form action="{{ route('vendeur.bundles.destroy', $bundle->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-[11px] text-red-600 border border-red-200 rounded-md hover:bg-red-50 transition-colors">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $bundles->links() }}
    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-12 text-center">
            <svg class="w-12 h-12 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M6 2h12a2 2 0 012 2v16a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z"/></svg>
            <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Aucun bundle</h2>
            <p class="text-[13px] text-[#a0a09a] font-light mb-6">Créez une offre groupée pour vos clients</p>
            <a href="{{ route('vendeur.bundles.create') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                Créer un bundle
            </a>
        </div>
    @endif
</div>
@endsection
