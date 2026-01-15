@extends('vendeur.layout-dashboard')

@section('vendeur-content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-12 flex justify-between items-center">
        <div>
            <h1 class="text-5xl font-bold text-slate-700">📦 Gestion du Stock</h1>
            <p class="text-slate-500 mt-2 text-lg">Gérez vos inventaires et seuils</p>
        </div>
        <a href="{{ route('vendeur.produits.create') }}" class="bg-sky-400 hover:bg-sky-500 text-white px-8 py-4 rounded-lg font-bold transition shadow-md transform hover:scale-105">
            <x-icon name="plus-circle" class="w-5 h-5 mr-2 inline" /> Ajouter Produit
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
                    <!-- Produit 1 - OK -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">Clavier Mécanique RGB</td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-bold text-lg text-sky-500">15</span> unités
                        </td>
                        <td class="px-6 py-4 text-slate-700">5 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold border border-emerald-300">
                                ✅ OK
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 2 - Faible -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">Souris Sans Fil</td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-bold text-lg text-sky-500">3</span> unités
                        </td>
                        <td class="px-6 py-4 text-slate-700">5 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold border border-amber-300">
                                ⚠️ Faible
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 3 - Rupture -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">Câble HDMI 2.1</td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-bold text-lg text-sky-500">0</span> unités
                        </td>
                        <td class="px-6 py-4 text-slate-700">10 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-300">
                                ❌ Rupture
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 4 - OK -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">Monitor 4K 27"</td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-bold text-lg text-sky-500">8</span> unités
                        </td>
                        <td class="px-6 py-4 text-slate-700">3 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold border border-emerald-300">
                                ✅ OK
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 5 - Faible -->
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">Casque Bluetooth</td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-bold text-lg text-sky-500">2</span> unités
                        </td>
                        <td class="px-6 py-4 text-slate-700">4 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold border border-amber-300">
                                ⚠️ Faible
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-sky-500 hover:text-sky-700 font-bold text-sm hover:underline">✏️ Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Légende -->
    <div class="mt-8 p-6 bg-sky-50 rounded-xl border border-sky-200 shadow-sm">
        <p class="text-sm text-slate-700 font-semibold">
            💡 <span class="font-bold">Conseil :</span> Un stock "Faible" signifie qu'il est proche du seuil minimum.
            Un stock en "Rupture" signifie qu'il est égal à 0. Cliquez sur "Modifier" pour ajuster les quantités.
        </p>
    </div>
</div>
@endsection
