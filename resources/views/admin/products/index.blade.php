@extends('layouts.admin-layout')

@section('title', 'Gestion des Produits')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Gestion des Produits</h1>
            <p class="text-gray-500 mt-2">Supervision et gestion du stock • Total: <strong>{{ $produits->total() }} produits</strong></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.critical-stock') }}" class="px-6 py-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 font-semibold transition border border-yellow-200">
                ⚠️ Stock Critique
            </a>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
                ← Retour
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <form method="GET" class="flex gap-4 items-end">
            <!-- Recherche -->
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rechercher un produit</label>
                <input type="text" name="search" placeholder="Nom du produit..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Statut -->
            <div class="w-48">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✓ Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>✗ Inactif</option>
                </select>
            </div>

            <!-- Bouton Filtrer -->
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Tableau Responsif -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Vendeur</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-900 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-900 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-900 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produits as $produit)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $produit->nom }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $produit->id }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $produit->vendeur?->shop_name ?? $produit->vendeur?->name ?? 'Supprimé' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $produit->categorie?->nom ?? 'Supprimée' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                {{ number_format($produit->prix, 0, ',', ' ') }} XOF
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold {{ $produit->stock <= 5 ? 'bg-red-100 text-red-800' : ($produit->stock <= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $produit->stock }} unités
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($produit->est_actif)
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">✓ Actif</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800">✗ Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('admin.products.show', $produit) }}" 
                                   class="inline-block px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition">
                                    <x-heroicon-o-eye class="w-5 h-5" /> Voir
                                </a>
                                @if($produit->est_actif)
                                    <form method="POST" action="{{ route('admin.products.disable', $produit) }}" 
                                          style="display: inline;"
                                          data-confirm="Désactiver ce produit ?"
                                          data-confirm-title="Désactiver le produit"
                                          data-confirm-type="warning"
                                          data-confirm-button="Désactiver">
                                        @csrf
                                        <button type="submit" class="inline-block px-3 py-1.5 text-xs font-semibold text-yellow-600 bg-yellow-50 rounded hover:bg-yellow-100 transition">
                                            ⚡ Désactiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.products.enable', $produit) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="inline-block px-3 py-1.5 text-xs font-semibold text-green-600 bg-green-50 rounded hover:bg-green-100 transition">
                                            ✓ Activer
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.products.destroy', $produit) }}" 
                                      style="display: inline;"
                                      data-confirm="Supprimer ce produit ? Cette action est irréversible."
                                      data-confirm-title="Supprimer le produit"
                                      data-confirm-type="danger"
                                      data-confirm-button="Supprimer">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded hover:bg-red-100 transition">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <p class="text-lg font-semibold">Aucun produit trouvé</p>
                                    <p class="text-sm mt-1">Ajustez vos filtres pour voir les produits</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($produits->hasPages())
        <div class="flex justify-center">
            {{ $produits->links('pagination::tailwind') }}
        </div>
    @endif
</div>


@endsection
