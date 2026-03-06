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
                                {{ old('est_actif', $produit->est_actif ?? true) ? 'Actif' : '⌀ Inactif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- ROW 5: Images (Drag & Drop Multi-fichiers) -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-3">Images du produit (Maximum 5 images)</label>
                    <p class="text-xs text-gray-500 mb-3">📁 Stockées en : <code class="bg-gray-100 px-2 py-1 rounded text-xs">storage/app/public/produits/</code></p>

                    <!-- Images actuelles -->
                    @if(isset($produit) && $produit->images && is_array($produit->images))
                        <div class="mb-6">
                            <p class="text-sm text-gray-700 font-semibold mb-3">📷 Images actuelles ({{ count($produit->images) }}/5)</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($produit->images as $index => $imagePath)
                                    @if(file_exists(storage_path('app/public/' . $imagePath)))
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Image {{ $index + 1 }}" class="h-32 w-full object-cover rounded border border-gray-200">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                <span class="text-white text-xs font-semibold">Image {{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Les nouvelles images remplaceront les existantes</p>
                        </div>
                    @endif

                    <!-- Zone de dépôt pour nouvelles images -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer" id="dropZone">
                        <p class="text-4xl mb-3">🖼️</p>
                        <p class="text-gray-700 font-semibold">Glissez jusqu'à 5 images ici ou cliquez</p>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG • Max 5MB chacune</p>
                    </div>

                    <!-- Preview des images sélectionnées -->
                    <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Les aperçus vont ici -->
                    </div>

                    <input type="file" name="images[]" id="images" accept="image/jpeg,image/png" multiple
                           class="hidden" id="fileInput">

                    @error('images')
                        <p class="text-red-500 text-sm mt-2">❌ {{ $message }}</p>
                    @enderror
                    @error('images.*')
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
                            <x-heroicon-o-check-circle class="w-4 h-4 inline mr-1" /> Mettre à jour
                        @else
                            <x-heroicon-o-check-circle class="w-4 h-4 inline mr-1" /> Ajouter le produit
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour Drag & Drop Multi-images et Toggle -->
    <script>
        // ====== DRAG & DROP MULTI-IMAGES ======
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview-container');
        const MAX_IMAGES = 5;
        let selectedFiles = new DataTransfer();

        // Click to select files
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

            const droppedFiles = e.dataTransfer.files;
            handleFiles(droppedFiles);
        });

        // Preview images on selection
        fileInput.addEventListener('change', () => {
            handleFiles(fileInput.files);
        });

        function handleFiles(files) {
            // Limiter à 5 fichiers
            const filesToAdd = Array.from(files).slice(0, MAX_IMAGES);

            // Ajouter les fichiers au DataTransfer
            selectedFiles = new DataTransfer();
            filesToAdd.forEach(file => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.items.add(file);
                }
            });

            // Limiter à 5 au total
            if (selectedFiles.items.length > MAX_IMAGES) {
                for (let i = selectedFiles.items.length - 1; i >= MAX_IMAGES; i--) {
                    selectedFiles.items.remove(i);
                }
            }

            // Mettre à jour l'input file
            fileInput.files = selectedFiles.files;

            // Afficher les aperçus
            showPreviews(selectedFiles.files);
            
            // Mettre à jour le texte du dropZone sans perdre les event listeners
            updateDropZoneText(selectedFiles.files.length);
        }

        function updateDropZoneText(currentCount) {
            const label = dropZone.querySelector('p:first-child');
            const sublabel = dropZone.querySelector('p:last-child');
            
            if (currentCount > 0) {
                if (label) {
                    label.innerHTML = `${currentCount} image(s) sélectionnée(s) (${currentCount}/${MAX_IMAGES})`;
                }
                if (sublabel) {
                    sublabel.innerHTML = `Glissez d'autres images pour les ajouter`;
                }
            } else {
                if (label) {
                    label.innerHTML = `🖼️`;
                }
                if (sublabel) {
                    sublabel.innerHTML = `JPG, PNG • Max 5MB chacune`;
                }
            }
        }

        function showPreviews(files) {
            previewContainer.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="h-32 w-full object-cover rounded border-2 border-green-400">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">Image ${index + 1}</span>
                        </div>
                    `;
                    previewContainer.appendChild(previewDiv);
                };

                reader.readAsDataURL(file);
            });
        }

        // ====== TOGGLE SWITCH ======
        const toggleSwitch = document.getElementById('toggle-status');
        const toggleLabel = document.querySelector('.toggle-label');

        if (toggleSwitch) {
            toggleSwitch.addEventListener('change', () => {
                toggleLabel.textContent = toggleSwitch.checked ? 'Actif' : '⌀ Inactif';
            });
        }
    </script>
@endsection
