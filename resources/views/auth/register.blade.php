<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-accent-50 via-white to-primary-50">
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
                        Rejoignez Supply dès maintenant
                    </h2>
                    <p class="text-xl text-gray-700 mb-12 leading-relaxed font-medium">
                        Que tu sois acheteur ou vendeur, Supply t'offre les meilleures opportunités.
                    </p>

                    <!-- Avantages Clients -->
                    <div class="mb-12">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">👥 Pour les Clients</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">💝</span>
                                <div>
                                    <p class="font-bold text-gray-900">Favoris illimités</p>
                                    <p class="text-sm text-gray-600">Sauvegarde tes produits préférés</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">🚚</span>
                                <div>
                                    <p class="font-bold text-gray-900">Livraison rapide</p>
                                    <p class="text-sm text-gray-600">2-5 jours en Côte d'Ivoire</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">💬</span>
                                <div>
                                    <p class="font-bold text-gray-900">Support direct</p>
                                    <p class="text-sm text-gray-600">Chat avec les vendeurs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avantages Vendeurs -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-6">🏪 Pour les Vendeurs</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">📊</span>
                                <div>
                                    <p class="font-bold text-gray-900">Tableau de bord complet</p>
                                    <p class="text-sm text-gray-600">Gère ton stock et tes ventes</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">🌍</span>
                                <div>
                                    <p class="font-bold text-gray-900">Audience large</p>
                                    <p class="text-sm text-gray-600">Accès à des milliers d'acheteurs</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-2xl flex-shrink-0">💰</span>
                                <div>
                                    <p class="font-bold text-gray-900">Paiements sécurisés</p>
                                    <p class="text-sm text-gray-600">Reçois tes gains rapidement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section droite - Formulaire -->
                <div>
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer un compte</h1>
                            <p class="text-gray-700 font-medium">Débute ton aventure Supply</p>
                        </div>

                        <!-- Formulaire -->
                        <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
                            @csrf

                            <!-- Nom complet -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                    👤 Nom complet
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                    placeholder="Jean Dupont"
                                >
                                @error('name')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                    📧 Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
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
                                        onclick="togglePasswordVisibility('password', 'eyeIcon1')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-primary-600 transition p-1"
                                        title="Afficher/Masquer le mot de passe"
                                        aria-label="Afficher/Masquer le mot de passe"
                                    >
                                        <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                                <p id="passwordHint" class="mt-1.5 text-xs text-gray-500">Minimum 8 caractères</p>
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                                    🔒 Confirmer mot de passe
                                </label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        class="w-full px-4 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                        placeholder="••••••••"
                                    >
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-primary-600 transition p-1"
                                        title="Afficher/Masquer le mot de passe"
                                        aria-label="Afficher/Masquer le mot de passe"
                                    >
                                        <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type de compte -->
                            <div class="pt-2 border-t border-gray-200 mt-6">
                                <p class="block text-sm font-semibold text-gray-900 mb-3">🎯 Je suis un...</p>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition" for="role_client">
                                        <input
                                            type="radio"
                                            id="role_client"
                                            name="role"
                                            value="client"
                                            checked
                                            class="w-4 h-4 accent-primary-500 cursor-pointer"
                                            onchange="toggleVendorFields()"
                                        >
                                        <span class="ml-3 flex-grow">
                                            <span class="text-sm font-semibold text-gray-900">👥 Client</span>
                                            <span class="block text-xs text-gray-600">Achète des produits informatiques</span>
                                        </span>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-accent-400 hover:bg-accent-50 transition" for="role_vendor">
                                        <input
                                            type="radio"
                                            id="role_vendor"
                                            name="role"
                                            value="vendor"
                                            class="w-4 h-4 accent-accent-500 cursor-pointer"
                                            onchange="toggleVendorFields()"
                                        >
                                        <span class="ml-3 flex-grow">
                                            <span class="text-sm font-semibold text-gray-900">🏪 Vendeur</span>
                                            <span class="block text-xs text-gray-600">Vends tes produits informatiques</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Champs vendeur -->
                            <div id="vendorFields" class="hidden pt-6 space-y-5 border-t border-gray-200 mt-6">
                                <div class="bg-accent-50 p-4 rounded-xl border border-accent-200">
                                    <p class="text-xs text-accent-900 font-semibold">ℹ️ Votre demande sera vérifiée avant activation de votre boutique</p>
                                </div>

                                <div>
                                    <label for="shop_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                        🏷️ Nom de la boutique
                                    </label>
                                    <input
                                        type="text"
                                        id="shop_name"
                                        name="shop_name"
                                        value="{{ old('shop_name') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                        placeholder="Ma Boutique"
                                    >
                                    @error('shop_name')
                                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                                        📞 Téléphone
                                    </label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                        placeholder="+225 05 01 23 45 67"
                                    >
                                    <p class="mt-1.5 text-xs text-gray-500">Format Côte d'Ivoire: +225 ou 0X XX XX XX XX</p>
                                    @error('phone')
                                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                                        📍 Adresse
                                    </label>
                                    <textarea
                                        id="address"
                                        name="address"
                                        rows="2"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                        placeholder="Votre adresse"
                                    >{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="id_document" class="block text-sm font-semibold text-gray-900 mb-2">
                                        🪪 Justificatif d'identité
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition" onclick="document.getElementById('id_document').click()">
                                        <input
                                            type="file"
                                            id="id_document"
                                            name="id_document"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="hidden"
                                            onchange="updateFileName(this)"
                                        >
                                        <p id="fileName" class="text-sm text-gray-600 font-medium">
                                            📤 Cliquez pour télécharger
                                        </p>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500">✓ Formats acceptés: PDF, JPG, PNG (max 2 Mo)</p>
                                    @error('id_document')
                                        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Conditions -->
                            <div class="flex items-start gap-3 pt-4">
                                <input
                                    type="checkbox"
                                    id="terms"
                                    name="terms"
                                    required
                                    class="w-5 h-5 mt-0.5 accent-primary-500 cursor-pointer rounded"
                                >
                                <label for="terms" class="text-sm text-gray-700 cursor-pointer">
                                    J'accepte les <a href="#" target="_blank" class="text-primary-600 hover:underline font-semibold">conditions d'utilisation</a> et la <a href="#" target="_blank" class="text-primary-600 hover:underline font-semibold">politique de confidentialité</a>
                                </label>
                            </div>
                            @error('terms')
                                <p class="text-sm text-danger-600">{{ $message }}</p>
                            @enderror

                            <!-- Bouton -->
                            <button
                                type="submit"
                                class="w-full mt-8 px-6 py-3 bg-gradient-to-r from-accent-600 to-accent-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-accent-500/50 hover:scale-105 active:scale-95 transition-all duration-200 text-lg"
                            >
                                ✨ Créer un compte
                            </button>
                        </form>

                        <!-- Lien login -->
                        <div class="mt-6 text-center border-t border-gray-200 pt-6">
                            <p class="text-gray-600 text-sm">
                                Déjà inscrit ?
                                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-bold transition">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleVendorFields() {
            const vendorFields = document.getElementById('vendorFields');
            const roleVendor = document.getElementById('role_vendor').checked;
            if (roleVendor) {
                vendorFields.classList.remove('hidden');
            } else {
                vendorFields.classList.add('hidden');
            }
        }

        function updateFileName(input) {
            const fileNameEl = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameEl.textContent = '✓ ' + input.files[0].name;
            }
        }

        function togglePasswordVisibility(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);

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

        // Validation dynamique: texte "Minimum 8 caractères" vire au vert quand pwd >= 8 chars
        function validatePasswordLength() {
            const password = document.getElementById('password');
            const hint = document.getElementById('passwordHint');

            if (password.value.length >= 8) {
                hint.classList.remove('text-gray-500');
                hint.classList.add('text-green-600', 'font-semibold');
                hint.textContent = '✓ Mot de passe valide (8+ caractères)';
            } else {
                hint.classList.remove('text-green-600', 'font-semibold');
                hint.classList.add('text-gray-500');
                hint.textContent = 'Minimum 8 caractères';
            }
        }

        // Ajouter l'event listener au champ password
        document.getElementById('password').addEventListener('input', validatePasswordLength);
    </script>
</body>
</html>
