@extends('vendeur.layout-dashboard')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('vendeur.promo-codes.show', $promoCode->id) }}" class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-4">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Retour
            </a>
            <h1 class="font-serif text-[32px] text-[#0a0a0a]">Modifier le code promo</h1>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('vendeur.promo-codes.update', $promoCode->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Description --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Informations</h3>
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Code (non modifiable)</label>
                    <input type="text" value="{{ $promoCode->code }}" disabled
                           class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] bg-[#f7f7f5] text-[#a0a09a]">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                    <input type="text" name="description"
                           value="{{ old('description', $promoCode->description) }}"
                           placeholder="Ex: Promotion été"
                           class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                </div>
            </div>

            {{-- Réduction --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Réduction</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Type</label>
                        <select name="type_reduction" id="type_reduction"
                                class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                            <option value="pourcentage" {{ old('type_reduction', $promoCode->type_reduction) === 'pourcentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                            <option value="montant_fixe" {{ old('type_reduction', $promoCode->type_reduction) === 'montant_fixe' ? 'selected' : '' }}>Montant fixe (FCFA)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Montant</label>
                        <div class="flex gap-2">
                            <input type="number" name="taux_reduction"
                                   value="{{ old('taux_reduction', $promoCode->taux_reduction) }}"
                                   min="0.01" step="0.01"
                                   class="flex-1 px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                            <span class="px-3.5 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#0a0a0a] flex items-center" id="unit-display">
                                {{ $promoCode->type_reduction === 'pourcentage' ? '%' : 'FCFA' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#efefed]">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Montant minimum d'achat</label>
                        <input type="number" name="montant_minimum"
                               value="{{ old('montant_minimum', $promoCode->montant_minimum) }}"
                               min="0" step="100"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Réduction maximale</label>
                        <input type="number" name="montant_maximum"
                               value="{{ old('montant_maximum', $promoCode->montant_maximum) }}"
                               min="0" step="100"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    </div>
                </div>
            </div>

            {{-- Limitations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Limitations</h3>
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nombre maximum d'utilisations</label>
                    <input type="number" name="max_utilisations"
                           value="{{ old('max_utilisations', $promoCode->max_utilisations) }}"
                           min="1"
                           class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    @if($promoCode->utilisations > 0)
                        <p class="text-[11px] text-[#a0a09a] mt-1">Actuellement utilisé {{ $promoCode->utilisations }} fois</p>
                    @endif
                </div>
            </div>

            {{-- Dates --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Validité</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de début</label>
                        <input type="date" name="date_debut"
                               value="{{ old('date_debut', $promoCode->date_debut->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de fin</label>
                        <input type="date" name="date_fin"
                               value="{{ old('date_fin', $promoCode->date_fin->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    </div>
                </div>
            </div>

            {{-- Statut --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Statut</h3>
                </div>

                <select name="statut"
                        class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    <option value="actif" {{ old('statut', $promoCode->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut', $promoCode->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            {{-- Produits ciblés --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-4">
                <div class="pb-4 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a]">Produits ciblés</h3>
                </div>

                @if($produits->count() > 0)
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($produits as $produit)
                            <label class="flex items-start gap-3 p-2.5 hover:bg-[#f7f7f5] rounded cursor-pointer transition-colors">
                                <input type="checkbox" name="produits[]" value="{{ $produit->id }}"
                                       {{ in_array($produit->id, $produitSélectionnés) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                                    <div class="text-[11px] text-[#a0a09a]">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-[13px] text-[#a0a09a]">Aucun produit. S'applique à tous les achats.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <a href="{{ route('vendeur.promo-codes.show', $promoCode->id) }}"
                   class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all text-center">
                    Annuler
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                    Enregistrer les modifications
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.getElementById('type_reduction').addEventListener('change', function() {
        document.getElementById('unit-display').textContent = this.value === 'pourcentage' ? '%' : 'FCFA';
    });
</script>
@endsection
