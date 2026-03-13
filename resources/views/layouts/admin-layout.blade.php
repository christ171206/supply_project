<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Supply')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/modals.css', 'resources/js/app.js'])
    <script>window.SOCKET_IO_URL = '{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}';</script>
</head>
<body class="antialiased bg-[#f7f7f5]" style="font-family:'Geist',sans-serif; font-weight:300;">

<div class="flex min-h-screen">

    {{-- ══════════════════════════════
         SIDEBAR — fond noir
    ══════════════════════════════ --}}
    <aside id="sidebar"
           class="w-[220px] bg-[#0a0a0a] flex flex-col sticky top-0 h-screen flex-shrink-0 z-50
                  fixed md:relative -translate-x-full md:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="px-5 pt-6 pb-5 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2.5" class="w-3.5 h-3.5">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[14px] font-medium text-white leading-none">Supply</div>
                    <div class="text-[9px] font-medium tracking-[0.12em] uppercase text-white/30 mt-0.5">Admin</div>
                </div>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/25 px-3 pt-1 pb-2">
                Administration
            </div>

            @php
                $navItems = [
                    ['route'=>'admin.dashboard',      'label'=>'Dashboard',    'icon'=>'<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>'],
                    ['route'=>'admin.statistics.index','label'=>'Statistiques', 'icon'=>'<path d="M3 3v18h18"/><path d="M18 17V9m-5 8V5m-5 12v-3m-5 3V11"/><circle cx="8" cy="11" r="1"/><circle cx="13" cy="5" r="1"/><circle cx="18" cy="9" r="1"/>'],
                    ['route'=>'admin.users.index',    'label'=>'Utilisateurs', 'icon'=>'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                    ['route'=>'admin.products.index', 'label'=>'Produits',     'icon'=>'<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'],
                    ['route'=>'admin.categories.index','label'=>'Catégories',  'icon'=>'<rect x="4" y="4" width="6" height="6"/><rect x="14" y="4" width="6" height="6"/><rect x="4" y="14" width="6" height="6"/><rect x="14" y="14" width="6" height="6"/>'],
                    ['route'=>'admin.orders.index',   'label'=>'Commandes',    'icon'=>'<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>'],
                    ['route'=>'admin.disputes.index', 'label'=>'Litiges',      'icon'=>'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = Route::currentRouteName() === $item['route']; @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
                          {{ $active ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="text-[9px] font-medium tracking-[0.14em] uppercase text-white/15 px-3 pt-5 pb-2">Rapports & Analyses</div>
            @php
                $reportItems = [
                    ['route' => 'admin.reports.index', 'label' => 'Rapports détaillés', 'icon' => '<path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M3 9v6c0 1.105 4.03 2 9 2s9-.895 9-2V9"/><path d="M3 15v6c0 1.105 4.03 2 9 2s9-.895 9-2v-6"/>'],
                ];
            @endphp
            @foreach($reportItems as $item)
                @php $active = str_contains(Route::currentRouteName(), str_replace('admin.', '', $item['route'])); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium transition-all
                          {{ $active ? 'bg-white text-[#0a0a0a]' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User row --}}
        <div class="border-t border-white/10 px-3 py-3 space-y-0.5">
            <a href="{{ route('accueil') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[12px] font-medium
                      text-white/50 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Vue client
            </a>
            <div class="flex items-center gap-3 px-3 py-3 border-t border-white/10 mt-1">
                <div class="w-7 h-7 bg-white/15 rounded-md flex items-center justify-center text-white text-[11px] font-medium flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12px] font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-white/40 font-light">Administrateur</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="w-6 h-6 flex items-center justify-center text-white/30 hover:text-white/70 transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
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

        {{-- Banner mode client --}}
        @if(session('admin_client_mode'))
            <div class="bg-[#fdf6ec] border-b border-[#fde68a] px-6 py-2.5 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>
                    <span class="text-[12px] font-medium text-[#b45309]">
                        Mode visualisation client — vous ne pouvez pas passer de commande
                    </span>
                </div>
                <form method="POST" action="{{ route('admin.mode.client-exit') }}">
                    @csrf
                    <button type="submit"
                            class="text-[11px] font-medium text-[#b45309] border border-[#fde68a] px-3 py-1.5 rounded-lg hover:bg-[#fde68a] transition-colors">
                        Quitter →
                    </button>
                </form>
            </div>
        @endif

        {{-- Topbar --}}
        <header class="h-[52px] bg-white border-b border-[#e0e0dc] flex items-center justify-between px-6 sticky top-0 z-40 flex-shrink-0">

            <button id="hamburger-btn"
                    class="md:hidden w-8 h-8 flex items-center justify-center text-[#666660] hover:text-[#0a0a0a] transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <div class="text-[12px] text-[#a0a09a] font-light hidden md:block">
                @yield('breadcrumb', 'Espace Admin')
            </div>

            <div class="flex items-center gap-3 ml-auto">

                {{-- Mode client --}}
                @if(!session('admin_client_mode'))
                    <form method="POST" action="{{ route('admin.mode.client-enter') }}">
                        @csrf
                        <button type="submit"
                                class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-3 py-1.5
                                       rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                            Mode client
                        </button>
                    </form>
                @endif

                {{-- Notifs --}}
                <div class="relative group">
                    <button class="relative w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg
                                   text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#0a0a0a] text-white text-[9px]
                                         font-mono rounded-sm flex items-center justify-center leading-none">
                                {{ $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>

                    <div class="absolute right-0 mt-1 w-[320px] bg-white border border-[#e0e0dc] rounded-xl overflow-hidden
                                opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50"
                         style="box-shadow:0 8px 24px rgba(0,0,0,0.08);">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-[#efefed]">
                            <span class="text-[13px] font-medium text-[#0a0a0a]">Notifications</span>
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <span class="text-[10px] font-mono text-[#a0a09a]">
                                    {{ $unreadNotificationsCount }} non lue{{ $unreadNotificationsCount > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-[#efefed]">
                            @if(isset($adminNotifications) && $adminNotifications->count() > 0)
                                @foreach($adminNotifications as $n)
                                    <div class="px-4 py-3.5 hover:bg-[#f7f7f5] transition-colors flex items-start gap-3">
                                        <div class="w-7 h-7 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[12px] font-medium text-[#0a0a0a] truncate">{{ $n->titre }}</div>
                                            <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">{{ Str::limit($n->message, 55) }}</div>
                                            <div class="font-mono text-[10px] text-[#a0a09a] mt-1">{{ $n->created_at->diffForHumans() }}</div>
                                        </div>
                                        <div class="flex flex-col gap-1 flex-shrink-0">
                                            <form action="{{ route('notifications.mark-as-read', $n->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="w-5 h-5 flex items-center justify-center text-[#a0a09a] hover:text-[#22c55e] transition-colors">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('notifications.delete', $n->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="w-5 h-5 flex items-center justify-center text-[#a0a09a] hover:text-[#dc2626] transition-colors">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-4 py-10 text-center">
                                    <p class="text-[12px] text-[#a0a09a] font-light">Aucune notification</p>
                                </div>
                            @endif
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications->count() > 0)
                            <div class="px-4 py-3 border-t border-[#efefed] text-center">
                                <a href="{{ route('notifications.index') }}"
                                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px">
                                    Voir toutes →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Profil --}}
                <div class="relative group">
                    <button class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-2.5 py-1.5 hover:border-[#2a2a28] transition-all">
                        <div class="w-5 h-5 bg-[#0a0a0a] rounded-sm flex items-center justify-center text-white text-[10px] font-medium">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">{{ auth()->user()->name }}</span>
                        <svg class="w-3 h-3 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-1 w-[200px] bg-white border border-[#e0e0dc] rounded-xl overflow-hidden
                                opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50"
                         style="box-shadow:0 8px 24px rgba(0,0,0,0.08);">
                        <div class="px-4 py-3 border-b border-[#efefed]">
                            <div class="text-[12px] font-medium text-[#0a0a0a]">{{ auth()->user()->name }}</div>
                            <div class="text-[11px] text-[#a0a09a] font-light truncate">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="py-1">
                            @foreach([
                                ['route'=>'admin.profile.edit',   'label'=>'Profil'],
                                ['route'=>'admin.security.index', 'label'=>'Sécurité'],
                                ['route'=>'admin.settings.index', 'label'=>'Paramètres'],
                            ] as $it)
                                <a href="{{ route($it['route']) }}"
                                   class="block px-4 py-2.5 text-[12px] text-[#2a2a28] hover:bg-[#f7f7f5] transition-colors">
                                    {{ $it['label'] }}
                                </a>
                            @endforeach
                        </div>
                        <div class="border-t border-[#efefed] py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-[12px] text-[#dc2626] hover:bg-[#fef2f2] transition-colors">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1">@yield('content')</main>

    </div>
</div>

<script>
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('sidebar');
    hamburgerBtn?.addEventListener('click', e => { e.stopPropagation(); sidebar.classList.toggle('-translate-x-full'); });
    document.addEventListener('click', e => {
        if (!sidebar?.contains(e.target) && !hamburgerBtn?.contains(e.target))
            sidebar?.classList.add('-translate-x-full');
    });
</script>
@include('components.confirmation-modal')
@yield('scripts')
</body>
</html>
