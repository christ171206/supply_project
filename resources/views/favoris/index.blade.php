@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">❤️ Mes Favoris</h1>
        <p class="text-gray-600">{{ $favoris->total() }} produit(s) en favoris</p>
    </div>

    @if($favoris->count() > 0)
        <!-- Grille de produits -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @foreach($favoris as $produit)
                @include('components.carte-produit', ['produit' => $produit])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $favoris->links() }}
        </div>
    @else
        <!-- Message vide -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">💔</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucun favori pour le moment</h2>
            <p class="text-gray-600 mb-8">Commencez à ajouter vos produits préférés en cliquant sur le cœur!</p>
            <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                🔍 Parcourir le catalogue
            </a>
        </div>
    @endif
</div>
@endsection
