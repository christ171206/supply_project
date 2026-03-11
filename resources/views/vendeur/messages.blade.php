@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Vendeur</div>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Messages</h1>
                <p class="text-[13px] text-white/40 font-light mt-2">Communication avec vos clients</p>
            </div>
        </div>
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10">
            @php
                $unreadConversations = $conversations->filter(fn($c) => $c['unread_count'] > 0)->count();
            @endphp
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $unreadConversations }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Non lues</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $conversations->count() }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Conversations</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $messagesTotal }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Messages total</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Flash --}}
    @if($msg = Session::get('success'))
        <div class="flex items-center gap-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl px-4 py-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e] flex-shrink-0"></span>
            <span class="text-[12px] text-[#15803d]">{{ $msg }}</span>
        </div>
    @endif
    @if($msg = Session::get('error'))
        <div class="flex items-center gap-3 bg-[#fef2f2] border border-[#fecaca] rounded-xl px-4 py-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#f87171] flex-shrink-0"></span>
            <span class="text-[12px] text-[#dc2626]">{{ $msg }}</span>
        </div>
    @endif

    {{-- Filtre --}}
    <form method="GET" class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-center gap-4">
        <select name="filtre"
                class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                       focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
            <option value="tous"    {{ request('filtre','tous') == 'tous'    ? 'selected' : '' }}>Toutes les conversations</option>
            <option value="non_lus" {{ request('filtre')        == 'non_lus' ? 'selected' : '' }}>Non lues uniquement</option>
        </select>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
    </form>

    {{-- Liste conversations --}}
    @if($conversations->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            @foreach($conversations as $i => $conv)
                <a href="{{ route('vendeur.messages.show', $conv['other_user']->id) }}"
                   class="flex items-start gap-4 px-5 py-4 border-b border-[#efefed] last:border-b-0
                          hover:bg-[#f7f7f5] transition-colors
                          {{ $conv['unread_count'] > 0 ? 'border-l-2 border-l-[#0a0a0a]' : '' }}">

                    {{-- Avatar --}}
                    <div class="w-9 h-9 bg-[#0a0a0a] rounded-md flex items-center justify-center text-white
                                text-[11px] font-medium flex-shrink-0 mt-0.5">
                        {{ strtoupper(substr($conv['other_user']->name, 0, 1)) }}
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between gap-3 mb-1">
                            <span class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $conv['other_user']->name }}</span>
                            @if($conv['last_message'])
                                <span class="font-mono text-[10px] text-[#a0a09a] flex-shrink-0">
                                    {{ $conv['last_message']->created_at->format('d/m · H:i') }}
                                </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-[#a0a09a] font-light truncate mb-0.5">{{ $conv['other_user']->email }}</div>

                        @if($conv['produit'])
                            <div class="inline-flex items-center gap-1 mt-1.5 mb-1.5">
                                <svg class="w-3 h-3 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                                <span class="text-[11px] text-[#666660] truncate max-w-[200px]">{{ $conv['produit']->nom }}</span>
                            </div>
                        @endif

                        @if($conv['last_message'])
                            <p class="text-[12px] text-[#666660] line-clamp-1 font-light">
                                @if($conv['last_message']->from_user_id === auth()->id())
                                    <span class="text-[#a0a09a]">Vous · </span>
                                @endif
                                {{ Str::limit($conv['last_message']->contenu, 80) }}
                            </p>
                        @endif
                    </div>

                    {{-- Badge non-lu --}}
                    @if($conv['unread_count'] > 0)
                        <div class="flex-shrink-0 self-center">
                            <span class="w-5 h-5 bg-[#0a0a0a] text-white text-[10px] font-mono font-medium
                                         rounded-full flex items-center justify-center">
                                {{ $conv['unread_count'] }}
                            </span>
                        </div>
                    @else
                        <div class="flex-shrink-0 self-center">
                            <svg class="w-3.5 h-3.5 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucune conversation</p>
            <p class="text-[12px] text-[#a0a09a] font-light">Les messages de vos clients apparaîtront ici</p>
        </div>
    @endif

    </div>
</div>
@endsection
