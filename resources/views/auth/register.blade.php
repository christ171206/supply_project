<x-guest-layout>
    <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white">
            <!-- Header simple -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer un compte</h1>
                <p class="text-gray-600">Rejoignez Supply dès aujourd'hui</p>
            </div>

            <!-- Formulaire -->
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                        placeholder="Jean Dupont"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                        placeholder="votre@email.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-500">Minimum 8 caractères</p>
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                        placeholder="••••••••"
                    >
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de compte -->
                <div class="pt-2">
                    <p class="block text-sm font-semibold text-gray-900 mb-3">Type de compte</p>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input
                                type="radio"
                                id="role_client"
                                name="role"
                                value="client"
                                checked
                                class="w-4 h-4 accent-blue-600 cursor-pointer"
                            >
                            <label for="role_client" class="ml-3 cursor-pointer text-gray-700 text-sm">
                                Client - Acheter des produits
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input
                                type="radio"
                                id="role_vendor"
                                name="role"
                                value="vendor"
                                class="w-4 h-4 accent-blue-600 cursor-pointer"
                                onchange="toggleVendorFields()"
                            >
                            <label for="role_vendor" class="ml-3 cursor-pointer text-gray-700 text-sm">
                                Vendeur - Vendre vos produits
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Champs vendeur -->
                <div id="vendorFields" class="hidden pt-4 space-y-4 border-t border-gray-200">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-xs text-blue-800">Votre demande sera vérifiée avant activation</p>
                    </div>

                    <div>
                        <label for="shop_name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Nom de la boutique
                        </label>
                        <input
                            type="text"
                            id="shop_name"
                            name="shop_name"
                            value="{{ old('shop_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                            placeholder="Ma Boutique"
                        >
                        @error('shop_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                            Téléphone
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                            placeholder="+33 6 12 34 56 78"
                        >
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                            Adresse
                        </label>
                        <textarea
                            id="address"
                            name="address"
                            rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-900"
                            placeholder="Votre adresse"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_document" class="block text-sm font-semibold text-gray-900 mb-2">
                            Justificatif d'identité
                        </label>
                        <div class="border border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:bg-gray-50 transition" onclick="document.getElementById('id_document').click()">
                            <input
                                type="file"
                                id="id_document"
                                name="id_document"
                                accept="image/*"
                                class="hidden"
                                onchange="updateFileName(this)"
                            >
                            <p id="fileName" class="text-sm text-gray-600">
                                Cliquez pour télécharger
                            </p>
                        </div>
                        @error('id_document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Conditions -->
                <div class="flex items-start gap-2 pt-2">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                        class="w-4 h-4 mt-1 accent-blue-600 cursor-pointer"
                    >
                    <label for="terms" class="text-sm text-gray-700 cursor-pointer">
                        J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                    </label>
                </div>
                @error('terms')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Bouton -->
                <button
                    type="submit"
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition"
                >
                    Créer un compte
                </button>
            </form>

            <!-- Lien login -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">
                        Se connecter
                    </a>
                </p>
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
    </script>
</x-guest-layout>
