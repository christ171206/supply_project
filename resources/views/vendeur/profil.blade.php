@extends('vendeur.layout')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900">⚙️ Mon Profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et votre boutique</p>
    </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>❌ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Infos Personnelles -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">📋 Informations Personnelles</h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom Complet</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-sm">
                    <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Mettre à jour
                </button>
            </form>
        </div>

        <!-- Infos Boutique -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-icon name="store" class="w-6 h-6 text-blue-600" /> Informations Boutique</h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom Boutique -->
                    <div>
                        <label for="shop_name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de la Boutique</label>
                        <input
                            type="text"
                            id="shop_name"
                            name="shop_name"
                            value="{{ old('shop_name', Auth::user()->shop_name ?? '') }}"
                            placeholder="Ex: Ma Boutique Électronique"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shop_name') border-red-500 @enderror"
                        >
                        @error('shop_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', Auth::user()->phone ?? '') }}"
                            placeholder="Ex: +221 77 123 45 67"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                        >
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        placeholder="Votre adresse complète"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                    >{{ old('address', Auth::user()->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Bio)</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Parlez un peu de vous et de votre boutique..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    >{{ old('description', Auth::user()->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-sm">
                    <x-icon name="check-circle" class="w-4 h-4 inline mr-1" /> Mettre à jour la boutique
                </button>
            </form>
        </div>

        <!-- Sécurité -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-icon name="lock" class="w-6 h-6 text-red-600" /> Sécurité</h2>

            <a href="{{ route('profile.edit') }}" class="inline-flex items-center bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-sm">
                🔑 Changer mon mot de passe
            </a>
        </div>

        <!-- Conseil -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <p class="text-blue-700">
                💡 <strong>Conseil :</strong> Maintenez vos informations à jour pour que vos clients puissent vous contacter facilement !
            </p>
        </div>

</div>
@endsection
