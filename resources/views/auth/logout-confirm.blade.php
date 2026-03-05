@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md border border-gray-200">
        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center text-4xl animate-bounce">
                🚪
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-3">
            Déconnexion
        </h1>

        <!-- Message -->
        <p class="text-gray-600 text-center mb-8">
            Êtes-vous sûr de vouloir vous déconnecter ?
        </p>

        <!-- Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('accueil') }}" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition text-center">
                ✗ Annuler
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
