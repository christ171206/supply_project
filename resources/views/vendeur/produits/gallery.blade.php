@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-display font-normal text-black mb-2">Galerie d'Images</h1>
                    <p class="text-gray-600 text-sm">{{ $produit->nom }}</p>
                </div>
                <a href="{{ route('vendeur.produits.edit', $produit->id) }}" class="px-4 py-2 border border-black text-black hover:bg-gray-50 transition-colors rounded">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-10">
        <!-- Upload Zone -->
        <div class="mb-12">
            <div class="border-2 border-dashed border-gray-200 rounded-lg p-8 text-center hover:border-black transition-colors cursor-pointer upload-zone" id="uploadZone">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <p class="text-gray-600 mb-2">Cliquez ou glissez des images ici</p>
                <p class="text-xs text-gray-400">PNG, JPG, WEBP (max. 10MB)</p>
                <input type="file" id="fileInput" multiple class="hidden" accept="image/*">
            </div>
            <div id="uploadProgress" class="mt-4 hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Envoi en cours...</span>
                    <span id="uploadPercentage" class="text-xs text-gray-500">0%</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-black transition-all w-0"></div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="mb-8">
            <h2 class="text-lg font-medium text-black mb-6">Images du produit</h2>
            
            @if($images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="imagesGrid">
                    @foreach($images as $image)
                        <div class="group relative border border-gray-200 rounded-lg overflow-hidden hover:border-black transition-colors" data-image-id="{{ $image->id }}">
                            <!-- Image -->
                            <div class="aspect-square bg-gray-100 overflow-hidden">
                                <img 
                                    src="{{ $image->cloudinary_url }}" 
                                    alt="{{ $produit->nom }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex flex-col items-center justify-center opacity-0 group-hover:opacity-100">
                                <!-- Primary Badge -->
                                @if($image->is_primary)
                                    <div class="absolute top-2 left-2 bg-black text-white px-3 py-1 rounded text-xs font-medium">
                                        Image principale
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-3 flex-wrap justify-center">
                                    @if(!$image->is_primary)
                                        <button 
                                            class="px-3 py-2 bg-white text-black hover:bg-gray-100 rounded font-medium text-sm transition-colors set-primary-btn"
                                            data-image-id="{{ $image->id }}"
                                        >
                                            Définir comme principale
                                        </button>
                                    @endif
                                    <button 
                                        class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded font-medium text-sm transition-colors delete-image-btn"
                                        data-image-id="{{ $image->id }}"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>

                            <!-- Image Info -->
                            <div class="p-3 bg-white border-t border-gray-200">
                                <p class="text-xs text-gray-600 truncate">
                                    {{ $image->width }}×{{ $image->height }}px
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ number_format($image->file_size / 1024, 1) }}KB
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Drag to Reorder Notice -->
                <div class="mt-6 p-3 bg-gray-100 border border-gray-200 rounded text-sm text-gray-600">
                    💡 Vous pouvez réorganiser les images en les glissant-déposant.
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 mb-2">Aucune image pour ce produit</p>
                    <p class="text-sm text-gray-400">Commencez par télécharger une image en utilisant la zone d'upload ci-dessus</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 bg-black text-white px-6 py-3 rounded hidden z-50 max-w-sm">
    <p id="toastMessage"></p>
</div>

