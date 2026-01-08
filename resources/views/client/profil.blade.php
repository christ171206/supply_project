@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Retour -->
        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-8">
            ← Retour au tableau de bord
        </a>

        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-4xl shadow-lg">
                    👤
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Mon Profil</h1>
                    <p class="text-gray-600 mt-2">{{ Auth::user()->name }} • Compte depuis le {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-600 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="text-green-800 font-semibold">Modifications enregistrées</p>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne Principale -->
            <div class="lg:col-span-2">
                <!-- Card: Informations Personnelles -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-lg">
                            ℹ️
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Informations Personnelles</h2>
                    </div>

                    <form action="{{ route('client.profil.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nom Complet -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Nom</label>
                                <input type="text" name="lastname" value="{{ Auth::user()->lastname ?? '' }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 hover:bg-white"
                                       placeholder="Votre nom">
                                @error('lastname') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Prénom</label>
                                <input type="text" name="firstname" value="{{ Auth::user()->firstname ?? '' }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 hover:bg-white"
                                       placeholder="Votre prénom">
                                @error('firstname') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Email (En Lecture Seule) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Email</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1">
                                    <input type="email" value="{{ Auth::user()->email }}" readonly
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                                </div>
                                <span class="text-green-600 text-2xl">✓</span>
                            </div>
                            <p class="text-gray-500 text-xs mt-2">🔒 L'email ne peut pas être modifié pour votre sécurité</p>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Téléphone</label>
                            <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 hover:bg-white"
                                   placeholder="+225 XX XX XX XX">
                            @error('phone') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Adresse de Livraison -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Adresse de Livraison</label>
                            <textarea name="address" rows="4"
                                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-gray-50 hover:bg-white resize-none"
                                      placeholder="Votre adresse complète pour les livraisons">{{ Auth::user()->address ?? '' }}</textarea>
                            @error('address') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition transform hover:scale-105 shadow-md flex items-center justify-center gap-2">
                                <span>✓</span>
                                <span>Enregistrer les modifications</span>
                            </button>
                            <a href="{{ route('client.dashboard') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Droit -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card: Sécurité -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                            🔒
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Sécurité</h3>
                    </div>

                    <div class="space-y-4">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                            <span class="text-xl">🔑</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-orange-600">Changer mot de passe</p>
                                <p class="text-xs text-gray-600">Mettre à jour votre sécurité</p>
                            </div>
                            <span class="text-gray-400 group-hover:text-orange-600">→</span>
                        </a>
                    </div>
                </div>

                <!-- Card: Infos Compte -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-lg">
                            <x-icon name="bar-chart-2" class="w-8 h-8 text-blue-600" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Mon Compte</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600">Statut</p>
                            <p class="font-bold text-blue-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Actif
                            </p>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600">Inscrit depuis</p>
                            <p class="font-semibold text-gray-900">{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600">Rôle</p>
                            <p class="font-semibold text-gray-900 flex items-center gap-2">
                                <span>🛒</span>
                                Client
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card: Actions Dangereuses -->
                <div class="bg-red-50 rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-lg">
                            ⚠️
                        </div>
                        <h3 class="text-lg font-bold text-red-900">Zone Dangereuse</h3>
                    </div>

                    <form action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('⚠️ Êtes-vous absolument sûr?\\nCette action est IRRÉVERSIBLE!\\nToutes vos données seront supprimées.')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-bold transition flex items-center justify-center gap-2">
                            <span>🗑️</span>
                            <span>Supprimer mon compte</span>
                        </button>
                    </form>
                    <p class="text-red-700 text-xs mt-3">
                        Cette action est définitive et ne peut pas être annulée.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
