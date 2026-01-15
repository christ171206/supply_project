<x-guest-layout>
    <!-- En-tête de la page -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Créer un compte</h1>
        <p class="mt-2 text-gray-600">Rejoignez Supply pour acheter ou vendre du matériel informatique</p>
    </div>

    <!-- Formulaire d'inscription -->
    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
        @csrf

        <!-- Nom complet -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                Nom complet
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                placeholder="Jean Dupont"
            >
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

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
            <p class="mt-2 text-xs text-gray-500">Minimum 8 caractères</p>
        </div>

        <!-- Confirmation mot de passe -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                Confirmer mot de passe
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                placeholder="••••••••"
            >
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Séparateur -->
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-600">Type de compte</span>
            </div>
        </div>

        <!-- Choix du rôle (CLIENT par défaut) -->
        <div class="space-y-3">
            <div class="flex items-center">
                <input
                    type="radio"
                    id="role_client"
                    name="role"
                    value="client"
                    checked
                    class="w-4 h-4 text-primary-500 accent-primary-500 cursor-pointer"
                >
                <label for="role_client" class="ml-3 cursor-pointer flex-1">
                    <span class="font-semibold text-gray-900">👤 Client</span>
                    <p class="text-sm text-gray-600">Accédez à nos produits et effectuez vos achats</p>
                </label>
            </div>

            <div class="flex items-center">
                <input
                    type="radio"
                    id="role_vendor"
                    name="role"
                    value="vendor"
                    class="w-4 h-4 text-primary-500 accent-primary-500 cursor-pointer"
                    onchange="toggleVendorFields()"
                >
                <label for="role_vendor" class="ml-3 cursor-pointer flex-1">
                    <span class="font-semibold text-gray-900">🧑‍💼 Vendeur</span>
                    <p class="text-sm text-gray-600">Proposez vos produits informatiques</p>
                </label>
            </div>
        </div>

        <!-- Champs vendeur (affichés dynamiquement) -->
        <div id="vendorFields" class="hidden space-y-5 pt-5 border-t border-gray-200">
            <p class="text-sm text-blue-600 bg-blue-50 p-3 rounded-lg">
                📋 Informations supplémentaires requises pour les vendeurs. Votre demande sera vérifiée manuellement.
            </p>

            <!-- Nom de la boutique -->
            <div>
                <label for="shop_name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Nom de la boutique
                </label>
                <input
                    type="text"
                    id="shop_name"
                    name="shop_name"
                    value="{{ old('shop_name') }}"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                    placeholder="Ma Boutique Tech"
                >
            </div>

            <!-- Téléphone -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                    Numéro de téléphone
                </label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                    placeholder="+33 6 12 34 56 78"
                >
            </div>

            <!-- Adresse -->
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                    Adresse
                </label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500"
                    placeholder="123 Rue de la Tech, 75000 Paris"
                >{{ old('address') }}</textarea>
            </div>

            <!-- Upload CNI -->
            <div>
                <label for="id_document" class="block text-sm font-semibold text-gray-900 mb-2">
                    Justificatif d'identité (CNI/Passeport)
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-400 transition-colors cursor-pointer" onclick="document.getElementById('id_document').click()">
                    <input
                        type="file"
                        id="id_document"
                        name="id_document"
                        accept="image/*"
                        class="hidden"
                        onchange="updateFileName(this)"
                    >
                    <p id="fileName" class="text-gray-600 text-sm">
                        <span class="block">📎 Cliquez pour télécharger une image</span>
                        <span class="block text-xs text-gray-500 mt-1">JPG, PNG - Max 5 MB</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Conditions d'utilisation -->
        <div class="flex items-start gap-3 pt-4">
            <input
                type="checkbox"
                id="terms"
                name="terms"
                required
                class="w-4 h-4 mt-1 border-2 border-gray-300 rounded accent-primary-500 cursor-pointer"
            >
            <label for="terms" class="text-sm text-gray-700 cursor-pointer">
                J'accepte les <a href="#" class="font-semibold text-primary-600 hover:text-primary-700">conditions d'utilisation</a> et la <a href="#" class="font-semibold text-primary-600 hover:text-primary-700">politique de confidentialité</a>
            </label>
        </div>
        @error('terms')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Bouton d'inscription -->
        <button
            type="submit"
            class="w-full px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary-500/30 hover:scale-105 transition-all duration-300"
        >
            Créer mon compte
        </button>
    </form>

    <!-- Lien vers connexion -->
    <div class="pt-4 border-t border-gray-200">
        <p class="text-center text-gray-600">
            Vous avez déjà un compte ?
            <a
                href="{{ route('login') }}"
                class="font-semibold text-primary-600 hover:text-primary-700 transition-colors"
            >
                Se connecter
            </a>
        </p>
    </div>

    <!-- Script pour afficher/masquer les champs vendeur -->
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

        // Initialiser l'affichage au chargement
        toggleVendorFields();

        // Gérer le upload de fichier
        function updateFileName(input) {
            const fileNameEl = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameEl.textContent = '✅ ' + input.files[0].name;
            }
        }
    </script>
</x-guest-layout>
