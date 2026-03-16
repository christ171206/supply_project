@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">
    <div class="mb-8">
        <h1 class="font-serif text-[32px] text-[#0a0a0a] mb-2">Nouvelle Vente Flash</h1>
        <p class="text-[13px] text-[#a0a09a]">Créez une promotion temps limité sur une catégorie</p>
    </div>

    <form action="{{ route('vendeur.flash-sales.store') }}" method="POST" class="max-w-2xl space-y-4">
        @csrf

        <!-- Catégorie -->
        <div class="bg-white border border-[#e0e0dc] rounded-xl p-5">
            <label for="categorie_id" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                Catégorie
            </label>
            <select id="categorie_id" name="categorie_id" required class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors cursor-pointer">
                <option value="">— Sélectionner une catégorie —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
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
                       value="{{ old('pourcentage_reduction', 10) }}"
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
                <input type="datetime-local" id="date_debut" name="date_debut" required
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
                       class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                @error('date_fin')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex gap-3">
            <a href="{{ route('vendeur.flash-sales.index') }}" class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#0a0a0a] transition-colors text-center">
                Annuler
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:opacity-85 transition-opacity">
                Créer la vente flash
            </button>
        </div>
    </form>
</div>
@endsection
