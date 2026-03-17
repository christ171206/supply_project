@extends('layouts.admin')

@section('content')
<div class="max-w-[1000px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-3xl text-[#0a0a0a]">{{ $offer->name }}</h1>
            <p class="text-[13px] text-[#a0a09a] mt-2">{{ $offer->description }}</p>
        </div>
        <span class="px-3 py-1 bg-{{ $stats['is_active'] ? 'green' : 'gray' }}-100 text-{{ $stats['is_active'] ? 'green' : 'gray' }}-700 rounded-full text-[12px] font-medium">
            {{ $stats['is_active'] ? '✓ Active' : '✗ Inactive' }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Offer Details --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Détails de l'Offre</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Type</div>
                        <div class="text-[14px] font-medium text-[#0a0a0a]">{{ $offer->getTypeLabel() }}</div>
                    </div>

                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Valeur</div>
                        <div class="text-[14px] font-bold text-green-600">
                            @if($offer->type === 'discount_percent')
                                -{{ $offer->value }}%
                            @else
                                -{{ number_format($offer->value, 0, ',', ' ') }} FCFA
                            @endif
                        </div>
                    </div>

                    @if($offer->max_discount)
                        <div>
                            <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Réduction Max</div>
                            <div class="text-[14px] font-medium text-[#0a0a0a]">{{ number_format($offer->max_discount, 0, ',', ' ') }} FCFA</div>
                        </div>
                    @endif

                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Cible</div>
                        <div class="text-[14px] font-medium text-[#0a0a0a]">{{ $offer->getTargetDescription() }}</div>
                    </div>
                </div>
            </div>

            {{-- Conditions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Conditions</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Montant Minimum</div>
                        <div class="text-[14px] font-medium text-[#0a0a0a]">
                            {{ $offer->min_purchase > 0 ? number_format($offer->min_purchase, 0, ',', ' ') . ' FCFA' : 'Aucun' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Quantité Minimale</div>
                        <div class="text-[14px] font-medium text-[#0a0a0a]">{{ $offer->min_quantity }}</div>
                    </div>
                </div>
            </div>

            {{-- Validity Period --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Période de Validité</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-[#0a0a0a]">Début</span>
                        <span class="font-mono font-bold">{{ $offer->start_date->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-[#0a0a0a]">Fin</span>
                        <span class="font-mono font-bold">{{ $offer->end_date->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="pt-3 border-t border-[#e0e0dc] flex items-center justify-between">
                        <span class="text-[13px] text-[#a0a09a]">Jours restants</span>
                        <span class="font-bold text-{{ $stats['days_remaining'] > 7 ? '[#0a0a0a]' : 'red-600' }}">
                            {{ $stats['days_remaining'] }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Stats Card --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="font-serif text-[16px] text-[#0a0a0a] mb-4">Performance</h3>

                <div class="space-y-4">
                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Utilisations</div>
                        <div class="font-mono text-[24px] font-bold text-[#0a0a0a]">{{ $stats['usage_count'] }}</div>
                    </div>

                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Réduction donnée</div>
                        <div class="font-mono text-[18px] font-bold text-green-600">
                            {{ number_format($stats['total_discount'], 0, ',', ' ') }}<span class="text-[12px]"> FCFA</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-[12px] font-medium text-[#a0a09a] mb-1">Réduction moyenne</div>
                        <div class="font-mono text-[16px] font-bold text-[#0a0a0a]">
                            {{ number_format($stats['avg_discount'], 0, ',', ' ') }}<span class="text-[12px]"> FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Created Info --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="font-serif text-[16px] text-[#0a0a0a] mb-3">Admin</h3>
                <div class="text-[13px]">
                    <div class="text-[#0a0a0a] font-medium">{{ $offer->creator->nom }}</div>
                    <div class="text-[#a0a09a] text-[12px]">{{ $offer->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-2">
                <a href="{{ route('admin.global-offers.edit', $offer) }}" class="w-full block px-4 py-2.5 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:bg-[#2a2a28] text-center">
                    Modifier
                </a>
                <button onclick="duplicateOffer()" class="w-full px-4 py-2.5 bg-[#f7f7f5] text-[#0a0a0a] text-[13px] font-medium rounded-lg hover:bg-[#e0e0dc]">
                    Dupliquer
                </button>
                <form action="{{ route('admin.global-offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-100 text-red-600 text-[13px] font-medium rounded-lg hover:bg-red-200">
                        Supprimer
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

<script>
async function duplicateOffer() {
    try {
        const response = await fetch('{{ route("admin.global-offers.duplicate", $offer) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}
</script>

@endsection
