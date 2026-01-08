@extends('layouts.app')

@section('content')
<div class="bg-slate-900">
    <!-- Hero Section -->
    <div class="relative overflow-hidden py-32">
        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 via-violet-600/20 to-pink-600/20"></div>
        
        <!-- Animated blobs -->
        <div class="absolute top-20 right-10 w-72 h-72 bg-indigo-500 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 left-10 w-72 h-72 bg-violet-500 rounded-full opacity-20 blur-3xl animate-pulse"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-8">
                <div>
                    <h1 class="text-6xl md:text-7xl font-black mb-4 leading-tight">
                        <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-pink-400 bg-clip-text text-transparent">
                            Bienvenue à Supply
                        </span>
                    </h1>
                    <p class="text-2xl md:text-3xl text-slate-300 mb-2">Votre boutique informatique premium</p>
                    <p class="text-slate-400">Technologie de pointe, qualité supérieure, service d'exception</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                    <a href="{{ route('produits.catalogue') }}" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-lg overflow-hidden rounded-xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 transition-all group-hover:scale-110 duration-300"></div>
                        <span class="relative text-white flex items-center gap-2">
                            🚀 Commencer à explorer
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Catégories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-5xl font-bold bg-gradient-to-r from-slate-100 to-slate-300 bg-clip-text text-transparent">Explorez nos Catégories</h2>
            <p class="text-slate-400 text-lg">Trouvez exactement ce qu'il vous faut</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $categorie)
            <a href="{{ route('produits.catalogue', ['categorie' => $categorie->id]) }}" class="group relative block bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:scale-105">
                <div class="h-40 flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-800 overflow-hidden relative">
                    @if($categorie->image)
                        <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover group-hover:scale-125 transition duration-500">
                    @else
                        <div class="flex flex-col items-center justify-center w-full h-full bg-gradient-to-br from-indigo-500/20 to-violet-500/20">
                            <svg class="w-12 h-12 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <p class="text-indigo-300 font-semibold text-center text-xs px-2">{{ $categorie->nom }}</p>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent opacity-0 group-hover:opacity-60 transition duration-300"></div>
                </div>
                <p class="font-semibold text-slate-200 p-4 text-center group-hover:text-indigo-300 transition duration-300">{{ $categorie->nom }}</p>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Produits en vedette -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-5xl font-bold bg-gradient-to-r from-slate-100 to-slate-300 bg-clip-text text-transparent">Produits en Vedette</h2>
            <p class="text-slate-400 text-lg">Nos meilleures ventes de la semaine</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($produits as $produit)
                @include('components.carte-produit', ['produit' => $produit])
            @endforeach
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="relative bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-12 md:p-20 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="relative z-10 text-center space-y-8">
                <h3 class="text-4xl md:text-5xl font-bold text-white">Rejoignez la communauté Supply</h3>
                <p class="text-lg text-indigo-100 max-w-2xl mx-auto">Accès exclusif à nos meilleures offres, livraison rapide et support client 24/7</p>
                <button class="inline-flex items-center gap-2 px-8 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition transform hover:scale-105 duration-300">
                    ✨ Devenir Membre
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
