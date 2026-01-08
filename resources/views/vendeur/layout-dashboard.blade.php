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
        <div class="min-h-screen bg-gray-50">
            <!-- Navigation -->
            @include('layouts.navigation-client')

            <!-- Main Content -->
            <div class="flex">
                <!-- Sidebar -->
                <aside class="w-64 bg-white shadow-lg min-h-[calc(100vh-64px)]">
                    <nav class="p-6 space-y-2">
                        <a href="{{ route('vendeur.dashboard') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📊 Tableau de Bord
                        </a>
                        <a href="{{ route('vendeur.apercu') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.apercu') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            👁️ Aperçu Boutique
                        </a>
                        <a href="{{ route('vendeur.produits.index') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.produits.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📦 Mes Produits
                        </a>
                        <a href="{{ route('vendeur.stock') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.stock') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📋 Gestion Stock
                        </a>
                        <a href="{{ route('vendeur.commandes') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.commandes*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            🛒 Commandes
                        </a>
                        <a href="{{ route('vendeur.avis') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.avis') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            ⭐ Avis Clients
                        </a>
                        <a href="{{ route('vendeur.messages') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.messages') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            💬 Messages
                        </a>
                        
                        <hr class="my-4">
                        
                        <a href="{{ route('vendeur.profil') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.profil') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            👤 Mon Profil
                        </a>
                        <a href="{{ route('vendeur.statistiques') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.statistiques') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            📈 Statistiques
                        </a>
                        <a href="{{ route('vendeur.parametres') }}" class="block px-4 py-2 rounded-lg font-medium {{ request()->routeIs('vendeur.parametres') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                            ⚙️ Paramètres
                        </a>
                    </nav>
                </aside>

                <!-- Content Area -->
                <main class="flex-1">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        @yield('vendeur-content')
                    </div>
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-900 text-gray-300 mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="text-center">
                        <p class="text-sm">&copy; 2026 Supply - Espace Vendeur. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @yield('scripts')
    </body>
</html>
