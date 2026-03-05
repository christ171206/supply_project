@extends('layouts.admin-layout')

@section('title', 'Sécurité')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <x-heroicon-o-lock-closed class="w-8 h-8 text-red-600" />
            <h1 class="text-3xl font-bold text-gray-900">Sécurité</h1>
        </div>
        <p class="text-gray-600">Gérez vos paramètres de sécurité et votre mot de passe</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Change Password -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <x-heroicon-o-lock-closed class="w-5 h-5" />
            <span>Changer le mot de passe</span>
        </h2>

        <form action="{{ route('admin.security.password') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Mot de passe actuel
                </label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <!-- New Password -->
            <div>
                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nouveau mot de passe
                </label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirmer le mot de passe
                </label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <button 
                type="submit" 
                class="w-full px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold"
            >
                Mettre à jour le mot de passe
            </button>
        </form>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            <span>Authentification à deux facteurs</span>
        </h2>

        <p class="text-gray-600 mb-4">Renforcez la sécurité de votre compte avec l'authentification à deux facteurs.</p>

        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
                <p class="font-semibold text-gray-900">État : <span class="text-orange-600">Désactivé</span></p>
                <p class="text-xs text-gray-500">Activez l'authentification à deux facteurs pour plus de sécurité</p>
            </div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm">
                Activer
            </button>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <x-heroicon-o-circle-stack class="w-5 h-5" />
            <span>Sessions actives</span>
        </h2>

        <div class="space-y-3">
            <!-- Current Session -->
            <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg bg-blue-50">
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Chrome sur Windows</p>
                    <p class="text-xs text-gray-500">127.0.0.1 • Session actuelle</p>
                    <p class="text-xs text-gray-400 mt-1">Connecté depuis 2 heures</p>
                </div>
                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-semibold">
                    Actif
                </span>
            </div>

            <p class="text-sm text-gray-600 mt-4">
                Vous n'avez qu'une seule session active.
            </p>
        </div>
    </div>
</div>
@endsection
