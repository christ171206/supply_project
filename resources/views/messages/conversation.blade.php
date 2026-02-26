@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4 h-[calc(100vh-8rem)]">
        <div class="bg-white rounded-2xl shadow-2xl h-full flex flex-col overflow-hidden">

            <!-- Header Conversation -->
            <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-primary-50 to-accent-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $otherUser->name }}</h1>
                            <p class="text-sm text-gray-600">{{ $otherUser->shop_name ?? 'Client' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('messages.index') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-semibold transition">
                        ← Retour
                    </a>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-white">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->from_user_id === Auth::id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $msg->id }}">
                        <div class="max-w-xs lg:max-w-md {{ $msg->from_user_id === Auth::id() ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-100 text-gray-900 rounded-3xl rounded-tl-none' }} px-5 py-3 shadow-md">
                            <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                            <p class="text-xs {{ $msg->from_user_id === Auth::id() ? 'text-primary-100' : 'text-gray-500' }} mt-2">
                                {{ $msg->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-center text-gray-500">
                        <div>
                            <p class="text-6xl mb-4">💬</p>
                            <p class="font-semibold text-lg">Aucun message</p>
                            <p class="text-sm mt-2">Commencez la conversation ci-dessous</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Formulaire d'envoi -->
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <form id="message-form" class="space-y-4">
                    <div class="flex gap-3">
                        <textarea
                            id="message-input"
                            placeholder="Écrivez votre message..."
                            rows="2"
                            class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors resize-none text-sm"
                        ></textarea>
                        <button
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-200 self-end"
                        >
                            📤
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@if(session('success'))
    <script>
        console.log('Message envoyé avec succès');
    </script>
@endif

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
    // Initialiser Socket.io
    const socket = io('{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}', {
        auth: {
            userId: {{ Auth::id() }},
            token: '{{ csrf_token() }}'
        }
    });

    const currentUserId = {{ Auth::id() }};
    const otherUserId = {{ $otherUser->id }};
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');

    // Joindre la chambre de conversation
    socket.emit('join-conversation', {
        userId: currentUserId,
        otherUserId: otherUserId
    });

    // Émettre un message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();

        if (content.length < 1) return;

        // Envoyer via socket
        socket.emit('send-message', {
            from_user_id: currentUserId,
            to_user_id: otherUserId,
            contenu: content,
            timestamp: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
        });

        messageInput.value = '';
        messageInput.focus();
    });

    // Recevoir les messages
    socket.on('receive-message', function(data) {
        if ((data.from_user_id === currentUserId && data.to_user_id === otherUserId) ||
            (data.from_user_id === otherUserId && data.to_user_id === currentUserId)) {

            const messageElement = document.createElement('div');
            const isFromCurrentUser = data.from_user_id === currentUserId;

            messageElement.className = `flex ${isFromCurrentUser ? 'justify-end' : 'justify-start'}`;
            messageElement.innerHTML = `
                <div class="max-w-xs lg:max-w-md ${isFromCurrentUser ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-100 text-gray-900 rounded-3xl rounded-tl-none'} px-5 py-3 shadow-md">
                    <p class="text-sm leading-relaxed">${data.contenu}</p>
                    <p class="text-xs ${isFromCurrentUser ? 'text-primary-100' : 'text-gray-500'} mt-2">
                        ${data.timestamp}
                    </p>
                </div>
            `;

            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });

    // Message de typing
    socket.on('user-typing', function(data) {
        if (data.userId === otherUserId) {
            console.log('L\'utilisateur tape un message...');
        }
    });

    // Scroll auto en bas au chargement
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
</script>
@endsection
