@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">⭐ Avis Clients</h1>
        <p class="text-gray-600 mt-2">Consultez et gérez les avis sur vos produits</p>
    </div>

    <!-- Stats Avis -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Note Moyenne -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Note Moyenne</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($noteMoyenne, 1) }}/5</p>
                </div>
                <div class="text-5xl">⭐</div>
            </div>
        </div>

        <!-- Total Avis -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total d'Avis</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $nombreAvis }}</p>
                </div>
                <div class="text-5xl">💬</div>
            </div>
        </div>

        <!-- 5 Étoiles -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">⭐⭐⭐⭐⭐</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $avisParNote[5] ?? 0 }}</p>
                </div>
                <div class="text-5xl">😍</div>
            </div>
        </div>

        <!-- Avis Critiques (1-2 étoiles) -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">⭐ ou ⭐⭐</p>
                    <p class="text-4xl font-bold text-red-600 mt-2">{{ ($avisParNote[1] ?? 0) + ($avisParNote[2] ?? 0) }}</p>
                </div>
                <div class="text-5xl">⚠️</div>
            </div>
        </div>
    </div>

    <!-- Graphique Répartition -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Répartition des Notes</h2>
        <div class="space-y-5">
            @for($i = 5; $i >= 1; $i--)
                <div class="flex items-center gap-6">
                    <div class="w-32 flex-shrink-0">
                        <div class="flex gap-1">
                            @for($j = 1; $j <= 5; $j++)
                                <span class="text-xl">{{ $j <= $i ? '⭐' : '☆' }}</span>
                            @endfor
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                style="width: {{ $nombreAvis > 0 ? (($avisParNote[$i] ?? 0) / $nombreAvis * 100) : 0 }}%; background: linear-gradient(90deg, rgb(251, 191, 36), rgb(34, 197, 94));"
                            ></div>
                        </div>
                    </div>
                    <span class="w-12 text-right font-bold text-gray-900 flex-shrink-0">{{ $avisParNote[$i] ?? 0 }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Filtre et Tri -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    id="search-avis"
                    placeholder="🔍 Rechercher dans les avis..."
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                    onkeyup="filterAvis()"
                >
            </div>
            <select id="filter-note" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary-500" onchange="filterAvis()">
                <option value="">Toutes les notes</option>
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
        </div>
    </div>

    <!-- Liste des Avis -->
    @if($avisComplets->count() > 0)
        <div class="space-y-6">
            @foreach($avisComplets as $avis)
                <div class="avis-item bg-white rounded-xl shadow-md border border-gray-100 p-8 hover:shadow-lg transition" data-note="{{ $avis->note }}" data-text="{{ strtolower($avis->commentaire . ' ' . $avis->user->name . ' ' . $avis->produit->nom) }}">
                    <!-- Header Avis -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($avis->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $avis->user->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $avis->created_at->locale('fr')->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex gap-1 justify-end mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-2xl">{{ $i <= $avis->note ? '⭐' : '☆' }}</span>
                                @endfor
                            </div>
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                                {{ $avis->note }}/5
                            </span>
                        </div>
                    </div>

                    <!-- Produit Concerné -->
                    <div class="mb-4 flex items-center gap-2 text-gray-600">
                        <span>📦</span>
                        <span class="font-semibold">{{ $avis->produit->nom }}</span>
                    </div>

                    <!-- Contenu Avis -->
                    <p class="text-gray-700 leading-relaxed mb-4 text-lg">{{ $avis->commentaire }}</p>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a
                            href="{{ route('produits.show', $avis->produit->id) }}"
                            class="px-4 py-2 bg-primary-50 text-primary-600 font-bold rounded-lg hover:bg-primary-100 transition text-sm"
                        >
                            👁️ Voir Produit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($avisComplets->hasPages())
            <div class="mt-8">
                {{ $avisComplets->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-md border border-gray-100">
            <p class="text-6xl mb-4">😊</p>
            <p class="text-2xl font-bold text-gray-900">Pas encore d'avis</p>
            <p class="text-gray-600 mt-2">Vos clients n'ont pas encore laissé d'avis sur vos produits</p>
        </div>
    @endif
</div>

<script>
    function filterAvis() {
        const search = document.getElementById('search-avis').value.toLowerCase();
        const noteFilter = document.getElementById('filter-note').value;

        document.querySelectorAll('.avis-item').forEach(item => {
            const text = item.dataset.text;
            const note = item.dataset.note;

            const matchText = text.includes(search);
            const matchNote = noteFilter === '' || note === noteFilter;

            item.style.display = (matchText && matchNote) ? 'block' : 'none';
        });
    }
</script>
@endsection
