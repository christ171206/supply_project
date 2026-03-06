@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center shadow-lg">
                    <x-heroicon-o-chat-bubble-left class="w-8 h-8 text-white" />
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Mes Messages</h1>
                    <p class="text-gray-600 mt-1">Communiquez avec les vendeurs</p>
                </div>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 flex-shrink-0" />
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Gauche - Conversations -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Conversations</h3>
                    <p class="text-sm text-gray-600 mb-6">{{ $conversations->count() }} conversation{{ $conversations->count() !== 1 ? 's' : '' }}</p>

                    <div class="space-y-2 max-h-[600px] overflow-y-auto">
                        @forelse($conversations as $message)
                            @php
                                $otherUser = $message->from_user_id === auth()->id() ? $message->toUser : $message->fromUser;
                            @endphp

                            <a href="{{ route('messages.show', $otherUser->id) }}"
                               class="flex items-center gap-3 p-3 hover:bg-primary-50 rounded-lg transition group {{ request()->route('userId') == $otherUser->id ? 'bg-primary-100 border-l-4 border-primary-500' : '' }}">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate group-hover:text-primary-600">{{ $otherUser->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-12 text-gray-500">
                                <x-heroicon-o-inbox class="w-12 h-12 mb-2 mx-auto text-gray-400" />
                                <p class="text-sm font-medium">Aucune conversation</p>
                                <p class="text-xs mt-2">Commencez à discuter avec les vendeurs</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Contenu Principal -->
            <div class="lg:col-span-3">
                @php
                    $routeUserId = request()->route('userId');
                    $otherUser = $routeUserId ? \App\Models\User::find($routeUserId) : null;
                @endphp

                @if($otherUser)
                    @php
                        $messages = \App\Models\Message::where(function ($query) use ($otherUser) {
                            $query->where('from_user_id', auth()->id())
                                  ->where('to_user_id', $otherUser->id)
                                  ->orWhere(function ($q) use ($otherUser) {
                                      $q->where('from_user_id', $otherUser->id)
                                        ->where('to_user_id', auth()->id());
                                  });
                        })->orderBy('created_at', 'asc')->get();

                        // Mark as read
                        \App\Models\Message::where('from_user_id', $otherUser->id)
                            ->where('to_user_id', Auth::id())
                            ->where('lu', false)
                            ->update(['lu' => true]);
                    @endphp

                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                        <!-- Header de conversation -->
                        <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-primary-50 to-accent-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">{{ $otherUser->name }}</h2>
                                        <p class="text-sm text-gray-600">{{ $otherUser->shop_name ?? 'Client' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('client.messages') }}" class="text-gray-600 hover:text-gray-900 transition font-semibold flex items-center gap-2">
                                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                                    <span>Retour</span>
                                </a>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            @forelse($messages as $msg)
                                <div class="flex {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md {{ $msg->from_user_id === auth()->id() ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-white text-gray-900 border border-gray-200 rounded-3xl rounded-tl-none' }} px-5 py-3 shadow-md">
                                        <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                                        <div class="flex items-center gap-2 mt-2 {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                            <p class="text-xs {{ $msg->from_user_id === auth()->id() ? 'text-primary-100' : 'text-gray-500' }}">
                                                {{ $msg->created_at->format('H:i') }}
                                            </p>
                                            @if($msg->from_user_id === auth()->id())
                                                <span class="text-xs {{ $msg->lu ? 'text-primary-300' : 'text-primary-400' }} font-semibold">
                                                    {{ $msg->lu ? '✓✓' : '✓' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center h-full text-center text-gray-500">
                                    <div>
                                        <x-heroicon-o-chat-bubble-left class="w-16 h-16 mb-3 mx-auto text-gray-400" />
                                        <p class="font-semibold text-gray-900">Aucun message</p>
                                        <p class="text-sm text-gray-600 mt-2">Commencez la conversation ci-dessous</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Formulaire d'envoi -->
                        <div class="border-t border-gray-200 p-6 bg-white">
                            <form action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="flex gap-3">
                                @csrf
                                <textarea
                                    name="contenu"
                                    placeholder="Écrivez votre message..."
                                    required
                                    class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors resize-none text-sm"
                                    rows="2"
                                ></textarea>
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-200 self-end hover:from-primary-700 hover:to-primary-800 flex items-center gap-2 whitespace-nowrap"
                                >
                                    <span>📤</span>
                                    <span>Envoyer</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-12 text-center h-[600px] flex items-center justify-center">
                        <div>
                            <x-heroicon-o-chat-bubble-left class="w-20 h-20 mb-3 mx-auto text-gray-400" />
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sélectionnez une conversation</h3>
                            <p class="text-gray-600">Choisissez une conversation à gauche pour commencer à discuter</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

