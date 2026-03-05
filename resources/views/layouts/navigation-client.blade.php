<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Badge Mode Client Admin -->
    @auth
        @if(auth()->user()->is_admin && session('admin_client_mode'))
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 text-sm font-bold">
                            <x-heroicon-o-eye class="w-4 h-4" />
                            <span>MODE VISUALISATION CLIENT ACTIF</span>
                        </div>
                        <span class="text-xs bg-blue-500 px-2 py-1 rounded">Naviguation en lecture seule</span>
                    </div>
                    <form method="POST" action="{{ route('admin.mode.client-exit') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-bold px-3 py-1 bg-blue-800 hover:bg-blue-900 rounded transition flex items-center gap-1.5">
                            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
                            <span>Revenir à l'Admin</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endauth

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center -ml-4">
                <a href="{{ route('accueil') }}" class="flex items-center gap-2 group">
                    <div class="bg-primary-600 p-2 rounded-lg group-hover:bg-primary-700 transition duration-200">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Supply</span>
                </a>
            </div>

            <!-- Navigation Desktop -->
            <div class="hidden md:flex items-center gap-6 flex-1 justify-start ml-8">
                <!-- Home -->
                <a href="{{ route('accueil') }}" class="relative px-4 py-2 text-gray-800 hover:text-primary-600 font-medium transition duration-150 flex items-center gap-2">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <span>Accueil</span>
                </a>

                <!-- Catalogue -->
                <a href="{{ route('produits.catalogue') }}" class="relative px-4 py-2 text-gray-800 hover:text-primary-600 font-medium transition duration-150 flex items-center gap-2">
                    <x-heroicon-o-cube class="w-5 h-5" />
                    <span>Catalogue</span>
                </a>

                <!-- Search Bar -->
                <form action="{{ route('produits.catalogue') }}" method="GET" class="flex-1 max-w-sm">
                    <div class="relative">
                        <input type="text" name="recherche" placeholder="Rechercher un produit..."
                               class="w-full px-4 pr-11 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white focus:border-primary-300 transition duration-150" />
                        <button type="submit" class="absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-primary-600 transition p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Panier -->
                <a href="{{ route('panier.index') }}" class="relative px-4 py-2 text-gray-800 hover:text-primary-600 font-medium transition duration-150 flex items-center gap-2">
                    <x-heroicon-o-shopping-cart class="w-5 h-5" />
                    <span>Panier</span>
                    <span id="cart-badge" class="absolute -top-2 -right-1 bg-danger-600 text-white text-xs rounded-full w-5 h-5 items-center justify-center font-bold shadow-sm @if(!auth()->check() || !auth()->user()->panier || auth()->user()->panier->items->count() === 0) modal-hidden @else modal-shown @endif">
                        {{ auth()->check() && auth()->user()->panier ? auth()->user()->panier->items->count() : '0' }}
                    </span>
                </a>

                <!-- Favoris (for all users) -->
                <a href="{{ route('favoris.index') }}" class="relative px-4 py-2 text-gray-800 hover:text-accent-600 font-medium transition duration-150 flex items-center gap-2">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    <span>Favoris</span>
                </a>

                <!-- Messages (for authenticated users only) -->
                @auth
                    <a href="{{ route('client.messages') }}" class="relative px-4 py-2 text-gray-800 hover:text-primary-600 font-medium transition duration-150 group flex items-center gap-2">
                        <x-heroicon-o-chat-bubble-left class="w-5 h-5" />
                        <span>Messages</span>
                        @php
                            $unreadCount = \App\Models\Message::where('to_user_id', Auth::id())->where('lu', false)->count();
                        @endphp
                        <span id="messages-badge" class="absolute -top-2 -right-1 bg-danger-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-sm transition-transform group-hover:scale-110" style="display: {{ $unreadCount === 0 ? 'none' : 'flex' }};">
                            <span id="unread-count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        </span>
                    </a>

                    <script>
                        // Mettre à jour le badge de messages en temps réel
                        document.addEventListener('DOMContentLoaded', function() {
                            // Charger le nombre initial
                            updateMessagesBadge();

                            // Mettre à jour toutes les 3 secondes
                            setInterval(updateMessagesBadge, 3000);
                        });

                        function updateMessagesBadge() {
                            fetch('{{ route('messages.unread-count') }}')
                                .then(response => response.json())
                                .then(data => {
                                    const badge = document.getElementById('messages-badge');
                                    const countSpan = document.getElementById('unread-count');

                                    if (data.count > 0) {
                                        badge.classList.remove('hidden');
                                        badge.classList.add('flex', 'animate-pulse');
                                        countSpan.textContent = data.count > 9 ? '9+' : data.count;
                                    } else {
                                        badge.classList.add('hidden');
                                        badge.classList.remove('flex', 'animate-pulse');
                                    }
                                })
                                .catch(error => console.error('Erreur mise à jour badge:', error));
                        }

                        // Avec Socket.io si disponible
                        if (typeof io !== 'undefined') {
                            try {
                                const socket = io('{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}');

                                socket.emit('user-connect', {
                                    userId: {{ Auth::id() }},
                                    name: '{{ Auth::user()->name }}'
                                });

                                socket.on('message-notification', function(data) {
                                    console.log('Nouveau message reçu!');
                                    updateMessagesBadge();

                                    // Animation du badge
                                    const badge = document.getElementById('messages-badge');
                                    if (badge) {
                                        badge.style.animation = 'pulse 1s infinite';
                                    }
                                });
                            } catch (e) {
                                console.log('Socket.io non disponible');
                            }
                        }
                    </script>
                @endauth
            </div>

            <!-- User Menu & Mobile -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-1 px-2 py-2 text-gray-700 hover:text-primary-600 transition duration-150 whitespace-nowrap">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-lg border border-gray-200 object-cover flex-shrink-0">
                            @else
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(Auth::user()->email) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-lg border border-gray-200 object-cover flex-shrink-0">
                            @endif
                            <span class="font-medium text-sm truncate max-w-24" :title="'{{ Auth::user()->name }}'">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition duration-200 flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition @click.outside="open = false"
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-md py-2 z-50">

                            @if(Auth::user()->role === 'vendor')
                                <a href="{{ route('vendeur.dashboard') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition font-semibold border-b border-gray-100 flex items-center gap-2">
                                    <x-heroicon-o-building-storefront class="w-5 h-5" />
                                    <span>Espace Vendeur</span>
                                </a>
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition flex items-center gap-2">
                                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                                    <span>Mes Commandes</span>
                                </a>
                                <form method="POST" action="{{ route('vendeur.switch-client') }}" class="border-t border-gray-100">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition font-semibold flex items-center gap-2">
                                        <x-heroicon-o-arrow-right class="w-5 h-5" />
                                        <span>Passer en mode client</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition font-semibold border-b border-gray-100 flex items-center gap-2">
                                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                                    <span>Mon Compte</span>
                                </a>
                                <a href="{{ route('client.commandes') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition flex items-center gap-2">
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                    <span>Mes Commandes</span>
                                </a>
                            @endif

                            <a href="{{ route('client.profil') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition flex items-center gap-2">
                                <x-heroicon-o-user class="w-5 h-5" />
                                <span>Mon Profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" 
                                  data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?"
                                  data-confirm-title="Déconnexion"
                                  data-confirm-type="warning"
                                  data-confirm-button="Déconnexion"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" class="block text-left px-4 py-2.5 text-danger-600 hover:bg-danger-50 transition font-semibold border-t border-gray-100 flex items-center gap-2">
                                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Auth Links -->
                    <div class="hidden md:flex gap-3 items-center">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-800 hover:text-primary-600 font-semibold transition duration-150 flex items-center gap-2">
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                            <span>Connexion</span>
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg font-bold hover:from-primary-700 hover:to-primary-800 transition-all duration-150 shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 inline-flex items-center gap-2">
                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                            <span>Créer un Compte</span>
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="md:hidden border-t border-gray-200 py-4 space-y-2">
            <a href="{{ route('accueil') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 rounded transition flex items-center gap-2">
                <x-heroicon-o-home class="w-5 h-5" />
                <span>Accueil</span>
            </a>
            <a href="{{ route('produits.catalogue') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 rounded transition flex items-center gap-2">
                <x-heroicon-o-cube class="w-5 h-5" />
                <span>Catalogue</span>
            </a>
            <a href="{{ route('panier.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 rounded transition flex items-center gap-2">
                <x-heroicon-o-shopping-cart class="w-5 h-5" />
                <span>Panier</span>
            </a>
            @auth
                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 rounded transition flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-5 h-5" />
                    <span>Mon Compte</span>
                </a>
                <form method="POST" action="{{ route('logout') }}"
                      data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?"
                      data-confirm-title="Déconnexion"
                      data-confirm-type="warning"
                      data-confirm-button="Déconnexion">
                    @csrf
                    <button type="submit" class="block px-4 py-2 text-danger-600 hover:bg-danger-50 rounded transition flex items-center gap-2">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                        <span>Déconnexion</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary-600 rounded transition flex items-center gap-2">
                    <x-heroicon-o-lock-closed class="w-5 h-5" />
                    <span>Connexion</span>
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg font-bold hover:from-primary-700 hover:to-primary-800 transition shadow-md flex items-center gap-2 justify-center">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                    <span>Créer un Compte</span>
                </a>
            @endauth
        </div>
    </div>
</nav>

