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
                <div class="relative group">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Photo de profil" class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-blue-400">
                    @else
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(Auth::user()->email) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-blue-400">
                    @endif
                    <label for="profile-photo-input" class="absolute inset-0 rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center cursor-pointer transition opacity-0 group-hover:opacity-100">
                        <span class="text-white font-semibold">📸</span>
                    </label>
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
                <!-- Card: Photo de Profil -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-lg">
                            📸
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Photo de Profil</h2>
                    </div>

                    <form action="{{ route('client.profil.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="photo-form">
                        @csrf
                        @method('PATCH')

                        <!-- Aperçu Photo -->
                        <div class="flex flex-col items-center">
                            @if(Auth::user()->profile_photo)
                                <img id="photo-preview" src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Photo de profil" class="w-40 h-40 rounded-full object-cover shadow-lg border-4 border-purple-400 mb-6">
                            @else
                                <div id="photo-preview" class="w-40 h-40 bg-gray-200 rounded-full flex items-center justify-center text-6xl shadow-lg border-4 border-gray-300 mb-6">
                                    👤
                                </div>
                            @endif
                        </div>

                        <!-- Input Fichier -->
                        <div>
                            <label for="profile-photo-input" class="block text-sm font-semibold text-gray-700 mb-3">Choisir une photo</label>
                            <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:border-purple-500" style="border-color: @error('profile_photo') #ef4444 @else #c4b5fd @enderror;" onchange="previewPhoto(event)">
                            @error('profile_photo')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-2">📁 JPG, PNG, GIF - Max 2 MB</p>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-sm" id="photo-submit-btn">
                            💾 Mettre à jour la photo
                        </button>
                    </form>
                </div>

                <!-- Card: Zone de Livraison (Leaflet Map) -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-8 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-lg">
                            📍
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Zone de Livraison</h2>
                    </div>

                    <p class="text-gray-600 text-sm mb-4">Cliquez sur la carte pour marquer votre zone de livraison préférée</p>

                    <!-- Carte Leaflet -->
                    <div id="leaflet-map" style="height: 300px; border-radius: 8px; border: 2px solid #e5e7eb; margin-bottom: 15px;"></div>

                    <!-- Coordonnées affichées -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Latitude</p>
                            <p id="latitude-display" class="text-lg font-bold text-gray-900 mt-1">{{ Auth::user()->delivery_latitude ?? '—' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Longitude</p>
                            <p id="longitude-display" class="text-lg font-bold text-gray-900 mt-1">{{ Auth::user()->delivery_longitude ?? '—' }}</p>
                        </div>
                    </div>

                    <!-- Inputs cachés pour les coordonnées -->
                    <input type="hidden" id="latitude-input" name="delivery_latitude" value="{{ Auth::user()->delivery_latitude ?? '' }}">
                    <input type="hidden" id="longitude-input" name="delivery_longitude" value="{{ Auth::user()->delivery_longitude ?? '' }}">
                </div>

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

                <!-- Security Logs Component -->
                @include('components.security-logs')

                <!-- Card: Actions Dangereuses -->
                <div class="bg-red-50 rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-lg">
                            ⚠️
                        </div>
                        <h3 class="text-lg font-bold text-red-900">Zone Dangereuse</h3>
                    </div>

                    <button type="button" onclick="openDeleteAccountModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-bold transition flex items-center justify-center gap-2">
                        <span>🗑️</span>
                        <span>Supprimer mon compte</span>
                    </button>
                    <p class="text-red-700 text-xs mt-3">
                        Cette action est définitive et ne peut pas être annulée.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmation de Suppression -->
<div id="deleteAccountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;" onclick="if(event.target === this) closeDeleteModal()"><div class="flex items-center justify-center h-full">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 p-6 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-white">Supprimer mon compte</h3>
                <p class="text-red-100 text-sm mt-1">Cette action est irréversible</p>
            </div>
            <button onclick="closeDeleteAccountModal()" class="text-white hover:text-red-100 text-2xl leading-none">×</button>
        </div>

        <!-- Corps -->
        <div class="p-6">
            <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-6">
                <p class="text-red-900 text-sm">
                    ⚠️ <strong>Attention!</strong> Supprimer votre compte supprimera définitivement:
                </p>
                <ul class="text-red-800 text-xs mt-3 space-y-1 ml-4 list-disc">
                    <li>Vos informations personnelles</li>
                    <li>Vos commandes et historique</li>
                    <li>Vos favoris et panier</li>
                    <li>Vos messages</li>
                </ul>
            </div>

            <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('DELETE')

                <!-- Mot de passe -->
                <div>
                    <label for="password-confirm" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                    <input
                        type="password"
                        id="password-confirm"
                        name="password"
                        required
                        placeholder="Entrez votre mot de passe pour confirmer"
                        class="w-full px-4 py-3 border-2 border-red-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-colors text-sm"
                    >
                    <p class="text-gray-500 text-xs mt-2">Votre mot de passe est demandé pour votre sécurité</p>
                </div>

                <!-- Messages d'erreur -->
                @if($errors->userDeletion->has('password'))
                    <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                        {{ $errors->userDeletion->first('password') }}
                    </div>
                @endif

                <!-- Boutons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeDeleteAccountModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">

<script>
    // Initialiser la carte Leaflet
    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('leaflet-map');

        if (!mapElement) return;

        // Coordonnées par défaut (Abidjan, Côte d'Ivoire)
        const defaultLat = {{ Auth::user()->delivery_latitude ?? 5.3536 }};
        const defaultLng = {{ Auth::user()->delivery_longitude ?? -4.0083 }};

        // Créer la carte
        const map = L.map('leaflet-map').setView([defaultLat, defaultLng], 13);

        // Ajouter la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        // Ajouter un marqueur existant s'il y a des coordonnées sauvegardées
        @if(Auth::user()->delivery_latitude && Auth::user()->delivery_longitude)
            marker = L.marker([{{ Auth::user()->delivery_latitude }}, {{ Auth::user()->delivery_longitude }}]).addTo(map)
                .bindPopup('📍 Votre zone de livraison')
                .openPopup();
        @endif

        // Ajouter un marqueur au clic
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            // Supprimer l'ancien marqueur
            if (marker) {
                map.removeLayer(marker);
            }

            // Ajouter le nouveau marqueur
            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup('📍 Marqueur de livraison')
                .openPopup();

            // Mettre à jour les inputs
            document.getElementById('latitude-input').value = lat;
            document.getElementById('longitude-input').value = lng;
            document.getElementById('latitude-display').textContent = lat;
            document.getElementById('longitude-display').textContent = lng;
        });
    });

    function previewPhoto(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('photo-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-40 h-40 rounded-full object-cover shadow-lg border-4 border-purple-400';
                    preview.innerHTML = '';
                    preview.appendChild(img);
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function openDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fermer le modal avec Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteAccountModal();
        }
    });

    // Fermer le modal si on clique en dehors
    document.getElementById('deleteAccountModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteAccountModal();
        }
    });

    // Rafraîchir la page après upload de photo pour mettre à jour l'avatar dans la navbar
    document.getElementById('photo-form').addEventListener('submit', function(e) {
        // Désactiver le bouton pour éviter les doubles submissions
        document.getElementById('photo-submit-btn').disabled = true;
        document.getElementById('photo-submit-btn').textContent = '⏳ Mise à jour...';

        // Rafraîchir après 1.5 secondes (temps de traitement du serveur)
        setTimeout(function() {
            window.location.reload();
        }, 1500);
    });
</script>
@endsection
