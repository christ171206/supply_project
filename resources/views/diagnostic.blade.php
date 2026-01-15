@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-20">
    <h1 class="text-4xl font-bold mb-8">🔍 Diagnostic Supply</h1>

    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-primary-50 p-6 rounded-xl">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Produits</h3>
                <p class="text-4xl font-bold text-primary-600">{{ $produits_count }}</p>
            </div>
            <div class="bg-accent-50 p-6 rounded-xl">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Catégories</h3>
                <p class="text-4xl font-bold text-accent-600">{{ $categories_count }}</p>
            </div>
            <div class="bg-secondary-50 p-6 rounded-xl">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Utilisateurs</h3>
                <p class="text-4xl font-bold text-secondary-600">{{ $users_count }}</p>
            </div>
        </div>

        <!-- Utilisateur actuel -->
        <div class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-2xl font-bold mb-4">👤 Utilisateur Actuel</h2>
            @auth
                <div class="space-y-2">
                    <p><strong>Nom:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Rôle:</strong> <span class="px-3 py-1 rounded-full bg-primary-100 text-primary-700 font-semibold">{{ auth()->user()->role }}</span></p>
                </div>
            @else
                <p class="text-gray-600">Vous n'êtes pas connecté.</p>
            @endauth
        </div>

        <!-- Produits -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">📦 Produits en Vedette (Accueil)</h2>
            @if($produits->count() > 0)
                <div class="space-y-4">
                    @foreach($produits as $produit)
                        <div class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="font-bold">{{ $produit->nom }}</p>
                                <p class="text-sm text-gray-600">{{ $produit->prix }}€ | Stock: {{ $produit->stock }}</p>
                            </div>
                            <span class="text-sm bg-gray-100 px-3 py-1 rounded">ID: {{ $produit->id }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-amber-600 font-semibold">⚠️ AUCUN PRODUIT TROUVÉ!</p>
                <p class="text-gray-600 mt-2">Vous devez exécuter: <code>php artisan db:seed</code></p>
            @endif
        </div>

        <!-- Catégories -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">🗂️ Catégories</h2>
            @if($categories->count() > 0)
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                        <li class="border-l-4 border-primary-500 pl-4 py-2">
                            <p class="font-bold">{{ $cat->nom }}</p>
                            <p class="text-sm text-gray-600">{{ $cat->produits()->count() }} produits</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-amber-600 font-semibold">⚠️ AUCUNE CATÉGORIE!</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-blue-50 border border-blue-200 p-6 rounded-xl">
            <h2 class="text-2xl font-bold mb-4">🔧 Actions</h2>
            <div class="space-y-3">
                @if($produits_count == 0 || $categories_count == 0)
                    <p class="text-blue-700 mb-4">La base de données semble vide. Exécutez le seeder:</p>
                    <code class="block bg-blue-900 text-blue-50 p-4 rounded font-mono text-sm">php artisan db:seed</code>
                    <p class="text-gray-700 mt-4">Ou pour réinitialiser complètement:</p>
                    <code class="block bg-blue-900 text-blue-50 p-4 rounded font-mono text-sm">php artisan migrate:fresh --seed</code>
                @else
                    <p class="text-green-700 font-semibold">✅ Les données sont présentes en base de données!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
