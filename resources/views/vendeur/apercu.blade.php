@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-2"><x-heroicon-o-eye class="w-8 h-8" /><span>Aperçu Boutique</span></h1>
        <p class="text-gray-600">Vue d'ensemble de votre boutique et performances</p>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventes Totales -->
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-600 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-banknotes class="w-4 h-4" /><span>Ventes Totales</span></p>
                <x-heroicon-o-chart-bar class="w-6 h-6 text-gray-400" />
            </div>
            <p class="text-3xl font-bold text-green-600">{{ number_format($totalVentes, 0, ',', ' ') }} CFA</p>
            <p class="text-xs text-gray-500 mt-2">Chiffre d'affaires total</p>
        </div>

        <!-- Commandes -->
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-600 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-shopping-cart class="w-4 h-4" /><span>Commandes</span></p>
                <x-heroicon-o-cube class="w-6 h-6 text-gray-400" />
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $nombreCommandes }}</p>
            <p class="text-xs text-gray-500 mt-2">Panier moyen: {{ number_format($panierMoyen, 0, ',', ' ') }} CFA</p>
        </div>

        <!-- Produits -->
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-purple-500 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-600 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-cube class="w-4 h-4" /><span>Produits</span></p>
                <x-heroicon-o-tag class="w-6 h-6 text-gray-400" />
            </div>
            <p class="text-3xl font-bold text-purple-600">{{ $nombreProduits }}</p>
            <p class="text-xs text-gray-500 mt-2">Produits en ligne</p>
        </div>

        <!-- Notation -->
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-600 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-star class="w-4 h-4 text-yellow-500" /><span>Notation</span></p>
                <x-heroicon-o-star class="w-6 h-6 text-yellow-400" />
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ round($noteMoyenne, 1) }}/5</p>
            <p class="text-xs text-gray-500 mt-2">{{ $nombreAvis }} avis clients</p>
        </div>
    </div>

    <!-- Profil & Complétude -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Taux de Complétude Profil -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-document-text class="w-5 h-5" /><span>Complétude du Profil</span></h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm font-semibold text-gray-700">Profil</p>
                        <p class="text-sm font-bold text-primary-600">{{ $tauxCompletion }}%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: {{ $tauxCompletion }}%;"></div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    @if($tauxCompletion < 100)
                        <p class="text-sm text-gray-600 mb-3">Éléments à compléter:</p>
                        <div class="space-y-2 text-sm">
                            @if(!auth()->user()->shop_name)
                                <p class="text-orange-600 flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Nom de la boutique</span></p>
                            @else
                                <p class="text-green-600 flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Nom de la boutique</span></p>
                            @endif
                            
                            @if(!auth()->user()->description)
                                <p class="text-orange-600 flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Description boutique</span></p>
                            @else
                                <p class="text-green-600 flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Description boutique</span></p>
                            @endif
                            
                            @if(!auth()->user()->phone)
                                <p class="text-orange-600 flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Téléphone</span></p>
                            @else
                                <p class="text-green-600 flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Téléphone</span></p>
                            @endif
                            
                            @if(!auth()->user()->address)
                                <p class="text-orange-600 flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Adresse</span></p>
                            @else
                                <p class="text-green-600 flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Adresse</span></p>
                            @endif
                            
                            @if(!auth()->user()->profile_photo)
                                <p class="text-orange-600 flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Photo de profil</span></p>
                            @else
                                <p class="text-green-600 flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Photo de profil</span></p>
                            @endif
                        </div>
                        
                        <a href="{{ route('vendeur.profil') }}" class="mt-4 inline-block bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-semibold text-sm flex items-center gap-2">
                            <span>Compléter le profil</span><x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @else
                        <p class="text-green-600 font-semibold text-center flex items-center justify-center gap-2"><x-heroicon-o-check-circle class="w-5 h-5" /><span>Profil completé à 100%!</span></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statut des Commandes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-5 h-5" /><span>Statut des Commandes</span></h3>
            <div class="space-y-4">
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

                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('vendeur.commandes') }}" class="block text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition font-semibold text-sm flex items-center justify-center gap-2">
                        <span>Voir toutes les commandes</span><x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8 mb-8">
        <!-- Graphique Statut Commandes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-rocket-launch class="w-5 h-5" /><span>Distribution des Commandes</span></h3>
            <div class="h-72 flex justify-center">
                <canvas id="chartStatutApercu"></canvas>
            </div>
        </div>

        <!-- Graphique Top Produits -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-star class="w-5 h-5 text-yellow-500" /><span>Top 5 Produits (Ventes)</span></h3>
            <div class="h-72">
                <canvas id="chartTopProduitsApercu"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Produits Liste & Avis Récents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top 5 Produits -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-5 h-5" /><span>Détail Top 5 Produits</span></h3>
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

        <!-- Avis Récents -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-star class="w-5 h-5 text-yellow-500" /><span>Avis Récents</span></h3>
            <div class="space-y-3">
                @forelse($avisRecents as $avis)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-start justify-between mb-2">
                            <p class="font-semibold text-gray-900 text-sm">{{ $avis->user->name }}</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avis->note)
                                        <x-heroicon-o-star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                                    @else
                                        <x-heroicon-o-star class="w-4 h-4 text-gray-300" />
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 mb-1">{{ Str::limit($avis->commentaire, 60) }}</p>
                        <p class="text-xs text-gray-500">Produit: {{ $avis->produit->nom }}</p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">Aucun avis yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js pour Aperçu -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Graphique Statut Commandes - Doughnut
    const ctxStatutApercu = document.getElementById('chartStatutApercu').getContext('2d');
    new Chart(ctxStatutApercu, {
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
                    labels: { 
                        padding: 15,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // Graphique Top Produits - Bar Horizontal
    const topProduitsData = {!! json_encode($topProduits->map(function($p) { return ['nom' => $p->nom, 'ventes' => $p->ventes_total]; })) !!};
    const ctxTopApercu = document.getElementById('chartTopProduitsApercu').getContext('2d');
    new Chart(ctxTopApercu, {
        type: 'bar',
        data: {
            labels: topProduitsData.map(p => p.nom.substring(0, 25) + (p.nom.length > 25 ? '...' : '')),
            datasets: [{
                label: 'Ventes (CFA)',
                data: topProduitsData.map(p => p.ventes),
                backgroundColor: [
                    '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981'
                ],
                borderRadius: 6,
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
</script>
@endsection

