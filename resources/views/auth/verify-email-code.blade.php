<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier votre email - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 justify-center">
                    <div class="bg-primary-600 p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-gray-900">Supply</span>
                </a>
            </div>

            <!-- Card principale -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- En-tête -->
                <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-8 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4 border-2 border-white/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Vérifiez votre email</h1>
                    <p class="text-white/90 text-sm md:text-base">Entrez le code reçu dans votre boîte mail</p>
                </div>

                <!-- Corps -->
                <div class="px-8 py-8">
                    <!-- Info utilisateur -->
                    <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                        <p class="text-xs md:text-sm text-gray-600 mb-2 font-medium">Email de vérification envoyé à :</p>
                        <p class="text-base md:text-lg font-bold text-primary-600 break-all">{{ $email }}</p>
                    </div>

                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <p class="font-semibold text-red-800 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Erreur
                            </p>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Message de succès -->
                    @if (session('message'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p class="text-green-700 font-medium flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ session('message') }}
                            </p>
                        </div>
                    @endif

                    <!-- Formulaire de vérification -->
                    <form action="{{ route('verification.code.verify') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-3">
                                Code de vérification (6 chiffres)
                            </label>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                maxlength="6"
                                placeholder="000000"
                                value="{{ old('code') }}"
                                class="w-full px-4 py-3 text-center text-2xl font-bold tracking-widest border-2 border-gray-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition"
                                autofocus
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                            />
                            @error('code')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 active:scale-95 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Vérifier mon email
                        </button>
                    </form>

                    <!-- Renvoi de code -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-center text-gray-600 text-sm mb-4">
                            Vous n'avez pas reçu le code ?
                        </p>
                        <form action="{{ route('verification.code.resend') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-primary-600 hover:text-primary-700 font-semibold py-2 px-4 rounded-lg border border-primary-200 hover:bg-primary-50 transition"
                            >
                                Renvoyer le code
                            </button>
                        </form>
                    </div>

                    <!-- Lien retour -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium transition">
                            Utiliser une autre adresse email
                        </a>
                    </div>
                </div>

                <!-- Pied -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-xs text-gray-600 text-center">
                        Le code expire dans <span class="font-semibold">10 minutes</span>
                    </p>
                </div>
            </div>

            <!-- Retour à l'accueil -->
            <div class="mt-6 text-center">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</body>
</html>
                    </div>

                    <!-- Infos importantes -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800 flex items-start gap-2">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Validité :</strong> Ce code expire dans 10 minutes. Veuillez l'utiliser rapidement.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lien de retour -->
            <div class="mt-8 text-center">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 font-medium transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 110-18 9 9 0 010 18zm0 0a9 9 0 1018 0 9 9 0 01-18 0z"/>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // Format de saisie automatique pour le code (6 chiffres seulement)
        const codeInput = document.getElementById('code');
        
        if (codeInput) {
            codeInput.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
                // Auto-submit si 6 chiffres saisis
                if (this.value.length === 6) {
                    // Optional: auto-submit après un délai
                    // this.form.submit();
                }
            });

            // Focus sur l'input au chargement
            codeInput.focus();
        }
    </script>
</body>
</html>
