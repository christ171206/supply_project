@extends('vendeur.layout-dashboard')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center gap-4">
                <x-heroicon-o-cog-6-tooth class="w-12 h-12 text-gray-900" />
                <div>
                    <h1 class="text-5xl font-bold text-gray-900">Profil Vendeur</h1>
                    <p class="text-gray-600 mt-3 text-lg">Gérez vos informations personnelles et professionnelles</p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-300 rounded-xl flex gap-3">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" />
                <div>
                    <p class="text-red-900 font-bold mb-2">Erreurs détectées</p>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <x-heroicon-o-x-circle class="w-4 h-4 mt-0.5 flex-shrink-0" />
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-300 rounded-xl flex gap-3">
                <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" />
                <div>
                    <p class="text-green-900 font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Section Photo de Profil -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-10 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-camera class="w-6 h-6 text-purple-600" />
                </div>
                Photo de Profil
            </h2>

            <form action="{{ route('vendeur.profil.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Aperçu Photo -->
                <div class="flex flex-col items-center mb-8">
                    @if(Auth::user()->profile_photo)
                        <img id="vendor-photo-preview" src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Photo de profil" class="w-48 h-48 rounded-2xl object-cover shadow-2xl border-4 border-purple-300 transition-transform hover:scale-105">
                    @else
                        <div class="w-48 h-48 rounded-2xl bg-gradient-to-br from-purple-200 to-blue-200 flex items-center justify-center shadow-xl border-4 border-purple-300">
                            <x-heroicon-o-user class="w-32 h-32 text-purple-600" />
                        </div>
                    @endif
                </div>

                <!-- Zone de dépôt -->
                <div class="border-2 border-dashed border-purple-300 rounded-2xl p-8 text-center hover:border-purple-500 hover:bg-purple-50 transition cursor-pointer" id="dropZone">
                    <input type="file" id="vendor-profile-photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewVendorPhoto(event)">

                    <div class="flex justify-center mb-3">
                        <x-heroicon-o-photo class="w-16 h-16 text-purple-400" />
                    </div>
                    <p class="text-gray-900 font-bold text-lg">Glissez votre photo ici ou cliquez</p>
                    <p class="text-gray-500 text-sm mt-2">JPG, PNG, GIF • Max 2 MB</p>
                </div>

                @error('profile_photo')
                    <p class="text-red-600 text-sm text-center mt-2">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-8 py-4 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl text-lg flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                    <span>Enregistrer la photo</span>
                </button>
            </form>
        </div>

        <!-- Section Informations Personnelles -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-10 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-user class="w-6 h-6 text-blue-600" />
                </div>
                Informations Personnelles
            </h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom Complet -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-900 mb-3">Nom Complet</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', Auth::user()->name) }}"
                            placeholder="Jean Dupont"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-gray-50"
                        >
                        @error('name')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-900 mb-3">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', Auth::user()->email) }}"
                            placeholder="votre@email.com"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-gray-50"
                        >
                        @error('email')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span>Mettre à jour</span>
                </button>
            </form>
        </div>

        <!-- Section Informations Boutique -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-10 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-building-storefront class="w-6 h-6 text-green-600" />
                </div>
                Informations Boutique
            </h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom Boutique -->
                    <div>
                        <label for="shop_name" class="block text-sm font-bold text-gray-900 mb-3">Nom de la Boutique *</label>
                        <input
                            type="text"
                            id="shop_name"
                            name="shop_name"
                            value="{{ old('shop_name', Auth::user()->shop_name ?? '') }}"
                            placeholder="Ma Boutique Électronique"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition bg-gray-50"
                        >
                        @error('shop_name')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-900 mb-3">Téléphone *</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', Auth::user()->phone ?? '') }}"
                            placeholder="+225 07 69 23 70 65"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition bg-gray-50"
                        >
                        @error('phone')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div>
                    <label for="address" class="block text-sm font-bold text-gray-900 mb-3">Adresse *</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        placeholder="Votre adresse complète (rue, commune, quartier)"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition bg-gray-50"
                    >{{ old('address', Auth::user()->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-900 mb-3">Description de Votre Boutique</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Parlez de votre boutique, vos spécialités, votre expérience..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition bg-gray-50"
                    >{{ old('description', Auth::user()->description ?? '') }}</textarea>
                    <p class="text-gray-500 text-xs mt-2">Cette description sera affichée sur votre profil</p>
                    @error('description')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span>Mettre à jour la boutique</span>
                </button>
            </form>
        </div>

        <!-- Section Sécurité -->
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl shadow-xl border border-red-200 p-10 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-lock-closed class="w-6 h-6 text-red-600" />
                </div>
                Sécurité & Confidentialité
            </h2>

            <div class="space-y-4">
                <p class="text-gray-700">Gérez la sécurité de votre compte et changez votre mot de passe.</p>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-3 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl">
                    <x-heroicon-o-key class="w-5 h-5" />
                    <span>Changer mon mot de passe</span>
                </a>
            </div>
        </div>

        <!-- Conseil -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-300 p-8">
            <div class="flex gap-4">
                <x-heroicon-o-light-bulb class="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" />
                <div>
                    <p class="text-blue-900 font-bold text-lg mb-2">Conseil Important</p>
                    <p class="text-blue-800">Maintenez vos informations à jour pour que vos clients puissent vous trouver facilement et vous contacter. Une boutique bien complétée inspire confiance et obtient plus de commandes!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('vendor-profile-photo');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
            fileInput.files = e.dataTransfer.files;
            previewVendorPhoto({ target: { files: e.dataTransfer.files } });
        });
    }

    function previewVendorPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('vendor-photo-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.className = 'w-48 h-48 rounded-2xl object-cover shadow-2xl border-4 border-purple-300 transition-transform hover:scale-105';
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
