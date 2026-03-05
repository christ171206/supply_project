@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête avec bouton création -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <x-heroicon-o-cube class="w-10 h-10" />
                <span>Mes Produits</span>
            </h1>
            <p class="text-gray-600">Gestion de votre catalogue de produits</p>
        </div>
        <a href="{{ route('vendeur.produits.create') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition font-semibold flex items-center gap-2">
            ➕ Ajouter un Produit
        </a>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Rechercher un produit..."
                   class="flex-1 min-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   value="{{ request('search') }}">

            <select name="categorie" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold flex items-center gap-2">
                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                <span>Filtrer</span>
            </button>
        </form>
    </div>

    <!-- Tableau des produits -->
    @if($produits->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($produits as $produit)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <!-- Image du produit -->
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                            <img src="{{ asset('storage/produits/' . $produit->images[0]) }}"
                                 alt="{{ $produit->nom }}"
                                 class="w-full h-full object-cover hover:scale-105 transition">
                        @elseif($produit->image)
                            <img src="{{ asset('storage/produits/' . $produit->image) }}"
                                 alt="{{ $produit->nom }}"
                                 class="w-full h-full object-cover hover:scale-105 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300">
                                <x-heroicon-o-cube class="w-16 h-16 text-gray-300" />
                            </div>
                        @endif

                        <!-- Badge statut -->
                        <div class="absolute top-3 right-3">
                            @if($produit->est_actif)
                                <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    ✓ Actif
                                </span>
                            @else
                                <span class="inline-block bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    ✗ Inactif
                                </span>
                            @endif
                        </div>

                        <!-- Badge stock -->
                        <div class="absolute top-3 left-3">
                            @if($produit->stock == 0)
                                <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    Rupture
                                </span>
                            @elseif($produit->stock <= $produit->stock_minimum)
                                <span class="inline-block bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                    <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                                    <span>Stock Faible</span>
                                </span>
                            @else
                                <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    ✓ En stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-4">
                        <!-- Catégorie -->
                        <p class="text-xs text-primary-600 font-semibold mb-1 flex items-center gap-1">
                            <x-heroicon-o-tag class="w-4 h-4" />
                            <span>{{ $produit->categorie->nom ?? 'N/A' }}</span>
                        </p>

                        <!-- Nom & Description -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $produit->nom }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit($produit->description, 50) }}
                        </p>

                        <!-- Prix & Stock -->
                        <div class="grid grid-cols-2 gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                    <x-heroicon-o-banknotes class="w-4 h-4" />
                                    <span>Prix</span>
                                </p>
                                <p class="text-lg font-bold text-green-600">
                                    {{ number_format($produit->prix, 0, ',', ' ') }} CFA
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                    <x-heroicon-o-chart-bar class="w-4 h-4" />
                                    <span>Stock</span>
                                </p>
                                <p class="text-lg font-bold text-blue-600">
                                    {{ $produit->stock }}
                                </p>
                                <p class="text-xs text-gray-500">Min: {{ $produit->stock_minimum }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('vendeur.produits.edit', $produit->id) }}"
                               class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold text-center flex items-center justify-center gap-2">
                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                <span>Éditer</span>
                            </a>
                            <form method="POST" action="{{ route('vendeur.produits.destroy', $produit->id) }}" class="flex flex-1"
                                  data-confirm="Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est définitive."
                                  data-confirm-title="Supprimer le produit"
                                  data-confirm-type="danger"
                                  data-confirm-button="Supprimer">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold flex items-center justify-center gap-2">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                    <span>Supprimer</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($produits->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $produits->links() }}
            </div>
        @endif
    @else
        <!-- Pas de produits -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <p class="text-6xl mb-4">📭</p>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun produit trouvé</h3>
            <p class="text-gray-600 mb-6">Commencez par créer votre premier produit</p>
            <a href="{{ route('vendeur.produits.create') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition font-semibold">
                ➕ Créer un produit
            </a>
        </div>
    @endif
</div>
@endsection
