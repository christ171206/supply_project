@extends('layouts.app')

@section('content')
<div class="bg-red-500 text-white p-8 text-center">
    <h1>🧪 TEST PAGE</h1>
    <p>Si vous voyez ce texte en rouge, les pages s'affichent</p>
    <p>Role: {{ auth()->check() ? auth()->user()->role : 'Pas connecté' }}</p>
    <p>User: {{ auth()->check() ? auth()->user()->name : 'Pas connecté' }}</p>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-6">Produits Disponibles</h2>

    @if(isset($produits) && $produits->count() > 0)
        <p class="text-green-600 font-bold mb-4">✅ {{ $produits->count() }} produits trouvés</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($produits as $p)
                <div class="border border-gray-300 p-4 rounded">
                    <p class="font-bold">{{ $p->nom }}</p>
                    <p class="text-sm text-gray-600">{{ $p->image ?? 'Pas d\'image' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-red-600 font-bold">❌ Aucun produit trouvé</p>
    @endif
</div>

@endsection
