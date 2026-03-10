@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-off-white flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-lg p-8 max-w-md text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-lg mx-auto mb-6 flex items-center justify-center">
            <svg viewBox="0 0 24 24"  fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="font-serif text-2xl mb-2">Merci !</h1>
        <p class="text-sm text-gray-600 mb-6">Votre demande vendeur a été reçue.</p>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-left text-xs space-y-3">
            <div class="flex gap-3">
                <span class="font-mono font-bold text-gray-800">1</span>
                <div>
                    <strong class="text-black">Vérification</strong>
                    <p class="text-gray-600">Nos équipes examinent vos documents</p>
                </div>
            </div>
            <div class="flex gap-3">
                <span class="font-mono font-bold text-gray-800">2</span>
                <div>
                    <strong class="text-black">Email de confirmation</strong>
                    <p class="text-gray-600">{{ session('registration_email') ?? 'Votre email' }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <span class="font-mono font-bold text-gray-800">3</span>
                <div>
                    <strong class="text-black">Accès tableau de bord</strong>
                    <p class="text-gray-600">Gérez vos produits et ventes</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-600 mb-6 border-t border-gray-200 pt-4">
            Délai estimé: <strong>24-48 heures</strong><br/>
            Vérifiez vos emails (spam aussi).
        </p>

        <div class="space-y-3">
            <a href="{{ route('accueil') }}" class="block px-4 py-2 bg-black text-white rounded-lg hover:opacity-85 font-medium text-sm transition">
                Retour à l'accueil
            </a>
            <a href="mailto:support@supply.ci" class="block px-4 py-2 border border-gray-200 text-black rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                Contacter le support
            </a>
        </div>
    </div>
</div>
@endsection
