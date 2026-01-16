@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-20">
    <h1 class="text-4xl font-bold mb-8">🖼️ Diagnostic Images</h1>

    <div class="grid grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">📊 Statistiques</h2>
            <div class="space-y-3">
                <p><strong>Total produits:</strong> {{ $total_produits }}</p>
                <p><strong>Produits avec image:</strong> {{ $avec_image }}</p>
                <p><strong>Produits sans image:</strong> {{ $sans_image }}</p>
                <p><strong>Pourcentage:</strong> {{ round(($avec_image / $total_produits * 100), 1) }}%</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">📁 Dossier Storage</h2>
            <div class="space-y-2 text-sm font-mono">
                <p><strong>Path:</strong></p>
                <code class="bg-gray-100 p-2 rounded block">{{ storage_path('app/public/produits') }}</code>
                <p class="mt-4"><strong>Symlink:</strong></p>
                <code class="bg-gray-100 p-2 rounded block">{{ public_path('storage/produits') }}</code>
                <p class="mt-4"><strong>URL:</strong></p>
                <code class="bg-gray-100 p-2 rounded block">{{ asset('storage/produits') }}</code>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow mt-8">
        <h2 class="text-2xl font-bold mb-6">🖼️ Aperçu des Produits</h2>

        @if($produits->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($produits->take(6) as $produit)
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold mb-2">{{ $produit->nom }}</h3>

                    <div class="mb-4">
                        @if($produit->image)
                            <div class="bg-gray-100 h-40 rounded overflow-hidden mb-2">
                                <img src="{{ asset('storage/produits/' . $produit->image) }}"
                                     alt="{{ $produit->nom }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.parentElement.innerHTML='<div class=\"flex items-center justify-center h-full text-gray-500\">❌ Image non trouvée</div>'">
                            </div>
                            <code class="text-xs bg-blue-50 p-2 rounded block">{{ $produit->image }}</code>
                        @else
                            <div class="bg-gray-100 h-40 rounded flex items-center justify-center text-gray-500">
                                ⚠️ Pas d'image
                            </div>
                        @endif
                    </div>

                    <div class="text-sm space-y-1">
                        <p><strong>Stock:</strong> {{ $produit->stock }}</p>
                        <p><strong>Prix:</strong> {{ $produit->prix }}€</p>
                        @if($produit->categorie)
                            <p><strong>Catégorie:</strong> {{ $produit->categorie->nom }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Aucun produit trouvé</p>
        @endif
    </div>
</div>
@endsection
