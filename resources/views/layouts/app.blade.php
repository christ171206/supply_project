<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Supply - Boutique Informatique')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <style>
            * { font-family: 'Inter', sans-serif; }
            body { background-color: #f9fafb; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Socket.io Client Library -->
        <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
        <script>
            // Configuration Socket.io URL
            window.SOCKET_IO_URL = '{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}';
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('layouts.navigation-client')

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900 text-gray-300 border-t border-gray-800 mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <!-- About -->
                        <div class="space-y-4">
                            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Supply
                            </h3>
                            <p class="text-sm text-gray-400 leading-relaxed">
                                Votre boutique informatique de confiance en Côte d'Ivoire. Livraison rapide, prix compétitifs, service client réactif.
                            </p>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Navigation</h3>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ route('accueil') }}" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>→</span> Accueil</a></li>
                                <li><a href="{{ route('produits.catalogue') }}" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>→</span> Catalogue</a></li>
                                <li><a href="{{ route('panier.index') }}" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>→</span> Panier</a></li>
                            </ul>
                        </div>

                        <!-- Customer Service -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Support</h3>
                            <ul class="space-y-3 text-sm">
                                <li><a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>?</span> FAQ</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>📋</span> Conditions</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200 flex items-center gap-2"><span>🔒</span> Confidentialité</a></li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Contact</h3>
                            <ul class="space-y-3 text-sm">
                                <li class="text-gray-400">📧 <a href="mailto:info@supply.ci" class="hover:text-primary-400 transition">info@supply.ci</a></li>
                                <li class="text-gray-400">📞 <a href="tel:+22527200000" class="hover:text-primary-400 transition">+225 27 20 00 00 00</a></li>
                                <li class="text-gray-400">📍 Abidjan, Côte d'Ivoire</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-8">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-sm text-gray-500">&copy; 2026 Supply. Tous droits réservés.</p>
                            <div class="flex gap-6">
                                <a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200">𝕏</a>
                                <a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200">f</a>
                                <a href="#" class="text-gray-400 hover:text-primary-400 transition duration-200">in</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Modal Quantité Global -->
        <div id="quantity-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-8 animate-scale-in border border-gray-200">
                <!-- Header -->
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Ajouter au panier</h3>

                <!-- Détails Produit -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Produit</p>
                    <p id="modal-product-name" class="font-bold text-gray-900 mb-4">-</p>

                    <p class="text-sm text-gray-600 mb-1">Stock disponible</p>
                    <p id="modal-stock" class="font-bold text-accent-600">-</p>
                </div>

                <!-- Sélecteur de Quantité -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Quantité</label>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            onclick="decreaseQuantity()"
                            class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-200 text-gray-900 font-bold hover:bg-gray-300 transition"
                        >
                            −
                        </button>
                        <input
                            type="number"
                            id="modal-quantity"
                            value="1"
                            min="1"
                            class="flex-1 text-center text-xl font-bold py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        >
                        <button
                            type="button"
                            onclick="increaseQuantity()"
                            class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-200 text-gray-900 font-bold hover:bg-gray-300 transition"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Prix Total -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Prix total</p>
                    <p id="modal-total-price" class="text-3xl font-bold text-primary-600">-</p>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeQuantityModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition"
                    >
                        Annuler
                    </button>
                    <button
                        type="button"
                        onclick="submitAddToCart()"
                        class="flex-1 px-4 py-3 bg-primary-600 text-white font-bold rounded-lg hover:bg-primary-700 transition"
                    >
                        Ajouter
                    </button>
                </div>
            </div>
        </div>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            let quantityModalData = {
                productId: null,
                productName: null,
                stock: null,
                price: null,
            };

            function openQuantityModal(productId, productName, stock, price) {
                quantityModalData = { productId, productName, stock, price };

                const modal = document.getElementById('quantity-modal');
                document.getElementById('modal-product-name').textContent = productName;
                document.getElementById('modal-stock').textContent = stock + ' unités';
                document.getElementById('modal-quantity').value = 1;
                document.getElementById('modal-quantity').max = stock;

                updateModalPrice();
                modal.classList.remove('hidden');
            }

            function closeQuantityModal() {
                const modal = document.getElementById('quantity-modal');
                if(modal) modal.classList.add('hidden');
            }

            function decreaseQuantity() {
                const input = document.getElementById('modal-quantity');
                if(parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateModalPrice();
                }
            }

            function increaseQuantity() {
                const input = document.getElementById('modal-quantity');
                const maxStock = quantityModalData.stock;
                if(parseInt(input.value) < maxStock) {
                    input.value = parseInt(input.value) + 1;
                    updateModalPrice();
                }
            }

            function updateModalPrice() {
                const quantity = parseInt(document.getElementById('modal-quantity').value);
                const totalPrice = quantity * quantityModalData.price;
                document.getElementById('modal-total-price').textContent =
                    totalPrice.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + ' FCFA';
            }

            function submitAddToCart() {
                const quantity = parseInt(document.getElementById('modal-quantity').value);
                const productId = quantityModalData.productId;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch('/panier/ajouter/' + productId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantite: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeQuantityModal();
                        updateCartBadge();
                        showSuccessNotification(data.message);
                    } else {
                        alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'ajout au panier');
                });
            }

            // Afficher une notification de succès
            function showSuccessNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-accent-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            // Fermer le modal avec Escape
            document.addEventListener('keydown', (e) => {
                if(e.key === 'Escape') closeQuantityModal();
            });

            // Update cart badge
            function updateCartBadge() {
                fetch("{{ route('panier.count') }}")
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('cart-badge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count;
                                badge.classList.remove('hidden');
                            } else {
                                badge.classList.add('hidden');
                            }
                        }
                    });
            }

            // Update cart badge on page load
            updateCartBadge();

            // Listen for cart updates
            document.addEventListener('cart-updated', updateCartBadge);

            // Favoris functionality with localStorage support
            function getFavoritesFromStorage() {
                const favs = localStorage.getItem('favorites');
                return favs ? JSON.parse(favs) : [];
            }

            function saveFavoritesToStorage(favorites) {
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }

            function isFavoritedLocally(productId) {
                return getFavoritesFromStorage().includes(productId);
            }

            async function toggleFavorite(productId, event) {
                event.preventDefault();

                @auth
                    // Utilisateur connecté: sauvegarder en BD
                    try {
                        const response = await fetch(`/favoris/${productId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            updateFavoriteButton(productId, data.is_favorited);
                        }
                    } catch (error) {
                        console.error('Error toggling favorite:', error);
                    }
                @else
                    // Utilisateur non connecté: utiliser localStorage
                    const favorites = getFavoritesFromStorage();
                    const isFavorited = favorites.includes(productId);

                    if (isFavorited) {
                        const index = favorites.indexOf(productId);
                        favorites.splice(index, 1);
                    } else {
                        favorites.push(productId);
                    }

                    saveFavoritesToStorage(favorites);
                    updateFavoriteButton(productId, !isFavorited);
                @endauth
            }

            function updateFavoriteButton(productId, isFavorited) {
                // Update all favorite buttons for this product
                const buttons = document.querySelectorAll(`[data-favorite-btn="${productId}"]`);
                buttons.forEach(btn => {
                    if (isFavorited) {
                        btn.classList.remove('text-gray-400');
                        btn.classList.add('text-red-500', 'animate-pulse');
                        btn.innerHTML = '❤️';
                    } else {
                        btn.classList.remove('text-red-500', 'animate-pulse');
                        btn.classList.add('text-gray-400');
                        btn.innerHTML = '🤍';
                    }
                });
            }

            // Check favorite status on load
            function checkFavoriteStatus(productId) {
                @auth
                    // Utilisateur connecté: vérifier en BD
                    fetch(`/favoris/${productId}/check`)
                        .then(response => response.json())
                        .then(data => updateFavoriteButton(productId, data.is_favorited))
                        .catch(error => console.error('Error checking favorite status:', error));
                @else
                    // Utilisateur non connecté: vérifier localStorage
                    const isFavorited = isFavoritedLocally(productId);
                    updateFavoriteButton(productId, isFavorited);
                @endauth
            }

            // Synchroniser les favoris localStorage vers la BD si l'utilisateur se connecte
            @auth
            function syncLocalFavoritesToDatabase() {
                const localFavorites = getFavoritesFromStorage();
                if (localFavorites.length > 0) {
                    localFavorites.forEach(productId => {
                        fetch(`/favoris/${productId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        }).catch(error => console.error('Error syncing favorite:', error));
                    });
                    // Vider localStorage après sync
                    saveFavoritesToStorage([]);
                }
            }

            // Sync on page load if user just logged in
            document.addEventListener('DOMContentLoaded', syncLocalFavoritesToDatabase);
            @endauth
        </script>
        </script>

        @yield('scripts')
    </body>
</html>
