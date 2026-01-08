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
            body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            @include('layouts.navigation-client')

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gradient-to-r from-slate-950 to-slate-900 text-slate-300 border-t border-slate-700 mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <!-- About -->
                        <div class="space-y-4">
                            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Supply
                            </h3>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                Votre boutique informatique premium. Qualité, innovation et service client exceptionnels.
                            </p>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Navigation</h3>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ route('accueil') }}" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>→</span> Accueil</a></li>
                                <li><a href="{{ route('produits.catalogue') }}" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>→</span> Catalogue</a></li>
                                <li><a href="{{ route('panier.index') }}" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>→</span> Panier</a></li>
                            </ul>
                        </div>

                        <!-- Customer Service -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Support</h3>
                            <ul class="space-y-3 text-sm">
                                <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>?</span> FAQ</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>📋</span> Conditions</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200 flex items-center gap-2"><span>🔒</span> Confidentialité</a></li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div>
                            <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Contact</h3>
                            <ul class="space-y-3 text-sm">
                                <li class="text-slate-400">📧 contact@supply.fr</li>
                                <li class="text-slate-400">📞 +33 (0)1 23 45 67 89</li>
                                <li class="text-slate-400">📍 Paris, France</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-slate-700 pt-8">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-sm text-slate-500">&copy; 2026 Supply. Tous droits réservés. 🚀</p>
                            <div class="flex gap-6">
                                <a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200">𝕏</a>
                                <a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200">f</a>
                                <a href="#" class="text-slate-400 hover:text-indigo-400 transition duration-200">in</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
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
        </script>

        @yield('scripts')
    </body>
</html>
