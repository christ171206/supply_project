@extends('layouts.admin-layout')

@section('title', 'Documents — ' . $user->name . ' — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.users.index') }}" class="hover:text-[#0a0a0a] transition-colors">Utilisateurs</a>
    &nbsp;/&nbsp;
    <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-[#0a0a0a] transition-colors">{{ $user->name }}</a>
    &nbsp;/&nbsp; Documents
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.users.show', $user->id) }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour au profil
        </a>
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Vérification KYC</div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Documents</h1>
                <div class="flex items-center gap-3 mt-3">
                    <span class="font-mono text-[12px] text-white/50">{{ $user->name }}</span>
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                    <span class="font-mono text-[12px] text-white/50">{{ $user->email }}</span>
                </div>
            </div>

            {{-- Badge global --}}
            @php
                $pending  = $documents->get('pending',  collect())->count();
                $verified = $documents->get('verified', collect())->count();
                $rejected = $documents->get('rejected', collect())->count();
                $total    = $pending + $verified + $rejected;
                if ($total === 0)                    { $gb = ['bg-[#f7f7f5] text-[#a0a09a]','bg-[#a0a09a]','Aucun doc']; }
                elseif ($pending === 0 && $rejected === 0) { $gb = ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Tous vérifiés']; }
                elseif ($rejected > 0)               { $gb = ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Rejeté(s)']; }
                else                                 { $gb = ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente']; }
            @endphp
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium px-3 py-1.5 rounded-md {{ $gb[0] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $gb[1] }}"></span>{{ $gb[2] }}
            </span>
        </div>

        {{-- Stats inline --}}
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10">
            @foreach([
                ['v'=>$total,    'l'=>'Total'],
                ['v'=>$pending,  'l'=>'En attente'],
                ['v'=>$verified, 'l'=>'Vérifiés'],
                ['v'=>$rejected, 'l'=>'Rejetés'],
            ] as $i => $s)
                @if($i>0)<div class="w-px h-8 bg-white/10"></div>@endif
                <div>
                    <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $s['v'] }}</div>
                    <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">{{ $s['l'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="px-8 space-y-5">

    @if($documents->isEmpty())
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun document soumis</p>
            <p class="text-[12px] text-[#a0a09a] font-light">Ce vendeur n'a pas encore soumis de documents</p>
        </div>
    @else

        {{-- EN ATTENTE --}}
        @if($pending > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-[#efefed] bg-[#fdf6ec]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>
                    <span class="text-[13px] font-medium text-[#b45309]">En attente ({{ $pending }})</span>
                </div>
                @foreach($documents->get('pending', []) as $doc)
                    <div class="flex items-start justify-between px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex-1 min-w-0 mr-4">
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                            <div class="font-mono text-[11px] text-[#a0a09a] mt-1">{{ $doc->created_at->format('d/m/Y · H:i') }}</div>
                            @if($doc->file_path)
                                <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">{{ basename($doc->file_path) }}</div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($doc->file_path)
                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>
                            @endif
                            <form method="POST" action="{{ route('admin.users.approve-document', $doc->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                               hover:bg-[#f0fdf4] transition-all">
                                    Approuver
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject-document', $doc->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                               hover:bg-[#fef2f2] transition-all">
                                    Rejeter
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- VÉRIFIÉS --}}
        @if($verified > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-[#efefed] bg-[#f0fdf4]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                    <span class="text-[13px] font-medium text-[#15803d]">Vérifiés ({{ $verified }})</span>
                </div>
                @foreach($documents->get('verified', []) as $doc)
                    <div class="flex items-start justify-between px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex-1 min-w-0 mr-4">
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                            <div class="font-mono text-[11px] text-[#a0a09a] mt-1">{{ $doc->created_at->format('d/m/Y · H:i') }}</div>
                            @if($doc->verified_at)
                                <div class="flex items-center gap-1.5 mt-1">
                                    <svg class="w-3 h-3 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span class="text-[11px] text-[#15803d] font-light">Vérifié le {{ $doc->verified_at->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                        @if($doc->file_path)
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                               class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                      hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all flex-shrink-0">
                                Voir
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- REJETÉS --}}
        @if($rejected > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-[#efefed] bg-[#fef2f2]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>
                    <span class="text-[13px] font-medium text-[#dc2626]">Rejetés ({{ $rejected }})</span>
                </div>
                @foreach($documents->get('rejected', []) as $doc)
                    <div class="flex items-start justify-between px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex-1 min-w-0 mr-4">
                            <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                            <div class="font-mono text-[11px] text-[#a0a09a] mt-1">{{ $doc->created_at->format('d/m/Y · H:i') }}</div>
                            @if($doc->rejection_reason)
                                <div class="mt-2 px-3 py-2 bg-[#fef2f2] border border-[#fecaca] rounded-lg">
                                    <span class="text-[11px] text-[#dc2626] font-light">{{ $doc->rejection_reason }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($doc->file_path)
                                <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>
                            @endif
                            <form method="POST" action="{{ route('admin.users.approve-document', $doc->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                               hover:bg-[#f0fdf4] transition-all">
                                    Approuver
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @endif
    </div>
</div>
@endsection
