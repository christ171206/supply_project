@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-user-group class="w-8 h-8" /><span>Activité des Utilisateurs</span></h1>
        <p class="text-gray-600 mt-2">Suivi de l'engagement et de l'activité des utilisateurs</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Utilisateurs Actifs</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $activeUsers ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Nouveaux Utilisateurs</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $newUsersMonth ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Panier Moyen</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ number_format($averageBasketValue ?? 0, 0, ',', ' ') }} XOF
            </p>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Taux de Conversion</h3>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ round($conversionRate ?? 0) }}%</p>
        </div>
    </div>

    <!-- User Activity Table -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Utilisateurs Récents</span></h2>

        @if(isset($recentUsers) && $recentUsers->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Utilisateur</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Rôle</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Commandes</th>
                            <th class="text-right py-4 px-4 font-semibold text-gray-700">Total Dépensé</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-700">Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="px-3 py-1 @if($user->role === 'vendor') bg-purple-100 text-purple-800 @elseif($user->is_admin) bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif rounded-full text-xs font-semibold">
                                        {{ $user->is_admin ? 'Admin' : ($user->role === 'vendor' ? 'Vendeur' : 'Client') }}
                                    </span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    {{ $user->commandes_count ?? 0 }}
                                </td>
                                <td class="text-right py-4 px-4 font-bold text-green-600">
                                    {{ number_format($user->total_spent ?? 0, 0, ',', ' ') }} XOF
                                </td>
                                <td class="text-center py-4 px-4 text-xs text-gray-600">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>❌ Aucune donnée disponible</p>
            </div>
        @endif
    </div>

    <!-- Top Categories by Users -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🏆 Catégories les Plus Visitées</h2>
        
        @if(isset($topCategories) && $topCategories->isNotEmpty())
            <div class="space-y-3">
                @foreach($topCategories as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $category->nom ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $category->visitors ?? 0 }} visiteurs</p>
                                <p class="font-bold text-blue-600">{{ $category->products ?? 0 }} produits</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>❌ Aucune donnée disponible</p>
            </div>
        @endif
    </div>
</div>
@endsection
