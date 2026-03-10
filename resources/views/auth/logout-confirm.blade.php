@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-off-white flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-lg p-8 max-w-sm">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-gray-100 rounded-lg mx-auto mb-4 flex items-center justify-center text-xl">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 3h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2M9 17h6M9 13h6"/>
                </svg>
            </div>
            <h1 class="font-serif text-2xl mb-2">Déconnexion</h1>
            <p class="text-sm text-gray-600">Êtes-vous sûr de vouloir te déconnecter ?</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('accueil') }}" class="block text-center px-4 py-2 border border-gray-200 text-black rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                Annuler
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-black text-white rounded-lg hover:opacity-85 font-medium text-sm transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
