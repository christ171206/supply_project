@extends('vendeur.layout-dashboard')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
    <div class="max-w-6xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Codes Promo</h1>
                <p class="text-[13px] text-[#a0a09a]">Gérez vos codes de réduction et promotions</p>
            </div>
            <a href="{{ route('vendeur.promo-codes.create') }}"
               class="flex items-center gap-2 bg-[#0a0a0a] text-white px-4 py-2.5 rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Créer un code
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[13px] text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="text-[13px] text-red-800">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tableau des codes --}}
        @if($promoCodes->count() > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#efefed]">
                            <th class="text-left px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Code</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Réduction</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Utilisations</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Validité</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                            <th class="text-right px-5 py-3.5 text-[11px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promoCodes as $code)
                            <tr class="border-b border-[#efefed] hover:bg-[#f7f7f5] transition-colors">
                                {{-- Code --}}
                                <td class="px-5 py-3.5">
                                    <div class="font-mono text-[13px] font-medium text-[#0a0a0a]">{{ $code->code }}</div>
                                    @if($code->description)
                                        <div class="text-[11px] text-[#a0a09a] mt-0.5 truncate">{{ $code->description }}</div>
                                    @endif
                                </td>

                                {{-- Réduction --}}
                                <td class="px-5 py-3.5">
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">
                                        @if($code->type_reduction === 'pourcentage')
                                            {{ $code->taux_reduction }}%
                                        @else
                                            {{ number_format($code->taux_reduction, 0, ',', ' ') }} FCFA
                                        @endif
                                    </div>
                                    @if($code->montant_maximum)
                                        <div class="text-[11px] text-[#a0a09a] mt-0.5">Max: {{ number_format($code->montant_maximum, 0, ',', ' ') }} FCFA</div>
                                    @endif
                                </td>

                                {{-- Utilisations --}}
                                <td class="px-5 py-3.5">
                                    @if($code->max_utilisations)
                                        <div class="text-[13px] font-medium text-[#0a0a0a]">
                                            {{ $code->utilisations }}/{{ $code->max_utilisations }}
                                        </div>
                                        <div class="w-24 h-1.5 bg-[#efefed] rounded-full mt-1 overflow-hidden">
                                            <div class="bg-[#0a0a0a] h-full" style="width: {{ $code->pourcentageUtilisation() }}%"></div>
                                        </div>
                                    @else
                                        <div class="text-[13px] text-[#a0a09a]">{{ $code->utilisations }} utilisations</div>
                                        <div class="text-[11px] text-[#a0a09a] mt-0.5">Illimité</div>
                                    @endif
                                </td>

                                {{-- Validité --}}
                                <td class="px-5 py-3.5">
                                    <div class="text-[12px] text-[#666660]">
                                        {{ $code->date_debut->format('d/m/Y') }}
                                        <br>
                                        {{ $code->date_fin->format('d/m/Y') }}
                                    </div>
                                    @if($code->joursRestants() > 0)
                                        <div class="text-[11px] text-[#a0a09a] mt-1">{{ $code->joursRestants() }} jours</div>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-3.5">
                                    @php
                                        $badge = match($code->statut) {
                                            'actif' => ['bg-green-100 text-green-800', '🟢', 'Actif'],
                                            'inactif' => ['bg-gray-100 text-gray-800', '⭕', 'Inactif'],
                                            'expire' => ['bg-red-100 text-red-800', '⭐', 'Expiré'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded {{ $badge[0] }}">
                                        {{ $badge[1] }} {{ $badge[2] }}
                                    </span>
                                    @if($code->archive)
                                        <div class="text-[10px] text-[#a0a09a] mt-1">📦 Archivé</div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('vendeur.promo-codes.show', $code->id) }}"
                                           class="p-1.5 hover:bg-[#efefed] rounded transition-colors" title="Voir détails">
                                            <svg class="w-4 h-4 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <a href="{{ route('vendeur.promo-codes.edit', $code->id) }}"
                                           class="p-1.5 hover:bg-[#efefed] rounded transition-colors" title="Modifier">
                                            <svg class="w-4 h-4 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </a>
                                        <form action="{{ route('vendeur.promo-codes.toggle-archive', $code->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1.5 hover:bg-[#efefed] rounded transition-colors" title="{{ $code->archive ? 'Désarchiver' : 'Archiver' }}">
                                                <svg class="w-4 h-4 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><line x1="1" y1="3" x2="23" y2="3"/><path d="M10 12v6M14 12v6M5 8l1-3h12l1 3"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $promoCodes->links() }}
            </div>
        @else
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-12 text-center">
                <svg class="w-16 h-16 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="3"/></svg>
                <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-1">Aucun code promo</h3>
                <p class="text-[13px] text-[#a0a09a] mb-4">Créez votre premier code promo pour attirer et fidéliser vos clients</p>
                <a href="{{ route('vendeur.promo-codes.create') }}"
                   class="inline-flex items-center gap-2 bg-[#0a0a0a] text-white px-4 py-2.5 rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Créer un code
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
