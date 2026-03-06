@extends('layouts.app')

@section('content')
@auth
    {{-- ========== VENDOR DASHBOARD ========== --}}
    @if(auth()->user()->role === 'vendor')
    <div class="bg-gradient-to-b from-primary-50 to-slate-50 min-h-screen">
        <!-- Header Moderne -->
        <div class="relative overflow-hidden pt-20 pb-12">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-100/50 via-transparent to-secondary-100/30"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="space-y-6">
                    <div>
                        <p class="text-primary-600 font-semibold">Bienvenue,</p>
                        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mt-2">
                            {{ auth()->user()->shop_name ?? auth()->user()->name }}
                        </h1>
                        <p class="text-xl text-gray-600 mt-3">Gérez votre boutique informatique</p>
                    </div>

                    <!-- Stats Rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-8">
                        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-primary-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Mes Produits</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $produits_vendeur ?? 0 }}</p>
                                </div>
                                <x-heroicon-o-shopping-bag class="w-12 h-12 text-primary-500 opacity-50" />
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-accent-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Stock Total</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stock_total ?? 0 }}</p>
                                </div>
                                <x-heroicon-o-cube class="w-12 h-12 text-accent-500 opacity-50" />
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-secondary-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Commandes</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $commandes_total ?? 0 }}</p>
                                </div>
                                <x-heroicon-o-document-text class="w-12 h-12 text-secondary-500 opacity-50" />
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Revenu</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">0 €</p>
                                </div>
                                <x-heroicon-o-banknotes class="w-12 h-12 text-green-500 opacity-50" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('vendeur.produits.index') }}" class="group relative overflow-hidden rounded-2xl p-8 bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <x-heroicon-o-shopping-bag class="w-8 h-8 mb-4" />
                        <h3 class="text-2xl font-bold mb-2">Gérer les Produits</h3>
                        <p class="text-primary-100">Ajouter, modifier, supprimer vos produits</p>
                    </div>
                </a>

                <a href="{{ route('vendeur.commandes') }}" class="group relative overflow-hidden rounded-2xl p-8 bg-gradient-to-br from-accent-500 to-accent-600 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <x-heroicon-o-document-text class="w-8 h-8 mb-4" />
                        <h3 class="text-2xl font-bold mb-2">Commandes</h3>
                        <p class="text-accent-100">Voir et gérer vos commandes en attente</p>
                    </div>
                </a>

                <a href="{{ route('vendeur.stock') }}" class="group relative overflow-hidden rounded-2xl p-8 bg-gradient-to-br from-secondary-500 to-secondary-600 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <x-heroicon-o-cube class="w-8 h-8 mb-4" />
                        <h3 class="text-2xl font-bold mb-2">Gestion Stock</h3>
                        <p class="text-secondary-100">Surveiller et gérer votre inventaire</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Derniers Produits du Vendeur -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-2xl shadow-md p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Vos Derniers Produits</h2>
                @if(isset($mes_produits) && $mes_produits->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($mes_produits as $produit)
                            @include('components.carte-produit', ['produit' => $produit])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-heroicon-o-shopping-bag class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <p class="text-gray-600 text-lg">Vous n'avez pas encore de produits</p>
                        <a href="{{ route('vendeur.produits.create') }}" class="btn-primary inline-block mt-4">
                            Ajouter votre premier produit
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========== CLIENT / NOT CONNECTED ========== --}}
    @else
        @include('partials.hero-section')
        @include('partials.categories-section')
        @include('partials.produits-vedettes')
        @include('partials.favoris-section')
        @include('partials.cta-section')
    @endif
@else
    {{-- ========== NOT AUTHENTICATED ========== --}}
    @include('partials.hero-section')
    @include('partials.categories-section')
    @include('partials.produits-vedettes')
    @include('partials.favoris-section')
    @include('partials.cta-section')
@endauth
@endsection



