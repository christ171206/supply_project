@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">💬 Messages</h1>
        <p class="text-gray-600">Communication avec vos clients</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex gap-4 flex-wrap">
            <select name="filtre" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="tous" {{ request('filtre') == 'tous' ? 'selected' : '' }}>Tous les messages</option>
                <option value="non_lus" {{ request('filtre') == 'non_lus' ? 'selected' : '' }}>Messages non lus</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Stats messages -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Messages non lus</p>
            <p class="text-3xl font-bold text-orange-600">{{ $messagesNonLus }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Messages total</p>
            <p class="text-3xl font-bold text-primary-600">{{ $messagesTotal }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Taux de réponse</p>
            <p class="text-3xl font-bold text-green-600">100%</p>
        </div>
    </div>

    <!-- Liste des messages -->
    @if($messages->count() > 0)
        <div class="space-y-4">
            @foreach($messages as $msg)
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition {{ !$msg->lu ? 'border-l-4 border-orange-500' : '' }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $msg->fromUser->name ?? 'Utilisateur' }}</h3>
                            <p class="text-xs text-gray-600">{{ $msg->fromUser->email ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
                            @if(!$msg->lu)
                                <span class="inline-block mt-2 px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">
                                    🔔 Non lu
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700">{{ $msg->contenu }}</p>
                    </div>

                    <div class="flex gap-3">
                        <a href="#" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold text-sm">
                            ✉️ Répondre
                        </a>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-sm">
                            🗑️ Supprimer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $messages->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <p class="text-6xl mb-4">💌</p>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun message</h3>
            <p class="text-gray-600">Vous n'avez pas encore reçu de messages de clients</p>
        </div>
    @endif
</div>
@endsection
