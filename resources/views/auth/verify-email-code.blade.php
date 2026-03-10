<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier votre code - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-off-white">
<div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
    <!-- LEFT PANEL -->
    <div class="bg-white border-r border-gray-200 p-12 sticky top-0 overflow-y-auto">
        <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-black mb-12 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Retour
        </a>

        <div class="mb-8">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center mb-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" class="w-4 h-4">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            <span class="text-sm font-medium">Supply</span>
        </div>

        <h2 class="font-serif text-3xl leading-tight mb-3">Vérifiez votre<br><em class="italic text-gray-600">code</em></h2>
        <p class="text-sm text-gray-600 font-light mb-8 max-w-xs">Entrez le code de confirmation reçu à votre adresse.</p>

        <div class="space-y-5">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">123</div>
                <div>
                    <strong class="text-sm">Code 6 chiffres</strong>
                    <p class="text-xs text-gray-600 font-light">Vérifiez votre boîte mail.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">⏱</div>
                <div>
                    <strong class="text-sm">Expire rapidement</strong>
                    <p class="text-xs text-gray-600 font-light">Le code expire en 10 minutes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="bg-off-white p-12 flex items-center justify-center">
        <div class="w-full max-w-sm">
            <h1 class="font-serif text-2xl mb-1">Confirmation</h1>
            <p class="text-xs text-gray-600 mb-2">Code envoyé à :</p>
            <p class="text-sm font-mono text-gray-800 mb-6 break-all">{{ $email }}</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-white border border-red-200 rounded-lg text-xs text-red-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('message'))
                <div class="mb-4 p-3 bg-white border border-gray-200 rounded-lg text-xs text-black">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('verification.code.verify') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-mono uppercase text-gray-600 mb-2 block">Code (6 chiffres)</label>
                    <input type="text" name="code" maxlength="6" placeholder="000000" value="{{ old('code') }}" autofocus
                        inputmode="numeric" pattern="[0-9]{6}"
                        class="w-full px-3 py-3 text-center text-2xl font-mono font-bold tracking-widest border border-gray-200 rounded-lg focus:border-black focus:outline-none transition" />
                    @error('code')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-black text-white py-2 rounded-lg text-sm font-medium hover:opacity-85 transition">
                    Vérifier
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-600 text-center mb-3">Pas reçu le code ?</p>
                <form action="{{ route('verification.code.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-sm text-gray-600 border border-gray-200 py-2 rounded-lg hover:bg-white transition">
                        Renvoyer le code
                    </button>
                </form>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-xs text-gray-600 hover:text-black">
                    Autre adresse email
                </a>
            </div>
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
