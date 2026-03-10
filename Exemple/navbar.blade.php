{{-- resources/views/components/navbar.blade.php --}}

<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-[#e0e0dc] h-14 flex items-center px-8 gap-0">

    {{-- Brand --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2 text-[15px] font-medium text-[#0a0a0a] tracking-tight no-underline mr-10">
        <div class="w-7 h-7 bg-[#0a0a0a] rounded-md flex items-center justify-center flex-shrink-0">
            <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
        </div>
        Supply
    </a>

    {{-- Nav links --}}
    <div class="flex items-center gap-1 flex-1">
        <a href="{{ route('home') }}"
           class="text-[13px] px-3 py-1.5 rounded-md transition-all duration-150
                  {{ request()->routeIs('home') ? 'text-[#0a0a0a] bg-[#efefed]' : 'text-[#666660] hover:text-[#0a0a0a] hover:bg-[#f7f7f5]' }}">
            Accueil
        </a>
        <a href="{{ route('products.index') }}"
           class="text-[13px] px-3 py-1.5 rounded-md transition-all duration-150
                  {{ request()->routeIs('products.*') ? 'text-[#0a0a0a] bg-[#efefed]' : 'text-[#666660] hover:text-[#0a0a0a] hover:bg-[#f7f7f5]' }}">
            Catalogue
        </a>
    </div>

    {{-- Search --}}
    <form action="{{ route('products.search') }}" method="GET"
          class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-3.5 py-1.5 text-[13px] text-[#a0a09a] w-56 mr-4 hover:border-[#a0a09a] transition-colors duration-150 focus-within:border-[#0a0a0a]">
        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Rechercher un produit…"
               class="bg-transparent outline-none w-full text-[#0a0a0a] placeholder:text-[#a0a09a] placeholder:font-light">
    </form>

    {{-- Actions --}}
    <div class="flex items-center gap-2">

        {{-- Panier --}}
        <a href="{{ route('cart.index') }}"
           class="relative w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150">
            <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            @if(session('cart_count', 0) > 0)
                <span class="absolute top-1 right-1 w-2 h-2 bg-[#0a0a0a] rounded-full border-2 border-white"></span>
            @endif
        </a>

        {{-- Favoris --}}
        <a href="{{ route('favorites.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150">
            <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </a>

        {{-- Rapport --}}
        @auth
        <a href="{{ route('reports.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg text-[#666660] hover:bg-[#f7f7f5] hover:text-[#0a0a0a] transition-all duration-150">
            <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
        </a>
        @endauth

        {{-- CTA --}}
        @guest
            <a href="{{ route('register') }}"
               class="ml-1 bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-[7px] hover:opacity-85 transition-opacity duration-150 tracking-tight">
                Créer un compte
            </a>
        @else
            <a href="{{ route('profile.index') }}"
               class="ml-1 w-8 h-8 bg-[#0a0a0a] text-white text-[12px] font-medium rounded-full flex items-center justify-center tracking-tight">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </a>
        @endguest
    </div>
</nav>
