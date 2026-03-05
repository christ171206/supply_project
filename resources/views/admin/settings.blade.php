@extends('layouts.admin-layout')

@section('title', 'Paramètres')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <x-heroicon-o-cog-6-tooth class="w-8 h-8 text-gray-600" />
            <h1 class="text-3xl font-bold text-gray-900">Paramètres Administrateur</h1>
        </div>
        <p class="text-gray-600">Configurez les préférences de votre espace administrateur</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Display Preferences -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <x-heroicon-o-paint-brush class="w-5 h-5" />
                <span>Préférences d'affichage</span>
            </h2>

            <div class="space-y-4">
                <!-- Theme -->
                <div>
                    <label for="theme" class="block text-sm font-semibold text-gray-700 mb-2">
                        Thème
                    </label>
                    <select id="theme" name="theme" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="light">Clair</option>
                        <option value="dark">Sombre</option>
                        <option value="system">Système</option>
                    </select>
                </div>

                <!-- Items Per Page -->
                <div>
                    <label for="items_per_page" class="block text-sm font-semibold text-gray-700 mb-2">
                        Éléments par page
                    </label>
                    <select id="items_per_page" name="items_per_page" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Notifications -->
                <div class="space-y-3 pt-3 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900">Notifications</h3>
                    
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="notify_orders" checked class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700">Notifications de nouvelles commandes</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="notify_vendors" checked class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700">Notifications de nouveaux vendeurs</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="notify_disputes" checked class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700">Notifications de litiges</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="notify_email" checked class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700">Recevoir les notifications par email</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <x-heroicon-o-bell class="w-5 h-5" />
                <span>Paramètres de notification</span>
            </h2>

            <div class="space-y-4">
                <!-- Notification Frequency -->
                <div>
                    <label for="notification_frequency" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fréquence des notifications
                    </label>
                    <select id="notification_frequency" name="notification_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="instant">Instantanée</option>
                        <option value="hourly">Horaire</option>
                        <option value="daily" selected>Quotidienne</option>
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label for="notification_email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email pour les notifications
                    </label>
                    <input 
                        type="email" 
                        id="notification_email" 
                        name="notification_email"
                        value="{{ auth()->user()->email }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>
        </div>

        <!-- Data & Privacy -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <x-heroicon-o-shield-check class="w-5 h-5" />
                <span>Données et confidentialité</span>
            </h2>

            <div class="space-y-4">
                <!-- Activity Log -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-900">Journal d'activité</p>
                        <p class="text-xs text-gray-500">Afficher votre historique d'activité</p>
                    </div>
                    <a href="{{ route('admin.audit.index') }}" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                        Voir
                    </a>
                </div>

                <!-- Export Data -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-900">Exporter les données</p>
                        <p class="text-xs text-gray-500">Télécharger une copie de vos données</p>
                    </div>
                    <button type="button" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                        Exporter
                    </button>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button 
                type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
            >
                Enregistrer les paramètres
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
@endsection
