@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8 flex items-center gap-2"><x-heroicon-o-clipboard class="w-10 h-10" /><span>Mes Commandes</span></h1>

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

    @if($commandes && count($commandes) > 0)
        <div class="grid gap-6">
            @foreach($commandes as $commande)
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Commande #{{ $commande->id }}</h2>
                            <p class="text-gray-600 text-sm">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($commande->total, 0, ',', ' ') }} CFA</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold mt-2
                                @if($commande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($commande->statut === 'payée') bg-green-100 text-green-800
                                @elseif($commande->statut === 'annulée') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 py-4 border-y border-gray-200">
                        <div>
                            <p class="text-gray-600 text-sm">Méthode de paiement</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $commande->payment_method ?? 'N/A')) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Adresse de livraison</p>
                            <p class="font-semibold text-gray-900 text-sm">{{ Str::limit($commande->adresse_livraison, 40) }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-end">
                        <a href="{{ route('commandes.show', $commande->id) }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Voir les détails →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($commandes->hasPages())
            <div class="mt-8">
                {{ $commandes->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <div class="mb-6">
                <span class="text-6xl">📭</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucune commande pour le moment</h2>
            <p class="text-gray-600 mb-8">Commencez vos achats en explorant notre catalogue</p>
            <a href="{{ route('produits.catalogue') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Découvrir nos produits
            </a>
        </div>
    @endif
</div>
@endsection
