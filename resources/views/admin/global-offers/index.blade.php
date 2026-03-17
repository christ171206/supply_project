@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-3xl text-[#0a0a0a] mb-1">Offres Globales</h1>
            <p class="text-[13px] text-[#a0a09a]">Gérez les réductions au niveau de la plateforme</p>
        </div>
        <a href="{{ route('admin.global-offers.create') }}" class="px-4 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
            + Créer Offre
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Total Offres</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Actives</div>
            <div class="font-mono text-[28px] font-bold text-green-600">{{ $stats['active'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">À Venir</div>
            <div class="font-mono text-[28px] font-bold text-blue-600">{{ $stats['upcoming'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Utilisations</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['total_usage'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Réductions Total</div>
            <div class="font-mono text-[12px] font-bold text-[#0a0a0a]">{{ number_format($stats['total_discount_given'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.global-offers.index') }}" class="px-3 py-2 text-[13px] font-medium border-b-2 border-[#0a0a0a] text-[#0a0a0a]">
            Toutes
        </a>
        <a href="{{ route('admin.global-offers.index', ['status' => 'active']) }}" class="px-3 py-2 text-[13px] font-medium border-b-2 border-transparent text-[#a0a09a] hover:text-[#0a0a0a]">
            Actives
        </a>
        <a href="{{ route('admin.global-offers.index', ['status' => 'upcoming']) }}" class="px-3 py-2 text-[13px] font-medium border-b-2 border-transparent text-[#a0a09a] hover:text-[#0a0a0a]">
            À Venir
        </a>
        <a href="{{ route('admin.global-offers.index', ['status' => 'expired']) }}" class="px-3 py-2 text-[13px] font-medium border-b-2 border-transparent text-[#a0a09a] hover:text-[#0a0a0a]">
            Expirées
        </a>
    </div>

    {{-- Offers List --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Nom</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Type</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Valeur</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Cible</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Période</th>
                        <th class="px-6 py-3 text-center text-[12px] font-medium text-[#0a0a0a]">Utilisations</th>
                        <th class="px-6 py-3 text-right text-[12px] font-medium text-[#0a0a0a]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($offers as $offer)
                        <tr class="hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-[13px] text-[#0a0a0a]">{{ $offer->name }}</div>
                                <div class="text-[11px] text-[#a0a09a] mt-1 truncate">{{ $offer->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-[12px]">
                                <span class="px-2 py-1 bg-[#f7f7f5] rounded text-[#0a0a0a] font-medium">
                                    {{ match($offer->type) {
                                        'discount_percent' => '% Réduction',
                                        'discount_fixed' => 'Montant fixe',
                                        'free_shipping' => 'Livraison gratuite',
                                        'buy_x_get_y' => 'Achetez X obtenez Y',
                                        'tiered_discount' => 'Réduction progressive',
                                        default => $offer->type
                                    } }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-[13px] text-[#0a0a0a]">
                                    @if($offer->type === 'discount_percent')
                                        -{{ $offer->value }}%
                                    @else
                                        -{{ number_format($offer->value, 0, ',', ' ') }} FCFA
                                    @endif
                                </div>
                                @if($offer->max_discount)
                                    <div class="text-[11px] text-[#a0a09a] mt-1">Max: {{ number_format($offer->max_discount, 0, ',', ' ') }} FCFA</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[12px] text-[#0a0a0a]">
                                    {{ match($offer->target_type) {
                                        'all' => '🌍 Tous les produits',
                                        'category' => '📁 Catégorie',
                                        'vendor' => '🏪 Vendeur',
                                        'product' => '📦 Produit',
                                        default => $offer->target_type
                                    } }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[12px]">
                                <div class="text-[#0a0a0a]">{{ $offer->start_date->format('d/m/y') }}</div>
                                <div class="text-[#a0a09a]">→ {{ $offer->end_date->format('d/m/y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-mono font-bold text-[12px]">{{ $offer->usage_count }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Active Toggle --}}
                                    <button onclick="toggleOffer({{ $offer->id }})"
                                            class="p-2 {{ $offer->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} rounded-lg hover:opacity-75 transition-opacity"
                                            title="{{ $offer->is_active ? 'Désactiver' : 'Activer' }}">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11z"/>
                                        </svg>
                                    </button>

                                    {{-- View Details --}}
                                    <a href="{{ route('admin.global-offers.show', $offer) }}" class="p-2 bg-[#f7f7f5] text-[#0a0a0a] rounded-lg hover:bg-[#e0e0dc]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.global-offers.edit', $offer) }}" class="p-2 bg-[#f7f7f5] text-[#0a0a0a] rounded-lg hover:bg-[#e0e0dc]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.global-offers.destroy', $offer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-[13px] text-[#a0a09a]">Aucune offre créée</div>
                                <a href="{{ route('admin.global-offers.create') }}" class="text-[12px] text-[#0a0a0a] font-medium mt-2 inline-block">
                                    Créer la première offre →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($offers->hasPages())
            <div class="px-6 py-4 border-t border-[#e0e0dc]">
                {{ $offers->links() }}
            </div>
        @endif
    </div>

</div>

<script>
async function toggleOffer(offerId) {
    try {
        const response = await fetch(`/admin/global-offers/${offerId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}
</script>

@endsection
