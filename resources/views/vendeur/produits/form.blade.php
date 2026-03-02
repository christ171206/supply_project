@extends('vendeur.layout-dashboard')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">
            {{ isset($produit) ? '✏️ Modifier le produit' : '➕ Ajouter un produit' }}
        </h1>
        <p class="text-gray-600 mt-2">Gérez efficacement votre inventaire</p>
    </div>

    <!-- Card Formulaire -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 space-y-8">
            <form action="{{ isset($produit) ? route('vendeur.produits.update', $produit->id) : route('vendeur.produits.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @if(isset($produit))
                    @method('PUT')
                @endif

                <!-- ROW 1: Nom & Catégorie (2 colonnes) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-bold text-gray-900 mb-3">Nom du produit</label>
                        <input type="text" name="nom" id="nom"
                               value="{{ old('nom', $produit->nom ?? '') }}"
                               placeholder="Ex: Dell XPS 13"
                               class="w-full px-4 py-3 border @error('nom') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        @error('nom')
                            <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="categorie_id" class="block text-sm font-bold text-gray-900 mb-3">Catégorie</label>
                        <select name="categorie_id" id="categorie_id"
                                class="w-full px-4 py-3 border @error('categorie_id') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white cursor-pointer" required>
                            <option value="">-- Sélectionner une catégorie --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- ROW 2: Description (pleine largeur) -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-900 mb-3">Description</label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Décrivez votre produit en détail..."
                              class="w-full px-4 py-3 border @error('description') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                              required>{{ old('description', $produit->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                    @enderror
                </div>

                <!-- ROW 3: Prix, Stock, Stock Min (3 colonnes) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-bold text-gray-900 mb-3">Prix (FCFA)</label>
                        <input type="number" name="prix" id="prix" step="0.01"
                               value="{{ old('prix', $produit->prix ?? '') }}"
                               placeholder="Ex: 649500"
                               class="w-full px-4 py-3 border @error('prix') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        @error('prix')
                            <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Initial -->
                    <div>
                        <label for="stock" class="block text-sm font-bold text-gray-900 mb-3">Stock initial</label>
                        <input type="number" name="stock" id="stock"
                               value="{{ old('stock', $produit->stock ?? 0) }}"
                               placeholder="Ex: 15"
                               class="w-full px-4 py-3 border @error('stock') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        <p class="text-xs text-gray-500 mt-2">Quantité de départ</p>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Minimum -->
                    <div>
                        <label for="stock_minimum" class="block text-sm font-bold text-gray-900 mb-3">Stock minimum</label>
                        <input type="number" name="stock_minimum" id="stock_minimum"
                               value="{{ old('stock_minimum', $produit->stock_minimum ?? 10) }}"
                               placeholder="Ex: 5"
                               class="w-full px-4 py-3 border @error('stock_minimum') border-red-400 @else border-gray-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               required>
                        <p class="text-xs text-gray-500 mt-2">Alerte si stock bas</p>
                        @error('stock_minimum')
                            <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- ROW 4: Statut (Toggle Switch Moderne) -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">Statut du produit</label>
                            <p class="text-xs text-gray-600">Visible ou masqué aux clients</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" for="toggle-status">
                            <input type="hidden" name="est_actif" value="0">
                            <input type="checkbox" id="toggle-status" name="est_actif" value="1"
                                   {{ old('est_actif', $produit->est_actif ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-green-500"></div>
                            <span class="ml-3 text-sm font-semibold text-gray-900 toggle-label">
                                {{ old('est_actif', $produit->est_actif ?? true) ? '✅ Actif' : '⊘ Inactif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- ROW 5: Image (Drag & Drop) -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-3">Image du produit</label>
                    <p class="text-xs text-gray-500 mb-3">📁 Stockée en : <code class="bg-gray-100 px-2 py-1 rounded text-xs">storage/app/public/produits/</code></p>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer" id="dropZone">
                        @if(isset($produit) && $produit->image && file_exists(storage_path('app/public/produits/' . $produit->image)))
                            <div class="mb-4">
                                <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="h-32 object-cover rounded mx-auto">
                                <p class="text-sm text-gray-600 mt-3">Image actuelle</p>
                            </div>
                        @else
                            <p class="text-4xl mb-3">📷</p>
                            <p class="text-gray-700 font-semibold">Glissez une image ici ou cliquez</p>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG • Max 5MB</p>
                        @endif
                    </div>

                    <input type="file" name="image" id="image" accept="image/jpeg,image/png"
                           class="hidden" id="fileInput">

                    @error('image')
                        <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons (droite, modernes) -->
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('vendeur.produits.index') }}" class="px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-bold rounded-xl transition hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition shadow-lg hover:shadow-xl">
                        @if(isset($produit))
                            <x-icon name="save" class="w-4 h-4 inline mr-1" /> Mettre à jour
                        @else
                            <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Ajouter le produit
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour Drag & Drop et Toggle -->
    <script>
        // ====== DRAG & DROP ======
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('image');

        // Click to select file
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag & Drop events
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                showFilePreview(files[0]);
            }
        });

        // Preview image on selection
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                showFilePreview(fileInput.files[0]);
            }
        });

        function showFilePreview(file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                dropZone.innerHTML = `
                    <div class="flex flex-col items-center">
                        <img src="${e.target.result}" alt="Preview" class="h-32 object-cover rounded mb-3">
                        <p class="text-sm text-green-600 font-semibold">✅ ${file.name}</p>
                    </div>
                `;
            };

            reader.readAsDataURL(file);
        }

        // ====== TOGGLE SWITCH ======
        const toggleSwitch = document.getElementById('toggle-status');
        const toggleLabel = document.querySelector('.toggle-label');

        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', () => {
                toggleLabel.textContent = toggleSwitch.checked ? '✅ Actif' : '⊘ Inactif';
            });
        }
    </script>
@endsection
