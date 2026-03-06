@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-12 flex justify-between items-center">
        <div>
            <h1 class="text-5xl font-bold text-slate-700 flex items-center gap-3">
                <x-heroicon-o-cube class="w-12 h-12" />
                <span>Gestion du Stock</span>
            </h1>
            <p class="text-slate-500 mt-2 text-lg">Gérez vos inventaires et seuils</p>
        </div>
        <a href="{{ route('vendeur.produits.create') }}" class="bg-sky-400 hover:bg-sky-500 text-white px-8 py-4 rounded-lg font-bold transition shadow-md transform hover:scale-105">
            <x-heroicon-o-light-bulb class="w-5 h-5 mr-2 inline" /> Ajouter Produit
        </a>
    </div>

    <!-- Tableau Stock -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 border-b border-slate-300">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Produit</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Stock Actuel</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Seuil Min.</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">État</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($produits as $produit)
                        @php
                            $etat = 'OK';
                            $statut_classe = 'emerald';
                            $icon = '✓';

                            if ($produit->stock == 0) {
                                $etat = 'Rupture';
                                $statut_classe = 'red';
                                $icon = '✗';
                            } elseif ($produit->stock <= $produit->stock_minimum) {
                                $etat = 'Faible';
                                $statut_classe = 'amber';
                                $icon = '⚠';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $produit->nom }}</td>
                            <td class="px-6 py-4 text-slate-700">
                                <span class="font-bold text-lg text-sky-500">{{ $produit->stock }}</span> unités
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $produit->stock_minimum }} unités</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-{{ $statut_classe }}-100 text-{{ $statut_classe }}-700 rounded-full text-xs font-bold border border-{{ $statut_classe }}-300">
                                    {{ $icon }} {{ $etat }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('vendeur.produits.edit', $produit->id) }}" class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline flex items-center justify-center gap-1"><x-heroicon-o-pencil-square class="w-4 h-4" /><span>Modifier</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-600">
                                <p class="text-lg mb-2 flex items-center justify-center gap-2"><x-heroicon-o-cube class="w-6 h-6" /><span>Aucun produit</span></p>
                                <p class="text-sm">Vous n'avez pas encore ajouté de produit</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Légende -->
    <div class="mt-8 p-6 bg-sky-50 rounded-xl border border-sky-200 shadow-sm">
        <p class="text-sm text-slate-700 font-semibold flex items-center gap-2">
            <x-heroicon-o-light-bulb class="w-5 h-5 text-amber-500" /><span class="font-bold">Conseil :</span> Un stock "Faible" signifie qu'il est proche du seuil minimum.
            Un stock en "Rupture" signifie qu'il est égal à 0. Cliquez sur "Modifier" pour ajuster les quantités.
        </p>
    </div>
</div>
@endsection
