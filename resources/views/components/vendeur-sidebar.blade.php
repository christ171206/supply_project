<!-- Sidebar Navigation - Espace Vendeur -->
<aside class="w-64 bg-white border-r border-gray-200 min-h-screen sticky top-0">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">
            🏪 Supply
        </h1>
        <p class="text-xs text-gray-600 mt-1">Espace vendeur</p>
    </div>

    <!-- Menu Items -->
    <nav class="mt-6 px-4 space-y-2">
        <!-- Aperçu -->
        <a href="{{ route('vendeur.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.index', 'vendeur.apercu')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <x-heroicon-o-cube class="w-5 h-5" />
            <span class="font-medium">Aperçu</span>
        </a>

        <!-- Mes produits -->
        <a href="{{ route('vendeur.produits.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.produits.*')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl"><x-heroicon-o-cube class="w-5 h-5" /></span>
            <span class="font-medium">Mes produits</span>
        </a>

        <!-- Gestion du stock -->
        <a href="{{ route('vendeur.stock.alertes') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.stock.*')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl">🛒</span>
            <span class="font-medium">Gestion du stock</span>
            @php
                $stockCritique = auth()->user()->produits()->where('stock', '<=', \DB::raw('stock_minimum'))->count();
            @endphp
            @if($stockCritique > 0)
                <span class="ml-auto bg-danger-600 text-white text-xs rounded-full px-2 py-0.5 font-bold">
                    {{ $stockCritique }}
                </span>
            @endif
        </a>

        <!-- 🧾 Commandes clients -->
        <a href="{{ route('vendeur.commandes.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.commandes.*')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl">🧾</span>
            <span class="font-medium">Commandes</span>
            @php
                $commandesPending = auth()->user()->commandes()->where('statut', '!=', 'livrée')->where('statut', '!=', 'annulée')->count();
            @endphp
            @if($commandesPending > 0)
                <span class="ml-auto bg-warning-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">
                    {{ $commandesPending }}
                </span>
            @endif
        </a>

        <!-- 📜 Historique -->
        <a href="{{ route('vendeur.historique') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.historique')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl">📜</span>
            <span class="font-medium">Historique</span>
        </a>

        <!-- Messages -->
        <a href="{{ route('vendeur.messages') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.messages')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl"><x-heroicon-o-chat-bubble-left class="w-5 h-5" /></span>
            <span class="font-medium">Messages</span>
            @php
                $messagesUnread = auth()->user()->messagesRecus()->where('lu', false)->count();
            @endphp
            @if($messagesUnread > 0)
                <span class="ml-auto bg-accent-600 text-white text-xs rounded-full px-2 py-0.5 font-bold">
                    {{ $messagesUnread }}
                </span>
            @endif
        </a>

        <!-- ⚙️ Mon profil -->
        <a href="{{ route('vendeur.profil') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all
                  {{ request()->routeIs('vendeur.profil*')
                     ? 'bg-primary-600 text-white shadow-sm'
                     : 'text-gray-700 hover:bg-gray-100' }}">
            <span class="text-xl">⚙️</span>
            <span class="font-medium">Mon profil</span>
        </a>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200"></div>

        <!-- 🛍️ Voir la boutique -->
        <a href="{{ route('produits.index') }}" target="_blank"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">
            <span class="text-xl">🛍️</span>
            <span class="font-medium">Voir la boutique</span>
            <span class="ml-auto text-gray-400 text-sm">↗</span>
        </a>

        <!-- 🚪 Déconnexion -->
        <form method="POST" action="{{ route('logout') }}"
              class="mt-4 w-full"
              data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?"
              data-confirm-title="Déconnexion"
              data-confirm-type="warning"
              data-confirm-button="Déconnexion">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-danger-50 transition-all hover:text-danger-700">
                <span class="text-xl">🚪</span>
                <span class="font-medium">Déconnexion</span>
            </button>
        </form>
    </nav>

    <!-- Footer Info -->
    <div class="absolute bottom-4 left-4 right-4 p-3 bg-gray-100 rounded-lg border border-gray-200">
        <p class="text-xs text-gray-600">
            <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span><br>
        </p>
    </div>
</aside>
