{{-- Navbar Minimaliste - Design Neutral Minimal --}}
<nav class="sticky top-0 z-50 bg-white/92 backdrop-blur-md border-b border-[#e0e0dc] h-14 flex items-center px-8 gap-0">

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
    <form action="{{ route('produits.catalogue') }}" method="GET"
          class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-3.5 py-1.5 text-[13px] text-[#a0a09a] w-56 mr-4 hover:border-[#a0a09a] transition-colors duration-150 focus-within:border-[#0a0a0a]">
        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher un produit…"
               class="bg-transparent outline-none w-full text-[#0a0a0a] placeholder:text-[#a0a09a] placeholder:font-light">
    </form>

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
            @if(session('cart_count', 0) > 0)
                <span class="absolute top-1 right-1 w-2 h-2 bg-[#0a0a0a] rounded-full border border-white"></span>
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
            <a href="{{ route('register') }}"
               class="ml-1 bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-[7px] hover:opacity-85 transition-opacity duration-150 tracking-tight">
                Créer un compte
            </a>
        @else
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
