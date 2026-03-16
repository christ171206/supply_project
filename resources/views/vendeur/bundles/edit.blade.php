@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="mb-8">
        <a href="{{ route('vendeur.bundles.show', $bundle->id) }}" class="text-[12px] text-[#a0a09a] hover:underline mb-4 inline-block">
            ← Retour au bundle
        </a>
        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Modifier le bundle</h1>
        <p class="text-[13px] text-[#a0a09a]">{{ $bundle->nom }}</p>
    </div>

    <form action="{{ route('vendeur.bundles.update', $bundle->id) }}" method="POST" class="max-w-2xl space-y-4">
        @csrf
        @method('PUT')

        <!-- Nom -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label for="nom" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Nom du bundle
            </label>
            <input type="text" id="nom" name="nom" required
                   value="{{ old('nom', $bundle->nom) }}" placeholder="Ex: Pack Gaming Ultimate"
                   class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
            @error('nom')
                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label for="description" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Description (optionnel)
            </label>
            <textarea id="description" name="description" rows="3"
                      placeholder="Décrivez l'offre..."
                      class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors resize-none">{{ old('description', $bundle->description) }}</textarea>
        </div>

        <!-- Produits -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <label class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">
                    Produits (min. 2)
                </label>
                <button type="button" id="add-produit-btn" class="text-[11px] text-blue-600 hover:underline">
                    + Ajouter un produit
                </button>
            </div>

            <div id="produits-container" class="space-y-3">
                @foreach($bundle->produits as $index => $produit)
                    <div class="produit-row flex gap-2 items-end">
                        <select name="produits[]" required class="flex-1 px-3 py-2 text-[12px] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] cursor-pointer">
                            <option value="">— Sélectionner —</option>
                            @foreach($allProduits as $p)
                                <option value="{{ $p->id }}" {{ $p->id == $produit->id ? 'selected' : '' }}>
                                    {{ $p->nom }} ({{ number_format($p->prix, 0, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="quantites[]" min="1" value="{{ $produit->pivot->quantite }}" placeholder="Qté" class="w-16 px-2 py-2 text-[12px] border border-[#e0e0dc] rounded-lg">
                        <button type="button" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded remove-produit-btn">✕</button>
                    </div>
                @endforeach
            </div>
            @error('produits')
                <p class="text-[11px] text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Prix -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="prix_bundle" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Prix bundle (FCFA)
                </label>
                <input type="number" id="prix_bundle" name="prix_bundle" required min="0" step="100"
                       value="{{ old('prix_bundle', $bundle->prix_bundle) }}"
                       class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                @error('prix_bundle')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="quantite_disponible" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Quantité disponible (optionnel)
                </label>
                <input type="number" id="quantite_disponible" name="quantite_disponible" min="1"
                       value="{{ old('quantite_disponible', $bundle->quantite_disponible) }}" placeholder="Illimité"
                       class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
            </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="date_debut" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Début
                </label>
                <input type="datetime-local" id="date_debut" name="date_debut" required
                       value="{{ old('date_debut', $bundle->date_debut->format('Y-m-d\TH:i')) }}"
                       class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                @error('date_debut')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="date_fin" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Fin
                </label>
                <input type="datetime-local" id="date_fin" name="date_fin" required
                       value="{{ old('date_fin', $bundle->date_fin->format('Y-m-d\TH:i')) }}"
                       class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                @error('date_fin')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Statut -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">
                Statut
            </label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="statut" value="actif" {{ old('statut', $bundle->statut) === 'actif' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-[13px] text-[#0a0a0a]">Actif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="statut" value="inactif" {{ old('statut', $bundle->statut) === 'inactif' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-[13px] text-[#0a0a0a]">Inactif</span>
                </label>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex gap-3">
            <a href="{{ route('vendeur.bundles.show', $bundle->id) }}" class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#0a0a0a] transition-colors text-center">
                Annuler
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
let produitCount = {{ count($bundle->produits) }};

document.getElementById('add-produit-btn').addEventListener('click', (e) => {
    e.preventDefault();
    const container = document.getElementById('produits-container');

    const row = document.createElement('div');
    row.className = 'produit-row flex gap-2 items-end';
    row.innerHTML = `
        <select name="produits[]" required class="flex-1 px-3 py-2 text-[12px] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] cursor-pointer">
            <option value="">— Sélectionner —</option>
            @foreach($allProduits as $p)
                <option value="{{ $p->id }}">{{ $p->nom }} ({{ number_format($p->prix, 0, ',', ' ') }} FCFA)</option>
            @endforeach
        </select>
        <input type="number" name="quantites[]" min="1" value="1" placeholder="Qté" class="w-16 px-2 py-2 text-[12px] border border-[#e0e0dc] rounded-lg">
        <button type="button" class="px-2 py-2 text-red-600 hover:bg-red-50 rounded remove-produit-btn">✕</button>
    `;
    container.appendChild(row);
    setupRemoveButtons();
    produitCount++;
});

function setupRemoveButtons() {
    document.querySelectorAll('.remove-produit-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            btn.closest('.produit-row').remove();
        });
    });
}

setupRemoveButtons();
</script>
@endsection
