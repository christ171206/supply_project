{{-- Navbar Minimaliste - Design Neutral Minimal --}}
<nav class="sticky top-0 z-50 bg-white/92 backdrop-blur-md border-b border-[#e0e0dc] h-14 flex items-center px-4 sm:px-8 gap-0">

    {{-- Brand --}}
    <a href="{{ route('accueil') }}" class="flex items-center gap-2 text-[15px] font-medium text-[#0a0a0a] tracking-tight no-underline mr-10">
        <div class="w-7 h-7 bg-[#0a0a0a] rounded-md flex items-center justify-center flex-shrink-0">
            <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
        </div>
        Supply
    </a>

    {{-- Nav links --}}
    <div class="flex items-center gap-1 flex-1">
        <a href="{{ route('accueil') }}"
           class="text-[13px] px-3 py-1.5 rounded-md transition-all duration-150
                  {{ request()->routeIs('accueil') ? 'text-[#0a0a0a] bg-[#efefed]' : 'text-[#666660] hover:text-[#0a0a0a] hover:bg-[#f7f7f5]' }}">
            Accueil
        </a>
        <a href="{{ route('produits.catalogue') }}"
           class="text-[13px] px-3 py-1.5 rounded-md transition-all duration-150
                  {{ request()->routeIs('produits.*') ? 'text-[#0a0a0a] bg-[#efefed]' : 'text-[#666660] hover:text-[#0a0a0a] hover:bg-[#f7f7f5]' }}">
            Catalogue
        </a>
    </div>

    {{-- Search --}}
    <div class="hidden sm:block">
        @include('components.smart-search')
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-2">

        {{-- Panier --}}
        <a href="{{ route('panier.index') }}"
           class="relative w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150">
            <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            @php
                $cart_count = 0;
                if(auth()->check()) {
                    $cart_count = auth()->user()->panier ? auth()->user()->panier->items()->count() : 0;
                } else {
                    $cart_count = count(session('cart', []));
                }
            @endphp
            @if($cart_count > 0)
                <span id="cart-badge" class="absolute -top-1 -right-1 w-4 h-4 bg-[#0a0a0a] text-white text-[9px] font-bold rounded-full flex items-center justify-center border border-white">{{ $cart_count }}</span>
            @else
                <span id="cart-badge" class="modal-hidden absolute -top-1 -right-1 w-4 h-4 bg-[#0a0a0a] text-white text-[9px] font-bold rounded-full flex items-center justify-center border border-white">0</span>
            @endif
        </a>

        {{-- Favoris --}}
        <a href="{{ route('favoris.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150">
            <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </a>

        {{-- CTA --}}
        @guest
            <a href="{{ route('login') }}"
               class="text-[13px] px-3 py-1.5 text-[#666660] hover:text-[#0a0a0a] transition-colors duration-150">
                Se connecter
            </a>
            <a href="{{ route('register') }}"
               class="ml-1 bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-[7px] hover:opacity-85 transition-opacity duration-150 tracking-tight">
                Créer un compte
            </a>
        @else
            {{-- Messagerie --}}
            <a href="{{ route('client.messages') }}"
               class="relative w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150 mr-2">
                <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                @if(auth()->user()->messagesRecus()->where('lu', false)->count() > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#dc2626] text-white text-[9px] font-bold rounded-full flex items-center justify-center border border-white">
                        {{ auth()->user()->messagesRecus()->where('lu', false)->count() }}
                    </span>
                @endif
            </a>

            {{-- Profil --}}
            <div class="ml-1 relative group">
                <button class="w-8 h-8 bg-[#0a0a0a] text-white text-[12px] font-medium rounded-full flex items-center justify-center tracking-tight hover:opacity-85 transition-opacity">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </button>
                <div class="absolute right-0 mt-0 w-48 bg-white border border-[#e0e0dc] rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 py-2 z-10">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-[13px] text-[#0a0a0a] hover:bg-[#f7f7f5]">Profil</a>
                    <a href="{{ route('commandes.index') }}" class="block px-4 py-2 text-[13px] text-[#0a0a0a] hover:bg-[#f7f7f5]">Mes commandes</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-[13px] text-[#0a0a0a] hover:bg-[#f7f7f5]">Déconnexion</button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>
