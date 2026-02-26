@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Catalogue Produits</h1>
        <p class="text-gray-600">{{ count($produits) ?? 0 }} produits disponibles</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Filtres -->
        <aside class="lg:col-span-1">
            <div class="card sticky top-20">
                <h2 class="text-lg font-semibold text-gray-900 mb-5">Filtres</h2>

                <form method="GET" action="{{ route('produits.catalogue') }}" class="space-y-4">
                    <!-- Catégories -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 text-sm">Catégories</h3>
                        <div class="space-y-2">
                            <label class="flex items-center text-sm">
                                <input type="checkbox" value="" class="rounded border-gray-300" {{ !request('categorie') ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Toutes</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" name="categorie" value="{{ $cat->id }}" class="rounded border-gray-300" {{ request('categorie') == $cat->id ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">{{ $cat->nom }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-semibold text-gray-900 mb-3 text-sm">Prix (FCFA)</h3>
                        <div class="space-y-2">
                            <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="Min" class="input-field text-sm">
                            <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Max" class="input-field text-sm">
                        </div>
                    </div>

                    <!-- Tri -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-semibold text-gray-900 mb-3 text-sm">Tri</h3>
                        <select name="tri" class="input-field text-sm">
                            <option value="latest" {{ request('tri') === 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="prix_asc" {{ request('tri') === 'prix_asc' ? 'selected' : '' }}>Prix ↑</option>
                            <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix ↓</option>
                            <option value="nom" {{ request('tri') === 'nom' ? 'selected' : '' }}>Nom (A-Z)</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="border-t border-gray-200 pt-4 flex gap-2">
                        <button type="submit" class="btn-primary flex-1 text-sm py-2">
                            Filtrer
                        </button>
                        <a href="{{ route('produits.catalogue') }}" class="btn-secondary flex-1 text-sm py-2 text-center">
                            Réinit
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Produits -->
        <div class="lg:col-span-3">
            <!-- Barre de Recherche -->
            <form method="GET" action="{{ route('produits.catalogue') }}" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..." class="input-field flex-1">
                    <button type="submit" class="btn-primary px-6">
                        Chercher
                    </button>
                </div>
            </form>

            @if($produits && count($produits) > 0)
                <!-- Grille Produits -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
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
                <div class="card p-12 text-center">
                    <div class="mb-6">
                        <span class="text-6xl">🔍</span>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h2>
                    <p class="text-gray-600 mb-8">
                        @if(request('recherche'))
                            Aucun produit ne correspond à votre recherche "{{ request('recherche') }}"
                        @elseif(request('categorie'))
                            Aucun produit dans cette catégorie
                        @else
                            Aucun produit disponible
                        @endif
                    </p>
                    <a href="{{ route('produits.catalogue') }}" class="btn-primary inline-block px-6">
                        Voir tous les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
