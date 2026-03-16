@extends('vendeur.layout-dashboard')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
    <div class="max-w-4xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('vendeur.promo-codes.index') }}" class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-4">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Retour aux codes
            </a>
            <h1 class="font-serif text-[32px] text-[#0a0a0a]">Créer un code promo</h1>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('vendeur.promo-codes.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Infos principales --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">
                <div class="pb-6 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-2">Informations principales</h3>
                    <p class="text-[12px] text-[#a0a09a]">Définissez le code et la description</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Code --}}
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Code promo</label>
                        <div class="flex gap-2">
                            <input type="text" name="code" id="code"
                                   value="{{ old('code') }}"
                                   placeholder="Ex: SUMMER2024"
                                   class="flex-1 px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                            <button type="button" id="generate-code"
                                    class="px-3.5 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[12px] font-medium text-[#666660] hover:border-[#0a0a0a] hover:bg-white transition-colors">
                                Générer
                            </button>
                        </div>
                        <div id="code-status" class="text-[11px] text-[#a0a09a] mt-2"></div>
                        @error('code')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                        <input type="text" name="description"
                               value="{{ old('description') }}"
                               placeholder="Ex: Promotion été"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    </div>
                </div>
            </div>

            {{-- Réduction --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">
                <div class="pb-6 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-2">Configuration de la réduction</h3>
                    <p class="text-[12px] text-[#a0a09a]">Définissez le type et le taux de réduction</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Type de réduction --}}
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Type de réduction</label>
                        <select name="type_reduction" id="type_reduction"
                                class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                            <option value="pourcentage" {{ old('type_reduction') === 'pourcentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                            <option value="montant_fixe" {{ old('type_reduction') === 'montant_fixe' ? 'selected' : '' }}>Montant fixe (FCFA)</option>
                        </select>
                        @error('type_reduction')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Taux de réduction --}}
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Montant</label>
                        <div class="flex gap-2">
                            <input type="number" name="taux_reduction"
                                   value="{{ old('taux_reduction') }}"
                                   placeholder="0"
                                   min="0.01" step="0.01"
                                   class="flex-1 px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                            <span class="px-3.5 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#0a0a0a] flex items-center" id="unit-display">%</span>
                        </div>
                        @error('taux_reduction')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Montants min/max --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#efefed]">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Montant minimum d'achat</label>
                        <input type="number" name="montant_minimum"
                               value="{{ old('montant_minimum') }}"
                               placeholder="Optionnel"
                               min="0" step="100"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                        <p class="text-[11px] text-[#a0a09a] mt-1">Laisser vide pour aucune limite</p>
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Réduction maximale</label>
                        <input type="number" name="montant_maximum"
                               value="{{ old('montant_maximum') }}"
                               placeholder="Optionnel"
                               min="0" step="100"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                        <p class="text-[11px] text-[#a0a09a] mt-1">Plafond de réduction en FCFA</p>
                    </div>
                </div>
            </div>

            {{-- Limitations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">
                <div class="pb-6 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-2">Limitations</h3>
                    <p class="text-[12px] text-[#a0a09a]">Définissez les restrictions d'utilisation</p>
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nombre maximum d'utilisations</label>
                    <input type="number" name="max_utilisations"
                           value="{{ old('max_utilisations') }}"
                           placeholder="Laisser vide pour illimité"
                           min="1"
                           class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                    <p class="text-[11px] text-[#a0a09a] mt-1">Nombre total de fois que ce code peut être utilisé</p>
                </div>
            </div>

            {{-- Dates --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">
                <div class="pb-6 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-2">Période de validité</h3>
                    <p class="text-[12px] text-[#a0a09a]">Définissez la durée de validité du code</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de début</label>
                        <input type="date" name="date_debut"
                               value="{{ old('date_debut', date('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                        @error('date_debut')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de fin</label>
                        <input type="date" name="date_fin"
                               value="{{ old('date_fin', date('Y-m-d', strtotime('+30 days'))) }}"
                               class="w-full px-3.5 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a] transition-colors">
                        @error('date_fin')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Produits ciblés --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-6 space-y-6">
                <div class="pb-6 border-b border-[#efefed]">
                    <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-2">Produits ciblés (optionnel)</h3>
                    <p class="text-[12px] text-[#a0a09a]">Laissez vide pour tous les produits</p>
                </div>

                @if($produits->count() > 0)
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($produits as $produit)
                            <label class="flex items-start gap-3 p-2.5 hover:bg-[#f7f7f5] rounded cursor-pointer transition-colors">
                                <input type="checkbox" name="produits[]" value="{{ $produit->id }}"
                                       class="mt-1" {{ in_array($produit->id, old('produits', [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                                    <div class="text-[11px] text-[#a0a09a]">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-[13px] text-[#a0a09a]">Vous n'avez aucun produit. Le code s'appliquera à tous les achats.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <a href="{{ route('vendeur.promo-codes.index') }}"
                   class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all text-center">
                    Annuler
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                    Créer le code
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // Générer un code aléatoire
    document.getElementById('generate-code').addEventListener('click', function() {
        fetch('{{ route("vendeur.promo-codes.generate") }}')
            .then(r => r.json())
            .then(data => {
                document.getElementById('code').value = data.code;
                checkCodeAvailability();
            });
    });

    // Changer l'unité d'affichage
    document.getElementById('type_reduction').addEventListener('change', function() {
        const unitDisplay = document.getElementById('unit-display');
        unitDisplay.textContent = this.value === 'pourcentage' ? '%' : 'FCFA';
    });

    // Vérifier la disponibilité du code
    const codeInput = document.getElementById('code');
    const codeStatus = document.getElementById('code-status');

    function checkCodeAvailability() {
        const code = codeInput.value.trim();
        if (!code) {
            codeStatus.textContent = '';
            return;
        }

        fetch('{{ route("vendeur.promo-codes.check") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ code }),
            method: 'POST'
        })
        .then(r => r.json())
        .then(data => {
            if (data.available) {
                codeStatus.textContent = '✓ Code disponible';
                codeStatus.className = 'text-[11px] text-green-600 mt-2';
            } else {
                codeStatus.textContent = '✗ Code déjà utilisé';
                codeStatus.className = 'text-[11px] text-red-600 mt-2';
            }
        });
    }

    codeInput.addEventListener('change', checkCodeAvailability);
    codeInput.addEventListener('blur', checkCodeAvailability);
</script>
@endsection
