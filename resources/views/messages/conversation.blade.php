@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5] py-8">
    <div class="max-w-5xl mx-auto px-4 h-[calc(100vh-8rem)]">
        <div class="bg-white rounded-2xl border border-[#e0e0dc] h-full flex flex-col overflow-hidden">

            <!-- Header Conversation avec Indicateurs -->
            <div class="border-b border-[#e0e0dc] p-6 bg-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-[#0a0a0a] rounded-full flex items-center justify-center text-white font-bold text-xl relative">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            <!-- Indicateur de présence (point vert/gris) -->
                            <span id="vendor-presence" class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-[#a0a09a] border-2 border-white animate-pulse" title="Chargement du statut..."></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-bold text-[#0a0a0a]" style="font-family: 'Instrument Serif', serif;">{{ $otherUser->name }}</h1>
                            </div>
                            <p class="text-sm text-[#a0a09a]">{{ $otherUser->shop_name ?? 'Client' }}</p>
                            <!-- Indicateur "En train d'écrire..." -->
                            <p id="typing-indicator" class="text-xs text-[#666660] italic font-semibold mt-1 min-h-4 transition-all"></p>
                        </div>
                    </div>
                    <a href="{{ route('client.messages') }}" class="px-4 py-2 text-[#a0a09a] hover:text-[#0a0a0a] hover:bg-[#f7f7f5] rounded-lg font-semibold transition">
                        ← Retour
                    </a>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-white">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $msg->id }}">
                        <div class="block space-y-2">
                            <!-- Produit associé (si existe) -->
                            @if($msg->produit)
                                <div class="max-w-xs lg:max-w-md bg-[#f7f7f5] border border-[#e0e0dc] rounded-xl p-4">
                                    <div class="flex gap-3">
                                        @if($msg->produit->images && is_array($msg->produit->images) && count($msg->produit->images) > 0)
                                            <img src="{{ asset('storage/' . $msg->produit->images[0]) }}" alt="{{ $msg->produit->nom }}" class="w-20 h-20 object-cover rounded-lg border border-[#e0e0dc]">
                                        @elseif($msg->produit->image)
                                            <img src="{{ asset('storage/produits/' . $msg->produit->image) }}" alt="{{ $msg->produit->nom }}" class="w-20 h-20 object-cover rounded-lg border border-[#e0e0dc]">
                                        @else
                                            <div class="w-20 h-20 bg-[#e0e0dc] rounded-lg flex items-center justify-center text-[#a0a09a]">
                                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] text-[#a0a09a] font-medium uppercase tracking-[0.05em]">Produit</p>
                                            <p class="text-sm font-bold text-[#0a0a0a] line-clamp-2">{{ $msg->produit->nom }}</p>
                                            <p class="text-[13px] font-mono font-bold text-[#0a0a0a] mt-1">{{ number_format($msg->produit->prix, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Message text -->
                            <div class="max-w-xs lg:max-w-md {{ $msg->from_user_id === auth()->id() ? 'bg-[#0a0a0a] text-white rounded-2xl rounded-tr-none' : 'bg-[#f7f7f5] text-[#0a0a0a] rounded-2xl rounded-tl-none border border-[#e0e0dc]' }} px-5 py-3">
                                <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                                <div class="flex items-center gap-2 mt-2 {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <p class="text-xs {{ $msg->from_user_id === auth()->id() ? 'text-white/60' : 'text-[#a0a09a]' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </p>
                                    <!-- Coches de confirmation de lecture -->
                                    @if($msg->from_user_id === auth()->id())
                                        <span class="text-xs {{ $msg->lu ? 'text-white/80' : 'text-white/50' }} font-semibold" title="{{ $msg->lu ? 'Lu' : 'Non lu' }}">
                                            {{ $msg->lu ? '✓✓' : '✓' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-center">
                        <div>
                            <svg class="w-16 h-16 mx-auto text-[#e0e0dc] mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <p class="font-semibold text-lg text-[#0a0a0a]">Aucun message</p>
                            <p class="text-sm mt-2 text-[#a0a09a]">Commencez la conversation ci-dessous</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Formulaire d'envoi avec validation -->
            <div class="border-t border-[#e0e0dc] p-6 bg-white">
                <form id="message-form" action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex gap-3 items-end">
                        <textarea
                            id="message-input"
                            name="contenu"
                            placeholder="Écrivez votre message (minimum 1 caractère)..."
                            rows="2"
                            minlength="1"
                            required
                            class="flex-1 px-4 py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] transition text-[12px] text-[#0a0a0a] placeholder-[#a0a09a] resize-none"
                        ></textarea>
                        <button
                            type="submit"
                            id="send-btn"
                            class="px-4 py-2 bg-[#0a0a0a] text-white font-medium rounded-lg hover:bg-[#2a2a28] transition text-[12px] whitespace-nowrap h-fit"
                        >
                            📤 Envoyer
                        </button>
                    </div>
                    <p id="char-count" class="text-xs text-[#a0a09a] text-right"></p>
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
            userId: {{ auth()->id() }},
            token: '{{ csrf_token() }}'
        }
    });

    // Éléments DOM
    const currentUserId = {{ auth()->id() }};
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
            presenceIndicator.style.backgroundColor = '#15803d';
            presenceIndicator.title = '✓ En ligne maintenant';
            presenceIndicator.classList.remove('animate-pulse');
        }
    });

    socket.on('user-joined', function(data) {
        if (data.otherUserId === otherUserId && presenceIndicator) {
            presenceIndicator.style.backgroundColor = '#15803d';
            presenceIndicator.title = '✓ En ligne maintenant';
            presenceIndicator.classList.remove('animate-pulse');
        }
    });

    socket.on('disconnect', function() {
        if (presenceIndicator) {
            presenceIndicator.style.backgroundColor = '#a0a09a';
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
                <div class="max-w-xs lg:max-w-md bg-[#0a0a0a] text-white rounded-2xl rounded-tr-none px-5 py-3">
                    <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                    <div class="flex items-center gap-2 mt-2 justify-end">
                        <p class="text-xs text-white/60">
                            ${new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                        </p>
                        <span class="text-xs text-white/50 font-semibold">✓</span>
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
            alert.className = 'mt-3 p-3 bg-[#f0fdf4] text-[#15803d] rounded-lg border border-[#bbf7d0]';
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
            alert.className = 'mt-3 p-3 bg-[#fef2f2] text-[#dc2626] rounded-lg border border-[#fecaca]';
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
