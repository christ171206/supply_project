<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Vendeur — Supply')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f5] antialiased" style="font-family:'Geist',sans-serif;">

<div class="flex min-h-screen">

    {{-- ══════════════════════════════
         SIDEBAR — fond noir
    ══════════════════════════════ --}}
    <aside class="w-[220px] bg-[#0a0a0a] flex flex-col sticky top-0 h-screen flex-shrink-0">

        {{-- Logo --}}
        <div class="px-5 pt-6 pb-5 border-b border-white/10">
            <a href="{{ route('vendeur.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2.5" class="w-3.5 h-3.5">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <span class="text-[14px] font-medium text-white">Supply</span>
            </a>
        </div>

        {{-- Shop name --}}
        <div class="px-5 py-4 border-b border-white/10">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-white/30 mb-1">Boutique</div>
            <div class="text-[12px] font-medium text-white/80 truncate">{{ auth()->user()->shop_name ?? auth()->user()->name }}</div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            {{-- Principal --}}
            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/25 px-3 pt-1 pb-2">Principal</div>

            <a href="{{ route('vendeur.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.dashboard') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Tableau de Bord
            </a>

            <a href="{{ route('vendeur.apercu') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.apercu') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
                Aperçu Boutique
            </a>

            {{-- Gestion --}}
            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/25 px-3 pt-4 pb-2">Gestion</div>

            <a href="{{ route('vendeur.produits.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.produits.*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                Mes Produits
            </a>

            <a href="{{ route('vendeur.stock') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.stock') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Gestion Stock
            </a>

            <a href="{{ route('vendeur.commandes') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.commandes*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
                Commandes
            </a>

            <a href="{{ route('vendeur.promo-codes.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.promo-codes*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7V5a2 2 0 012-2h14a2 2 0 012 2v2"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="2"/><path d="M7 12h10"/>
                </svg>
                Codes Promo
            </a>

            <a href="{{ route('vendeur.flash-sales.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.flash-sales*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Soldes Éclair
            </a>

            <a href="{{ route('vendeur.bundles.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.bundles*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M9 2v4"/><path d="M15 2v4"/><line x1="6" y1="10" x2="18" y2="10"/>
                </svg>
                Bundles
            </a>

            {{-- Client --}}
            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/25 px-3 pt-4 pb-2">Client</div>

            <a href="{{ route('vendeur.avis') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.avis') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Avis Clients
            </a>

            <a href="{{ route('vendeur.messages') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.messages') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Messages
                <span id="notif-badge" class="ml-auto hidden w-4 h-4 bg-white text-[#0a0a0a] text-[9px] font-mono font-medium rounded-sm flex items-center justify-center">0</span>
            </a>

            <a href="{{ route('vendeur.message-templates.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.message-templates*') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M15 4H9a2 2 0 0 0-2 2v2h10V6a2 2 0 0 0-2-2z"/><path d="M7 8h10v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V8z"/><path d="M9 12h6"/><path d="M9 15h4"/>
                </svg>
                Modèles de messages
            </a>

            {{-- Compte --}}
            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/25 px-3 pt-4 pb-2">Compte</div>

            <a href="{{ route('vendeur.statistiques') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.statistiques') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Statistiques
            </a>

            <a href="{{ route('vendeur.parametres') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
               {{ request()->routeIs('vendeur.parametres') ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Paramètres
            </a>

        </nav>

        {{-- Bottom : retour boutique + user --}}
        <div class="border-t border-white/10 px-3 py-3 space-y-0.5">
            <a href="{{ route('accueil') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium text-white/50 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Retour Boutique
            </a>

            {{-- User row --}}
            <div class="flex items-center gap-3 px-3 py-3 mt-1 border-t border-white/10">
                <div class="w-7 h-7 bg-white/15 rounded-md flex items-center justify-center text-white text-[11px] font-medium flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12px] font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-white/40 font-light">Vendeur</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Déconnexion"
                        class="w-6 h-6 flex items-center justify-center text-white/30 hover:text-white/70 transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ══════════════════════════════
         MAIN
    ══════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="h-[52px] bg-white border-b border-[#e0e0dc] flex items-center justify-between px-6 sticky top-0 z-40 flex-shrink-0">
            <div class="text-[12px] text-[#a0a09a] font-light">
                @yield('breadcrumb', 'Espace Vendeur')
            </div>
            <div class="flex items-center gap-3">
                {{-- Notif bell --}}
                <button id="notif-btn" onclick="toggleNotifications(event)"
                    class="relative w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span id="notif-count" class="absolute -top-1 -right-1 hidden w-4 h-4 bg-[#0a0a0a] text-white text-[9px] font-mono rounded-sm items-center justify-center">0</span>
                </button>

                {{-- Profil --}}
                <a href="{{ route('vendeur.profil') }}"
                   class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-2.5 py-1.5 hover:border-[#2a2a28] transition-all">
                    <div class="w-5 h-5 bg-[#0a0a0a] rounded-sm flex items-center justify-center text-white text-[10px] font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-[12px] font-medium text-[#0a0a0a]">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

</div>

{{-- ══════════════════════════════
     PANEL NOTIFICATIONS
══════════════════════════════ --}}
<div id="notif-panel" class="hidden fixed top-[60px] right-4 w-[340px] bg-white border border-[#e0e0dc] rounded-xl overflow-hidden z-50 shadow-lg">
    <div class="flex items-center justify-between px-4 py-3 border-b border-[#efefed]">
        <span class="text-[13px] font-medium text-[#0a0a0a]">Notifications</span>
        <button onclick="document.getElementById('notif-panel').classList.add('hidden')"
            class="w-6 h-6 flex items-center justify-center text-[#a0a09a] hover:text-[#0a0a0a] transition-colors text-lg leading-none">×</button>
    </div>
    <div id="notif-content" class="max-h-[400px] overflow-y-auto">
        <div class="px-4 py-8 text-center text-[13px] text-[#a0a09a] font-light">Chargement…</div>
    </div>
</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
<script>
const currentUserId   = {{ auth()->user()->id }};
const currentUserName = "{{ addslashes(auth()->user()->name) }}";
const notifUrl        = "{{ route('vendeur.notifications') }}";

// ── Socket.io ──
let socket = null;
try {
    const socketUrl = ['localhost','127.0.0.1'].includes(location.hostname)
        ? 'http://127.0.0.1:3000'
        : `http://${location.hostname}:3000`;

    socket = io(socketUrl, { reconnectionAttempts: 5, transports: ['websocket','polling'] });
    socket.on('connect', () => socket.emit('user-connect', { userId: currentUserId, name: currentUserName }));
    socket.on('message-notification', data => { updateBadge(); showToast(data); });
} catch(e) {}

// ── Badge ──
function updateBadge() {
    fetch(notifUrl)
        .then(r => r.json())
        .then(data => {
            const count = data.total ?? 0;
            ['notif-count','notif-badge'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.textContent = count;
                el.classList.toggle('hidden', count === 0);
                if (!el.classList.contains('hidden')) el.classList.add('flex');
            });
        })
        .catch(() => {});
}

// ── Panel ──
function toggleNotifications(event) {
    event?.stopPropagation();
    const panel = document.getElementById('notif-panel');
    if (!panel.classList.contains('hidden')) { panel.classList.add('hidden'); return; }
    panel.classList.remove('hidden');
    loadNotifications();
}
document.addEventListener('click', e => {
    const panel = document.getElementById('notif-panel');
    const btn   = document.getElementById('notif-btn');
    if (!panel?.contains(e.target) && e.target !== btn) panel?.classList.add('hidden');
});

function loadNotifications() {
    const content = document.getElementById('notif-content');
    fetch(notifUrl)
        .then(r => r.json())
        .then(data => {
            if (!data.notifications?.length) {
                content.innerHTML = `<div class="px-4 py-10 text-center">
                    <svg class="w-8 h-8 text-[#e0e0dc] mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <p class="text-[13px] text-[#a0a09a] font-light">Aucune notification</p>
                </div>`;
                return;
            }
            content.innerHTML = data.notifications.map(n => `
                <div class="px-4 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[13px] font-medium text-[#0a0a0a]">${n.title}</span>
                        <span class="text-[10px] font-mono bg-[#fdf6ec] text-[#b45309] px-1.5 py-0.5 rounded">${n.type?.toUpperCase() ?? ''}</span>
                    </div>
                    ${n.data?.slice(0,3).map(i => `<div class="text-[11px] text-[#a0a09a] font-light truncate">— ${i.nom ?? i.name ?? ''}</div>`).join('') ?? ''}
                    <a href="${n.link}" class="inline-block mt-2 text-[11px] text-[#a0a09a] border-b border-[#e0e0dc] pb-px hover:text-[#0a0a0a] hover:border-[#0a0a0a] transition-all">Voir →</a>
                </div>
            `).join('');
        })
        .catch(() => { document.getElementById('notif-content').innerHTML = '<div class="px-4 py-6 text-center text-[13px] text-[#a0a09a]">Erreur de chargement</div>'; });
}

// ── Toast ──
function showToast(data) {
    const t = document.createElement('div');
    t.className = 'fixed top-[64px] right-4 w-[300px] bg-white border border-[#e0e0dc] rounded-xl overflow-hidden z-50';
    t.innerHTML = `
        <div class="flex items-start gap-3 px-4 py-3.5">
            <div class="w-7 h-7 bg-[#0a0a0a] rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[12px] font-medium text-[#0a0a0a]">Nouveau message</div>
                <div class="text-[11px] text-[#a0a09a] font-light mt-0.5 truncate">${data.preview ?? ''}</div>
                <a href="{{ route('vendeur.messages') }}" class="inline-block mt-1.5 text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir →</a>
            </div>
            <button onclick="this.closest('div.fixed').remove()" class="text-[#a0a09a] hover:text-[#0a0a0a] transition-colors text-lg leading-none flex-shrink-0">×</button>
        </div>`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 5000);
}

document.addEventListener('DOMContentLoaded', () => {
    updateBadge();
    setInterval(updateBadge, 30000);
});
</script>

@yield('scripts')
</body>
</html>
