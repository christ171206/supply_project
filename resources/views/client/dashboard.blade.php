@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
        <p class="text-gray-600 mt-1">Bienvenue, <span class="font-semibold text-primary-600">{{ Auth::user()->name }}</span></p>
    </div>

    <!-- Résumé Cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <!-- Total Commandes -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Commandes</p>
            <p class="text-3xl font-bold text-primary-600 mt-2">{{ $commandesTotal }}</p>
            <p class="text-xs text-gray-500 mt-2">Total depuis votre inscription</p>
        </div>

        <!-- En Cours -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">En Cours</p>
            <p class="text-3xl font-bold text-accent-600 mt-2">{{ $commandesEnCours }}</p>
            <p class="text-xs text-gray-500 mt-2">À livrer bientôt</p>
        </div>

        <!-- Total Dépensé -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Total Dépensé</p>
            <p class="text-3xl font-bold text-secondary-600 mt-2">{{ number_format($montantTotal, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-2">FCFA</p>
        </div>

        <!-- Dernier Achat -->
        <div class="card p-6 hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-semibold">Dernier Achat</p>
            <p class="text-3xl font-bold text-danger-600 mt-2">
                @if($commandesRecentes->first())
                    {{ $commandesRecentes->first()->created_at->format('d/m') }}
                @else
                    —
                @endif
            </p>
            <p class="text-xs text-gray-500 mt-2">
                @if($commandesRecentes->first())
                    {{ $commandesRecentes->first()->created_at->diffForHumans() }}
                @else
                    Pas d'achat
                @endif
            </p>
        </div>
    </div>

    <!-- Graphique Dépenses -->
    <div class="card p-8 mb-8">
        <div class="flex items-center gap-3 mb-6">
            <x-heroicon-o-chart-bar class="w-6 h-6 text-primary-600" />
            <h2 class="text-lg font-semibold text-gray-900">Vos Dépenses (7 derniers jours)</h2>
        </div>
        <div id="expensesChart" style="height: 300px;"></div>
    </div>

    <!-- Dernières Commandes -->
    <div class="card p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Dernières Commandes</h2>

        @if($commandesRecentes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">N°</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Montant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Paiement</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-900">Statut</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($commandesRecentes as $commande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-semibold text-gray-900">#{{ $commande->id }}</td>
                                <td class="py-4 px-4 text-gray-700">{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4 font-semibold text-primary-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs font-semibold text-gray-700">
                                        @if($commande->mode_paiement == 'mobile_money') Mobile Money
                                        @elseif($commande->mode_paiement == 'carte_bancaire') Carte
                                        @else À la livraison @endif
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @switch($commande->statut)
                                        @case('en_attente')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">En attente</span>
                                        @break
                                        @case('confirmee')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">✓ Confirmée</span>
                                        @break
                                        @case('expediee')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">🚚 Expédiée</span>
                                        @break
                                        @case('livree')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">✓ Livrée</span>
                                        @break
                                        @default
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">✗ Annulée</span>
                                    @endswitch
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('client.commande-detail', $commande->id) }}" class="text-primary-600 hover:text-primary-700 font-semibold text-xs">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-600 mb-4">Vous n'avez pas encore de commandes</p>
                <a href="{{ route('produits.catalogue') }}" class="btn-primary inline-block">
                    Commencer à acheter
                </a>
            </div>
        @endif
    </div>

        <!-- Raccourcis Rapides - Améliorés -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-6">Accès Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('produits.catalogue') }}"
                   class="group bg-black text-white rounded-xl p-8 text-center font-semibold transition hover:opacity-90 border border-black">
                    <div class="mb-3 flex justify-center">
                        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 3h12m0 0l-1.5 12H7.5L6 3m0 0H2.5m17 0H21.5"/>
                            <rect x="5" y="18" width="14" height="2" rx="1"/>
                        </svg>
                    </div>
                    <span>Continuer les Achats</span>
                </a>
                <a href="{{ route('panier.index') }}"
                   class="group bg-black text-white rounded-xl p-8 text-center font-semibold transition hover:opacity-90 border border-black">
                    <div class="mb-3 flex justify-center">
                        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                    </div>
                    <span>Voir Panier</span>
                </a>
                <a href="{{ route('client.messages') }}"
                   class="group bg-black text-white rounded-xl p-8 text-center font-semibold transition hover:opacity-90 border border-black">
                    <div class="mb-3 flex justify-center">
                        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <span>Messages</span>
                </a>
                <a href="{{ route('client.commandes') }}"
                   class="group bg-black text-white rounded-xl p-8 text-center font-semibold transition hover:opacity-90 border border-black">
                    <div class="mb-3 flex justify-center">
                        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <polyline points="12 22.08 12 12"/>
                        </svg>
                    </div>
                    <span>Mes Commandes</span>
                </a>
                <a href="{{ route('client.profil') }}"
                   class="group bg-black text-white rounded-xl p-8 text-center font-semibold transition hover:opacity-90 border border-black">
                    <div class="mb-3 flex justify-center">
                        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <span>Mon Profil</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script>
    // Graphique des dépenses
    const expensesData = @json($graph_data);
    const dates = Object.keys(expensesData).map(date => {
        const d = new Date(date);
        return d.toLocaleDateString('fr-FR', { month: 'short', day: 'numeric' });
    });
    const amounts = Object.values(expensesData);

    const options = {
        series: [{
            name: 'Dépenses (FCFA)',
            data: amounts
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: true
            },
            animations: {
                enabled: true
            }
        },
        colors: ['#3b82f6'],
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.45,
                opacityTo: 0.05
            }
        },
        xaxis: {
            categories: dates,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Montant (FCFA)'
            },
            labels: {
                formatter: function(value) {
                    return (value / 1000).toFixed(0) + 'K';
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                }
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        }
    };

    const chart = new ApexCharts(document.querySelector("#expensesChart"), options);
    chart.render();
</script>
@endsection
