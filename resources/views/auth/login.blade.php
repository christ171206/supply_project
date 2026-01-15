<x-guest-layout>
    <!-- En-tête de la page -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Se connecter</h1>
        <p class="mt-2 text-gray-600">Accédez à votre compte Supply</p>
    </div>

    <!-- Messages d'erreur/succès -->
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-red-800 font-semibold text-sm">{{ __('auth.failed') }}</p>
        </div>
    @endif

    @if (session('status'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-green-800 text-sm">{{ session('status') }}</p>
        </div>
    @endif

    <!-- Formulaire de connexion -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                Adresse email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                placeholder="votre@email.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mot de passe -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                Mot de passe
            </label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Se souvenir de moi & Mot de passe oublié -->
        <div class="flex items-center justify-between">
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
            class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary-500/30 hover:scale-105 transition-all duration-300"
        >
            Se connecter
        </button>
    </form>

    <!-- Lien vers inscription -->
    <div class="pt-4 border-t border-gray-200">
        <p class="text-center text-gray-600">
            Vous n'avez pas de compte ?
            <a
                href="{{ route('register') }}"
                class="font-semibold text-primary-600 hover:text-primary-700 transition-colors"
            >
                Créer un compte
            </a>
        </p>
    </div>

    <!-- Comptes de test (développement) -->
    @if (app()->environment('local'))
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm">
            <p class="font-semibold text-blue-900 mb-2">🧪 Comptes de test :</p>
            <p class="text-blue-800"><strong>Client :</strong> client@test.com / password</p>
            <p class="text-blue-800"><strong>Vendeur :</strong> vendeur@test.com / password</p>
        </div>
    @endif
</x-guest-layout>
