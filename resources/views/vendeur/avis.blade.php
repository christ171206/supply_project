@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Vendeur</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Avis clients</h1>
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10 flex-wrap">
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ number_format($noteMoyenne, 1) }}<span class="text-[14px] text-white/40 font-sans font-light">/5</span></div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Note moyenne</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $nombreAvis }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Total avis</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $avisParNote[5] ?? 0 }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">5 étoiles</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium {{ (($avisParNote[1] ?? 0) + ($avisParNote[2] ?? 0)) > 0 ? 'text-[#f87171]' : 'text-white' }} leading-none">
                    {{ ($avisParNote[1] ?? 0) + ($avisParNote[2] ?? 0) }}
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Critiques</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Répartition des notes --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-5">
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Répartition</div>
        <div class="space-y-2.5">
            @for($i = 5; $i >= 1; $i--)
                @php
                    $count = $avisParNote[$i] ?? 0;
                    $pct   = $nombreAvis > 0 ? ($count / $nombreAvis * 100) : 0;
                    $bar   = $i >= 4 ? '#22c55e' : ($i >= 3 ? '#f59e0b' : '#f87171');
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-6 font-mono text-[11px] text-[#a0a09a] flex-shrink-0 text-right">{{ $i }}</span>
                    <svg class="w-3 h-3 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <div class="flex-1 h-2 bg-[#f7f7f5] rounded-full overflow-hidden border border-[#e0e0dc]">
                        <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%; background:{{ $bar }};"></div>
                    </div>
                    <span class="w-6 font-mono text-[11px] text-[#0a0a0a] flex-shrink-0">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- Filtre --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-center gap-4 flex-wrap">
        <div class="flex-1 min-w-[180px]">
            <input type="text" id="search-avis" placeholder="Rechercher dans les avis…"
                   onkeyup="filterAvis()"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <select id="filter-note" onchange="filterAvis()"
                class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                       focus:bg-white focus:border-[#0a0a0a] outline-none transition-all w-44">
            <option value="">Toutes les notes</option>
            <option value="5">5 étoiles</option>
            <option value="4">4 étoiles</option>
            <option value="3">3 étoiles</option>
            <option value="2">2 étoiles</option>
            <option value="1">1 étoile</option>
        </select>
    </div>

    {{-- Liste avis --}}
    @if($avisComplets->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden" id="avis-list">
            @foreach($avisComplets as $avis)
                @php
                    $note = $avis->note;
                    $dotColor = $note >= 4 ? 'bg-[#22c55e]' : ($note >= 3 ? 'bg-[#f59e0b]' : 'bg-[#f87171]');
                @endphp
                <div class="avis-item border-b border-[#efefed] last:border-b-0 px-5 py-5 hover:bg-[#f7f7f5] transition-colors"
                     data-note="{{ $note }}"
                     data-text="{{ strtolower($avis->commentaire . ' ' . $avis->user->name . ' ' . $avis->produit->nom) }}">

                    {{-- Row: avatar + nom + note + date --}}
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#0a0a0a] rounded-md flex items-center justify-center text-white
                                        text-[11px] font-medium flex-shrink-0">
                                {{ strtoupper(substr($avis->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $avis->user->name }}</div>
                                <div class="font-mono text-[11px] text-[#a0a09a]">{{ $avis->created_at->locale('fr')->diffForHumans() }}</div>
                            </div>
                        </div>

                        {{-- Note --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <div class="flex items-center gap-0.5">
                                @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-3.5 h-3.5 {{ $s <= $note ? 'text-[#f59e0b]' : 'text-[#e0e0dc]' }}"
                                         viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="inline-flex items-center gap-1.5 font-mono text-[10px] font-medium px-2 py-1 rounded
                                         {{ $note >= 4 ? 'bg-[#f0fdf4] text-[#15803d]' : ($note >= 3 ? 'bg-[#fdf6ec] text-[#b45309]' : 'bg-[#fef2f2] text-[#dc2626]') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>{{ $note }}/5
                            </span>
                        </div>
                    </div>

                    {{-- Produit --}}
                    <div class="flex items-center gap-1.5 mb-2.5">
                        <svg class="w-3 h-3 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        <span class="text-[11px] text-[#666660]">{{ $avis->produit->nom }}</span>
                    </div>

                    {{-- Commentaire --}}
                    <p class="text-[13px] text-[#2a2a28] font-light leading-relaxed mb-3">{{ $avis->commentaire }}</p>

                    {{-- Action --}}
                    <div class="pt-3 border-t border-[#efefed]">
                        <a href="{{ route('produits.show', $avis->produit->id) }}"
                           class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all inline-flex items-center gap-1.5">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            Voir le produit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($avisComplets->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $avisComplets->firstItem() }}–{{ $avisComplets->lastItem() }} / {{ $avisComplets->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($avisComplets->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $avisComplets->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif
                    @foreach($avisComplets->getUrlRange(max(1,$avisComplets->currentPage()-2),min($avisComplets->lastPage(),$avisComplets->currentPage()+2)) as $page => $url)
                        @if($page == $avisComplets->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($avisComplets->hasMorePages())
                        <a href="{{ $avisComplets->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                    @endif
                </div>
            </div>
        @endif

    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Pas encore d'avis</p>
            <p class="text-[12px] text-[#a0a09a] font-light">Vos clients n'ont pas encore laissé d'avis sur vos produits</p>
        </div>
    @endif

    </div>
</div>

@section('scripts')
<script>
function filterAvis() {
    const search = document.getElementById('search-avis').value.toLowerCase();
    const note   = document.getElementById('filter-note').value;
    document.querySelectorAll('.avis-item').forEach(item => {
        const matchText = item.dataset.text.includes(search);
        const matchNote = note === '' || item.dataset.note === note;
        item.style.display = (matchText && matchNote) ? '' : 'none';
    });
}
</script>
@endsection

@endsection
