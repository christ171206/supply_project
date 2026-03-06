<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin Dashboard - Supply')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/modals.css', 'resources/js/app.js'])

        <script>
            window.SOCKET_IO_URL = '{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}';
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed left-0 top-0 z-1000 h-screen w-72 bg-white border-r border-gray-200 overflow-y-auto shadow-sm md:relative md:translate-x-0 -translate-x-full transition-transform duration-300">
                <!-- Logo -->
                <div class="border-b border-gray-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900">Supply Admin</h1>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="p-4 space-y-1">
                    <!-- Admin Section -->
                    <div class="mb-8">
                        <p class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Administration</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition {{ Route::currentRouteName() === 'admin.dashboard' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9M9 12l3-3m0 0l3 3m-3-3v6" /></svg>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition {{ Route::currentRouteName() === 'admin.users.index' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m0 0a4 4 0 00-4 4v2h16v-2a4 4 0 00-4-4h-4z" /></svg>
                                <span>Utilisateurs</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition {{ Route::currentRouteName() === 'admin.products.index' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m0 0L4 7m16 0v10l-8 4m0-10L4 7v10l8 4" /></svg>
                                <span>Produits</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition {{ Route::currentRouteName() === 'admin.orders.index' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                <span>Commandes</span>
                            </a>
                            <a href="{{ route('admin.disputes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition {{ Route::currentRouteName() === 'admin.disputes.index' ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>Litiges</span>
                            </a>
                        </div>
                    </div>

                    <!-- Autres sections (désactivées) -->
                    <div class="mb-8 opacity-50">
                        <p class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Rapports</p>
                        <div class="space-y-1">
                            <div class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span>Audit Logs</span>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Mode Client Banner (si activé) -->
                @if(session('admin_client_mode'))
                    <div class="bg-yellow-50 border-b-2 border-yellow-400 px-6 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M15 13H9v-2h6v2zm0-6H9v2h6V7zm6 6v5H3v-5h3V5c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2v1h3zm-2 3H5v2h14v-2z"/></svg>
                            <div>
                                <p class="font-semibold text-yellow-900 flex items-center gap-2"><x-heroicon-o-eye class="w-5 h-5" /><span>Mode Visualisation Client Activé</span></p>
                                <p class="text-xs text-yellow-700">Vous visualisez la plateforme comme un client. Vous ne pouvez pas passer de commande.</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.mode.client-exit') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-semibold text-sm whitespace-nowrap">
                                Quitter Mode Client
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Topbar -->
                <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between h-16 px-6">
                        <button id="hamburger-btn" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        
                        <!-- Center spacer -->
                        <div></div>
                        
                        <!-- Right section: Notifications + Profile -->
                        <div class="flex items-center gap-6">
                            <!-- Notifications -->
                            <div class="relative group">
                                <button class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                    <x-heroicon-o-bell class="w-5 h-5" />
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div class="absolute right-0 mt-0 w-80 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <div class="p-4 border-b border-gray-100">
                                        <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <!-- Sample notifications -->
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <x-heroicon-o-user class="w-4 h-4 text-blue-600" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-gray-900">Nouveau vendeur en attente</p>
                                                    <p class="text-xs text-gray-500 mt-1">Test Shop en attente de validation</p>
                                                    <p class="text-xs text-gray-400 mt-1">Il y a 2 heures</p>
                                                </div>
                                            </div>
                                        </a>
                                        
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <x-heroicon-o-shopping-cart class="w-4 h-4 text-green-600" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-gray-900">Nouvelle commande reçue</p>
                                                    <p class="text-xs text-gray-500 mt-1">Commande #1 - 680 000 FCFA</p>
                                                    <p class="text-xs text-gray-400 mt-1">Il y a 4 heures</p>
                                                </div>
                                            </div>
                                        </a>
                                        
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <x-heroicon-o-exclamation-triangle class="w-4 h-4 text-yellow-600" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-gray-900">5 produits en attente de validation</p>
                                                    <p class="text-xs text-gray-500 mt-1">Veuillez valider les produits</p>
                                                    <p class="text-xs text-gray-400 mt-1">Il y a 1 jour</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="p-3 border-t border-gray-100 text-center">
                                        <a href="#" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Voir toutes les notifications</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mode Client Toggle -->
                            @if(!session('admin_client_mode'))
                                <form method="POST" action="{{ route('admin.mode.client-enter') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200 transition font-semibold flex items-center gap-1">
                                        <x-heroicon-o-eye class="w-3 h-3" /> Mode Client
                                    </button>
                                </form>
                            @endif

                            <!-- Profile Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center gap-3 hover:bg-gray-100 rounded-lg px-3 py-2 transition">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-xs font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">Admin</p>
                                    </div>
                                    <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400" />
                                </button>

                                <!-- Profile Dropdown Menu -->
                                <div class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <!-- Profile Info -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="py-2">
                                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <x-heroicon-o-user class="w-4 h-4" />
                                            <span>Profil</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.security.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <x-heroicon-o-lock-closed class="w-4 h-4" />
                                            <span>Sécurité</span>
                                        </a>

                                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                                            <span>Paramètres</span>
                                        </a>

                                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <x-heroicon-o-document-text class="w-4 h-4" />
                                            <span>Documentation</span>
                                        </a>
                                    </div>

                                    <!-- Divider -->
                                    <div class="border-t border-gray-100 py-2">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                                                <span>Déconnexion</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <main class="flex-1 overflow-auto">
                    <div class="p-8">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <script>
            // Mobile sidebar toggle
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar');
            hamburgerBtn?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (!sidebar?.contains(e.target) && !hamburgerBtn?.contains(e.target)) {
                    sidebar?.classList.add('-translate-x-full');
                }
            });
        </script>

        <!-- Composant Modal de Confirmation -->
        @include('components.confirmation-modal')
    </body>
</html>
