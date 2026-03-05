@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4 h-[calc(100vh-8rem)]">
        <div class="bg-white rounded-2xl shadow-2xl h-full flex flex-col overflow-hidden">

            <!-- Header Conversation avec Indicateurs -->
            <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-primary-50 to-accent-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-xl relative">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            <!-- Indicateur de présence (point vert/gris) -->
                            <span id="vendor-presence" class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-gray-300 border-2 border-white animate-pulse" title="Chargement du statut..."></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $otherUser->name }}</h1>
                            </div>
                            <p class="text-sm text-gray-600">{{ $otherUser->shop_name ?? 'Client' }}</p>
                            <!-- Indicateur "En train d'écrire..." -->
                            <p id="typing-indicator" class="text-xs text-primary-600 italic font-semibold mt-1 min-h-4 transition-all"></p>
                        </div>
                    </div>
                    <a href="{{ route('client.messages') }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-semibold transition">
                        ← Retour
                    </a>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-white">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->from_user_id === Auth::id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $msg->id }}">
                        <div class="block space-y-2">
                            <!-- Produit associé (si existe) -->
                            @if($msg->produit)
                                <div class="max-w-xs lg:max-w-md bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-3">
                                    <div class="flex gap-3">
                                        @if($msg->produit->images && is_array($msg->produit->images) && count($msg->produit->images) > 0)
                                            <img src="{{ asset('storage/' . $msg->produit->images[0]) }}" alt="{{ $msg->produit->nom }}" class="w-16 h-16 object-cover rounded">
                                        @elseif($msg->produit->image)
                                            <img src="{{ asset('storage/produits/' . $msg->produit->image) }}" alt="{{ $msg->produit->nom }}" class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-300 rounded flex items-center justify-center"><x-heroicon-o-cube class="w-8 h-8" /></div>
                                        @endif
                                        <div class="flex-1">
                                            <p class="text-xs text-blue-600 font-semibold flex items-center gap-1"><x-heroicon-o-cube class="w-4 h-4" /><span>PRODUIT</span></p>
                                            <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $msg->produit->nom }}</p>
                                            <p class="text-xs text-blue-700 font-semibold">{{ number_format($msg->produit->prix, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Message text -->
                            <div class="max-w-xs lg:max-w-md {{ $msg->from_user_id === Auth::id() ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-100 text-gray-900 rounded-3xl rounded-tl-none' }} px-5 py-3 shadow-md">
                                <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                                <div class="flex items-center gap-2 mt-2 {{ $msg->from_user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <p class="text-xs {{ $msg->from_user_id === Auth::id() ? 'text-primary-100' : 'text-gray-500' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </p>
                                    <!-- Coches de confirmation de lecture (gris → bleu) -->
                                    @if($msg->from_user_id === Auth::id())
                                        <span class="text-xs {{ $msg->lu ? 'text-primary-300' : 'text-primary-400' }} font-semibold" title="{{ $msg->lu ? 'Lu' : 'Non lu' }}">
                                            {{ $msg->lu ? '✓✓' : '✓' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-center text-gray-500">
                        <div>
                            <p class="text-6xl mb-4"><x-heroicon-o-chat-bubble-left class="w-16 h-16" /></p>
                            <p class="font-semibold text-lg">Aucun message</p>
                            <p class="text-sm mt-2">Commencez la conversation ci-dessous</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Formulaire d'envoi avec validation -->
            <div class="border-t border-gray-200 p-6 bg-gray-50">
                <form id="message-form" action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex gap-3">
                        <textarea
                            id="message-input"
                            name="contenu"
                            placeholder="Écrivez votre message (minimum 1 caractère)..."
                            rows="2"
                            minlength="1"
                            required
                            class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-colors resize-none text-sm"
                        ></textarea>
                        <button
                            type="submit"
                            id="send-btn"
                            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-200 self-end hover:from-primary-700 hover:to-primary-800"
                        >
                            📤 Envoyer
                        </button>
                    </div>
                    <p id="char-count" class="text-xs text-gray-500 text-right"></p>
                </form>
                <div id="alert" class="mt-3 hidden"></div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
    // Configuration Socket.io
    const socket = io('{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}', {
        auth: {
            userId: {{ Auth::id() }},
            token: '{{ csrf_token() }}'
        }
    });

    // Éléments DOM
    const currentUserId = {{ Auth::id() }};
    const otherUserId = {{ $otherUser->id }};
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const presenceIndicator = document.getElementById('vendor-presence');
    const typingIndicator = document.getElementById('typing-indicator');
    const charCount = document.getElementById('char-count');
    let typingTimeout;
    let isTyping = false;

    // ========== Joindre la Conversation ==========
    socket.emit('join-conversation', {
        userId: currentUserId,
        otherUserId: otherUserId
    });

    // ========== INDICATEUR DE PRÉSENCE ==========
    socket.on('users-online-count', function(count) {
        if (presenceIndicator && count > 0) {
            presenceIndicator.style.backgroundColor = '#22c55e';
            presenceIndicator.title = '✓ En ligne maintenant';
            presenceIndicator.classList.remove('animate-pulse');
        }
    });

    socket.on('user-joined', function(data) {
        if (data.otherUserId === otherUserId && presenceIndicator) {
            presenceIndicator.style.backgroundColor = '#22c55e';
            presenceIndicator.title = '✓ En ligne maintenant';
            presenceIndicator.classList.remove('animate-pulse');
        }
    });

    socket.on('disconnect', function() {
        if (presenceIndicator) {
            presenceIndicator.style.backgroundColor = '#d1d5db';
            presenceIndicator.title = '⊘ Statut indisponible';
            presenceIndicator.classList.add('animate-pulse');
        }
    });

    // ========== INDICATEUR "EN TRAIN D'ÉCRIRE..." ==========
    messageInput.addEventListener('keydown', function() {
        if (!isTyping) {
            isTyping = true;
            socket.emit('typing', {
                userId: currentUserId,
                otherUserId: otherUserId,
                isTyping: true
            });
        }

        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(function() {
            isTyping = false;
            socket.emit('typing', {
                userId: currentUserId,
                otherUserId: otherUserId,
                isTyping: false
            });
            typingIndicator.textContent = '';
        }, 3000);
    });

    socket.on('user-typing', function(data) {
        if (data.userId === otherUserId && data.isTyping) {
            typingIndicator.textContent = 'En train d\'écrire...';
            typingIndicator.classList.add('animate-pulse');
        } else if (data.userId === otherUserId && !data.isTyping) {
            typingIndicator.textContent = '';
            typingIndicator.classList.remove('animate-pulse');
        }
    });

    // ========== COMPTEUR DE CARACTÈRES ==========
    messageInput.addEventListener('input', function() {
        const length = this.value.length;
        if (length > 0) {
            charCount.textContent = `${length} caractère${length > 1 ? 's' : ''}`;
            sendBtn.disabled = false;
        } else {
            charCount.textContent = '';
            sendBtn.disabled = true;
        }
    });

    // ========== ENVOYER UN MESSAGE ==========
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();

        if (content.length < 1) return;

        // Désactiver le bouton d'envoi
        sendBtn.disabled = true;
        sendBtn.textContent = 'Envoi...';

        // Envoyer le message via AJAX
        fetch('{{ route('messages.reply', $otherUser->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                contenu: content
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de l\'envoi du message');
            }
            return response.json();
        })
        .then(data => {
            // Message envoyé avec succès
            const messageElement = document.createElement('div');
            messageElement.className = `flex justify-end`;
            messageElement.innerHTML = `
                <div class="max-w-xs lg:max-w-md bg-primary-600 text-white rounded-3xl rounded-tr-none px-5 py-3 shadow-md">
                    <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                    <div class="flex items-center gap-2 mt-2 justify-end">
                        <p class="text-xs text-primary-100">
                            ${new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                        </p>
                        <span class="text-xs text-primary-400 font-semibold">✓</span>
                    </div>
                </div>
            `;

            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Réinitialiser le formulaire
            messageInput.value = '';
            messageInput.focus();
            charCount.textContent = '';
            sendBtn.disabled = false;
            sendBtn.textContent = '📤 Envoyer';

            // Afficher confirmation
            const alert = document.getElementById('alert');
            alert.className = 'mt-3 p-3 bg-green-100 text-green-700 rounded-lg';
            alert.textContent = '✓ Message envoyé avec succès';
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            sendBtn.disabled = false;
            sendBtn.textContent = '📤 Envoyer';

            // Afficher erreur
            const alert = document.getElementById('alert');
            alert.className = 'mt-3 p-3 bg-red-100 text-red-700 rounded-lg';
            alert.textContent = 'Erreur lors de l\'envoi du message';
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
        });

        // Arrêter l'indicateur de typing
        isTyping = false;
        socket.emit('typing', {
            userId: currentUserId,
            otherUserId: otherUserId,
            isTyping: false
        });
        typingIndicator.textContent = '';
        clearTimeout(typingTimeout);
    });

    // ========== RECEVOIR LES MESSAGES ==========
    socket.on('receive-message', function(data) {
        if ((data.from_user_id === currentUserId && data.to_user_id === otherUserId) ||
            (data.from_user_id === otherUserId && data.to_user_id === currentUserId)) {

            const messageElement = document.createElement('div');
            const isFromCurrentUser = data.from_user_id === currentUserId;

            messageElement.className = `flex ${isFromCurrentUser ? 'justify-end' : 'justify-start'}`;
            messageElement.innerHTML = `
                <div class="max-w-xs lg:max-w-md ${isFromCurrentUser ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-100 text-gray-900 rounded-3xl rounded-tl-none'} px-5 py-3 shadow-md">
                    <p class="text-sm leading-relaxed">${escapeHtml(data.contenu)}</p>
                    <div class="flex items-center gap-2 mt-2 ${isFromCurrentUser ? 'justify-end' : 'justify-start'}">
                        <p class="text-xs ${isFromCurrentUser ? 'text-primary-100' : 'text-gray-500'}">
                            ${data.timestamp}
                        </p>
                        ${isFromCurrentUser ? '<span class="text-xs text-primary-400 font-semibold">✓</span>' : ''}
                    </div>
                </div>
            `;

            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Marquer comme lu
            socket.emit('mark-as-read', {
                userId: currentUserId,
                otherUserId: otherUserId
            });
        }
    });

    // Scroll au fond au chargement
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Fonction pour échapper le HTML (XSS protection)
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endsection
