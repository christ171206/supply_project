@extends('layouts.app')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- En-tête avec retour -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('support.index') }}" class="inline-flex items-center gap-2 text-[#0a0a0a] hover:text-[#666660] font-medium mb-4 text-sm">
                ← Retour
            </a>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-[#0a0a0a] rounded-lg flex items-center justify-center text-white font-bold text-2xl">
                    #{{ $ticket->id }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[#0a0a0a]" style="font-family: 'Instrument Serif', serif;">{{ $ticket->subject }}</h1>
                    <p class="text-[#a0a09a] text-sm">{{ ucfirst(str_replace('_', ' ', $ticket->support_type)) }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($ticket->status === 'ouvert')
                <form method="POST" action="{{ route('support.close', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 text-[12px] font-medium border border-[#e0e0dc] text-[#dc2626] hover:bg-[#fee2e2] transition rounded-lg">
                        Fermer
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('support.reopen', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 text-[12px] font-medium border border-[#e0e0dc] text-[#0a0a0a] hover:bg-[#f7f7f5] transition rounded-lg">
                        Réouvrir
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Ticket Info Strip -->
    <div class="bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc] mb-8">
        <div class="grid grid-cols-5 gap-8">
            <div>
                <p class="text-xs text-[#a0a09a] font-semibold uppercase mb-1">Statut</p>
                <p class="text-sm font-bold text-[#0a0a0a]">
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium
                        {{ $ticket->status === 'ouvert' ? 'bg-[#fef3c7] text-[#92400e]' : 'bg-[#dbeafe] text-[#1e40af]' }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-xs text-[#a0a09a] font-semibold uppercase mb-1">Priorité</p>
                <p class="text-sm font-bold text-[#0a0a0a]">{{ ucfirst($ticket->priority) }}</p>
            </div>
            <div>
                <p class="text-xs text-[#a0a09a] font-semibold uppercase mb-1">Contact</p>
                <p class="text-sm font-bold text-[#0a0a0a]">{{ ucfirst($ticket->contact_method) }}</p>
            </div>
            <div>
                <p class="text-xs text-[#a0a09a] font-semibold uppercase mb-1">Créé</p>
                <p class="text-sm font-bold text-[#0a0a0a]">{{ $ticket->created_at->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-[#a0a09a] font-semibold uppercase mb-1">Messages</p>
                <p class="text-sm font-bold text-[#0a0a0a]">{{ $ticket->messages()->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Zone de conversation -->
    <div class="bg-white rounded-lg border border-[#e0e0dc] overflow-hidden mb-8">
        <!-- Messages -->
        <div class="h-[600px] overflow-y-auto p-6 space-y-4 bg-white" id="messagesContainer">
            @forelse($messages as $message)
                <div class="flex {{ auth()->id() === $message->user_id ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md">
                        <div class="flex gap-2 {{ auth()->id() === $message->user_id ? 'flex-row-reverse' : '' }}">
                            <!-- Avatar -->
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ auth()->id() === $message->user_id ? 'bg-[#0a0a0a]' : 'bg-[#e0e0dc]' }} text-[11px] font-bold {{ auth()->id() === $message->user_id ? 'text-white' : 'text-[#0a0a0a]' }}">
                                {{ strtoupper(substr($message->user->name, 0, 1)) }}
                            </div>

                            <!-- Bulle de message -->
                            <div class="flex-1">
                                <div class="{{ auth()->id() === $message->user_id ? 'bg-[#0a0a0a] text-white rounded-2xl rounded-tr-none' : 'bg-[#f7f7f5] text-[#0a0a0a] rounded-2xl rounded-tl-none' }} p-4">
                                    <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                                    <p class="text-xs mt-2 {{ auth()->id() === $message->user_id ? 'text-white/60' : 'text-[#a0a09a]' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-[#a0a09a] mx-auto mb-3 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <p class="text-[#a0a09a] text-sm">Aucun message encore. Commencez une conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Formulaire d'envoi -->
        <div class="border-t border-[#e0e0dc] p-6 bg-white">
            @if($ticket->status === 'ouvert')
                <form action="{{ route('support.add-message', $ticket) }}" method="POST" class="flex gap-3">
                    @csrf
                    <textarea
                        name="message"
                        placeholder="Écrivez votre message..."
                        class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] resize-none text-[#0a0a0a] text-sm placeholder-[#a0a09a] focus:outline-none"
                        rows="3"
                        required
                        maxlength="2000"
                        style="font-family: 'Geist', -apple-system, BlinkMacSystemFont, sans-serif;"
                    ></textarea>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-semibold whitespace-nowrap h-fit text-sm"
                    >
                        Envoyer
                    </button>
                </form>
                @error('message')
                    <p class="text-[#dc2626] text-sm mt-2">{{ $message }}</p>
                @enderror
            @else
                <div class="text-center py-6">
                    <p class="text-[#a0a09a] text-sm mb-4">Ce ticket est fermé</p>
                    <form method="POST" action="{{ route('support.reopen', $ticket) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-[#0a0a0a] text-white text-sm font-medium rounded-lg hover:opacity-85 transition">
                            Réouvrir le ticket
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Infos du ticket -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc]">
            <h3 class="text-sm font-bold text-[#0a0a0a] mb-4 uppercase tracking-[0.05em]">Détails</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Type</p>
                    <p class="text-[#0a0a0a] text-sm">{{ ucfirst(str_replace('_', ' ', $ticket->support_type)) }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Description</p>
                    <p class="text-[#0a0a0a] text-sm leading-relaxed">{{ $ticket->description }}</p>
                </div>
                @if($ticket->contact_method === 'whatsapp' && $ticket->whatsapp_number)
                    <div>
                        <p class="text-xs text-[#a0a09a] font-semibold">WhatsApp</p>
                        <p class="text-[#0a0a0a] text-sm">{{ $ticket->whatsapp_number }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc]">
            <h3 class="text-sm font-bold text-[#0a0a0a] mb-4 uppercase tracking-[0.05em]">Chronologie</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Créé</p>
                    <p class="text-[#0a0a0a]">{{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                @if($ticket->response_at)
                    <div>
                        <p class="text-xs text-[#a0a09a] font-semibold">Première réponse</p>
                        <p class="text-[#0a0a0a]">{{ $ticket->response_at->format('d/m/Y à H:i') }}</p>
                    </div>
                @endif
                @if($ticket->resolved_at)
                    <div>
                        <p class="text-xs text-[#a0a09a] font-semibold">Résolu</p>
                        <p class="text-[#0a0a0a]">{{ $ticket->resolved_at->format('d/m/Y à H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-scroll vers le bas
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection
