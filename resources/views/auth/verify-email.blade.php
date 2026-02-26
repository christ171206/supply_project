<x-guest-layout>
    <!-- Titre -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Vérifier votre email</h1>
        <p class="text-gray-600 text-sm">Un lien de vérification a été envoyé à votre adresse email.</p>
    </div>

    <!-- Message -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-900">
            📧 Cliquez sur le lien dans l'email pour vérifier votre adresse email. Si vous n'avez pas reçu d'email, nous vous en renverrons un.
        </p>
    </div>

    <!-- Status -->
    @if (session('status') == 'verification-link-sent')
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
            <p class="text-green-800 text-sm font-semibold">
                ✅ Un nouveau lien de vérification a été envoyé à votre adresse email.
            </p>
        </div>
    @endif

    <!-- Formulaire pour renvoyer l'email -->
    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf

        <button
            type="submit"
            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md"
        >
            📧 Renvoyer l'email de vérification
        </button>
    </form>

    <!-- Lien déconnexion -->
    <div class="pt-6 border-t border-gray-200 text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
