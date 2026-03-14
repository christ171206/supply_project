@extends('vendeur.layout-dashboard')

@section('title', isset($produit) ? 'Modifier le produit — Supply' : 'Ajouter un produit — Supply')

@section('breadcrumb')
    Espace Vendeur &nbsp;/&nbsp;
    <a href="{{ route('vendeur.produits.index') }}" class="hover:text-[#0a0a0a] transition-colors">Mes Produits</a>
    &nbsp;/&nbsp; {{ isset($produit) ? 'Modifier' : 'Ajouter' }}
@endsection

@section('content')
<div class="pb-20">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('vendeur.produits.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux produits
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Gestion</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">
            {{ isset($produit) ? 'Modifier le produit' : 'Ajouter un produit' }}
        </h1>
        <p class="text-[13px] text-white/40 font-light mt-2">
            {{ isset($produit) ? 'Mettez à jour les informations de votre produit' : 'Créez un nouveau produit dans votre catalogue' }}
        </p>
    </div>

    <div class="px-8">
    <div class="max-w-2xl mx-auto">

        <form action="{{ isset($produit) ? route('vendeur.produits.update', $produit->id) : route('vendeur.produits.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf
            @if(isset($produit)) @method('PUT') @endif

            {{-- Section : Informations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a] tracking-tight">Informations générales</span>
                </div>
                <div class="px-6 py-5 space-y-5">

                    {{-- Nom --}}
                    <div>
                        <label for="nom" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                            Nom du produit <span class="text-[#dc2626]">*</span>
                        </label>
                        <input type="text" name="nom" id="nom"
                               value="{{ old('nom', $produit->nom ?? '') }}"
                               placeholder="Ex : Dell XPS 13"
                               required
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                      placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                      @error('nom') border-[#f87171] bg-[#fef2f2] @enderror">
                        @error('nom')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catégorie + Prix --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="categorie_id" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                                Catégorie <span class="text-[#dc2626]">*</span>
                            </label>
                            <select name="categorie_id" id="categorie_id" required
                                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                           @error('categorie_id') border-[#f87171] bg-[#fef2f2] @enderror">
                                <option value="">— Sélectionner</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="prix" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                                Prix (FCFA) <span class="text-[#dc2626]">*</span>
                            </label>
                            <input type="number" name="prix" id="prix" step="1"
                                   value="{{ old('prix', $produit->prix ?? '') }}"
                                   placeholder="649500"
                                   required
                                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                          font-mono placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                          @error('prix') border-[#f87171] bg-[#fef2f2] @enderror">
                            @error('prix')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                            Description <span class="text-[#dc2626]">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                                  placeholder="Décrivez votre produit en détail…"
                                  class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                         placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none
                                         @error('description') border-[#f87171] bg-[#fef2f2] @enderror">{{ old('description', $produit->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Section : Stock --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a] tracking-tight">Stock</span>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="stock" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                                Stock initial <span class="text-[#dc2626]">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" min="0"
                                   value="{{ old('stock', $produit->stock ?? 0) }}"
                                   placeholder="15"
                                   required
                                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                          font-mono placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                          @error('stock') border-[#f87171] bg-[#fef2f2] @enderror">
                            @error('stock')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stock_minimum" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                                Seuil d'alerte <span class="text-[#dc2626]">*</span>
                            </label>
                            <input type="number" name="stock_minimum" id="stock_minimum" min="0"
                                   value="{{ old('stock_minimum', $produit->stock_minimum ?? 10) }}"
                                   placeholder="5"
                                   required
                                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] text-[#0a0a0a]
                                          font-mono placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all
                                          @error('stock_minimum') border-[#f87171] bg-[#fef2f2] @enderror">
                            @error('stock_minimum')
                                <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-[11px] text-[#a0a09a] font-light mt-3">
                        Un badge "Stock faible" s'affiche quand le stock descend sous le seuil d'alerte.
                    </p>
                </div>
            </div>

            {{-- Section : Statut --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Visibilité</div>
                        <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">Actif = visible aux clients</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="est_actif" value="0">
                        <label class="relative inline-flex items-center cursor-pointer" for="toggle-status">
                            <input type="checkbox" id="toggle-status" name="est_actif" value="1"
                                   {{ old('est_actif', $produit->est_actif ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-[#e0e0dc] rounded-full
                                        peer-checked:bg-[#0a0a0a]
                                        after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                                        after:bg-white after:border after:border-[#e0e0dc] after:rounded-full
                                        after:h-4 after:w-4 after:transition-all
                                        peer-checked:after:translate-x-4 peer-checked:after:border-white
                                        relative"></div>
                        </label>
                        <span id="status-label" class="text-[12px] font-medium text-[#0a0a0a]">
                            {{ old('est_actif', $produit->est_actif ?? true) ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Section : Images --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed] flex items-center justify-between">
                    <span class="text-[12px] font-medium text-[#0a0a0a] tracking-tight">Images</span>
                    <span class="text-[11px] text-[#a0a09a] font-mono">max 5 · JPG, PNG · 2 MB chacune</span>
                </div>
                <div class="px-6 py-5 space-y-4">

                    {{-- Images existantes --}}
                    @if(isset($produit) && $produit->images && is_array($produit->images) && count($produit->images) > 0)
                        <div>
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">
                                Images actuelles ({{ count($produit->images) }})
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($produit->images as $index => $imagePath)
                                    @if(file_exists(storage_path('app/public/' . $imagePath)))
                                        <div class="relative group aspect-square overflow-hidden rounded-lg border border-[#e0e0dc]">
                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                 alt="Image {{ $index + 1 }}"
                                                 class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                <span class="font-mono text-white text-[10px]">#{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="border-t border-[#efefed] pt-4">
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">
                                Remplacer les images
                            </div>
                        </div>
                    @endif

                    {{-- Zone de dépôt --}}
                    <div id="dropZone"
                         class="border border-dashed border-[#e0e0dc] rounded-xl p-8 text-center
                                hover:border-[#2a2a28] hover:bg-[#f7f7f5] transition-all cursor-pointer">
                        <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Glissez les images ici</p>
                        <p class="text-[11px] text-[#a0a09a] font-light">ou cliquez pour sélectionner</p>
                    </div>

                    {{-- Preview --}}
                    <div id="preview-container" class="grid grid-cols-4 gap-2 hidden"></div>

                    <input type="file" name="images[]" id="images" accept="image/jpeg,image/png" multiple class="hidden">

                    @error('images')
                        <p class="text-[11px] text-[#dc2626]">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-[11px] text-[#dc2626]">{{ $message }}</p>
                    @enderror

                </div>
            </div>

            {{-- Lien galerie Cloudinary (édition uniquement) --}}
            @if(isset($produit))
                <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-4 flex items-center justify-between">
                    <div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Galerie Cloudinary</div>
                        <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">Gestion avancée des images avec optimisation</div>
                    </div>
                    <a href="{{ route('vendeur.produits.gallery', $produit) }}"
                       class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-3 py-1.5 rounded-lg
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Accéder →
                    </a>
                </div>
            @endif

            {{-- Footer actions --}}
            <div class="flex items-center justify-end gap-2 pt-2">
                <a href="{{ route('vendeur.produits.index') }}"
                   class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2.5 rounded-lg
                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg
                               hover:opacity-85 transition-opacity flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($produit) ? 'Mettre à jour' : 'Créer le produit' }}
                </button>
            </div>

        </form>
    </div>
    </div>
</div>

@section('scripts')
<script>
const dropZone        = document.getElementById('dropZone');
const fileInput       = document.getElementById('images');
const previewContainer= document.getElementById('preview-container');
const MAX_IMAGES      = 5;
const MAX_FILE_SIZE   = 2 * 1024 * 1024; // 2 MB
let   selectedFiles   = new DataTransfer();

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-[#2a2a28]', 'bg-[#f7f7f5]');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-[#2a2a28]', 'bg-[#f7f7f5]');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-[#2a2a28]', 'bg-[#f7f7f5]');
    handleFiles(e.dataTransfer.files);
});
fileInput.addEventListener('change', () => handleFiles(fileInput.files));

function handleFiles(files) {
    selectedFiles = new DataTransfer();
    let skipped = 0;
    let tooLarge = [];
    
    Array.from(files).forEach(file => {
        // Vérifier la taille
        if (file.size > MAX_FILE_SIZE) {
            tooLarge.push(file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)');
            return;
        }
        // Vérifier le type
        if (!file.type.startsWith('image/')) {
            skipped++;
            return;
        }
        // Vérifier le nombre max
        if (selectedFiles.items.length >= MAX_IMAGES) {
            skipped++;
            return;
        }
        selectedFiles.items.add(file);
    });
    
    // Afficher les erreurs
    if (tooLarge.length > 0) {
        alert('Les fichiers suivants sont trop volumineux (max 2 MB chacun):\n\n' + tooLarge.join('\n'));
    }
    
    fileInput.files = selectedFiles.files;
    showPreviews(selectedFiles.files);
}

function showPreviews(files) {
    previewContainer.innerHTML = '';
    if (!files.length) { previewContainer.classList.add('hidden'); return; }
    previewContainer.classList.remove('hidden');
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'relative group aspect-square overflow-hidden rounded-lg border border-[#0a0a0a]';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                    <span class="font-mono text-white text-[10px]">#${index + 1}</span>
                </div>
            `;
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// Toggle label statut
document.getElementById('toggle-status')?.addEventListener('change', function() {
    document.getElementById('status-label').textContent = this.checked ? 'Actif' : 'Inactif';
});
</script>
@endsection

@endsection
