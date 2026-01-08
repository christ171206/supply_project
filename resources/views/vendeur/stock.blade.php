@extends('vendeur.layout-dashboard')

@section('vendeur-content')
<div>
    <!-- Header -->
    <div class="mb-12 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">📦 Gestion du Stock</h1>
            <p class="text-gray-600 mt-2">Gérez vos inventaires et seuils</p>
        </div>
        <a href="{{ route('vendeur.produits.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
            <x-icon name="plus-circle" class="w-5 h-5 mr-2 inline" /> Ajouter Produit
        </a>
    </div>

    <!-- Tableau Stock -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Produit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Stock Actuel</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Seuil Min.</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">État</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <!-- Produit 1 - OK -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">Clavier Mécanique RGB</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="font-bold text-lg">15</span> unités
                        </td>
                        <td class="px-6 py-4 text-gray-700">5 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                ✅ OK
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 2 - Faible -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">Souris Sans Fil</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="font-bold text-lg">3</span> unités
                        </td>
                        <td class="px-6 py-4 text-gray-700">5 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                ⚠️ Faible
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 3 - Rupture -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">Câble HDMI 2.1</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="font-bold text-lg">0</span> unités
                        </td>
                        <td class="px-6 py-4 text-gray-700">10 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                ❌ Rupture
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 4 - OK -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">Monitor 4K 27"</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="font-bold text-lg">8</span> unités
                        </td>
                        <td class="px-6 py-4 text-gray-700">3 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                ✅ OK
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">✏️ Modifier</button>
                        </td>
                    </tr>

                    <!-- Produit 5 - Faible -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">Casque Bluetooth</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="font-bold text-lg">2</span> unités
                        </td>
                        <td class="px-6 py-4 text-gray-700">4 unités</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                ⚠️ Faible
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-bold text-sm">✏️ Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Légende -->
    <div class="mt-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
        <p class="text-sm text-blue-900">
            💡 <span class="font-semibold">Conseil :</span> Un stock "Faible" signifie qu'il est proche du seuil minimum.
            Un stock en "Rupture" signifie qu'il est égal à 0. Cliquez sur "Modifier" pour ajuster les quantités.
        </p>
    </div>
</div>
@endsection
