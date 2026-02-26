<x-guest-layout>
    <!-- Titre -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Confirmation de mot de passe</h1>
        <p class="text-gray-600 text-sm">Veuillez confirmer votre mot de passe avant de continuer.</p>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

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
                autofocus
                placeholder="••••••••"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 text-gray-900 @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bouton -->
        <button
            type="submit"
            class="w-full mt-6 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md"
        >
            Confirmer
        </button>
    </form>
</x-guest-layout>
