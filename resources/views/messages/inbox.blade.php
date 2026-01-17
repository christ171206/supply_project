@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full flex items-center justify-center text-4xl shadow-lg">
                    💬
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Mes Messages</h1>
                    <p class="text-gray-600 mt-2">Communiquez avec les vendeurs</p>
                </div>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-600 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Gauche -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Conversations</h3>
                    <p class="text-sm text-gray-600 mb-6">{{ $conversations->count() }} conversation{{ $conversations->count() !== 1 ? 's' : '' }}</p>

                    <div class="space-y-2">
                        @forelse($conversations as $message)
                            @php
                                $otherUser = $message->from_user_id === Auth::id() ? $message->toUser : $message->fromUser;
                            @endphp

                            <a href="{{ route('messages.show', $otherUser->id) }}"
                               class="flex items-center gap-3 p-3 hover:bg-purple-50 rounded-lg transition group {{ request()->route('userId') == $otherUser->id ? 'bg-purple-100' : '' }}">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    👤
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate group-hover:text-purple-600">{{ $otherUser->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p class="text-3xl mb-2">📭</p>
                                <p class="text-sm">Aucune conversation</p>
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
                        $messages = \App\Models\Message::where(function ($query) {
                            $query->where('from_user_id', Auth::id())->where('to_user_id', $otherUser->id)
                                  ->orWhere('from_user_id', $otherUser->id)->where('to_user_id', Auth::id());
                        })->orderBy('created_at', 'asc')->get();

                        // Mark as read
                        \App\Models\Message::where('from_user_id', $otherUser->id)
                            ->where('to_user_id', Auth::id())
                            ->where('lu', false)
                            ->update(['lu' => true]);
                    @endphp

                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                        <!-- Header de conversation -->
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 border-b border-gray-200">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-xl">
                                    👤
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold">{{ $otherUser->name }}</h2>
                                    <p class="text-purple-100 text-sm">{{ $otherUser->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            @forelse($messages as $msg)
                                <div class="flex {{ $msg->from_user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs {{ $msg->from_user_id === Auth::id() ? 'bg-purple-500 text-white' : 'bg-white border border-gray-200 text-gray-900' }} rounded-lg p-4 shadow">
                                        <p class="text-sm">{{ $msg->contenu }}</p>
                                        <p class="text-xs {{ $msg->from_user_id === Auth::id() ? 'text-purple-100' : 'text-gray-500' }} mt-2">
                                            {{ $msg->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center h-full">
                                    <div class="text-center text-gray-500">
                                        <p class="text-4xl mb-2">💬</p>
                                        <p>Commencez une conversation</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Indicateur "en train de taper" -->
                        <div id="typing-indicator" class="px-6 pb-2"></div>

                        <!-- Formulaire d'envoi -->
                        <div class="border-t border-gray-200 p-4 bg-white">
                            <form action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <textarea
                                    name="contenu"
                                    placeholder="Votre message..."
                                    required
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"
                                    rows="2"
                                ></textarea>
                                <button
                                    type="submit"
                                    class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-lg transition transform hover:scale-105 flex items-center gap-2 whitespace-nowrap"
                                >
                                    <span>📤</span>
                                    <span>Envoyer</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center h-[600px] flex items-center justify-center">
                        <div>
                            <p class="text-6xl mb-4">💬</p>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sélectionnez une conversation</h3>
                            <p class="text-gray-600">Choisissez une conversation à gauche pour commencer à discuter</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@auth
<script type="module">
    import { initializeSocketIO, sendMessage, notifyTyping, stopTyping } from '{{ asset('resources/js/socketio.js') }}';

    document.addEventListener('DOMContentLoaded', () => {
        const userId = {{ Auth::id() }};
        const userName = '{{ Auth::user()->name }}';

        // Initialiser Socket.io
        initializeSocketIO(userId, userName);

        // Form submission pour envoyer un message
        @if(request()->route('userId'))
            const form = document.querySelector('form[action*="messages"]');
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const messageInput = form.querySelector('textarea[name="contenu"]');
                    const message = messageInput.value.trim();
                    const toUserId = {{ request()->route('userId') }};

                    if (message) {
                        sendMessage(toUserId, message, null, userName);
                        messageInput.value = '';
                    }
                });
            }

            // Indicateur de typing
            const messageInput = document.querySelector('textarea[name="contenu"]');
            if (messageInput) {
                const toUserId = {{ request()->route('userId') }};
                let typingTimer;

                messageInput.addEventListener('input', () => {
                    clearTimeout(typingTimer);
                    notifyTyping(toUserId);

                    typingTimer = setTimeout(() => {
                        stopTyping(toUserId);
                    }, 1000);
                });
            }
        @endif

        // Demander la permission pour les notifications
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    });
</script>
@endauth

@endsection
