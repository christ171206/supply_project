@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-20">
    <h1 class="text-4xl font-bold mb-8">🖼️ Test Images Produits</h1>

    <div class="space-y-8">
        @foreach($produits as $produit)
        <div class="border rounded-lg p-6 bg-white shadow">
            <div class="grid grid-cols-3 gap-6">
                <!-- Affichage Actual -->
                <div class="col-span-1">
                    <h3 class="font-bold mb-4">{{ $produit->nom }}</h3>
                    <img src="{{ asset('storage/produits/' . $produit->image) }}"
                         alt="{{ $produit->nom }}"
                         class="w-full h-auto rounded-lg"
                         onerror="this.classList.add('border-4', 'border-red-500')">
                    <p class="text-xs text-gray-600 mt-2">Chemin: {{ $produit->image }}</p>
                </div>

                <!-- Debug Info -->
                <div class="col-span-2 bg-gray-100 p-4 rounded">
                    <div class="space-y-2 font-mono text-sm">
                        <p><strong>ID:</strong> {{ $produit->id }}</p>
                        <p><strong>Nom:</strong> {{ $produit->nom }}</p>
                        <p><strong>Image (DB):</strong> <span class="text-blue-600">{{ $produit->image }}</span></p>
                        <p><strong>URL Générée:</strong> <span class="text-green-600">{{ asset('storage/produits/' . $produit->image) }}</span></p>
                        <p><strong>Fichier Existe:</strong>
                            @if(file_exists(public_path('storage/produits/' . $produit->image)))
                                <span class="text-green-600">✅ OUI</span>
                            @else
                                <span class="text-red-600">❌ NON</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
