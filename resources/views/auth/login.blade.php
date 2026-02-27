<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-6xl">
            <!-- Retour à l'accueil -->
            <a href="{{ route('accueil') }}" class="inline-flex items-center gap-3 text-gray-700 hover:text-primary-600 font-semibold mb-8 transition py-2 px-3 rounded-lg hover:bg-gray-100 text-base md:text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                ← Retour à l'accueil
            </a>

            <!-- Logo mobile -->
            <div class="mb-8 lg:hidden">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2">
                    <div class="bg-primary-600 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Supply</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">

                <!-- Section gauche - Bénéfices -->
                <div class="hidden lg:block">
                    <div class="mb-8">
                        <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2">
                            <div class="bg-primary-600 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">Supply</span>
                        </a>
                    </div>

                    <h2 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        Accédez à vos favoris et commandes
                    </h2>
                    <p class="text-xl text-gray-700 mb-12 leading-relaxed font-medium">
                        Connecte-toi pour bénéficier d'une meilleure expérience d'achat et gérer tes commandes.
                    </p>

                    <!-- Avantages -->
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-primary-100">
                                    <span class="text-2xl">❤️</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Vos favoris sauvegardés</h3>
                                <p class="text-gray-600">Retrouvez tous vos articles préférés en un clic.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-accent-100">
                                    <span class="text-2xl">📋</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Historique de commandes</h3>
                                <p class="text-gray-600">Suivi simple de vos achats et de vos livraisons.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-secondary-100">
                                    <span class="text-2xl">🚀</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Checkout rapide</h3>
                                <p class="text-gray-600">Enregistre tes informations pour commander plus vite.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100">
                                    <span class="text-2xl">💬</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">Chat avec vendeurs</h3>
                                <p class="text-gray-600">Communique directement avec nos vendeurs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section droite - Formulaire -->
                <div>
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Se connecter</h1>
                            <p class="text-gray-700 font-medium">Accédez à votre compte Supply</p>
                        </div>

                        <!-- Messages d'erreur/succès -->
                        @if ($errors->any())
                            <div class="p-4 mb-6 bg-danger-50 border border-danger-200 rounded-xl flex gap-3">
                                <span class="text-2xl flex-shrink-0">⚠️</span>
                                <div>
                                    <p class="text-danger-900 font-semibold text-sm">Erreur de connexion</p>
                                    <p class="text-danger-700 text-sm mt-1">{{ __('auth.failed') }}</p>
                                </div>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="p-4 mb-6 bg-green-50 border border-green-200 rounded-xl flex gap-3">
                                <span class="text-2xl flex-shrink-0">✓</span>
                                <div>
                                    <p class="text-green-800 text-sm">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Formulaire de connexion -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                    📧 Adresse email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                    placeholder="votre@email.com"
                                >
                                @error('email')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                                    🔐 Mot de passe
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        required
                                        class="w-full px-4 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                        placeholder="••••••••"
                                    >
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility()"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-primary-600 transition p-1"
                                        title="Afficher/Masquer le mot de passe"
                                        aria-label="Afficher/Masquer le mot de passe"
                                    >
                                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Se souvenir de moi & Mot de passe oublié -->
                            <div class="flex items-center justify-between pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        id="remember"
                                        name="remember"
                                        class="w-4 h-4 border-2 border-gray-300 rounded accent-primary-500 cursor-pointer"
                                    >
                                    <span class="text-sm text-gray-700">Se souvenir de moi</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors"
                                    >
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <!-- Bouton connexion -->
                            <button
                                type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary-500/50 hover:scale-105 active:scale-95 transition-all duration-200 text-lg mt-8"
                            >
                                🔓 Se connecter
                            </button>
                        </form>

                        <!-- Lien vers inscription -->
                        <div class="pt-6 border-t border-gray-200">
                            <p class="text-center text-gray-600">
                                Vous n'avez pas de compte ?
                                <a
                                    href="{{ route('register') }}"
                                    class="font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                >
                                    Créer un compte
                                </a>
                            </p>
                        </div>

                        <!-- Lien panier guest -->
                        <div class="pt-4">
                            <p class="text-center text-sm text-gray-600">
                                Tu peux aussi
                                <a href="{{ route('panier.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                                    continuer tes achats sans te connecter
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Comptes de test (développement) -->
                    @if (app()->environment('local'))
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm">
                            <p class="font-semibold text-blue-900 mb-3">🧪 Comptes de test :</p>
                            <div class="space-y-2 text-blue-800">
                                <p><strong>Client :</strong> <code class="bg-blue-100 px-2 py-1 rounded">client@test.com</code> / <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                                <p><strong>Vendeur :</strong> <code class="bg-blue-100 px-2 py-1 rounded">vendeur@test.com</code> / <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Script pour le toggle du mot de passe -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Icône oeil ouvert
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>';
            } else {
                passwordInput.type = 'password';
                // Icône oeil fermé
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>
