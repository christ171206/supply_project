@extends('layouts.admin-layout')

@section('title', 'Utilisateurs — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Utilisateurs
@endsection

@section('content')
<div class="pb-16">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Administration</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Utilisateurs</h1>
        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-white/10">
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $users->total() }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Au total</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $users->count() }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Sur cette page</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                Rechercher
            </label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nom, email, téléphone…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Rôle</label>
            <select name="role"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="client"  {{ request('role') === 'client'  ? 'selected' : '' }}>Client</option>
                <option value="vendor"  {{ request('role') === 'vendor'  ? 'selected' : '' }}>Vendeur</option>
                <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="w-40">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Statut</label>
            <select name="status"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="">Tous</option>
                <option value="active"  {{ request('status') === 'active'  ? 'selected' : '' }}>Actif</option>
                <option value="banned"  {{ request('status') === 'banned'  ? 'selected' : '' }}>Banni</option>
            </select>
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('search') || request('role') || request('status'))
            <a href="{{ route('admin.users.index') }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Utilisateur</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Email</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Rôle</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Téléphone</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Inscrit</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $roleBadge = match($user->role ?? '') {
                            'admin'  => 'bg-[#fef2f2] text-[#dc2626]',
                            'vendor' => 'bg-[#fdf6ec] text-[#b45309]',
                            default  => 'bg-[#eff6ff] text-[#2563eb]',
                        };
                    @endphp
                    <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">

                        {{-- Nom --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#0a0a0a] rounded-md flex items-center justify-center
                                            text-white text-[11px] font-medium flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $user->name }}</div>
                                    @if($user->is_admin)
                                        <span class="text-[10px] font-mono font-medium text-[#dc2626]">ADMIN</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="px-5 py-3.5">
                            <span class="font-mono text-[11px] text-[#666660]">{{ $user->email }}</span>
                        </td>

                        {{-- Rôle --}}
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center text-[10px] font-mono font-medium px-2 py-1 rounded {{ $roleBadge }}">
                                {{ ucfirst($user->role ?? '—') }}
                            </span>
                        </td>

                        {{-- Téléphone --}}
                        <td class="px-5 py-3.5 font-mono text-[12px] text-[#a0a09a]">
                            {{ $user->phone ?? '—' }}
                        </td>

                        {{-- Statut --}}
                        <td class="px-5 py-3.5">
                            @if($user->is_banned)
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#fef2f2] text-[#dc2626]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>Banni
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                          hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                    Voir
                                </a>
                                @if($user->role === 'vendor')
                                    <a href="{{ route('admin.users.documents', $user) }}"
                                       class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                        Docs
                                    </a>
                                @endif
                                @if(!$user->is_banned)
                                    <button type="button"
                                            onclick="openBanModal({{ $user->id }})"
                                            class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                                   hover:bg-[#fef2f2] transition-all">
                                        Bannir
                                    </button>
                                @else
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-[11px] font-medium text-[#15803d] border border-[#bbf7d0] px-2.5 py-1.5 rounded-lg
                                                       hover:bg-[#f0fdf4] transition-all">
                                            Débannir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </div>
                            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucun utilisateur trouvé</p>
                            <p class="text-[12px] text-[#a0a09a] font-light">Modifiez vos critères de recherche</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-[11px] font-mono text-[#a0a09a]">
                {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }}
            </div>
            <div class="flex items-center gap-1">
                @if($users->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                @else
                    <a href="{{ $users->appends(request()->query())->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                @endif

                @foreach($users->getUrlRange(max(1, $users->currentPage()-2), min($users->lastPage(), $users->currentPage()+2)) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                    @endif
                @endforeach

                @if($users->hasMorePages())
                    <a href="{{ $users->appends(request()->query())->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                @endif
            </div>
        </div>
    @endif

    </div>{{-- /px-8 --}}
</div>

{{-- ══════════════════════════════
     MODALS BAN — un par user
══════════════════════════════ --}}
@foreach($users as $user)
    @if(!$user->is_banned)
        <div id="banModal{{ $user->id }}"
             class="fixed inset-0 z-50 hidden items-center justify-center"
             style="background:rgba(0,0,0,0.4);">
            <div class="bg-white border border-[#e0e0dc] rounded-xl w-[460px] overflow-hidden"
                 style="box-shadow:0 20px 40px rgba(0,0,0,0.12);">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#efefed]">
                    <div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">Bannir l'utilisateur</div>
                        <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">{{ $user->name }}</div>
                    </div>
                    <button onclick="closeBanModal({{ $user->id }})"
                            class="w-7 h-7 flex items-center justify-center text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-[11px] font-medium text-[#666660] mb-1.5">Raison</label>
                            <select name="reason" required
                                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                                <option value="">Sélectionner…</option>
                                <option value="fraud">Fraude détectée</option>
                                <option value="late_delivery">Livraison tardive répétée</option>
                                <option value="policy_violation">Violation des conditions</option>
                                <option value="harassment">Harcèlement</option>
                                <option value="counterfeit">Produits contrefaits</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-[#666660] mb-1.5">Détails</label>
                            <textarea name="details" rows="3" required
                                      placeholder="Expliquez la raison du bannissement…"
                                      class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                             placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-[#666660] mb-1.5">
                                Durée (jours) — laisser vide pour permanent
                            </label>
                            <input type="number" name="duration" min="0"
                                   placeholder="Permanent"
                                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-[#efefed]">
                        <button type="button" onclick="closeBanModal({{ $user->id }})"
                                class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                                       hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                            Annuler
                        </button>
                        <button type="submit"
                                class="text-[12px] font-medium text-white bg-[#dc2626] px-4 py-2 rounded-lg
                                       hover:opacity-85 transition-opacity">
                            Bannir l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach
@endsection

@section('scripts')
<script>
function openBanModal(id) {
    const m = document.getElementById('banModal' + id);
    if (m) { m.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}
function closeBanModal(id) {
    const m = document.getElementById('banModal' + id);
    if (m) { m.style.display = 'none'; document.body.style.overflow = ''; }
}
document.addEventListener('click', e => {
    if (e.target.id?.startsWith('banModal')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="banModal"]').forEach(m => {
            m.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});
</script>
@endsection
