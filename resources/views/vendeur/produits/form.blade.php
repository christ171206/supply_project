@extends('vendeur.layout-dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-serif text-[#0a0a0a]">
            {{ isset($produit) ? 'Modifier le produit' : 'Ajouter un produit' }}
        </h1>
        <p class="text-[#666660] text-sm font-light mt-2">
            {{ isset($produit) ? 'Mettez à jour les informations' : 'Créez un nouveau produit' }}
        </p>
    </div>

    <!-- Card Formulaire -->
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-6 space-y-6">
        <form action="{{ isset($produit) ? route('vendeur.produits.update', $produit->id) : route('vendeur.produits.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($produit))
                @method('PUT')
            @endif

            <!-- Nom du produit -->
            <div>
                <label for="nom" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Nom du produit</label>
                <input type="text" name="nom" id="nom"
                       value="{{ old('nom', $produit->nom ?? '') }}"
                       placeholder="Ex: Dell XPS 13"
                       class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white @error('nom') border-red-400 @enderror"
                       required>
                @error('nom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catégorie & Prix Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Catégorie -->
                <div>
                    <label for="categorie_id" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Catégorie</label>
                    <select name="categorie_id" id="categorie_id"
                            class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white @error('categorie_id') border-red-400 @enderror" required>
                        <option value="">— Sélectionner</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix -->
                <div>
                    <label for="prix" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Prix (FCFA)</label>
                    <input type="number" name="prix" id="prix" step="0.01"
                           value="{{ old('prix', $produit->prix ?? '') }}"
                           placeholder="Ex: 649500"
                           class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white font-mono @error('prix') border-red-400 @enderror"
                           required>
                    @error('prix')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                          placeholder="Décrivez votre produit en détail..."
                          class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white @error('description') border-red-400 @enderror"
                          required>{{ old('description', $produit->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Stock initial -->
                <div>
                    <label for="stock" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Stock initial</label>
                    <input type="number" name="stock" id="stock"
                           value="{{ old('stock', $produit->stock ?? 0) }}"
                           placeholder="Ex: 15"
                           class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white font-mono @error('stock') border-red-400 @enderror"
                           required>
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Minimum -->
                <div>
                    <label for="stock_minimum" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Stock minimum</label>
                    <input type="number" name="stock_minimum" id="stock_minimum"
                           value="{{ old('stock_minimum', $produit->stock_minimum ?? 10) }}"
                           placeholder="Ex: 5"
                           class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white font-mono @error('stock_minimum') border-red-400 @enderror"
                           required>
                    @error('stock_minimum')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Statut du produit -->
            <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Statut</label>
                        <p class="text-xs text-[#666660] font-light mt-1">Visible ou masqué aux clients</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="est_actif" value="0">
                        <label class="relative inline-flex items-center cursor-pointer" for="toggle-status">
                            <input type="checkbox" id="toggle-status" name="est_actif" value="1"
                                   {{ old('est_actif', $produit->est_actif ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-[#e0e0dc] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-[#e0e0dc] after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0a0a0a]"></div>
                            <span class="ml-2 text-sm font-medium text-[#0a0a0a]">{{ old('est_actif', $produit->est_actif ?? true) ? 'Actif' : 'Inactif' }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Images du produit -->
            <div>
                <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Images (max 5)</label>

                <!-- Images actuelles -->
                @if(isset($produit) && $produit->images && is_array($produit->images))
                    <div class="mb-4 pb-4 border-b border-[#e0e0dc]">
                        <p class="text-xs text-[#666660] font-light mb-3">{{ count($produit->images) }} image(s) actuelles</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($produit->images as $index => $imagePath)
                                @if(file_exists(storage_path('app/public/' . $imagePath)))
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Image {{ $index + 1 }}" class="h-24 w-full object-cover rounded border border-[#e0e0dc]">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">#{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Zone de dépôt minimaliste -->
                <div class="border-2 border-dashed border-[#e0e0dc] rounded-lg p-6 text-center hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition cursor-pointer" id="dropZone">
                    <p class="text-[#0a0a0a] font-medium mb-1">Glissez les images ici</p>
                    <p class="text-xs text-[#a0a09a] font-light">ou cliquez pour sélectionner • JPG, PNG • Max 5MB</p>
                </div>

                <!-- Preview des images sélectionnées -->
                <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3"></div>

                <input type="file" name="images[]" id="images" accept="image/jpeg,image/png" multiple class="hidden">

                @error('images')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cloudinary Gallery Link (if editing) -->
            @if(isset($produit))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-900">✨ Galerie d'images Cloudinary</p>
                        <p class="text-xs text-blue-700 mt-1">Gères les images du produit avec optimisation automatique</p>
                    </div>
                    <a href="{{ route('vendeur.produits.gallery', $produit) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Accéder à la galerie
                    </a>
                </div>
            @endif

            <!-- Boutons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-[#e0e0dc]">
                <a href="{{ route('vendeur.produits.index') }}" class="px-4 py-2.5 border border-[#e0e0dc] text-[#0a0a0a] text-sm font-medium rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2.5 bg-[#0a0a0a] text-white text-sm font-medium rounded-lg hover:opacity-85 transition">
                    {{ isset($produit) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // DRAG & DROP MINIMALISTE
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('preview-container');
    const MAX_IMAGES = 5;
    let selectedFiles = new DataTransfer();

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-[#0a0a0a]', 'bg-[#f7f7f5]');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-[#0a0a0a]', 'bg-[#f7f7f5]');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-[#0a0a0a]', 'bg-[#f7f7f5]');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', () => handleFiles(fileInput.files));

    function handleFiles(files) {
        selectedFiles = new DataTransfer();
        Array.from(files).slice(0, MAX_IMAGES).forEach(file => {
            if (file.type.startsWith('image/')) {
                selectedFiles.items.add(file);
            }
        });
        fileInput.files = selectedFiles.files;
        showPreviews(selectedFiles.files);
    }

    function showPreviews(files) {
        previewContainer.innerHTML = '';
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="h-24 w-full object-cover rounded border border-[#0a0a0a]">
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <span class="text-white text-xs font-medium">#${index + 1}</span>
                    </div>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    // Toggle statut
    document.getElementById('toggle-status')?.addEventListener('change', function() {
        const span = this.parentElement.querySelector('span');
        if (span) span.textContent = this.checked ? 'Actif' : 'Inactif';
    });
</script>
@endsection
