@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">

    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Analytics</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">
            Statistiques
        </h1>
        <p class="text-[13px] text-white/50 font-light mt-2">Analyse détaillée de vos performances</p>
    </div>

    <div class="px-8">
    <!-- En-tête avec contrôles -->
    <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
        <!-- Sélecteur de période -->
        <form method="GET" id="periodForm" class="flex gap-2">
            <select name="periode" class="px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]" onchange="updateExportLinks()">
                <option value="7" {{ request('periode') == 7 ? 'selected' : '' }}>Derniers 7 jours</option>
                <option value="30" {{ request('periode') == 30 ? 'selected' : '' }}>Dernier mois</option>
                <option value="90" {{ request('periode') == 90 ? 'selected' : '' }}>Dernier trimestre</option>
                <option value="365" {{ request('periode') == 365 ? 'selected' : '' }}>Dernière année</option>
            </select>
        </form>

        <!-- Boutons d'export avec menu -->
        <div class="relative group">
            <button class="px-4 py-2 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-medium text-[13px] flex items-center gap-2">
                Exporter les données
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            <div class="absolute right-0 top-full mt-2 w-56 bg-white border border-[#e0e0dc] rounded-lg shadow-lg p-4 hidden group-hover:block z-10">
                <p class="text-[11px] font-medium text-[#a0a09a] uppercase mb-3">Format d'export</p>
                <a href="{{ route('vendeur.statistiques.export', ['format' => 'csv-complet', 'periode' => request('periode', 7)]) }}" 
                   id="exportCsvLink"
                   class="block w-full px-3 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg hover:bg-[#f7f7f5] transition font-medium text-[13px] mb-2">
                    CSV Complet (Données + Graphiques)
                </a>
                <a href="{{ route('vendeur.statistiques.export', ['format' => 'pdf-complet', 'periode' => request('periode', 7)]) }}" 
                   id="exportPdfLink"
                   class="block w-full px-3 py-2 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-medium text-[13px] mb-2">
                    PDF Complet (Incluant graphiques)
                </a>
                <hr class="my-2 border-[#e0e0dc]">
                <p class="text-[11px] text-[#a0a09a] mb-2">Exports rapides</p>
                <a href="{{ route('vendeur.statistiques.export', ['format' => 'csv', 'periode' => request('periode', 7)]) }}" 
                   id="exportCsvLiteLink"
                   class="block w-full px-3 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg hover:bg-[#f7f7f5] transition text-[12px] mb-2">
                    CSV (KPIs seulement)
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Chiffre d'Affaires</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ number_format($totalCA, 0, ',', ' ') }} CFA</p>
            <p class="text-[11px] text-[#a0a09a] mt-2">Derniers {{ request('periode', 7) }} jours</p>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Commandes</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ $nombreCommandes }}</p>
            <p class="text-[11px] text-[#a0a09a] mt-2">Panier moyen: {{ number_format($panierMoyen, 0, ',', ' ') }} CFA</p>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Notation</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">{{ round($noteMoyenne, 1) }}/5</p>
            <p class="text-[11px] text-[#a0a09a] mt-2">{{ $nombreAvis }} avis</p>
        </div>

        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <p class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Taux Complétion</p>
            <p class="text-2xl font-mono font-bold text-[#0a0a0a]">
                @php $tauxCompletion = $nombreCommandes > 0 ? round(($commandeslivrees / $nombreCommandes) * 100) : 0; @endphp
                {{ $tauxCompletion }}%
            </p>
            <p class="text-[11px] text-[#a0a09a] mt-2">Commandes livrées</p>
        </div>
    </div>

    <!-- Top Produits & Statut Commandes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top 5 Produits -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Top 5 Produits</h3>
            <div class="space-y-3">
                @forelse($topProduits as $idx => $produit)
                    <div class="flex items-center justify-between p-3 bg-[#f7f7f5] rounded hover:bg-[#efefed]">
                        <div class="flex-1">
                            <p class="text-[13px] font-medium text-[#0a0a0a]">{{ $idx + 1 }}. {{ $produit->nom }}</p>
                            <p class="text-[11px] text-[#a0a09a]">{{ $produit->ventes_nombre }} ventes</p>
                        </div>
                        <p class="font-mono text-[13px] font-bold text-[#0a0a0a]">{{ number_format($produit->ventes_total, 0, ',', ' ') }} CFA</p>
                    </div>
                @empty
                    <p class="text-center text-[#a0a09a] text-[13px] py-6">Aucun produit vendu</p>
                @endforelse
            </div>
        </div>

        <!-- Statut Commandes -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Statut Commandes</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-[#fef2f2] rounded">
                    <div>
                        <p class="text-[13px] font-medium text-[#0a0a0a]">En Attente</p>
                        <p class="text-[11px] text-[#a0a09a]">À traiter</p>
                    </div>
                    <p class="font-mono text-lg font-bold text-[#dc2626]">{{ $commandesEnAttente }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-[#fef3c7] rounded">
                    <div>
                        <p class="text-[13px] font-medium text-[#0a0a0a]">Confirmées</p>
                        <p class="text-[11px] text-[#a0a09a]">Prêtes</p>
                    </div>
                    <p class="font-mono text-lg font-bold text-[#92400e]">{{ $commandesConfirmees }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-[#f0fdf4] rounded">
                    <div>
                        <p class="text-[13px] font-medium text-[#0a0a0a]">Expédiées</p>
                        <p class="text-[11px] text-[#a0a09a]">En route</p>
                    </div>
                    <p class="font-mono text-lg font-bold text-[#15803d]">{{ $commandesExpediees }}</p>
                </div>

                <div class="flex items-center justify-between p-3 bg-[#f0fdf4] rounded">
                    <div>
                        <p class="text-[13px] font-medium text-[#0a0a0a]">Livrées</p>
                        <p class="text-[11px] text-[#a0a09a]">Complètes</p>
                    </div>
                    <p class="font-mono text-lg font-bold text-[#15803d]">{{ $commandeslivrees }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques Chart.js -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Évolution CA -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Évolution du CA</h3>
            <div class="h-64">
                <canvas id="chartCA"></canvas>
            </div>
        </div>

        <!-- Statut Commandes - Donut Chart -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Distribution des Commandes</h3>
            <div class="h-64 flex justify-center">
                <canvas id="chartStatutCommandes"></canvas>
            </div>
        </div>
    </div>

    <!-- Deuxième rangée de graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Top Produits - Bar Chart -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Ventes par Produit</h3>
            <div class="h-64">
                <canvas id="chartTopProduits"></canvas>
            </div>
        </div>

        <!-- Répartition par Catégorie - Pie Chart -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Répartition par Catégorie</h3>
            <div class="h-64 flex justify-center">
                <canvas id="chartCategories"></canvas>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Palette de couleurs minimaliste
    const colors = {
        primary: '#0a0a0a',
        secondary: '#e0e0dc',
        success: '#15803d',
        warning: '#92400e',
        danger: '#dc2626'
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
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, labels: { color: colors.primary, font: { size: 12 } } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: colors.secondary },
                    ticks: { color: colors.primary, font: { size: 11 }, callback: function(value) { return value.toLocaleString('fr-FR'); } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: colors.primary, font: { size: 11 } }
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
                    '#fef2f2',
                    '#fef3c7',
                    '#f0fdf4',
                    '#f0fdf4'
                ],
                borderColor: colors.primary,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, color: colors.primary, font: { size: 12 } }
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
                backgroundColor: colors.secondary,
                borderColor: colors.primary,
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, labels: { color: colors.primary, font: { size: 12 } } }
            },
            scales: {
                x: {
                    grid: { color: colors.secondary },
                    ticks: { color: colors.primary, font: { size: 11 }, callback: function(value) { return value.toLocaleString('fr-FR'); } }
                },
                y: {
                    grid: { display: false },
                    ticks: { color: colors.primary, font: { size: 11 } }
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
                backgroundColor: categories.map(c => c.color || colors.secondary),
                borderColor: colors.primary,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, color: colors.primary, font: { size: 12 } }
                }
            }
        }
    });
    @endif

    // Fonction pour mettre à jour les liens d'export
    function updateExportLinks() {
        const periode = document.querySelector('[name="periode"]').value;
        document.getElementById('exportCsvLink').href = `/vendeur/statistiques/export?format=csv-complet&periode=${periode}`;
        document.getElementById('exportPdfLink').href = `/vendeur/statistiques/export?format=pdf-complet&periode=${periode}`;
        document.getElementById('exportCsvLiteLink').href = `/vendeur/statistiques/export?format=csv&periode=${periode}`;
    }
</script>
@endsection
