@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md border border-gray-200">
        <!-- Success Icon with Animation -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="absolute inset-0 bg-green-200 rounded-full animate-pulse opacity-75"></div>
                <div class="relative w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-5xl animate-bounce">
                    ✓
                </div>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-2">
            Merci !
        </h1>
        <p class="text-sm text-green-600 font-semibold text-center mb-6">
            Inscription vendeur en cours de vérification
        </p>

        <!-- Main Message -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 mb-6">
            <p class="text-gray-700 text-center leading-relaxed mb-4">
                Votre demande de compte vendeur a été reçue avec succès.
            </p>
            <p class="text-gray-700 text-center leading-relaxed">
                Nos équipes vérifieront votre <strong>document d'identité</strong> et les informations de votre boutique.
            </p>
        </div>

        <!-- Timeline Steps -->
        <div class="space-y-4 mb-8">
            <div class="flex gap-4">
                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white font-bold text-sm">
                    1
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Vérification en cours</p>
                    <p class="text-sm text-gray-600">Nos équipes examinant vos documents</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold text-sm">
                    2
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Confirmation par email</p>
                    <p class="text-sm text-gray-600">Un email vous sera envoyé à <strong>{{ session('registration_email') ?? 'votre email' }}</strong></p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white font-bold text-sm">
                    3
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Accès à votre tableau de bord</p>
                    <p class="text-sm text-gray-600">Gérez vos produits et vos ventes</p>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
            <p class="text-sm text-blue-900">
                <strong>⏱️ Délai estimé :</strong> Votre compte sera généralement approuvé en <strong>24-48 heures</strong>.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('accueil') }}" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 font-semibold transition text-center">
                <x-heroicon-o-home class=\"w-5 h-5\" /><span>Retour à l'accueil</span>
            </a>
            <a href="mailto:support@supply.ci" class="w-full px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition text-center">
                Contacter le support
            </a>
        </div>

        <!-- Important Notice -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-600 text-center leading-relaxed">
                Vérifiez vos emails, y compris le dossier <strong>spam</strong>, pour la notification d'approbation. Si vous avez des questions, n'hésitez pas à nous contacter.
            </p>
        </div>
    </div>
</div>
@endsection
