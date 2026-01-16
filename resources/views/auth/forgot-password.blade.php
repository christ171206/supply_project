@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-white to-secondary-50 px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="w-full max-w-md">
        <!-- Logo/Titre -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Supply</h1>
            <h2 class="text-2xl font-bold text-gray-900">Mot de passe oublié?</h2>
            <p class="text-gray-600 mt-2">Pas de souci! Nous vous aiderons à le réinitialiser.</p>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-900">
                📧 Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('password.email') }}" class="bg-white rounded-2xl shadow-xl p-8">
            @csrf

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    📧 Adresse Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="exemple@email.com"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message de succès -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">
                        ✅ {{ session('status') }}
                    </p>
                </div>
            @endif

            <!-- Boutons -->
            <div class="space-y-3">
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200 shadow-lg hover:shadow-xl"
                >
                    🔗 Envoyer le Lien de Réinitialisation
                </button>

                <a
                    href="{{ route('login') }}"
                    class="block w-full text-center px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition duration-200"
                >
                    ← Retour à la Connexion
                </a>
            </div>
        </form>

        <!-- Lien inscription -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Pas encore inscrit?
                <a href="{{ route('register') }}" class="text-primary-600 font-bold hover:text-primary-700">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
