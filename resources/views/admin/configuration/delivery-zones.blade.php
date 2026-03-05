@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🗺️ Zones de Livraison</h1>
            <p class="text-gray-600 mt-2">Gérez les zones de livraison disponibles</p>
        </div>
        <button onclick="openModal('addZoneModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
            ➕ Nouvelle Zone
        </button>
    </div>

    <!-- Zones List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($deliveryZones as $zone)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $zone->nom }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $zone->description }}</p>
                    </div>
                    <button onclick="openModal('editModal{{ $zone->id }}')" class="text-blue-600 hover:text-blue-800">
                        ✏️
                    </button>
                </div>

                <div class="space-y-2 mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Frais de Livraison:</span>
                        <span class="font-bold text-gray-900">{{ number_format($zone->frais, 0, ',', ' ') }} XOF</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Délai Estimé:</span>
                        <span class="font-bold text-gray-900">{{ $zone->delai_jours }} jour(s)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Statut:</span>
                        <form method="POST" action="{{ route('admin.configuration.toggle-delivery-zone', $zone->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 {{ $zone->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full text-xs font-semibold hover:opacity-80 transition">
                                {{ $zone->active ? 'Actif' : 'Inactif' }}
                            </button>
                        </form>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.configuration.delete-delivery-zone', $zone->id) }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        🗑️ Supprimer
                    </button>
                </form>

                <!-- Edit Modal -->
                <div id="editModal{{ $zone->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-lg p-6 w-96">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Modifier Zone</h2>
                        
                        <form method="POST" action="{{ route('admin.configuration.update-delivery-zone', $zone->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" name="nom" value="{{ $zone->nom }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ $zone->description }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Frais de Livraison (XOF)</label>
                                <input type="number" name="frais" value="{{ $zone->frais }}" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Délai (Jours)</label>
                                <input type="number" name="delai_jours" value="{{ $zone->delai_jours }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                                    Mettre à jour
                                </button>
                                <button type="button" onclick="closeModal('editModal{{ $zone->id }}')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 text-center py-12 bg-white rounded-xl shadow-lg">
                <p class="text-gray-500 text-lg">Aucune zone de livraison définie</p>
            </div>
        @endforelse
    </div>

    <!-- Add Zone Modal -->
    <div id="addZoneModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Nouvelle Zone de Livraison</h2>
            
            <form method="POST" action="{{ route('admin.configuration.create-delivery-zone') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input type="text" name="nom" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Frais de Livraison (XOF) *</label>
                    <input type="number" name="frais" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Délai de Livraison (Jours) *</label>
                    <input type="number" name="delai_jours" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                        ✅ Créer
                    </button>
                    <button type="button" onclick="closeModal('addZoneModal')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg">
                        ❌ Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
@endsection
