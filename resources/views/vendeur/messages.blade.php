@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
            <x-heroicon-o-chat-bubble-left class="w-10 h-10" />
            <span>Messages</span>
        </h1>
        <p class="text-gray-600">Communication avec vos clients</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex gap-4 flex-wrap">
            <select name="filtre" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="tous" {{ request('filtre') == 'tous' ? 'selected' : '' }}>Tous les messages</option>
                <option value="non_lus" {{ request('filtre') == 'non_lus' ? 'selected' : '' }}>Non lus uniquement</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold flex items-center gap-2">
                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                <span>Filtrer</span>
            </button>
        </form>
    </div>

    <!-- Messages d'alerte -->
    @if($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded-lg mb-6" role="alert">
            <p class="font-bold">✓ Succès</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-lg mb-6" role="alert">
            <p class="font-bold">❌ Erreur</p>
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Stats messages -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Conversations non lues</p>
            <p class="text-3xl font-bold text-orange-600">
                @php
                    $unreadConversations = $conversations->filter(function($conv) { return $conv['unread_count'] > 0; })->count();
                @endphp
                {{ $unreadConversations }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Total conversations</p>
            <p class="text-3xl font-bold text-primary-600">{{ $conversations->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4">
            <p class="text-gray-600 text-sm">Messages total</p>
            <p class="text-3xl font-bold text-green-600">{{ $messagesTotal }}</p>
        </div>
    </div>

    <!-- Liste des conversations -->
    @if($conversations->count() > 0)
        <div class="space-y-4">
            @foreach($conversations as $conv)
                <a href="{{ route('vendeur.messages.show', $conv['other_user']->id) }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition {{ $conv['unread_count'] > 0 ? 'border-l-4 border-orange-500 bg-orange-50' : '' }}">
                    <div class="flex items-start justify-between gap-4">
                        <!-- Avatar et info client -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ strtoupper(substr($conv['other_user']->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $conv['other_user']->name }}</h3>
                                    <p class="text-xs text-gray-600">{{ $conv['other_user']->email }}</p>
                                </div>
                            </div>

                            <!-- Produit associé -->
                            @if($conv['produit'])
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-xs text-blue-600 font-semibold flex items-center gap-1">
                                        <x-heroicon-o-cube class="w-4 h-4" />
                                        <span>Produit:</span>
                                    </p>
                                    <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $conv['produit']->nom }}</p>
                                </div>
                            @endif

                            @if($conv['last_message'])
                                <p class="text-sm text-gray-700 mt-3 line-clamp-2">
                                    <strong>{{ $conv['last_message']->from_user_id === auth()->id() ? 'Vous: ' : '' }}</strong>
                                    {{ strlen($conv['last_message']->contenu) > 100 ? substr($conv['last_message']->contenu, 0, 100) . '...' : $conv['last_message']->contenu }}
                                </p>
                            @endif
                        </div>

                        <div class="text-right ml-4">
                            @if($conv['last_message'])
                                <p class="text-sm text-gray-600 whitespace-nowrap">{{ $conv['last_message']->created_at->format('d/m H:i') }}</p>
                            @endif

                            @if($conv['unread_count'] > 0)
                                <span class="inline-block mt-2 px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full flex items-center gap-1 w-fit">
                                    <x-heroicon-o-bell class="w-4 h-4" />
                                    <span>{{ $conv['unread_count'] }} nouveau{{ $conv['unread_count'] > 1 ? 'x' : '' }}</span>
                                </span>
                            @else
                                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                    ✓ Tout lu
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <p class="text-6xl mb-4">💌</p>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune conversation</h3>
            <p class="text-gray-600">Vous n'avez pas encore de messages de clients. Les messages apparaîtront ici.</p>
        </div>
    @endif
</div>
@endsection
