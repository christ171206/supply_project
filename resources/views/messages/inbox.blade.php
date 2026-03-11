@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] rounded-xl px-8 pt-10 pb-8 mb-6">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Messagerie</div>

        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none mb-2">
                    Mes Messages
                </h1>
                <div class="flex items-center gap-3 mt-3">
                    <span class="font-mono text-[12px] text-white/50">{{ $conversations->count() }} conversation{{ $conversations->count() !== 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="mt-5 pt-5 border-t border-white/10 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#22c55e] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[12px] text-white/60 font-light">{{ session('success') }}</span>
            </div>
        @endif
    </div>

    {{-- Retour --}}
    <a href="{{ route('accueil') }}"
       class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-6">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Accueil
    </a>

    <div class="grid grid-cols-[280px_1fr] gap-5">

        {{-- ══ SIDEBAR - CONVERSATIONS ══ --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden sticky top-24 h-fit">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Conversations</span>
            </div>
            
            <div class="max-h-[600px] overflow-y-auto">
                @forelse($conversations as $message)
                    @php
                        $otherUser = $message->from_user_id === auth()->id() ? $message->toUser : $message->fromUser;
                        $isActive = request()->route('userId') == $otherUser->id;
                    @endphp

                    <a href="{{ route('messages.show', $otherUser->id) }}"
                       class="flex items-center gap-3 px-4 py-3 border-b border-[#efefed] last:border-b-0 {{ $isActive ? 'bg-[#f7f7f5]' : 'hover:bg-[#f7f7f5]' }} transition-colors">
                        <div class="w-9 h-9 bg-[#0a0a0a] text-white rounded-full flex items-center justify-center flex-shrink-0 text-[11px] font-bold">
                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[12px] font-medium text-[#0a0a0a] truncate">{{ $otherUser->name }}</p>
                            <p class="text-[10px] text-[#a0a09a] mt-0.5">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                        @if($message->from_user_id !== auth()->id() && !$message->lu)
                            <span class="w-2 h-2 bg-[#0a0a0a] rounded-full flex-shrink-0"></span>
                        @endif
                    </a>
                @empty
                    <div class="px-5 py-8 text-center">
                        <svg class="w-10 h-10 mx-auto text-[#e0e0dc] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <p class="text-[11px] font-medium text-[#a0a09a]">Aucune conversation</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ══ CONTENU PRINCIPAL ══ --}}
        <div>
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

                <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden flex flex-col h-[600px]">
                    {{-- Header conversation --}}
                    <div class="px-5 py-4 border-b border-[#efefed] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0a0a0a] text-white rounded-full flex items-center justify-center text-[11px] font-bold">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[12px] font-medium text-[#0a0a0a]">{{ $otherUser->name }}</p>
                                <p class="text-[10px] text-[#a0a09a] mt-0.5">{{ $otherUser->shop_name ?? 'Client' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('client.messages') }}" class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors font-medium">
                            ← Retour
                        </a>
                    </div>

                    {{-- Messages Container --}}
                    <div id="messages-container" class="flex-1 overflow-y-auto p-5 space-y-3 bg-[#f7f7f5]">
                        @forelse($messages as $msg)
                            <div class="flex {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md {{ $msg->from_user_id === auth()->id() ? 'bg-[#0a0a0a] text-white rounded-2xl rounded-tr-none' : 'bg-white text-[#0a0a0a] border border-[#e0e0dc] rounded-2xl rounded-tl-none' }} px-4 py-2.5 shadow-sm">
                                    <p class="text-[12px] leading-relaxed">{{ $msg->contenu }}</p>
                                    <div class="flex items-center gap-1.5 mt-1.5 {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <p class="text-[10px] {{ $msg->from_user_id === auth()->id() ? 'text-white/60' : 'text-[#a0a09a]' }}">
                                            {{ $msg->created_at->format('H:i') }}
                                        </p>
                                        @if($msg->from_user_id === auth()->id())
                                            <span class="text-[10px] font-semibold {{ $msg->lu ? 'text-white/80' : 'text-white/50' }}">
                                                {{ $msg->lu ? '✓✓' : '✓' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-full text-center">
                                <div>
                                    <svg class="w-12 h-12 mx-auto text-[#e0e0dc] mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                    </svg>
                                    <p class="text-[11px] font-medium text-[#a0a09a]">Aucun message</p>
                                    <p class="text-[10px] text-[#a0a09a] mt-1">Commencez la conversation ci-dessous</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Formulaire d'envoi --}}
                    <div class="border-t border-[#efefed] p-4 bg-white">
                        <form action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <textarea
                                name="contenu"
                                placeholder="Écrivez votre message…"
                                required
                                class="flex-1 px-3 py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] transition text-[12px] text-[#0a0a0a] placeholder-[#a0a09a] resize-none"
                                rows="2"
                            ></textarea>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-[#0a0a0a] text-white font-medium rounded-lg hover:bg-[#2a2a28] transition text-[12px] whitespace-nowrap self-end"
                            >
                                Envoyer
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white border border-[#e0e0dc] rounded-xl p-12 text-center h-[600px] flex items-center justify-center">
                    <div>
                        <svg class="w-16 h-16 mx-auto text-[#e0e0dc] mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-1">Sélectionnez une conversation</h3>
                        <p class="text-[12px] text-[#a0a09a]">Choisissez une conversation à gauche pour commencer</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
</div>
@endsection
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
                    <div class="bg-white border border-[#e0e0dc] rounded-xl p-12 text-center h-[600px] flex items-center justify-center">
                        <div>
                            <svg class="w-16 h-16 mx-auto text-[#e0e0dc] mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <h3 class="text-[14px] font-medium text-[#0a0a0a] mb-1">Sélectionnez une conversation</h3>
                            <p class="text-[12px] text-[#a0a09a]">Choisissez une conversation à gauche pour commencer</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
    </div>
    @endsection