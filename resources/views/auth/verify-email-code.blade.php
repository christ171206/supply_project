<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier votre email - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-accent-50 via-white to-primary-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg">
            <!-- Logo -->
            <div class="mb-8 text-center">
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
                <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-8 py-10 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Vérifiez votre email</h1>
                    <p class="text-white text-opacity-90">Entrez le code de vérification reçu</p>
                </div>

                <!-- Corps -->
                <div class="px-8 py-10">
                    <!-- Message email -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Code envoyé à :</p>
                        <p class="text-lg font-bold text-primary-600">{{ $email }}</p>
                    </div>

                    @if (session('verification_code_debug'))
                        <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 rounded-lg">
                            <p class="font-bold text-amber-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 17v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                                </svg>
                                Mode Développement - Code de test
                            </p>
                            <div class="bg-white rounded border border-amber-200 p-3 text-center">
                                <p class="text-xs text-gray-600 mb-2">Votre code de vérification:</p>
                                <p class="text-3xl font-bold tracking-widest text-amber-600">{{ session('verification_code_debug') }}</p>
                            </div>
                            <p class="text-xs text-amber-800 mt-3">
                                💡 Conseil : En production, le code sera envoyé uniquement par email. Ce code s'affiche ici en développement pour faciliter les tests.
                            </p>
                        </div>
                    @endif

                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <p class="font-semibold text-red-800 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Erreur
                            </p>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Message de succès -->
                    @if (session('message'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p class="text-green-700 font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
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
                                name="code"
                                id="code"
                                inputmode="numeric"
                                maxlength="6"
                                placeholder="000000"
                                class="w-full px-5 py-4 text-center text-4xl font-bold tracking-widest border-2 border-gray-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none transition bg-gray-50 hover:bg-white"
                                required
                                autofocus
                            />
                            @error('code')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414 0L10 20.485l-6.687-6.686a1 1 0 00-1.414 1.414l7.394 7.394a1 1 0 001.414 0l8.394-8.393a1 1 0 000-1.414z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 text-lg shadow-lg"
                        >
                            Vérifier le code
                        </button>
                    </form>

                    <!-- Options supplémentaires -->
                    <div class="mt-8 space-y-4 border-t border-gray-200 pt-8">
                        <!-- Renvoyer le code -->
                        <div>
                            <p class="text-sm text-gray-600 text-center mb-3">Vous n'avez pas reçu le code ?</p>
                            <form action="{{ route('verification.code.resend') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg border border-gray-300 transition duration-200 hover:border-gray-400"
                                >
                                    Renvoyer le code
                                </button>
                            </form>
                        </div>

                        <!-- Recommencer -->
                        <div>
                            <a href="{{ route('register') }}" class="block text-center text-primary-600 hover:text-primary-700 font-semibold py-3 px-4 rounded-lg hover:bg-primary-50 transition duration-200">
                                Recommencer l'inscription
                            </a>
                        </div>
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
