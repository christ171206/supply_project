@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">📦 Catalogue Produits</h1>
        <p class="text-gray-600 text-lg">Découvrez notre sélection complète de produits informatiques</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filtres -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Filtres</h2>

                <form method="GET" action="{{ route('produits.catalogue') }}" class="space-y-6">
                    <!-- Catégories -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Catégories</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" value="" class="rounded" {{ !request('categorie') ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Toutes les catégories</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center">
                                    <input type="checkbox" name="categorie" value="{{ $cat->id }}" class="rounded" {{ request('categorie') == $cat->id ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">{{ $cat->nom }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="border-t pt-6">
                        <h3 class="font-bold text-gray-900 mb-3">Prix</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-700">Prix Min</label>
                                <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded">
                            </div>
                            <div>
                                <label class="text-sm text-gray-700">Prix Max</label>
                                <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="10000" class="w-full px-3 py-2 border border-gray-300 rounded">
                            </div>
                        </div>
                    </div>

                    <!-- Tri -->
                    <div class="border-t pt-6">
                        <h3 class="font-bold text-gray-900 mb-3">Tri</h3>
                        <select name="tri" class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="latest" {{ request('tri') === 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="prix_asc" {{ request('tri') === 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="nom" {{ request('tri') === 'nom' ? 'selected' : '' }}>Nom A-Z</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="border-t pt-6 flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Filtrer
                        </button>
                        <a href="{{ route('produits.catalogue') }}" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-center">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Produits -->
        <div class="lg:col-span-3">
            <!-- Barre de Recherche -->
            <form method="GET" action="{{ route('produits.catalogue') }}" class="mb-8">
                <div class="flex gap-2">
                    <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher un produit..." class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        🔍 Chercher
                    </button>
                </div>
            </form>

            @if($produits && count($produits) > 0)
                <!-- Grille Produits -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                    @foreach($produits as $produit)
                        @include('components.carte-produit', ['produit' => $produit])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $produits->links() }}
                </div>
            @else
                <!-- Aucun Résultat -->
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🔍</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucun produit trouvé</h2>
                    <p class="text-gray-600 mb-8">
                        @if(request('recherche'))
                            Aucun produit ne correspond à votre recherche "{{ request('recherche') }}"
                        @elseif(request('categorie'))
                            Aucun produit dans cette catégorie
                        @else
                            Aucun produit disponible
                        @endif
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Voir tous les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
