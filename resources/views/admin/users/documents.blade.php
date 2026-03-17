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
            @php
                $pending  = $documents->get('pending',  collect())->count();
                $verified = $documents->get('verified', collect())->count();
                $rejected = $documents->get('rejected', collect())->count();
                $total    = $pending + $verified + $rejected;
                if ($total === 0)                          { $gb = ['bg-[#f7f7f5] text-[#a0a09a]','bg-[#a0a09a]','Aucun doc']; }
                elseif ($pending === 0 && $rejected === 0) { $gb = ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Tous vérifiés']; }
                elseif ($rejected > 0)                    { $gb = ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Rejeté(s)']; }
                else                                      { $gb = ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente']; }
            @endphp
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium px-3 py-1.5 rounded-md {{ $gb[0] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $gb[1] }}"></span>{{ $gb[2] }}
            </span>
        </div>
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10">
            @foreach([['v'=>$total,'l'=>'Total'],['v'=>$pending,'l'=>'En attente'],['v'=>$verified,'l'=>'Vérifiés'],['v'=>$rejected,'l'=>'Rejetés']] as $i => $s)
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

    @php
    $docSections = [
        ['key'=>'pending',  'label'=>'En attente', 'count'=>$pending,  'header'=>'bg-[#fdf6ec]', 'dot'=>'bg-[#f59e0b]', 'text'=>'text-[#b45309]'],
        ['key'=>'verified', 'label'=>'Vérifiés',   'count'=>$verified, 'header'=>'bg-[#f0fdf4]', 'dot'=>'bg-[#22c55e]', 'text'=>'text-[#15803d]'],
        ['key'=>'rejected', 'label'=>'Rejetés',    'count'=>$rejected, 'header'=>'bg-[#fef2f2]', 'dot'=>'bg-[#f87171]', 'text'=>'text-[#dc2626]'],
    ];
    @endphp

    @foreach($docSections as $section)
    @if($section['count'] > 0)
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-[#efefed] {{ $section['header'] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $section['dot'] }}"></span>
            <span class="text-[13px] font-medium {{ $section['text'] }}">{{ $section['label'] }} ({{ $section['count'] }})</span>
        </div>
        @foreach($documents->get($section['key'], []) as $doc)
        <div class="flex items-start justify-between px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
            <div class="flex-1 min-w-0 mr-4">
                <div class="flex items-center gap-2 mb-1">
                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                    @if($doc->document_side)
                        <span class="inline-flex items-center text-[10px] font-mono font-medium px-1.5 py-0.5 rounded-md bg-[#efefed] text-[#666660]">
                            {{ ucfirst($doc->document_side) }}
                        </span>
                    @endif
                </div>
                <div class="flex items-baseline gap-2">
                    @if($doc->document_number)
                        <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $doc->document_number }}</span>
                        <span class="text-[11px] text-[#a0a09a] font-light">·</span>
                    @endif
                    <span class="font-mono text-[11px] text-[#a0a09a]">{{ $doc->created_at?->format('d/m/Y · H:i') ?? '—' }}</span>
                </div>
                @if($doc->document_path && $section['key'] === 'pending')
                    <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">{{ basename($doc->document_path) }}</div>
                @endif
                @if($section['key'] === 'verified' && $doc->verified_at)
                    <div class="flex items-center gap-1.5 mt-1">
                        <svg class="w-3 h-3 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span class="text-[11px] text-[#15803d] font-light">Vérifié le {{ is_string($doc->verified_at) ? \Carbon\Carbon::parse($doc->verified_at)->format('d/m/Y') : $doc->verified_at->format('d/m/Y') }}</span>
                    </div>
                @endif
                @if($section['key'] === 'rejected' && $doc->rejection_reason)
                    <div class="mt-2 px-3 py-2 bg-[#fef2f2] border border-[#fecaca] rounded-lg">
                        <span class="text-[11px] text-[#dc2626] font-light">{{ $doc->rejection_reason }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($doc->document_path)
                    <button type="button"
                            onclick="openPreview('{{ asset('storage/'.$doc->document_path) }}', '{{ addslashes(ucfirst(str_replace('_', ' ', $doc->document_type))) }}{{ $doc->document_number ? ' — ' . $doc->document_number : '' }}')"
                            class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                   hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Voir
                    </button>
                @endif
                @if(in_array($section['key'], ['pending', 'rejected']))
                    <form method="POST" action="{{ route('admin.users.approve-document', $doc->id) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                       hover:bg-[#f0fdf4] transition-all">
                            Approuver
                        </button>
                    </form>
                @endif
                @if($section['key'] === 'pending')
                    <button type="button"
                            onclick="openRejectModal('{{ $doc->id }}', '{{ addslashes(ucfirst(str_replace('_', ' ', $doc->document_type))) }}{{ $doc->document_side ? (' — ' . $doc->document_side) : '' }}')"
                            class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                   hover:bg-[#fef2f2] transition-all">
                        Rejeter
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endforeach

    @endif
    </div>
</div>

{{-- MODAL : APERÇU --}}
<div id="previewModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4"
     onclick="if(event.target===this)closePreview()">
    <div class="bg-white border border-[#e0e0dc] rounded-xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e0e0dc]">
            <h3 id="previewTitle" class="font-serif text-[18px] text-[#0a0a0a] leading-none">Document</h3>
            <button onclick="closePreview()"
                    class="w-8 h-8 flex items-center justify-center text-[#a0a09a] hover:text-[#0a0a0a] hover:bg-[#f7f7f5] rounded-lg transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto flex items-center justify-center bg-[#f7f7f5] p-6">
            <img id="previewImage" src="" alt="Aperçu document"
                 class="max-h-full max-w-full object-contain rounded-lg border border-[#e0e0dc]">
        </div>
        <div class="px-6 py-4 border-t border-[#e0e0dc] flex items-center justify-between">
            <span class="text-[11px] text-[#a0a09a] font-light">Ouvrez dans un nouvel onglet pour agrandir</span>
            <a id="downloadLink" href="#" download
               class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-3 py-1.5 rounded-lg
                      hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                Télécharger
            </a>
        </div>
    </div>
</div>

{{-- MODAL : REJET --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
     onclick="if(event.target===this)closeRejectModal()">
    <div class="bg-white border border-[#e0e0dc] rounded-xl w-full max-w-md">
        <div class="px-6 py-5 border-b border-[#efefed]">
            <div class="text-[15px] font-medium text-[#0a0a0a]">Rejeter le document</div>
            <div class="text-[12px] text-[#a0a09a] font-light mt-1" id="rejectDocName"></div>
        </div>
        <div class="px-6 py-5">
            <form id="rejectForm" method="POST">
                @csrf
                <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">
                    Motif du rejet <span class="text-[#dc2626]">*</span>
                </label>
                <textarea id="rejectReason" name="reason" rows="4" required
                          placeholder="Photo floue, document expiré, informations illisibles…"
                          class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2.5
                                 text-[13px] text-[#0a0a0a] placeholder-[#a0a09a]
                                 focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none"></textarea>
                <p class="text-[11px] text-[#a0a09a] font-light mt-1.5">Le motif sera transmis au vendeur par email.</p>
            </form>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed]">
            <button type="button" onclick="closeRejectModal()"
                    class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                           hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                Annuler
            </button>
            <button type="button" onclick="submitRejectForm()"
                    class="flex items-center gap-1.5 bg-[#dc2626] text-white text-[12px] font-medium
                           px-4 py-2 rounded-lg hover:opacity-85 transition-opacity">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Rejeter
            </button>
        </div>
    </div>
</div>

@section('scripts')
<script>
function openPreview(src, title) {
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewImage').src = src;
    document.getElementById('downloadLink').href = src;
    document.getElementById('previewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
    document.body.style.overflow = '';
}
function openRejectModal(docId, docName) {
    document.getElementById('rejectDocName').textContent = docName;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectForm').action =
        '{{ route("admin.users.reject-document", ":id") }}'.replace(':id', docId);
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('rejectReason').focus(), 50);
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}
function submitRejectForm() {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) { document.getElementById('rejectReason').focus(); return; }
    document.getElementById('rejectForm').submit();
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closePreview(); closeRejectModal(); }
});
</script>
@endsection

@endsection
