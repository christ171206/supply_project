@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('accueil') }}" class="hover:text-blue-600">Accueil</a>
        <span>/</span>
        <a href="{{ route('produits.catalogue') }}" class="hover:text-blue-600">Catalogue</a>
        <span>/</span>
        @if($produit->categorie)
            <a href="{{ route('produits.catalogue', ['categorie' => $produit->categorie->id]) }}" class="hover:text-blue-600">
                {{ $produit->categorie->nom }}
            </a>
            <span>/</span>
        @endif
        <span class="text-gray-900 font-semibold">{{ $produit->nom }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Image Produit -->
        <div class="lg:col-span-1">
            <div class="bg-gray-200 rounded-lg overflow-hidden sticky top-24">
                @if($produit->image)
                    <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full aspect-square flex items-center justify-center bg-gray-300">
                        <span class="text-6xl">📦</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails Produit -->
        <div class="lg:col-span-2">
            <!-- Catégorie -->
            @if($produit->categorie)
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        {{ $produit->categorie->nom }}
                    </span>
                </div>
            @endif

            <!-- Titre -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $produit->nom }}</h1>

            <!-- Vendeur -->
            @if($produit->vendeur)
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <p class="text-gray-600 text-sm">Vendu par</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $produit->vendeur->name }}</p>
                </div>
            @endif

            <!-- Prix -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-baseline gap-4">
                    <span class="text-5xl font-bold text-gray-900">{{ number_format($produit->prix, 2, ',', ' ') }} €</span>
                    @if($produit->prix_original && $produit->prix_original > $produit->prix)
                        <span class="text-2xl text-gray-500 line-through">
                            {{ number_format($produit->prix_original, 2, ',', ' ') }} €
                        </span>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                            -{{ round(((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100)) }}%
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stock -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                @if($produit->stock > 0)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-600 font-semibold">{{ $produit->stock }} en stock</span>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-600 font-semibold">Rupture de stock</span>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700 text-lg leading-relaxed">{{ $produit->description }}</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 mb-8">
                @if($produit->stock > 0)
                    <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantite" value="1">
                        <button type="submit" class="flex-1 px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-lg">
                            🛒 Ajouter au Panier
                        </button>
                    </form>
                @else
                    <button disabled class="flex-1 px-8 py-4 bg-gray-400 text-white rounded-lg cursor-not-allowed font-bold text-lg">
                        Indisponible
                    </button>
                @endif
                <button class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 transition font-bold text-lg">
                    ❤️
                </button>
            </div>

            <!-- Caractéristiques -->
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Référence</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Catégorie</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->categorie?->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Stock Disponible</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->stock }} unités</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Actif</p>
                        <p class="text-gray-900 font-semibold">{{ $produit->est_actif ? '✓ Oui' : '✗ Non' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avis Clients -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Avis Clients</h2>
            @auth
                <a href="#ajouter-avis" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Donner votre avis
                </a>
            @endauth
        </div>

        @if($avis && count($avis) > 0)
            <div class="space-y-6">
                @foreach($avis as $av)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-gray-900">{{ $av->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $av->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-lg">{{ $i <= $av->note ? '⭐' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $av->commentaire }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 text-center py-8">Aucun avis pour le moment. Soyez le premier à donner votre avis !</p>
        @endif
    </div>

    <!-- Produits Recommandés -->
    @if($produitsSimilaires && count($produitsSimilaires) > 0)
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Produits Similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($produitsSimilaires as $similaire)
                    @include('components.carte-produit', ['produit' => $similaire])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