<script>
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const uploadPercentage = document.getElementById('uploadPercentage');
    const imagesGrid = document.getElementById('imagesGrid');
    const produitId = {{ $produit->id }};

    // Upload Zone Handlers
    uploadZone.addEventListener('click', () => fileInput.click());
    
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-black', 'bg-gray-50');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('border-black', 'bg-gray-50');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-black', 'bg-gray-50');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', () => {
        handleFiles(fileInput.files);
    });

    function handleFiles(files) {
        if (files.length === 0) return;

        uploadProgress.classList.remove('hidden');
        let completed = 0;

        Array.from(files).forEach((file) => {
            if (!file.type.startsWith('image/')) {
                showToast('Veuillez sélectionner uniquement des images', 'error');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                showToast('Le fichier est trop volumineux (max. 10MB)', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            fetch(`/vendeur/produits/${produitId}/images/upload`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur lors de l\'upload');
                return response.json();
            })
            .then(data => {
                completed++;
                updateProgress(completed, files.length);

                if (data.success) {
                    addImageToGallery(data.image);
                    showToast(`Image "${file.name}" téléchargée avec succès`);
                } else {
                    showToast(`Erreur: ${data.error}`, 'error');
                }

                if (completed === files.length) {
                    setTimeout(() => {
                        uploadProgress.classList.add('hidden');
                        progressBar.style.width = '0%';
                        fileInput.value = '';
                    }, 1500);
                }
            })
            .catch(error => {
                completed++;
                updateProgress(completed, files.length);
                console.error('Upload error:', error);
                showToast('Erreur lors du téléchargement', 'error');
            });
        });
    }

    function updateProgress(completed, total) {
        const percentage = Math.round((completed / total) * 100);
        uploadPercentage.textContent = `${percentage}%`;
        progressBar.style.width = `${percentage}%`;
    }

    function addImageToGallery(image) {
        if (!imagesGrid) return;

        if (imagesGrid.innerHTML.includes('Aucune image')) {
            location.reload();
            return;
        }

        const imageHtml = `
            <div class="group relative border border-gray-200 rounded-lg overflow-hidden hover:border-black transition-colors" data-image-id="${image.id}">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    <img src="${image.url}" alt="Produit" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex flex-col items-center justify-center opacity-0 group-hover:opacity-100">
                    ${image.is_primary ? '<div class="absolute top-2 left-2 bg-black text-white px-3 py-1 rounded text-xs font-medium">Image principale</div>' : ''}
                    <div class="flex gap-3 flex-wrap justify-center">
                        ${!image.is_primary ? `<button class="px-3 py-2 bg-white text-black hover:bg-gray-100 rounded font-medium text-sm transition-colors set-primary-btn" data-image-id="${image.id}">Définir comme principale</button>` : ''}
                        <button class="px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded font-medium text-sm transition-colors delete-image-btn" data-image-id="${image.id}">Supprimer</button>
                    </div>
                </div>
                <div class="p-3 bg-white border-t border-gray-200">
                    <p class="text-xs text-gray-600">${image.width}×${image.height}px</p>
                    <p class="text-xs text-gray-400">${Math.round(image.size / 1024)}KB</p>
                </div>
            </div>
        `;

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = imageHtml;
        imagesGrid.insertBefore(tempDiv.firstElementChild, imagesGrid.firstChild);
        
        attachEventListeners();
    }

    // Set Primary Image
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('set-primary-btn')) {
            const imageId = e.target.dataset.imageId;
            setPrimaryImage(imageId);
        }
    });

    function setPrimaryImage(imageId) {
        fetch(`/vendeur/produits/${produitId}/images/${imageId}/primary`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
                showToast('Image principale mise à jour');
            } else {
                showToast(`Erreur: ${data.error}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erreur lors de la mise à jour', 'error');
        });
    }

    // Delete Image
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-image-btn')) {
            const imageId = e.target.dataset.imageId;
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image?')) {
                deleteImage(imageId);
            }
        }
    });

    function deleteImage(imageId) {
        fetch(`/vendeur/produits/${produitId}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
                if (imageElement) {
                    imageElement.closest('.group').remove();
                    showToast('Image supprimée avec succès');
                    
                    // Reload if no images left
                    if (document.querySelectorAll('[data-image-id]').length === 0) {
                        setTimeout(() => location.reload(), 1500);
                    }
                }
            } else {
                showToast(`Erreur: ${data.error}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erreur lors de la suppression', 'error');
        });
    }

    // Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toastMessage.textContent = message;
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded hidden z-50 max-w-sm transition-all ${
            type === 'error' 
                ? 'bg-red-600 text-white' 
                : 'bg-black text-white'
        }`;
        
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function attachEventListeners() {
        // Re-attach listeners for newly added elements
        document.querySelectorAll('.set-primary-btn').forEach(btn => {
            btn.addEventListener('click', () => setPrimaryImage(btn.dataset.imageId));
        });

        document.querySelectorAll('.delete-image-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Êtes-vous sûr?')) {
                    deleteImage(btn.dataset.imageId);
                }
            });
        });
    }

    attachEventListeners();
</script>
@endsection
