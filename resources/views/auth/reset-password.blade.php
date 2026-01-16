@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-white to-secondary-50 px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="w-full max-w-md">
        <!-- Logo/Titre -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Supply</h1>
            <h2 class="text-2xl font-bold text-gray-900">Réinitialiser le Mot de Passe</h2>
            <p class="text-gray-600 mt-2">Entrez votre nouveau mot de passe sécurisé</p>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('password.store') }}" class="bg-white rounded-2xl shadow-xl p-8">
            @csrf

            <!-- Token Caché -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email (lecture seule) -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    📧 Adresse Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    readonly
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                >
            </div>

            <!-- Nouveau Mot de Passe -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    🔐 Nouveau Mot de Passe
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Minimum 8 caractères"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation Mot de Passe -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    ✓ Confirmation
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Confirmer le mot de passe"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition"
                >
            </div>

            <!-- Conseils Mot de Passe -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-amber-900 font-semibold mb-2">💡 Conseils de sécurité:</p>
                <ul class="text-sm text-amber-800 space-y-1">
                    <li>✓ Minimum 8 caractères</li>
                    <li>✓ Lettres majuscules et minuscules</li>
                    <li>✓ Au moins un chiffre</li>
                    <li>✓ Au moins un caractère spécial (!@#$%)</li>
                </ul>
            </div>

            <!-- Boutons -->
            <div class="space-y-3">
                <button
                    type="submit"
                    class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-primary-700 transition duration-200 shadow-lg hover:shadow-xl"
                >
                    🔄 Réinitialiser le Mot de Passe
                </button>

                <a
                    href="{{ route('login') }}"
                    class="block w-full text-center px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition duration-200"
                >
                    ← Retour à la Connexion
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
