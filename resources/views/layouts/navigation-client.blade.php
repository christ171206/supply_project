<nav x-data="{ open: false }" class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border-b border-indigo-500/30 shadow-lg shadow-slate-900/50 sticky top-0 z-50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <a href="{{ route('accueil') }}" class="flex items-center gap-2 group">
                    <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-2 rounded-lg group-hover:shadow-lg group-hover:shadow-indigo-500/50 transition duration-300">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">Supply</span>
                </a>
            </div>

            <!-- Navigation Desktop -->
            <div class="hidden md:flex items-center gap-1">
                <!-- Home -->
                <a href="{{ route('accueil') }}" class="relative px-4 py-2 text-slate-300 hover:text-white font-medium transition duration-200 group">
                    🏠 Accueil
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-violet-500 group-hover:w-full transition-all duration-300"></span>
                </a>

                <!-- Catalogue -->
                <a href="{{ route('produits.catalogue') }}" class="relative px-4 py-2 text-slate-300 hover:text-white font-medium transition duration-200 group">
                    📦 Catalogue
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-violet-500 group-hover:w-full transition-all duration-300"></span>
                </a>

                <!-- Panier -->
                <a href="{{ route('panier.index') }}" class="relative px-4 py-2 text-slate-300 hover:text-white font-medium transition duration-200 group">
                    🛒 Panier
                    <span id="cart-badge" class="absolute -top-2 -right-1 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-lg shadow-red-500/50 @if(!auth()->check() || !auth()->user()->panier || auth()->user()->panier->items->count() === 0) hidden @endif">
                        {{ auth()->check() && auth()->user()->panier ? auth()->user()->panier->items->count() : '0' }}
                    </span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-500 to-violet-500 group-hover:w-full transition-all duration-300"></span>
                </a>
            </div>

            <!-- User Menu & Mobile -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 text-slate-300 hover:text-white transition duration-200 group">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="font-medium text-sm">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition @click.outside="open = false"
                             class="absolute right-0 mt-2 w-56 bg-slate-900 border border-indigo-500/30 rounded-xl shadow-xl shadow-slate-900/50 py-2 z-50">

                            @if(Auth::user()->role === 'vendeur')
                                <a href="{{ route('vendeur.dashboard') }}" class="block px-4 py-2.5 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition font-semibold border-b border-slate-700/50">
                                    🏪 Espace Vendeur
                                </a>
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2.5 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition">
                                    📊 Mes Commandes
                                </a>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2.5 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition font-semibold border-b border-slate-700/50">
                                    📊 Mon Compte
                                </a>
                                <a href="{{ route('client.commandes') }}" class="block px-4 py-2.5 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition">
                                    📋 Mes Commandes
                                </a>
                            @endif

                            <a href="{{ route('client.profil') }}" class="block px-4 py-2.5 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition">
                                👤 Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-700/50">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-red-400 hover:bg-red-500/20 transition font-semibold">
                                    🚪 Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Auth Links -->
                    <div class="hidden md:flex gap-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white font-medium transition">
                            🔐 Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-indigo-500/50 transition duration-300">
                            ✍️ S'inscrire
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-slate-300 hover:bg-slate-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="md:hidden border-t border-slate-700/50 py-4 space-y-2">
            <a href="{{ route('accueil') }}" class="block px-4 py-2 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 rounded transition">
                🏠 Accueil
            </a>
            <a href="{{ route('produits.catalogue') }}" class="block px-4 py-2 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 rounded transition">
                📦 Catalogue
            </a>
            <a href="{{ route('panier.index') }}" class="block px-4 py-2 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 rounded transition">
                🛒 Panier
            </a>
            @auth
                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 rounded transition">
                    📊 Mon Compte
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/20 rounded transition">
                        🚪 Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-slate-300 hover:bg-indigo-500/20 hover:text-indigo-300 rounded transition">
                    🔐 Connexion
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded font-semibold transition">
                    ✍️ S'inscrire
                </a>
            @endauth
        </div>
    </div>
</nav>

