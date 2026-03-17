@extends('layouts.app')

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Support</div>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Support Client</h1>
                <p class="text-[13px] text-white/40 font-light mt-2">Gérez vos tickets de support</p>
            </div>
            <a href="{{ route('support.create') }}"
               class="bg-white text-[#0a0a0a] px-4 py-2 rounded-lg font-medium text-sm hover:opacity-85 transition-opacity">
                + Nouveau ticket
            </a>
        </div>
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10">
            @php
                $openTickets = $tickets->where('status', 'ouvert')->count();
                $totalTickets = $tickets->count();
            @endphp
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $openTickets }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Ouverts</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $totalTickets }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Total</div>
            </div>
        </div>
    </div>

    <div class="px-8">

    {{-- Tickets List --}}
    @if($tickets->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            @foreach($tickets as $ticket)
                <a href="{{ route('support.show', $ticket) }}"
                   class="flex items-start gap-4 px-5 py-4 border-b border-[#efefed] last:border-b-0
                          hover:bg-[#f7f7f5] transition-colors
                          {{ $ticket->status === 'ouvert' ? 'border-l-2 border-l-[#0a0a0a]' : 'border-l-2 border-l-[#e0e0dc]' }}">

                    {{-- Icon --}}
                    <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        @if($ticket->support_type === 'produit')
                            <svg class="w-5 h-5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        @elseif($ticket->support_type === 'commande')
                            <svg class="w-5 h-5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                            </svg>
                        @elseif($ticket->support_type === 'paiement')
                            <svg class="w-5 h-5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        @elseif($ticket->support_type === 'livraison')
                            <svg class="w-5 h-5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M1 18h22M1 6h22M3 10h18v8H3z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between gap-3 mb-1">
                            <span class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $ticket->subject }}</span>
                            <span class="font-mono text-[10px] text-[#a0a09a] flex-shrink-0">
                                {{ $ticket->created_at->format('d/m · H:i') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="inline-block px-2 py-0.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded text-[10px] text-[#666660] font-medium uppercase tracking-tight">
                                {{ $ticket->support_type }}
                            </span>
                            @if($ticket->priority === 'urgente')
                                <span class="inline-block px-2 py-0.5 bg-[#fee2e2] border border-[#fecaca] rounded text-[10px] text-[#dc2626] font-medium">
                                    Urgente
                                </span>
                            @elseif($ticket->priority === 'haute')
                                <span class="inline-block px-2 py-0.5 bg-[#fef3c7] border border-[#fde68a] rounded text-[10px] text-[#d97706] font-medium">
                                    Haute
                                </span>
                            @endif
                        </div>
                        <p class="text-[12px] text-[#666660] line-clamp-1 font-light">
                            {{ Str::limit($ticket->description, 100) }}
                        </p>
                    </div>

                    {{-- Status --}}
                    @if($ticket->status === 'ouvert')
                        <div class="flex-shrink-0 self-center">
                            <span class="w-5 h-5 bg-[#0a0a0a] text-white text-[10px] font-mono font-medium rounded-full flex items-center justify-center">
                                {{ $ticket->messages()->count() }}
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
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun ticket</p>
            <p class="text-[12px] text-[#a0a09a] font-light mb-4">Vos tickets de support apparaîtront ici</p>
            <a href="{{ route('support.create') }}"
               class="inline-block bg-[#0a0a0a] text-white px-4 py-2 rounded-lg font-medium text-sm hover:opacity-85 transition-opacity">
                Créer un ticket
            </a>
        </div>
    @endif

    </div>
</div>
@endsection
