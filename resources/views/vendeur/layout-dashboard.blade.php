<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Espace Vendeur - Supply')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50">
            <!-- Header Vendeur -->
            <header class="sticky top-0 z-50 bg-white shadow-lg border-b-2 border-primary-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <!-- Logo -->
                        <a href="{{ route('vendeur.dashboard') }}" class="text-2xl font-bold text-primary-600">
                            📦 Supply
                        </a>

                        <!-- Centre: Breadcrumb -->
                        <div class="hidden md:flex items-center gap-2 text-sm">
                            <span class="text-gray-600">🏪</span>
                            <span class="font-bold text-gray-900">{{ auth()->user()->shop_name ?? auth()->user()->name }}</span>
                        </div>

                        <!-- Droite: Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Notifications -->
                            <button class="relative p-2 text-gray-600 hover:text-primary-600 transition" onclick="toggleNotifications()">
                                🔔
                                <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">3</span>
                            </button>

                            <!-- Profil Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                                </button>

                                <!-- Menu Dropdown -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <a href="{{ route('vendeur.profil') }}" class="block px-4 py-3 text-gray-900 hover:bg-gray-100 rounded-t-lg font-medium">👤 Mon Profil</a>
                                    <a href="{{ route('accueil') }}" class="block px-4 py-3 text-gray-900 hover:bg-gray-100 font-medium">🛍️ Mode Client</a>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-lg font-medium">🚪 Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="flex">
                <!-- Sidebar -->
                <aside class="w-64 bg-white shadow-lg min-h-[calc(100vh-80px)] border-r-2 border-gray-100 sticky top-20">
                    <nav class="p-6 space-y-2">
                        <!-- Section Princ -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Principal</p>

                        <a href="{{ route('vendeur.dashboard') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.dashboard') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📊 Tableau de Bord
                        </a>
                        <a href="{{ route('vendeur.apercu') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.apercu') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            👁️ Aperçu Boutique
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Gestion -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Gestion</p>

                        <a href="{{ route('vendeur.produits.index') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.produits.*') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📦 Mes Produits
                        </a>
                        <a href="{{ route('vendeur.stock') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.stock') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📋 Gestion Stock
                        </a>
                        <a href="{{ route('vendeur.commandes') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.commandes*') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            🛒 Commandes
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Client -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Client</p>

                        <a href="{{ route('vendeur.avis') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.avis') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            ⭐ Avis Clients
                        </a>
                        <a href="{{ route('vendeur.messages') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.messages') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            💬 Messages
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Section Compte -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-3">Compte</p>

                        <a href="{{ route('vendeur.profil') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.profil') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            👤 Mon Profil
                        </a>
                        <a href="{{ route('vendeur.statistiques') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.statistiques') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📈 Statistiques
                        </a>
                        <a href="{{ route('vendeur.parametres') }}" class="block px-4 py-3 rounded-lg font-medium transition duration-200 {{ request()->routeIs('vendeur.parametres') ? 'bg-primary-100 text-primary-600 border-l-4 border-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            ⚙️ Paramètres
                        </a>

                        <hr class="my-4 border-gray-200">

                        <!-- Retour Boutique -->
                        <a href="{{ route('accueil') }}" class="block px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                            ← Retour Boutique
                        </a>
                    </nav>
                </aside>

                <!-- Content Area -->
                <main class="flex-1">
                    @yield('content')
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-gray-300 mt-16 border-t-4 border-primary-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <div>
                            <p class="font-bold text-white mb-3">Supply</p>
                            <p class="text-sm">Plateforme de vente en ligne pour revendeurs informatiques</p>
                        </div>
                        <div>
                            <p class="font-bold text-white mb-3">Contact</p>
                            <p class="text-sm">📧 support@supply.fr</p>
                            <p class="text-sm">📞 01 23 45 67 89</p>
                        </div>
                        <div>
                            <p class="font-bold text-white mb-3">Liens</p>
                            <p class="text-sm"><a href="#" class="hover:text-white transition">Centre d'aide</a></p>
                            <p class="text-sm"><a href="#" class="hover:text-white transition">Politique de confidentialité</a></p>
                        </div>
                    </div>
                    <div class="text-center border-t border-gray-700 pt-8">
                        <p class="text-sm">&copy; 2026 Supply - Espace Vendeur. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            function toggleNotifications() {
                // TODO: Implémenter le menu notifications
            }
        </script>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @yield('scripts')
    </body>
</html>
