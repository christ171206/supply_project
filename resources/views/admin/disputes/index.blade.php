@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">⚖️ Litiges</h1>
        <p class="text-gray-600 mt-2">Gérez les réclamations et litiges des clients</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="open" @selected(request('status') === 'open')>Ouvert</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Résolu</option>
                    <option value="closed" @selected(request('status') === 'closed')>Fermé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" placeholder="Chercher..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Disputes List -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($disputes->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Aucun litige</p>
                <p class="text-gray-400 text-sm mt-2">Tous les litiges ont été résolus!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($disputes as $dispute)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $dispute->subject ?? 'Litige #' . $dispute->id }}</h3>
                                    @if($dispute->status === 'open')
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">🔴 Ouvert</span>
                                    @elseif($dispute->status === 'in_progress')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">🟡 En cours</span>
                                    @elseif($dispute->status === 'resolved')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">🔵 Résolu</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">⚪ Fermé</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 text-sm">{{ $dispute->description ?? 'Aucune description' }}</p>
                            </div>
                            <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold ml-4">
                                Voir →
                            </a>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-xs text-gray-600 font-medium">DEMANDEUR</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $dispute->requester->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">DEMANDÉ</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $dispute->respondent->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">MONTANT</p>
                                <p class="text-sm font-semibold text-green-600 mt-1">
                                    {{ number_format($dispute->resolution_amount ?? 0, 0, ',', ' ') }} XOF
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-medium">CRÉÉ LE</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $dispute->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        @if($dispute->status === 'open')
                            <div class="mt-4 flex gap-2">
                                <form method="POST" action="{{ route('admin.disputes.update-status', $dispute->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition">
                                        Marquer en cours
                                    </button>
                                </form>
                            </div>
                        @elseif($dispute->status === 'in_progress')
                            <div class="mt-4 flex gap-2">
                                <form method="POST" action="{{ route('admin.disputes.resolve', $dispute->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                                        Marquer résolu
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.disputes.close', $dispute->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition">
                                        Fermer
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($disputes->total() > 15)
                <div class="mt-6 flex justify-center">
                    {{ $disputes->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Ouverts</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $openCount ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">En Cours</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $inProgressCount ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Résolus</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $resolvedCount ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6">
            <h3 class="text-sm font-medium text-gray-700">Montant Total</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalAmount ?? 0, 0, ',', ' ') }} XOF</p>
        </div>
    </div>
</div>
@endsection
