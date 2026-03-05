@extends('layouts.admin-layout')

@section('title', 'Éditer Profil')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <x-heroicon-o-user class="w-8 h-8 text-blue-600" />
            <h1 class="text-3xl font-bold text-gray-900">Éditer Profil</h1>
        </div>
        <p class="text-gray-600">Gérez les informations de votre profil administrateur</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 space-y-6">
        <!-- Avatar Section -->
        <div class="flex items-center gap-6 pb-6 border-b border-gray-200">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-2">Photo de profil</p>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                    Changer la photo
                </button>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom Complet
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ auth()->user()->name }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <!-- Email (Read-only) -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    value="{{ auth()->user()->email }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                    disabled
                >
                <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
            </div>

            <!-- Phone (Optional) -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Téléphone (Optionnel)
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone"
                    placeholder="+225 XX XX XX XX"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Bio (Optional) -->
            <div>
                <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Bio (Optionnel)
                </label>
                <textarea 
                    id="bio" 
                    name="bio"
                    rows="4"
                    placeholder="Décrivez votre rôle et responsabilités..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    Enregistrer les modifications
                </button>
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold"
                >
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-900">
            <strong>Note :</strong> Votre adresse email est liée à votre compte de connexion. Pour modifier votre email ou votre mot de passe, veuillez accéder à la section <a href="{{ route('admin.security') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Sécurité</a>.
        </p>
    </div>
</div>
@endsection
