@extends('layouts.admin-layout')

@section('title', 'Litiges — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Litiges
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Administration</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Litiges</h1>
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            @foreach([
                ['v' => $openCount       ?? 0, 'l' => 'Ouverts',   'c' => 'text-[#f87171]'],
                ['v' => $inProgressCount ?? 0, 'l' => 'En cours',  'c' => 'text-[#fbbf24]'],
                ['v' => $resolvedCount   ?? 0, 'l' => 'Résolus',   'c' => 'text-white'],
                ['v' => number_format($totalAmount ?? 0, 0, ',', ' '), 'l' => 'Montant total', 'c' => 'text-white', 'u' => 'FCFA'],
            ] as $i => $k)
                @if($i > 0)<div class="w-px h-8 bg-white/10"></div>@endif
                <div>
                    <div class="font-mono text-[22px] font-medium {{ $k['c'] }} leading-none">
                        {{ $k['v'] }}@isset($k['u'])<span class="text-[11px] text-white/35 font-sans font-light ml-0.5">{{ $k['u'] }}</span>@endisset
                    </div>
                    <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">{{ $k['l'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="w-44">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Statut</label>
            <select name="status"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="open"        @selected(request('status') === 'open')>Ouvert</option>
                <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
                <option value="resolved"    @selected(request('status') === 'resolved')>Résolu</option>
                <option value="closed"      @selected(request('status') === 'closed')>Fermé</option>
            </select>
        </div>
        <div class="flex-1 min-w-[180px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Rechercher</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, demandeur…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('status') || request('search'))
            <a href="{{ route('admin.disputes.index') }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Liste --}}
    @if($disputes->isEmpty())
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun litige</p>
            <p class="text-[12px] text-[#a0a09a] font-light">Tous les litiges ont été résolus</p>
        </div>
    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            @foreach($disputes as $dispute)
                @php
                    $b = match($dispute->status ?? 'open') {
                        'open'        => ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Ouvert'],
                        'in_progress' => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En cours'],
                        'resolved'    => ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Résolu'],
                        default       => ['bg-[#f7f7f5] text-[#a0a09a]','bg-[#a0a09a]','Fermé'],
                    };
                @endphp
                <div class="border-b border-[#efefed] last:border-b-0 px-5 py-5 hover:bg-[#f7f7f5] transition-colors">

                    {{-- Row haut --}}
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $b[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $b[1] }}"></span>{{ $b[2] }}
                                </span>
                                <span class="font-mono text-[10px] text-[#a0a09a]">#{{ $dispute->id }}</span>
                            </div>
                            <div class="text-[14px] font-medium text-[#0a0a0a] truncate">
                                {{ $dispute->subject ?? 'Litige #' . $dispute->id }}
                            </div>
                            @if($dispute->description)
                                <p class="text-[12px] text-[#666660] font-light mt-1 line-clamp-1">{{ $dispute->description }}</p>
                            @endif
                        </div>
                        <a href="{{ route('admin.disputes.show', $dispute->id) }}"
                           class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all flex-shrink-0">
                            Voir →
                        </a>
                    </div>

                    {{-- Méta --}}
                    <div class="flex items-center gap-6 border-t border-[#efefed] pt-3.5">
                        <div>
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Demandeur</div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">{{ $dispute->requester->name ?? '—' }}</div>
                        </div>
                        <div class="w-px h-6 bg-[#e0e0dc]"></div>
                        <div>
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Mis en cause</div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">{{ $dispute->respondent->name ?? '—' }}</div>
                        </div>
                        <div class="w-px h-6 bg-[#e0e0dc]"></div>
                        <div>
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Montant</div>
                            <div class="font-mono text-[12px] font-medium text-[#0a0a0a]">
                                {{ number_format($dispute->resolution_amount ?? 0, 0, ',', ' ') }}
                                <span class="text-[10px] text-[#a0a09a] font-sans">FCFA</span>
                            </div>
                        </div>
                        <div class="w-px h-6 bg-[#e0e0dc]"></div>
                        <div>
                            <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-0.5">Créé</div>
                            <div class="font-mono text-[11px] text-[#a0a09a]">{{ $dispute->created_at->format('d/m/Y') }}</div>
                        </div>

                        {{-- Actions rapides --}}
                        @if($dispute->status === 'open')
                            <div class="ml-auto">
                                <form method="POST" action="{{ route('admin.disputes.update-status', $dispute->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit"
                                            class="text-[11px] font-medium text-[#b45309] border border-[#fde68a] px-2.5 py-1.5 rounded-lg
                                                   hover:bg-[#fdf6ec] transition-all">
                                        Prendre en charge
                                    </button>
                                </form>
                            </div>
                        @elseif($dispute->status === 'in_progress')
                            <div class="ml-auto flex items-center gap-1.5">
                                <form method="POST" action="{{ route('admin.disputes.resolve', $dispute->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                                   hover:bg-[#f0fdf4] transition-all">
                                        Résoudre
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.disputes.close', $dispute->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                                   hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                        Fermer
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($disputes->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $disputes->firstItem() }}–{{ $disputes->lastItem() }} / {{ $disputes->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($disputes->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $disputes->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif
                    @foreach($disputes->getUrlRange(max(1,$disputes->currentPage()-2),min($disputes->lastPage(),$disputes->currentPage()+2)) as $page => $url)
                        @if($page == $disputes->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($disputes->hasMorePages())
                        <a href="{{ $disputes->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                    @endif
                </div>
            </div>
        @endif
    @endif

    </div>
</div>
@endsection
