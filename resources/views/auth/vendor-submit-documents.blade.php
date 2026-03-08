<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'identité - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <!-- Retour à l'accueil -->
            <a href="{{ route('accueil') }}" class="inline-flex items-center gap-3 text-gray-700 hover:text-primary-600 font-semibold mb-8 transition py-2 px-3 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                ← Retour
            </a>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- En-tête avec gradient -->
                <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-8 py-12">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h5m4 0h5a2 2 0 002-2V8a2 2 0 00-2-2h-5m4 0V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v1"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Vérification d'identité</h1>
                                <p class="text-white/90 text-sm mt-1">Étape requise pour activer votre boutique</p>
                            </div>
                        </div>
                    </div>
                    <!-- Badge de progression -->
                    <div class="inline-block mt-4 px-3 py-1 bg-white/10 border border-white/20 rounded-full">
                        <p class="text-white/90 text-xs font-semibold">Étape 2 sur 3</p>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="px-8 py-8 md:px-10 md:py-10">
                    <!-- Message d'information -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-8">
                        <p class="text-blue-900 font-medium flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Veuillez fournir une copie claire des deux côtés (recto et verso) de votre document d'identité pour confirmer votre identité.</span>
                        </p>
                    </div>

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('vendor.documents.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Type de document -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-4">
                                🪪 Type de document d'identité
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach(['cni' => ['label' => 'Carte d\'identité nationale', 'icon' => '🇨🇮'], 'cmu' => ['label' => 'Carte Multiservice', 'icon' => '📱'], 'passport' => ['label' => 'Passeport', 'icon' => '🛂']] as $type => $info)
                                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition" for="id_type_{{ $type }}">
                                        <input
                                            type="radio"
                                            id="id_type_{{ $type }}"
                                            name="id_type"
                                            value="{{ $type }}"
                                            class="w-4 h-4 accent-primary-500 cursor-pointer"
                                            {{ old('id_type') === $type ? 'checked' : '' }}
                                        >
                                        <span class="ml-3 flex-grow">
                                            <span class="text-sm font-semibold text-gray-900">{{ $info['icon'] }} {{ $info['label'] }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('id_type')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Numéro du document -->
                        <div>
                            <label for="id_number" class="block text-sm font-bold text-gray-900 mb-2">
                                🆔 Numéro du document
                            </label>
                            <input
                                type="text"
                                id="id_number"
                                name="id_number"
                                value="{{ old('id_number') }}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors bg-gray-50 text-gray-900 placeholder-gray-500 font-medium"
                                placeholder="Entrez le numéro de votre document"
                            >
                            @error('id_number')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photos recto verso -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Photo recto -->
                            <div>
                                <label for="id_front" class="block text-sm font-bold text-gray-900 mb-3">
                                    📷 Recto (avant)
                                </label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        id="id_front"
                                        name="id_front"
                                        accept="image/*"
                                        class="hidden"
                                        required
                                        onchange="previewImage(this, 'preview_front')"
                                    >
                                    <div
                                        id="upload_front"
                                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition"
                                        onclick="document.getElementById('id_front').click()"
                                    >
                                        <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Cliquez pour télécharger</p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG (max 5 Mo)</p>
                                    </div>
                                    <img
                                        id="preview_front"
                                        class="hidden mt-3 w-full rounded-lg border-2 border-gray-200 max-h-48 object-cover"
                                        alt="Aperçu recto"
                                    >
                                </div>
                                @error('id_front')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Photo verso -->
                            <div>
                                <label for="id_back" class="block text-sm font-bold text-gray-900 mb-3">
                                    📷 Verso (arrière)
                                </label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        id="id_back"
                                        name="id_back"
                                        accept="image/*"
                                        class="hidden"
                                        required
                                        onchange="previewImage(this, 'preview_back')"
                                    >
                                    <div
                                        id="upload_back"
                                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition"
                                        onclick="document.getElementById('id_back').click()"
                                    >
                                        <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Cliquez pour télécharger</p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG (max 5 Mo)</p>
                                    </div>
                                    <img
                                        id="preview_back"
                                        class="hidden mt-3 w-full rounded-lg border-2 border-gray-200 max-h-48 object-cover"
                                        alt="Aperçu verso"
                                    >
                                </div>
                                @error('id_back')
                                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Conditions d'acceptation -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 font-medium mb-2">🔒 Sécurité et confidentialité :</p>
                            <ul class="text-xs text-gray-600 space-y-1 ml-3">
                                <li>✓ Vos documents sont cryptés et sécurisés</li>
                                <li>✓ Utilisés uniquement à des fins de vérification</li>
                                <li>✓ Jamais partagés avec des tiers</li>
                                <li>✓ Stockés conformément aux réglementations</li>
                            </ul>
                        </div>

                        <!-- Messages d'erreur généraux -->
                        @if ($errors->any() && !$errors->has(['id_type', 'id_number', 'id_front', 'id_back']))
                            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <p class="text-red-800 font-semibold mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Erreur
                                </p>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-700 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Boutons d'action -->
                        <div class="flex gap-4 pt-4">
                            <button
                                type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary-500/50 transition-all duration-200 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Soumettre les documents
                            </button>
                            <a
                                href="{{ route('accueil') }}"
                                class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all duration-200"
                            >
                                Annuler
                            </a>
                        </div>
                    </form>

                    <!-- Conseil utile -->
                    <div class="mt-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm text-amber-900 font-medium">💡 Conseil :</p>
                        <ul class="text-xs text-amber-800 mt-2 space-y-1">
                            <li>✓ Assurez-vous que le texte est bien lisible</li>
                            <li>✓ Photographiez sur un fond clair</li>
                            <li>✓ Évitez les reflets et les ombres</li>
                            <li>✓ Vérifiez que tous les coins sont visibles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const uploadDiv = input.closest('div').querySelector('[onclick*="click"]');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (uploadDiv) {
                        uploadDiv.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Afficher aperçu si document déjà soumis avec erreur
        document.addEventListener('DOMContentLoaded', function() {
            const frontInput = document.getElementById('id_front');
            const backInput = document.getElementById('id_back');

            if (frontInput.files.length > 0) {
                previewImage(frontInput, 'preview_front');
            }
            if (backInput.files.length > 0) {
                previewImage(backInput, 'preview_back');
            }
        });
    </script>
</body>
</html>
