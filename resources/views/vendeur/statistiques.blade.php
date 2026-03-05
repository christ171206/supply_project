@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête avec sélecteur de période -->
    <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-8 h-8" /><span>Statistiques</span></h1>
            <p class="text-gray-600">Analyse détaillée de vos performances</p>
        </div>

        <!-- Sélecteur de période -->
        <form method="GET" class="flex gap-2">
            <select name="periode" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" onchange="this.form.submit()">
                <option value="7" {{ request('periode') == 7 ? 'selected' : '' }}>Derniers 7 jours</option>
                <option value="30" {{ request('periode') == 30 ? 'selected' : '' }}>Dernier mois</option>
                <option value="90" {{ request('periode') == 90 ? 'selected' : '' }}>Dernier trimestre</option>
                <option value="365" {{ request('periode') == 365 ? 'selected' : '' }}>Dernière année</option>
            </select>
        </form>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-banknotes class="w-4 h-4" /><span>Chiffre d'Affaires</span></p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($totalCA, 0, ',', ' ') }} CFA</p>
            <p class="text-xs text-gray-500 mt-2">Derniers {{ request('periode', 7) }} jours</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-shopping-cart class="w-4 h-4" /><span>Commandes</span></p>
            <p class="text-3xl font-bold text-blue-600">{{ $nombreCommandes }}</p>
            <p class="text-xs text-gray-500 mt-2">Panier moyen: {{ number_format($panierMoyen, 0, ',', ' ') }} CFA</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-star class="w-4 h-4 text-yellow-500" /><span>Notation</span></p>
            <p class="text-3xl font-bold text-yellow-600">{{ round($noteMoyenne, 1) }}/5</p>
            <p class="text-xs text-gray-500 mt-2">{{ $nombreAvis }} avis</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-purple-500 p-6">
            <p class="text-gray-600 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-chart-pie class="w-4 h-4" /><span>Taux de Complétion</span></p>
            <p class="text-3xl font-bold text-purple-600">
                @php
                    $tauxCompletion = $nombreCommandes > 0 ? round(($commandeslivrees / $nombreCommandes) * 100) : 0;
                @endphp
                {{ $tauxCompletion }}%
            </p>
            <p class="text-xs text-gray-500 mt-2">Commandes livrées</p>
        </div>
    </div>

    <!-- Top Produits & Statut Commandes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top 5 Produits -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class=\"text-lg font-bold text-gray-900 mb-4 flex items-center gap-2\"><x-heroicon-o-star class=\"w-5 h-5 text-yellow-500\" /><span>Top 5 Produits</span></h3>
            <div class="space-y-3">
                @forelse($topProduits as $idx => $produit)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $idx + 1 }}. {{ $produit->nom }}</p>
                            <p class="text-xs text-gray-600">{{ $produit->ventes_nombre }} ventes</p>
                        </div>
                        <p class="font-bold text-primary-600">{{ number_format($produit->ventes_total, 0, ',', ' ') }} CFA</p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">Aucun produit vendu</p>
                @endforelse
            </div>
        </div>

        <!-- Statut Commandes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-5 h-5" /><span>Statut Commandes</span></h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">En Attente</p>
                        <p class="text-xs text-gray-600">À traiter</p>
                    </div>
                    <p class="text-2xl font-bold text-red-600">{{ $commandesEnAttente }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Confirmées</p>
                        <p class="text-xs text-gray-600">Vérifiées</p>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $commandesConfirmees }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Expédiées</p>
                        <p class="text-xs text-gray-600">En transit</p>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ $commandesExpediees }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Livrées</p>
                        <p class="text-xs text-gray-600">Complétées</p>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ $commandeslivrees }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques Chart.js -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Évolution CA -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-line class="w-5 h-5" /><span>Évolution du CA</span></h3>
            <div class="h-64">
                <canvas id="chartCA"></canvas>
            </div>
        </div>

        <!-- Statut Commandes - Donut Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-rocket-launch class="w-5 h-5" /><span>Distribution des Commandes</span></h3>
            <div class="h-64 flex justify-center">
                <canvas id="chartStatutCommandes"></canvas>
            </div>
        </div>
    </div>

    <!-- Deuxième rangée de graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Top Produits - Bar Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🏆 Ventes par Produit</h3>
            <div class="h-64">
                <canvas id="chartTopProduits"></canvas>
            </div>
        </div>

        <!-- Répartition par Catégorie - Pie Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🎨 Répartition par Catégorie</h3>
            <div class="h-64 flex justify-center">
                <canvas id="chartCategories"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Palette de couleurs
    const colors = {
        primary: '#3B82F6',
        success: '#10B981',
        danger: '#EF4444',
        warning: '#F59E0B',
        info: '#06B6D4',
        purple: '#8B5CF6'
    };

    // 1. Graphique Évolution CA
    @if(count($chartDates) > 0)
    const ctxCA = document.getElementById('chartCA').getContext('2d');
    new Chart(ctxCA, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Chiffre d\'Affaires (CFA)',
                data: {!! json_encode($chartVentes) !!},
                borderColor: colors.primary,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('fr-FR') + ' CFA';
                        }
                    }
                }
            }
        }
    });
    @endif

    // 2. Graphique Statut Commandes
    const ctxStatut = document.getElementById('chartStatutCommandes').getContext('2d');
    new Chart(ctxStatut, {
        type: 'doughnut',
        data: {
            labels: ['En Attente', 'Confirmées', 'Expédiées', 'Livrées'],
            datasets: [{
                data: [
                    {{ $commandesEnAttente }},
                    {{ $commandesConfirmees }},
                    {{ $commandesExpediees }},
                    {{ $commandeslivrees }}
                ],
                backgroundColor: [
                    '#EF4444',    // En Attente - Rouge
                    '#F59E0B',    // Confirmées - Jaune
                    '#3B82F6',    // Expédiées - Bleu
                    '#10B981'     // Livrées - Vert
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15 }
                }
            }
        }
    });

    // 3. Graphique Top Produits
    const topProduits = {!! json_encode($topProduits->map(function($p) { return ['nom' => $p->nom, 'ventes' => $p->ventes_total]; })) !!};
    const ctxTop = document.getElementById('chartTopProduits').getContext('2d');
    new Chart(ctxTop, {
        type: 'bar',
        data: {
            labels: topProduits.map(p => p.nom.substring(0, 20) + (p.nom.length > 20 ? '...' : '')),
            datasets: [{
                label: 'Ventes (CFA)',
                data: topProduits.map(p => p.ventes),
                backgroundColor: [
                    '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('fr-FR') + ' CFA';
                        }
                    }
                }
            }
        }
    });

    // 4. Graphique Catégories
    @if(count($donneesCategories) > 0)
    const categories = {!! json_encode($donneesCategories) !!};
    const ctxCat = document.getElementById('chartCategories').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: categories.map(c => c.label),
            datasets: [{
                data: categories.map(c => c.value),
                backgroundColor: categories.map(c => c.color),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15 }
                }
            }
        }
    });
    @endif
</script>
@endsection
