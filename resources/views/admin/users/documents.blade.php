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
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                                @if($doc->document_side)
                                    <span class="inline-flex items-center text-[10px] font-mono font-medium px-1.5 py-0.5 rounded-md
                                           bg-[#efefed] text-[#666660]">
                                        {{ ucfirst($doc->document_side) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-baseline gap-2">
                                @if($doc->document_number)
                                    <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $doc->document_number }}</span>
                                    <span class="text-[11px] text-[#a0a09a] font-light">·</span>
                                @endif
                                <span class="font-mono text-[11px] text-[#a0a09a]">{{ $doc->created_at->format('d/m/Y · H:i') }}</span>
                            </div>
                            @if($doc->document_path)
                                <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">{{ basename($doc->document_path) }}</div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($doc->document_path)
                                <button type="button" onclick="openPreview('{{ asset('storage/'.$doc->document_path) }}', '{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}{{ $doc->document_number ? ' - ' . $doc->document_number : '' }}')"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </button>
                            @endif
                            <form method="POST" action="{{ route('admin.users.approve-document', $doc->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                               hover:bg-[#f0fdf4] transition-all">
                                    Approuver
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal('{{ $doc->id }}', '{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}{{ $doc->document_side ? (' - ' . $doc->document_side) : '' }}')"
                                    class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                           hover:bg-[#fef2f2] transition-all">
                                Rejeter
                            </button>
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
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                                @if($doc->document_side)
                                    <span class="inline-flex items-center text-[10px] font-mono font-medium px-1.5 py-0.5 rounded-md
                                           bg-[#efefed] text-[#666660]">
                                        {{ ucfirst($doc->document_side) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-baseline gap-2">
                                @if($doc->document_number)
                                    <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $doc->document_number }}</span>
                                    <span class="text-[11px] text-[#a0a09a] font-light">·</span>
                                @endif
                                <span class="font-mono text-[11px] text-[#a0a09a]">{{ $doc->created_at->format('d/m/Y · H:i') }}</span>
                            </div>
                            @if($doc->verified_at)
                                <div class="flex items-center gap-1.5 mt-1">
                                    <svg class="w-3 h-3 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span class="text-[11px] text-[#15803d] font-light">Vérifié le {{ $doc->verified_at->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                        @if($doc->document_path)
                            <button type="button" onclick="openPreview('{{ asset('storage/'.$doc->document_path) }}', '{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}{{ $doc->document_number ? ' - ' . $doc->document_number : '' }}')"
                               class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                      hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all flex-shrink-0">
                                Voir
                            </button>
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
                            <div class="flex items-center gap-2 mb-1">
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                                @if($doc->document_side)
                                    <span class="inline-flex items-center text-[10px] font-mono font-medium px-1.5 py-0.5 rounded-md
                                           bg-[#efefed] text-[#666660]">
                                        {{ ucfirst($doc->document_side) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-baseline gap-2">
                                @if($doc->document_number)
                                    <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $doc->document_number }}</span>
                                    <span class="text-[11px] text-[#a0a09a] font-light">·</span>
                                @endif
                                <span class="font-mono text-[11px] text-[#a0a09a]">{{ $doc->created_at->format('d/m/Y · H:i') }}</span>
                            </div>
                            @if($doc->rejection_reason)
                                <div class="mt-2 px-3 py-2 bg-[#fef2f2] border border-[#fecaca] rounded-lg">
                                    <span class="text-[11px] text-[#dc2626] font-light">{{ $doc->rejection_reason }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($doc->document_path)
                                <button type="button" onclick="openPreview('{{ asset('storage/'.$doc->document_path) }}', '{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}{{ $doc->document_number ? ' - ' . $doc->document_number : '' }}')"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </button>
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

{{-- Document Preview Modal --}}
<div id="previewModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4 transition-opacity duration-200"
     onclick="closePreview(event)">
    <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col"
         onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e0e0dc]">
            <div>
                <h3 id="previewTitle" class="font-serif text-[18px] text-[#0a0a0a]">Document</h3>
            </div>
            <button type="button" onclick="closePreview()"
                    class="w-8 h-8 flex items-center justify-center text-[#a0a09a] hover:text-[#0a0a0a] hover:bg-[#f7f7f5] rounded-lg transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-auto flex items-center justify-center bg-[#f7f7f5] p-6">
            <img id="previewImage" src="" alt="Document preview" class="max-h-full max-w-full object-contain rounded-lg">
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-[#e0e0dc] bg-[#f7f7f5] flex items-center justify-between">
            <div class="text-[12px] text-[#a0a09a] font-light">
                Cliquez sur l'image pour l'agrandir ou utilisez votre navigateur
            </div>
            <a id="downloadLink" href="#" download
               class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-3 py-1.5 rounded-lg
                      hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all inline-block">
                Télécharger
            </a>
        </div>
    </div>
</div>

<script>
    function openPreview(imagePath, title) {
        const modal = document.getElementById('previewModal');
        const img = document.getElementById('previewImage');
        const titleEl = document.getElementById('previewTitle');
        const downloadLink = document.getElementById('downloadLink');

        titleEl.textContent = title;
        img.src = imagePath;
        downloadLink.href = imagePath;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreview(event) {
        // Allow closing by clicking outside the modal content
        if (event && event.target.id !== 'previewModal') return;

        const modal = document.getElementById('previewModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePreview();
            closeRejectModal();
        }
    });

    // Gestion du rejet de document avec modal
    function openRejectModal(docId, docName) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        document.getElementById('rejectDocName').textContent = docName;
        document.getElementById('rejectReason').value = '';
        
        // Set the form action to the correct route
        form.action = '{{ route("admin.users.reject-document", ":id") }}'.replace(':id', docId);
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('rejectReason').focus();
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function submitRejectForm() {
        const form = document.getElementById('rejectForm');
        const reason = document.getElementById('rejectReason').value.trim();
        
        if (!reason) {
            alert('Veuillez expliquer la raison du rejet');
            return;
        }
        
        form.submit();
    }

    // Fermer la modal en cliquant sur le backdrop
    document.getElementById('rejectModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

{{-- MODAL: REJET DOCUMENT --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md border border-[#e0e0dc] animate-in fade-in zoom-in-95 duration-200">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-[#efefed]">
            <h3 class="text-[15px] font-semibold text-[#0a0a0a]">Rejeter le document</h3>
            <p class="text-[12px] text-[#a0a09a] font-light mt-1">
                <span id="rejectDocName"></span>
            </p>
        </div>

        {{-- Content --}}
        <div class="px-6 py-4">
            <form id="rejectForm" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="rejectReason" class="block text-[12px] font-medium text-[#0a0a0a] mb-2">
                        <i class="fas fa-exclamation-triangle text-[#dc2626]"></i> Raison du rejet <span class="text-[#dc2626]">*</span>
                    </label>
                    <textarea 
                        id="rejectReason" 
                        name="reason" 
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] outline-none transition-all resize-none"
                        placeholder="Exemple: Photo floue, document expiré, informations incomplètes, mauvaise qualité..."
                        rows="4"
                        required></textarea>
                    <small class="text-[11px] text-[#a0a09a] block mt-1.5">
                        💡 Le motif sera envoyé au vendeur par email
                    </small>
                </div>
            </form>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed] bg-[#f7f7f5]">
            <button type="button" onclick="closeRejectModal()"
                    class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                           hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                Annuler
            </button>
            <button type="button" onclick="submitRejectForm()"
                    class="bg-[#dc2626] text-white text-[12px] font-medium px-4 py-2 rounded-lg
                           hover:bg-[#991b1b] transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Rejeter
            </button>
        </div>
    </div>
</div>

<script>
// Correction du formulaire de rejet
document.addEventListener('DOMContentLoaded', function() {
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const docId = document.getElementById('rejectDocId').value;
            const route = '{{ route("admin.users.reject-document", ":id") }}'.replace(':id', docId);
            rejectForm.action = route;
        });
    }
});
</script>
@endsection
