<!-- Hero Section Modern -->
<div class="relative overflow-hidden pt-20 pb-32 bg-gradient-to-b from-blue-50 to-slate-50">
    <!-- Dégradé de fond subtil -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50/50 via-transparent to-accent-50/30"></div>

    <!-- Éléments décor abstraits -->
    <div class="absolute top-10 right-5 w-96 h-96 bg-gradient-to-br from-primary-300 to-primary-100 rounded-full opacity-10 blur-3xl"></div>
    <div class="absolute -bottom-10 left-20 w-80 h-80 bg-gradient-to-tr from-accent-300 to-accent-100 rounded-full opacity-10 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center space-y-8 animate-fade-in-up">
            <h1 class="text-6xl md:text-7xl font-bold text-gray-900 leading-tight">
                Bienvenue à <span class="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">Supply</span>
            </h1>
            <p class="text-2xl text-gray-700 max-w-3xl mx-auto">Votre boutique informatique premium</p>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Technologie de pointe, qualité supérieure, service d'exception</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
                <a href="{{ route('produits.catalogue') }}" class="btn-primary inline-flex items-center justify-center gap-2">
                    Explorer la boutique
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="btn-secondary">Devenir vendeur</a>
            </div>
        </div>
    </div>
</div>
