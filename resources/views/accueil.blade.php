@extends('layouts.app')

@section('content')
@auth
    {{-- ========== VENDOR DASHBOARD ========== --}}
    @if(auth()->user()->role === 'vendor')
    <div class="bg-off-white min-h-screen">
        {{-- Header --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 sm:py-12 border-b border-gray-200">
            <p class="text-gray-600 text-sm mb-2">Bienvenue,</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-display font-bold text-black mb-1">
                {{ auth()->user()->shop_name ?? auth()->user()->name }}
            </h1>
            <p class="text-gray-600">Gérez votre boutique informatique</p>
        </div>

        {{-- Stats Grid --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                {{-- Stat Card --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-8">
                    <p class="text-gray-600 text-xs sm:text-sm mb-3">Mes Produits</p>
                    <p class="text-2xl sm:text-3xl font-mono font-bold text-black">{{ $produits_vendeur ?? 0 }}</p>
                </div>

                {{-- Stat Card --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-8">
                    <p class="text-gray-600 text-xs sm:text-sm mb-3">Stock Total</p>
                    <p class="text-2xl sm:text-3xl font-mono font-bold text-black">{{ $stock_total ?? 0 }}</p>
                </div>

                {{-- Stat Card --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-8">
                    <p class="text-gray-600 text-xs sm:text-sm mb-3">Commandes</p>
                    <p class="text-2xl sm:text-3xl font-mono font-bold text-black">{{ $commandes_total ?? 0 }}</p>
                </div>

                {{-- Stat Card --}}
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-8">
                    <p class="text-gray-600 text-xs sm:text-sm mb-3">Revenu</p>
                    <p class="text-2xl sm:text-3xl font-mono font-bold text-black">0 €</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <a href="{{ route('vendeur.produits.index') }}" class="bg-black text-white p-4 sm:p-8 rounded-lg hover:opacity-85 transition-opacity duration-150 border border-black">
                    <x-heroicon-o-shopping-bag class="w-5 h-5 sm:w-6 sm:h-6 mb-3 sm:mb-4" />
                    <h3 class="font-display text-base sm:text-lg font-bold mb-2">Gérer les Produits</h3>
                    <p class="text-gray-200 text-xs sm:text-sm">Ajouter, modifier, supprimer vos produits</p>
                </a>

                <a href="{{ route('vendeur.commandes') }}" class="bg-black text-white p-4 sm:p-8 rounded-lg hover:opacity-85 transition-opacity duration-150 border border-black">
                    <x-heroicon-o-document-text class="w-5 h-5 sm:w-6 sm:h-6 mb-3 sm:mb-4" />
                    <h3 class="font-display text-base sm:text-lg font-bold mb-2">Commandes</h3>
                    <p class="text-gray-200 text-xs sm:text-sm">Voir et gérer vos commandes en attente</p>
                </a>

                <a href="{{ route('vendeur.stock') }}" class="bg-black text-white p-4 sm:p-8 rounded-lg hover:opacity-85 transition-opacity duration-150 border border-black">
                    <x-heroicon-o-cube class="w-5 h-5 sm:w-6 sm:h-6 mb-3 sm:mb-4" />
                    <h3 class="font-display text-base sm:text-lg font-bold mb-2">Gestion Stock</h3>
                    <p class="text-gray-200 text-xs sm:text-sm">Surveiller et gérer votre inventaire</p>
                </a>
            </div>
        </div>

        {{-- Latest Products --}}
        <div class="max-w-7xl mx-auto px-8 py-12">
            <h2 class="text-2xl font-display font-bold text-black mb-8">Vos Derniers Produits</h2>
            @if(isset($mes_produits) && $mes_produits->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($mes_produits as $produit)
                        @include('components.carte-produit', ['produit' => $produit])
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
                    <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore de produits</p>
                    <a href="{{ route('vendeur.produits.create') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:opacity-85 transition-opacity duration-150 inline-block">
                        Ajouter votre premier produit
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ========== CLIENT / NOT CONNECTED ========== --}}
    @else
    <div class="bg-off-white min-h-screen">
        {{-- Hero Section --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12 sm:py-24">
            <div class="max-w-2xl">
                <p class="text-gray-600 text-xs sm:text-sm mb-4 uppercase">MARKETPLACE B2B</p>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-bold text-black mb-4 sm:mb-6">Le matériel tech, <em class="italic font-normal text-gray-600">sans compromis.</em></h1>
                <p class="text-gray-600 text-sm sm:text-base mb-6 sm:mb-8 max-w-lg">Des milliers de produits informatiques sourcés directement auprès de vendeurs vérifiés. Livraison rapide, prix transparents.</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('produits.catalogue') }}" class="bg-black text-white px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg hover:opacity-85 transition-opacity duration-150 text-center">
                        Explorer le catalogue
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base border border-black text-black rounded-lg hover:bg-gray-50 transition-colors duration-150 text-center">
                        Devenir vendeur
                    </a>
                    @endguest
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-12 mt-16 border-t border-gray-200 pt-16">
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">{{ $total_produits ?? '2 400+' }}</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Produits listés</p>
                </div>
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">{{ $total_vendeurs ?? '186' }}</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Vendeurs actifs</p>
                </div>
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">99%</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Vendeurs vérifiés</p>
                </div>
            </div>
        </div>

        {{-- Catégories --}}
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <h2 class="text-2xl font-display font-bold text-black mb-8">Catégories</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @forelse($categories ?? [] as $categorie)
                <a href="{{ route('produits.catalogue', ['categorie' => $categorie->id]) }}" class="group">
                    <div class="bg-white border border-gray-200 rounded-lg h-32 flex items-center justify-center mb-3 group-hover:border-black transition-colors duration-150">
                        @if($categorie->image)
                            <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-black text-center group-hover:text-gray-600 transition-colors duration-150">{{ $categorie->nom }}</p>
                </a>
                @empty
                @endforelse
            </div>
        </div>

        {{-- Produits en Vedette --}}
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <h2 class="text-2xl font-display font-bold text-black mb-8">Produits <em class="italic font-normal text-gray-600">en vedette</em></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($produits->take(8) as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @empty
                    <p class="text-gray-600">Aucun produit disponible</p>
                @endforelse
            </div>
        </div>

        {{-- CTA --}}
        @guest
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <div class="bg-black text-white rounded-lg p-12 text-center">
                <h3 class="text-lg font-display font-bold mb-2">Rejoignez Supply</h3>
                <p class="text-gray-300 text-sm mb-6">Accédez à la meilleure sélection de matériel informatique</p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-white text-black rounded-lg hover:opacity-85 transition-opacity duration-150 font-medium">
                    Créer un compte
                </a>
            </div>
        </div>
        @endguest
    </div>
    @endif
@else
    {{-- ========== NOT AUTHENTICATED ========== --}}
    <div class="bg-off-white min-h-screen">
        {{-- Hero Section --}}
        <div class="max-w-7xl mx-auto px-8 py-24">
            <div class="max-w-2xl">
                <p class="text-gray-600 text-sm mb-4">MARKETPLACE B2B</p>
                <h1 class="text-5xl font-display font-bold text-black mb-6">Le matériel tech, <em class="italic font-normal text-gray-600">sans compromis.</em></h1>
                <p class="text-gray-600 mb-8 max-w-lg">Des milliers de produits informatiques sourcés directement auprès de vendeurs vérifiés. Livraison rapide, prix transparents.</p>
                <div class="flex gap-3">
                    <a href="{{ route('produits.catalogue') }}" class="bg-black text-white px-6 py-3 rounded-lg hover:opacity-85 transition-opacity duration-150">
                        Explorer le catalogue
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 border border-black text-black rounded-lg hover:bg-gray-50 transition-colors duration-150">
                        Devenir vendeur
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-12 mt-16 border-t border-gray-200 pt-16">
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">{{ $total_produits ?? '2 400+' }}</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Produits listés</p>
                </div>
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">{{ $total_vendeurs ?? '186' }}</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Vendeurs actifs</p>
                </div>
                <div>
                    <p class="text-3xl font-mono font-bold text-black mb-2">99%</p>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Vendeurs vérifiés</p>
                </div>
            </div>
        </div>

        {{-- Catégories --}}
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <h2 class="text-2xl font-display font-bold text-black mb-8">Catégories</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @forelse($categories ?? [] as $categorie)
                <a href="{{ route('produits.catalogue', ['categorie' => $categorie->id]) }}" class="group">
                    <div class="bg-white border border-gray-200 rounded-lg h-32 flex items-center justify-center mb-3 group-hover:border-black transition-colors duration-150">
                        @if($categorie->image)
                            <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-black text-center group-hover:text-gray-600 transition-colors duration-150">{{ $categorie->nom }}</p>
                </a>
                @empty
                @endforelse
            </div>
        </div>

        {{-- Produits en Vedette --}}
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <h2 class="text-2xl font-display font-bold text-black mb-8">Produits <em class="italic font-normal text-gray-600">en vedette</em></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($produits->take(8) as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @empty
                    <p class="text-gray-600">Aucun produit disponible</p>
                @endforelse
            </div>
        </div>

        {{-- CTA --}}
        <div class="max-w-7xl mx-auto px-8 py-16 border-t border-gray-200">
            <div class="bg-black text-white rounded-lg p-12 text-center">
                <h3 class="text-lg font-display font-bold mb-2">Rejoignez Supply</h3>
                <p class="text-gray-300 text-sm mb-6">Accédez à la meilleure sélection de matériel informatique</p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-white text-black rounded-lg hover:opacity-85 transition-opacity duration-150 font-medium">
                    Créer un compte
                </a>
            </div>
        </div>
    </div>
@endauth
@endsection



