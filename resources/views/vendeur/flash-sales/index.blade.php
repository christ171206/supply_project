@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Ventes Flash</h1>
                <p class="text-[13px] text-[#a0a09a]">Créez des promotions temps limité sur vos catégories</p>
            </div>
            <a href="{{ route('vendeur.flash-sales.create') }}"
               class="flex items-center gap-2 bg-[#0a0a0a] text-white px-4 py-2.5 rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouvelle vente
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="text-[13px] text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if($flashSales->count() > 0)
        <div class="space-y-4">
            @foreach($flashSales as $sale)
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-5 hover:border-[#0a0a0a] transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-medium text-[#0a0a0a]">{{ $sale->categorie->nom }}</h3>
                                <span class="text-[11px] font-mono font-medium px-2 py-1 rounded
                                    {{ $sale->isActive() ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $sale->isActive() ? 'ACTIF' : 'INACTIF' }}
                                </span>
                            </div>
                            <p class="text-[13px] text-[#0a0a0a] font-serif">-{{ $sale->pourcentage_reduction }}%</p>
                            <p class="text-[11px] text-[#a0a09a] mt-2">
                                Du {{ $sale->date_debut->format('d M') }} au {{ $sale->date_fin->format('d M Y') }}
                                @if($sale->isActive())
                                    <span class="font-medium text-orange-600">• {{ $sale->joursRestants() }} jours restants</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('vendeur.flash-sales.show', $sale->id) }}"
                               class="px-3 py-1.5 text-[11px] text-[#666660] border border-[#e0e0dc] rounded-md hover:border-[#0a0a0a] transition-colors">
                                Détails
                            </a>
                            <a href="{{ route('vendeur.flash-sales.edit', $sale->id) }}"
                               class="px-3 py-1.5 text-[11px] text-[#666660] border border-[#e0e0dc] rounded-md hover:border-[#0a0a0a] transition-colors">
                                Éditer
                            </a>
                            <form action="{{ route('vendeur.flash-sales.destroy', $sale->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-[11px] text-red-600 border border-red-200 rounded-md hover:bg-red-50 transition-colors">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $flashSales->links() }}
    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-12 text-center">
            <svg class="w-12 h-12 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><tag x1="12" y1="5" x2="12" y2="19"/></svg>
            <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Aucune vente flash</h2>
            <p class="text-[13px] text-[#a0a09a] font-light mb-6">Créez votre première promotion temps limité</p>
            <a href="{{ route('vendeur.flash-sales.create') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                Créer une vente flash
            </a>
        </div>
    @endif
</div>
@endsection
