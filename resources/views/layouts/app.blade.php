<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Supply — Boutique Minimaliste')</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#0a0a0a">
        <meta name="description" content="Plateforme e-commerce minimaliste pour acheter et vendre avec élégance">
        <meta name="app-name" content="Supply">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Supply">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-status-bar-style" content="black">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" type="image/png" href="/icons/icon-192x192.png">
        <link rel="apple-touch-icon" href="/icons/apple-touch-icon-180x180.png">
        <link rel="shortcut icon" href="/favicon.ico">

        <!-- Fonts from Google -->
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/modals.css', 'resources/js/app.js'])

        <!-- Socket.io Client Library -->
        <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
        <script>
            window.SOCKET_IO_URL = '{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}';
        </script>
    </head>
    <body class="font-body bg-off-white text-black antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('components.navbar')

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('partials.footer-minimal')
        </div>

        <!-- Modals -->
        <div id="quantity-modal" class="modal-hidden fixed inset-0 bg-black/40 backdrop-blur items-center justify-center z-100 p-3 sm:p-4">
            <div class="bg-white rounded-xl border border-[#e0e0dc] w-full max-w-md p-4 sm:p-8 transition duration-25 overflow-y-auto max-h-[90vh] sm:max-h-none">
                <h3 class="text-display-2 font-display mb-6">Ajouter au panier</h3>

                <!-- Détails Produit -->
                <div class="bg-[#f7f7f5] rounded-lg p-4 mb-6 border border-[#e0e0dc]">
                    <p class="text-xs text-[#666660] mb-1">Produit</p>
                    <p id="modal-product-name" class="font-bold text-[#0a0a0a] mb-4">-</p>

                    <p class="text-sm text-[#666660] mb-1">Stock disponible</p>
                    <p id="modal-stock" class="font-bold text-[#0a0a0a]">-</p>
                </div>

                <!-- Sélecteur de Quantité -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-[#2a2a28] mb-3">Quantité</label>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            onclick="decreaseQuantity()"
                            class="w-12 h-12 flex items-center justify-center rounded-lg bg-[#efefed] text-[#0a0a0a] font-bold hover:bg-[#e0e0dc] transition"
                        >
                            −
                        </button>
                        <input
                            type="number"
                            id="modal-quantity"
                            value="1"
                            min="1"
                            step="1"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="validateQuantity()"
                            onchange="validateQuantity()"
                            onkeydown="return /[0-9]/.test(event.key) || ['Backspace','Delete','Tab'].includes(event.key)"
                            class="flex-1 text-center text-xl font-bold py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] bg-white"
                        >
                        <button
                            type="button"
                            onclick="increaseQuantity()"
                            class="w-12 h-12 flex items-center justify-center rounded-lg bg-[#efefed] text-[#0a0a0a] font-bold hover:bg-[#e0e0dc] transition"
                        >
                            +
                        </button>
                    </div>
                    <p id="quantity-warning" class="text-sm text-red-600 mt-2 hidden">
                        Quantité maximale: <span id="max-qty">0</span> unités
                    </p>
                </div>

                <!-- Prix Total -->
                <div class="bg-[#f7f7f5] rounded-lg p-4 mb-6 border border-[#e0e0dc]">
                    <p class="text-sm text-[#666660] mb-1">Prix total</p>
                    <p id="modal-total-price" class="text-3xl font-bold text-[#0a0a0a]">-</p>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeQuantityModal()"
                        class="flex-1 px-4 py-3 bg-[#efefed] text-[#0a0a0a] font-bold rounded-lg hover:bg-[#e0e0dc] transition"
                    >
                        Annuler
                    </button>
                    <button
                        type="button"
                        onclick="submitAddToCart()"
                        class="flex-1 px-4 py-3 bg-[#0a0a0a] text-white font-bold rounded-lg hover:bg-[#2a2a28] transition"
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
                const input = document.getElementById('modal-quantity');

                document.getElementById('modal-product-name').textContent = productName;
                document.getElementById('modal-stock').textContent = stock + ' unités';

                // Réinitialiser le champ de quantité
                input.value = '1';
                input.min = '1';
                input.max = stock.toString();
                document.getElementById('max-qty').textContent = stock;
                document.getElementById('quantity-warning').classList.add('hidden');

                updateModalPrice();

                // Prevent layout shift by hiding scrollbar
                document.body.style.overflow = 'hidden';
                modal.classList.remove('modal-hidden');
                modal.classList.add('modal-shown');
            }

            function closeQuantityModal() {
                const modal = document.getElementById('quantity-modal');
                if(modal) {
                    modal.classList.remove('modal-shown');
                    modal.classList.add('modal-hidden');
                    // Restore scrollbar
                    document.body.style.overflow = 'auto';
                }
            }

            // Valider et limiter la quantité selon le stock
            function validateQuantity() {
                const input = document.getElementById('modal-quantity');
                let value = input.value ? String(input.value).trim() : '1';

                if (!value || value === '') {
                    input.value = '1';
                    updateModalPrice();
                    return;
                }

                // Accepter seulement les chiffres
                let cleanValue = value.replace(/[^0-9]/g, '');

                if (!cleanValue) {
                    input.value = '1';
                    updateModalPrice();
                    return;
                }

                let intValue = parseInt(cleanValue, 10);
                const maxStock = quantityModalData.stock || 1;

                if (intValue < 1) {
                    intValue = 1;
                } else if (intValue > maxStock) {
                    intValue = maxStock;
                }

                input.value = String(intValue);
                updateModalPrice();
            }

            function decreaseQuantity() {
                const input = document.getElementById('modal-quantity');
                const warning = document.getElementById('quantity-warning');
                if(parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    warning.classList.add('hidden');
                    updateModalPrice();
                }
            }

            function increaseQuantity() {
                const input = document.getElementById('modal-quantity');
                const warning = document.getElementById('quantity-warning');
                const maxStock = quantityModalData.stock;
                if(parseInt(input.value) < maxStock) {
                    input.value = parseInt(input.value) + 1;
                    warning.classList.add('hidden');
                    updateModalPrice();
                } else {
                    warning.classList.remove('hidden');
                }
            }

            function updateModalPrice() {
                const quantity = parseInt(document.getElementById('modal-quantity').value);
                const totalPrice = quantity * quantityModalData.price;
                document.getElementById('modal-total-price').textContent =
                    totalPrice.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + ' FCFA';
            }

            function submitAddToCart() {
                const input = document.getElementById('modal-quantity');

                // Sécurité multiple pour garantir une valeur valide
                let rawValue = input.value ? String(input.value).trim() : '';

                // Si vide ou invalide, utiliser 1
                if (!rawValue) {
                    rawValue = '1';
                    input.value = '1';
                }

                // Extraire les chiffres seulement
                let cleanValue = rawValue.replace(/[^0-9]/g, '');

                if (!cleanValue) {
                    cleanValue = '1';
                    input.value = '1';
                }

                // Convertir en entier sûr
                let quantity = parseInt(cleanValue, 10);

                // Vérifier que la conversion a fonctionné
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    input.value = '1';
                }

                const productId = quantityModalData.productId;
                const maxStock = quantityModalData.stock;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Dernière vérification des limites
                if (quantity > maxStock) {
                    quantity = maxStock;
                }

                console.log('Envoi au serveur - Quantité:', quantity, 'Type:', typeof quantity);

                // Envoyer au serveur
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
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        closeQuantityModal();
                        updateCartBadge();
                        showSuccessNotification(data.message);
                    } else {
                        showErrorNotification(data.message || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    let errorMsg = 'Erreur de connexion';
                    if (error && error.message) {
                        errorMsg = error.message;
                    }
                    showErrorNotification(errorMsg);
                });
            }

            // Afficher une notification de succès
            function showSuccessNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-[#0a0a0a] text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                notification.textContent = message;
                notification.style.animation = 'slideIn 0.3s ease-in-out';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-in-out';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Afficher une notification d'erreur
            function showErrorNotification(message) {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                notification.textContent = message;
                notification.style.animation = 'slideIn 0.3s ease-in-out';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-in-out';
                    setTimeout(() => notification.remove(), 300);
                }, 4000);
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
                                badge.classList.remove('modal-hidden');
                                badge.classList.add('modal-shown');
                            } else {
                                badge.classList.remove('modal-shown');
                                badge.classList.add('modal-hidden');
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
                        btn.classList.remove('text-[#a0a09a]');
                        btn.classList.add('text-[#dc2626]');
                        // Use a filled heart SVG
                        btn.innerHTML = '<svg class="w-6 h-6 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
                    } else {
                        btn.classList.remove('text-[#dc2626]');
                        btn.classList.add('text-[#a0a09a]');
                        // Use an outline heart SVG
                        btn.innerHTML = '<svg class="w-6 h-6 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
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
            document.addEventListener('DOMContentLoaded', function() {
                syncLocalFavoritesToDatabase();

                // Check favorite status for all favorite buttons on the page
                const favoriteButtons = document.querySelectorAll('[data-favorite-btn]');
                favoriteButtons.forEach(btn => {
                    const productId = btn.dataset.favoritBtn;
                    if (productId) {
                        checkFavoriteStatus(productId);
                    }
                });
            });
            @endauth
        </script>
        </script>

        <!-- Composant Modal de Confirmation -->
        @include('components.confirmation-modal')

        @yield('scripts')
    </body>
</html>
