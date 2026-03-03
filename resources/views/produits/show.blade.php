@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-6 text-xs text-gray-600">
        <a href="{{ route('accueil') }}" class="hover:text-primary-600">Accueil</a>
        <span>/</span>
        <a href="{{ route('produits.catalogue') }}" class="hover:text-primary-600">Catalogue</a>
        <span>/</span>
        @if($produit->categorie)
            <a href="{{ route('produits.catalogue', ['categorie' => $produit->categorie->id]) }}" class="hover:text-primary-600">
                {{ $produit->categorie->nom }}
            </a>
            <span>/</span>
        @endif
        <span class="text-gray-900 font-semibold">{{ Str::limit($produit->nom, 30) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Image Produit avec Galerie -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg overflow-hidden sticky top-20 border border-gray-200 shadow-md">
                <!-- Image Principale -->
                <div class="bg-gray-100 flex items-center justify-center aspect-square overflow-hidden" id="main-image-container">
                    @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                        <img
                            src="{{ asset('storage/produits/' . $produit->images[0]) }}"
                            alt="{{ $produit->nom }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            id="main-image"
                        >
                    @elseif($produit->image)
                        <img
                            src="{{ asset('storage/produits/' . $produit->image) }}"
                            alt="{{ $produit->nom }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            id="main-image"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <div class="text-center">
                                <svg class="w-32 h-32 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-semibold text-gray-400">Image indisponible</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Miniatures (Galerie d'images) -->
                <div class="bg-white p-3 border-t border-gray-200">
                    <div class="flex gap-2 overflow-x-auto">
                        @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                            @foreach($produit->images as $index => $imagePath)
                                <button
                                    class="flex-shrink-0 w-16 h-16 rounded-lg border-2 {{ $index === 0 ? 'border-primary-500' : 'border-gray-300' }} overflow-hidden bg-gray-100 hover:border-primary-400 transition"
                                    title="Image {{ $index + 1 }}"
                                    aria-label="Visualiser l'image {{ $index + 1 }}"
                                    onclick="document.getElementById('main-image').src = '{{ asset('storage/produits/' . $imagePath) }}';"
                                >
                                    <img src="{{ asset('storage/produits/' . $imagePath) }}" alt="{{ $produit->nom }} - Image {{ $index + 1 }}" class="w-full h-full object-cover" />
                                </button>
                            @endforeach
                        @elseif($produit->image)
                            <button
                                class="flex-shrink-0 w-16 h-16 rounded-lg border-2 border-primary-500 overflow-hidden bg-gray-100"
                                title="Image principale"
                                aria-label="Visualiser l'image principale"
                            >
                                <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover" />
                            </button>
                        @else
                            <div class="flex-shrink-0 w-16 h-16 rounded-lg border-2 border-gray-300 bg-gray-100 flex items-center justify-center text-sm text-gray-400">
                                📦
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Cliquez pour agrandir</p>
                </div>
            </div>
        </div>

        <!-- Détails Produit -->
        <div class="lg:col-span-2">
            <!-- Catégorie -->
            @if($produit->categorie)
                <div class="mb-4">
                    <span class="inline-block px-2.5 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-semibold">
                        {{ $produit->categorie->nom }}
                    </span>
                </div>
            @endif

            <!-- Titre -->
            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $produit->nom }}</h1>

            <!-- Vendeur - Simplifié -->
            @if($produit->vendeur)
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center text-sm font-bold text-primary-700">
                            {{ strtoupper(substr($produit->vendeur->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">VENDU PAR</p>
                            <p class="font-semibold text-gray-900">{{ $produit->vendeur->shop_name ?? $produit->vendeur->name }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Prix -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-bold text-primary-600">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                    @if($produit->prix_original && $produit->prix_original > $produit->prix)
                        <span class="text-sm text-gray-500 line-through">
                            {{ number_format($produit->prix_original, 2, ',', ' ') }} €
                        </span>
                        <span class="px-2.5 py-1 bg-danger-100 text-danger-700 rounded-full text-xs font-semibold">
                            -{{ round(((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100)) }}%
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stock -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                @if($produit->stock > 0)
                    <span class="text-accent-600 font-semibold text-sm">✓ {{ $produit->stock }} en stock</span>
                @else
                    <span class="text-danger-600 font-semibold text-sm">✗ Rupture de stock</span>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $produit->description }}</p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3 mb-8">
                <div class="flex gap-3">
                    @if($produit->stock > 0)
                        <button
                            type="button"
                            onclick="openQuantityModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->stock }}, {{ $produit->prix }})"
                            class="btn-primary flex-1 py-3"
                        >
                            Ajouter au Panier
                        </button>
                    @else
                        <button disabled class="btn-secondary flex-1 py-3 opacity-50 cursor-not-allowed">
                            Indisponible
                        </button>
                    @endif
                    <button
                        onclick="toggleFavorite({{ $produit->id }}, event)"
                        data-favorite-btn="{{ $produit->id }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg hover:bg-primary-50 hover:border-primary-400 transition-all duration-200 font-semibold text-lg"
                        title="Ajouter à mes favoris"
                        aria-label="Ajouter ce produit à mes favoris"
                    >
                        🤍
                    </button>
                </div>
                <!-- Bouton Contacter le Vendeur -->
                <button
                    type="button"
                    onclick="openContactModal({{ $produit->vendeur->id ?? 0 }}, '{{ addslashes($produit->vendeur->shop_name ?? $produit->vendeur->name ?? 'Vendeur') }}', {{ $produit->id }}, '{{ addslashes($produit->nom) }}')"
                    class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-primary-500/50 transition-all duration-200"
                >
                    💬 Contacter le Vendeur
                </button>

                <!-- Bouton WhatsApp -->
                <a
                    href="https://wa.me/{{ config('services.whatsapp.contact_phone') }}?text=Je suis intéressé par : {{ urlencode($produit->nom) }} - {{ url(route('produits.show', $produit->id)) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="w-full py-3 px-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-green-500/50 transition-all duration-200 text-center block"
                >
                    💚 Contacter sur WhatsApp
                </a>
            </div>

            <!-- Informations -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-4 text-sm">Informations Produit</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 text-xs">Références</p>
                        <p class="text-gray-900 font-semibold">#{{ $produit->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Catégorie</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->categorie?->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Stock</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->stock }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Statut</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->est_actif ? '✓ Actif' : '✗ Inactif' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avis Clients -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Avis Clients</h2>

        @if($avis && $avis->count() > 0)
            <div class="space-y-4">
                @foreach($avis as $av)
                    <div class="card p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $av->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $av->created_at->locale('fr')->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm">{{ $i <= $av->note ? '⭐' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-700 text-sm">{{ $av->commentaire }}</p>
                        @auth
                            @if(auth()->id() === $av->user_id)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <form action="{{ route('avis.destroy', $av->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="text-xs text-danger-600 hover:text-danger-700">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
            @if($avis->hasPages())
                <div class="mt-6">
                    {{ $avis->links() }}
                </div>
            @endif
        @else
            <div class="card text-center p-8">
                <p class="text-4xl mb-2">💬</p>
                <p class="text-gray-600">Aucun avis pour le moment</p>
            </div>
        @endif
    </div>

    <!-- Formulaire Ajouter Avis -->
    @auth
        <div id="form-avis" class="card p-8 mb-12">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Votre Avis</h3>
            <p class="text-sm text-gray-600 mb-6">Aidez les autres clients à faire le bon choix</p>

            <form action="{{ route('avis.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                <!-- Note -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Votre Note</label>
                    <div class="flex gap-3" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer text-2xl">
                                <input type="radio" name="note" value="{{ $i }}" class="hidden rating-input" required>
                                <span class="rating-star">☆</span>
                            </label>
                        @endfor
                    </div>
                    @error('note')
                        <p class="text-danger-600 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const labels = document.querySelectorAll('#rating-stars label');
                        const stars = document.querySelectorAll('#rating-stars .rating-star');
                        const inputs = document.querySelectorAll('#rating-stars .rating-input');

                        labels.forEach((label, index) => {
                            label.addEventListener('mouseenter', () => {
                                stars.forEach((s, i) => s.textContent = i <= index ? '⭐' : '☆');
                            });
                            label.addEventListener('click', () => { inputs[index].checked = true; });
                        });
                        document.getElementById('rating-stars').addEventListener('mouseleave', () => {
                            const selected = Array.from(inputs).findIndex(i => i.checked);
                            stars.forEach((s, i) => s.textContent = i <= selected ? '⭐' : '☆');
                        });
                    });
                </script>

                <!-- Commentaire -->
                <div>
                    <label for="commentaire" class="block text-sm font-semibold text-gray-900 mb-2">Votre Avis</label>
                    <textarea
                        id="commentaire"
                        name="commentaire"
                        rows="4"
                        placeholder="Partagez votre expérience... (min. 10 caractères)"
                        required
                        class="textarea-field text-sm @error('commentaire') border-danger-500 @enderror"
                    ></textarea>
                    @error('commentaire')
                        <p class="text-danger-600 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1">
                        Publier
                    </button>
                    <button type="reset" class="btn-secondary flex-1">
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="card p-8 mb-12 text-center">
            <p class="text-gray-600 mb-4 text-sm">Connectez-vous pour laisser un avis</p>
            <a href="{{ route('login') }}" class="btn-primary inline-block">
                Se Connecter
            </a>
        </div>
    @endauth

    <!-- Modal Contacter Vendeur -->
    <div id="contactVendorModal" class="modal-hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" onclick="closeContactModal(event)">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <!-- Header du modal -->
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Contacter le vendeur</h3>
                    <p id="modalVendorName" class="text-sm text-gray-600"></p>
                </div>
                <button onclick="closeContactModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>

            <!-- Corps du modal -->
            <div class="p-6">
                @auth
                    <!-- Message de succès -->
                    <div id="successMessage" class="hidden mb-4 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">✓</span>
                            <div>
                                <p class="font-semibold">Message envoyé avec succès!</p>
                                <p class="text-sm mt-1">Vous pouvez consulter votre conversation dans <a href="{{ route('client.messages') }}" class="underline font-semibold">Mes Messages</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du Produit -->
                    <div id="productPreview" class="hidden mb-6 p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg">
                        <p class="text-xs font-semibold text-blue-600 mb-2">📦 PRODUIT</p>
                        <div class="flex gap-3">
                            <div id="productImage" class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                <span class="text-2xl">📦</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p id="productNamePreview" class="font-semibold text-gray-900 text-sm truncate"></p>
                                <p id="productPricePreview" class="text-xs text-blue-600 mt-1"></p>
                                <p id="productStockPreview" class="text-xs text-gray-500 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <form id="contactForm" class="space-y-4" onsubmit="return submitContactForm(event)">
                        @csrf
                        <input type="hidden" name="destinataire_id" id="modalVendorId" value="">
                        <input type="hidden" name="produit_id" id="modalProduitId" value="">

                        <div id="formError" class="p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg hidden" role="alert">
                            <p id="errorMessage"></p>
                        </div>

                        <!-- Objet du message -->
                        <div>
                            <label for="sujet" class="block text-sm font-semibold text-gray-900 mb-2">Sujet</label>
                            <input
                                type="text"
                                id="sujet"
                                name="sujet"
                                readonly
                                class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-sm"
                            >
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">Message</label>
                            <textarea
                                id="message"
                                name="contenu"
                                rows="5"
                                placeholder="Posez votre question au vendeur..."
                                minlength="5"
                                required
                                oninput="validateMessageLength(this)"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors text-sm resize-none"
                            ></textarea>
                            <p id="charWarning" class="text-xs text-gray-500 mt-1">Minimum 5 caractères requis</p>
                        </div>

                        <!-- Boutons -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="button" onclick="closeContactModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                Annuler
                            </button>
                            <button type="submit" id="submitContactBtn" class="flex-1 px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Envoyer
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8">
                        <p class="text-4xl mb-4">🔐</p>
                        <p class="text-gray-600 mb-6">Vous devez être connecté pour contacter un vendeur</p>
                        <a href="{{ route('login') }}" class="btn-primary inline-block">
                            Se Connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <script>
        // Fonction pour ouvrir le modal de contact vendeur
        function openContactModal(vendorId, vendorName, productId, productName) {
            @auth
                // Convertir en nombre si c'est une chaîne
                vendorId = parseInt(vendorId);
                productId = parseInt(productId);

                console.log('Ouverture du modal avec:', { vendorId, vendorName, productId, productName });

                // Vérifier que le vendeur ID est valide
                if (!vendorId || vendorId === 0) {
                    alert('Erreur: Ce produit n\'a pas de vendeur associé');
                    return;
                }

                const modal = document.getElementById('contactVendorModal');
                modal.classList.remove('modal-hidden');
                modal.classList.add('modal-shown');
                document.getElementById('modalVendorName').textContent = vendorName;
                document.getElementById('modalVendorId').value = vendorId;
                document.getElementById('modalProduitId').value = productId;
                document.getElementById('sujet').value = '📦 Demande sur: ' + productName;
                document.getElementById('message').focus();
                document.body.style.overflow = 'hidden';

                // Récupérer les informations du produit et afficher l'aperçu
                if (productId > 0) {
                    const productPreview = document.getElementById('productPreview');
                    const productNamePreview = document.getElementById('productNamePreview');
                    const productPricePreview = document.getElementById('productPricePreview');
                    const productStockPreview = document.getElementById('productStockPreview');
                    const productImage = document.getElementById('productImage');

                    // Afficher le nom du produit immédiatement
                    productNamePreview.textContent = productName;
                    productPreview.classList.remove('hidden');

                    // Récupérer les détails via AJAX
                    fetch(`/api/produits/${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const produit = data.data;
                                productPricePreview.textContent = `💰 ${new Intl.NumberFormat('fr-FR').format(produit.prix)} F CFA`;
                                productStockPreview.textContent = `📊 Stock: ${produit.stock}`;

                                // Afficher l'image du produit si disponible
                                if (produit.image) {
                                    productImage.innerHTML = `<img src="{{ asset('storage/produits') }}/${produit.image}" alt="${produit.nom}" class="w-full h-full object-cover rounded-lg">`;
                                }
                            }
                        })
                        .catch(error => {
                            console.log('Erreur récupération produit:', error);
                            // Si erreur, afficher juste le nom et prix par défaut
                            productPricePreview.textContent = '💰 Prix non disponible';
                        });
                }
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        }

        // Fonction pour fermer le modal
        function closeContactModal(event) {
            const modal = document.getElementById('contactVendorModal');
            // Si on clique en dehors du modal, le fermer
            if (event && event.target.id === 'contactVendorModal') {
                modal.classList.remove('modal-shown');
                modal.classList.add('modal-hidden');
                document.body.style.overflow = 'auto';
            }
            // Si on clique sur le bouton X, le fermer
            else if (!event) {
                modal.classList.remove('modal-shown');
                modal.classList.add('modal-hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Fermer le modal avec Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('contactVendorModal');
                modal.classList.remove('modal-shown');
                modal.classList.add('modal-hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Valider la longueur du message
        function validateMessageLength(textarea) {
            const warning = document.getElementById('charWarning');
            const submitBtn = document.getElementById('submitContactBtn');
            const length = textarea.value.trim().length;

            if (length < 5) {
                warning.textContent = `Vous avez ${length} caractère(s), minimum 5 requis`;
                warning.className = 'text-xs text-red-600 mt-1 font-semibold';
                submitBtn.disabled = true;
            } else {
                warning.textContent = '✓ Message valide';
                warning.className = 'text-xs text-green-600 mt-1 font-semibold';
                submitBtn.disabled = false;
            }
        }

        // Ajouter l'événement typing au textarea du modal
        document.getElementById('message').addEventListener('input', function() {
            validateMessageLength(this);
        });

        // Valider le formulaire de contact avant envoi
        async function submitContactForm(event) {
            event.preventDefault();

            const message = document.getElementById('message').value.trim();
            const vendorIdInput = document.getElementById('modalVendorId').value;
            const vendorId = parseInt(vendorIdInput);
            const productIdInput = document.getElementById('modalProduitId').value;
            const productId = parseInt(productIdInput);
            const errorDiv = document.getElementById('formError');
            const errorMessage = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            const submitBtn = document.getElementById('submitContactBtn');
            const csrfToken = document.querySelector('[name="_token"]').value;

            console.log('Envoi du message:', {
                vendorId,
                productId,
                message: message.substring(0, 50) + '...',
                csrfToken: csrfToken ? 'OK' : 'MISSING'
            });

            // Réinitialiser les messages
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            errorMessage.textContent = '';

            // Vérifier le message
            if (message.length < 5) {
                errorMessage.textContent = 'Le message doit contenir au moins 5 caractères';
                errorDiv.classList.remove('hidden');
                return false;
            }

            // Vérifier le vendeur
            if (!vendorId || vendorId === 0 || isNaN(vendorId)) {
                errorMessage.textContent = 'Erreur: ID vendeur invalide (' + vendorIdInput + ')';
                errorDiv.classList.remove('hidden');
                return false;
            }

            // Afficher un message de chargement
            submitBtn.disabled = true;
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="animate-spin">⌛</span> Envoi...';

            try {
                // Envoyer le message via AJAX
                const response = await fetch('{{ route("messages.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        destinataire_id: vendorId,
                        produit_id: productId,
                        contenu: message
                    })
                });

                const data = await response.json();

                console.log('Réponse du serveur:', { status: response.status, data });

                if (!response.ok) {
                    // Si c'est une erreur de validation, afficher les messages d'erreur
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        throw new Error(errorMessages);
                    }
                    throw new Error(data.message || 'Erreur lors de l\'envoi du message');
                }

                // Afficher le message de succès
                successDiv.classList.remove('hidden');
                document.getElementById('message').value = '';
                validateMessageLength(document.getElementById('message'));

                // Fermer le modal après 3 secondes
                setTimeout(() => {
                    closeContactModal();
                    successDiv.classList.add('hidden');
                }, 3000);

            } catch (error) {
                console.error('Erreur AJAX:', error);
                errorMessage.textContent = error.message || 'Erreur lors de l\'envoi du message. Veuillez réessayer.';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }

            return false;
        }
    </script>

    <!-- Produits Similaires -->
    @if($produitsSimilaires && count($produitsSimilaires) > 0)
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits Similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($produitsSimilaires as $similaire)
                    @include('components.carte-produit', ['produit' => $similaire])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
