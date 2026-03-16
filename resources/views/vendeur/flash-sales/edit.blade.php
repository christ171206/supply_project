@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="mb-8">
        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Éditer la Vente Flash</h1>
    </div>

    <form action="{{ route('vendeur.flash-sales.update', $flashSale->id) }}" method="POST" class="max-w-2xl space-y-4">
        @csrf @method('PUT')

        <!-- Catégorie -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label for="categorie_id" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Catégorie
            </label>
            <select id="categorie_id" name="categorie_id" required class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors cursor-pointer">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected($cat->id === $flashSale->categorie_id)>{{ $cat->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id')
                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Réduction -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label for="pourcentage_reduction" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Pourcentage de réduction (%)
            </label>
            <div class="flex items-center gap-2">
                <input type="number" id="pourcentage_reduction" name="pourcentage_reduction"
                       min="1" max="99" step="1" required
                       value="{{ old('pourcentage_reduction', $flashSale->pourcentage_reduction) }}"
                       class="flex-1 px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                <span class="text-[13px] font-mono text-[#0a0a0a]">%</span>
            </div>
            @error('pourcentage_reduction')
                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="date_debut" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Début
                </label>
                <input type="datetime-local" id="date_debut" name="date_debut" required disabled
                       value="{{ $flashSale->date_debut->format('Y-m-d\TH:i') }}"
                       class="w-full px-3.5 py-3 text-[13px] text-[#a0a09a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg cursor-not-allowed">
                <p class="text-[10px] text-[#a0a09a] mt-1">Non modifiable</p>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
                <label for="date_fin" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                    Fin
                </label>
                <input type="datetime-local" id="date_fin" name="date_fin" required
                       value="{{ old('date_fin', $flashSale->date_fin->format('Y-m-d\TH:i')) }}"
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
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="statut" value="actif" @checked($flashSale->statut === 'actif') class="cursor-pointer">
                    <span class="text-[13px]">Actif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="statut" value="inactif" @checked($flashSale->statut === 'inactif') class="cursor-pointer">
                    <span class="text-[13px]">Inactif</span>
                </label>
            </div>
            @error('statut')
                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Boutons -->
        <div class="flex gap-3">
            <a href="{{ route('vendeur.flash-sales.show', $flashSale->id) }}" class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#0a0a0a] transition-colors text-center">
                Annuler
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
