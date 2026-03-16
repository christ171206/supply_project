<!-- Enhanced Smart Search Component -->
<div class="relative w-40 md:w-56" x-data="smartSearch()" @click.away="resultsOpen = false">
    <div class="relative">
        <!-- Search Input -->
        <form action="{{ route('search.results') }}" method="GET" class="flex items-center gap-2 border border-[#e0e0dc] rounded-lg px-3.5 py-1.5 text-[13px] text-[#a0a09a] hover:border-[#a0a09a] transition-colors duration-150 focus-within:border-[#0a0a0a]">
            <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="q"
                x-model="query"
                @input="fetchSuggestions()"
                @focus="resultsOpen = query.length > 0 || showTrending"
                @keydown.escape="resultsOpen = false"
                @keydown.enter="submitSearch()"
                value="{{ request('q') }}"
                placeholder="Rechercher un produit…"
                class="bg-transparent outline-none w-full text-[#0a0a0a] placeholder:text-[#a0a09a] placeholder:font-light"
                autocomplete="off"
            >
        </form>

        <!-- Loading Indicator -->
        <div x-show="isLoading" class="absolute right-3.5 top-1/2 -translate-y-1/2">
            <svg class="animate-spin h-3.5 w-3.5 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke-dasharray="62.8" stroke-dashoffset="15.7" stroke-linecap="round"></circle>
            </svg>
        </div>
    </div>

    <!-- Results Dropdown -->
    <div x-show="resultsOpen && (suggestions.length > 0 || trendingProducts.length > 0 || trendingCategories.length > 0)"
         @click.away="resultsOpen = false"
         class="absolute top-full left-0 right-0 mt-2 bg-white border border-[#e0e0dc] rounded-lg shadow-lg max-h-96 overflow-y-auto z-50">

        <!-- Suggestions de produits -->
        <template x-if="suggestions.length > 0">
            <div>
                <div class="px-4 py-2 bg-[#f7f7f5] border-b border-[#efefed] sticky top-0">
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a]">Produits</span>
                </div>
                <template x-each="suggestions" :key="suggestion.id">
                    <a :href="`/produits/${suggestion.id}`" click="resultsOpen = false"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-[#f7f7f5] border-b border-[#efefed] last:border-b-0 transition-colors">
                        <div class="w-10 h-10 bg-[#f7f7f5] rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <template x-if="suggestion.image">
                                <img :src="`/storage/${suggestion.image}`" :alt="suggestion.nom" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!suggestion.image">
                                <span class="text-[#a0a09a] text-[12px]">📦</span>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[12px] font-medium text-[#0a0a0a] truncate" x-html="suggestion.nom"></div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-mono text-[11px] text-[#0a0a0a]" x-text="formatPrice(suggestion.prix)"></span>
                                <template x-if="suggestion.badge">
                                    <span class="text-[10px] text-[#f59e0b]" x-text="suggestion.badge"></span>
                                </template>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </template>

        <!-- Catégories tendance (quand vide) -->
        <template x-if="suggestions.length === 0 && trendingCategories.length > 0">
            <div>
                <div class="px-4 py-2 bg-[#f7f7f5] border-b border-[#efefed] sticky top-0">
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a]">Catégories</span>
                </div>
                <template x-each="trendingCategories" :key="category.id">
                    <a :href="`/produits/catalogue?categorie=${category.id}`"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-[#f7f7f5] border-b border-[#efefed] last:border-b-0 transition-colors text-[13px] text-[#0a0a0a]">
                        <span class="text-[14px]">📂</span>
                        <span x-text="category.nom"></span>
                        <span class="text-[11px] text-[#a0a09a] ml-auto flex-shrink-0" x-text="`(${category.count})`"></span>
                    </a>
                </template>
            </div>
        </template>

        <!-- Produits tendance (quand vide) -->
        <template x-if="suggestions.length === 0 && trendingProducts.length > 0">
            <div>
                <div class="px-4 py-2 bg-[#f7f7f5] border-b border-[#efefed] sticky top-0">
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] flex items-center gap-1">
                        <span>🔥</span> Tendance
                    </span>
                </div>
                <template x-each="trendingProducts" :key="product.nom">
                    <button type="button" @click="query = product.nom; fetchSuggestions();"
                            class="w-full text-left px-4 py-3 hover:bg-[#f7f7f5] border-b border-[#efefed] last:border-b-0 transition-colors">
                        <div class="text-[12px] font-medium text-[#0a0a0a]" x-text="product.nom"></div>
                        <div class="text-[10px] text-[#a0a09a] mt-0.5" x-text="product.badge"></div>
                    </button>
                </template>
            </div>
        </template>
    </div>
</div>

<script>
function smartSearch() {
    return {
        query: '',
        suggestions: [],
        trendingProducts: [],
        trendingCategories: [],
        resultsOpen: false,
        isLoading: false,
        showTrending: true,
        searchTimeout: null,

        async fetchSuggestions() {
            clearTimeout(this.searchTimeout);

            if (this.query.trim().length === 0) {
                this.suggestions = [];
                this.resultsOpen = this.showTrending;
                if (this.showTrending) this.loadTrending();
                return;
            }

            this.isLoading = true;
            this.searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/autocomplete?q=${encodeURIComponent(this.query)}`);
                    const data = await response.json();
                    this.suggestions = data.suggestions || [];
                    this.resultsOpen = this.suggestions.length > 0;
                } catch (error) {
                    console.error('Search error:', error);
                    this.suggestions = [];
                } finally {
                    this.isLoading = false;
                }
            }, 300);
        },

        async loadTrending() {
            try {
                const response = await fetch('/api/search/trending');
                const data = await response.json();
                this.trendingProducts = data.trending || [];
                this.trendingCategories = data.categories || [];
            } catch (error) {
                console.error('Trending error:', error);
            }
        },

        submitSearch() {
            if (this.query.trim()) {
                window.location.href = `/search/results?q=${encodeURIComponent(this.query)}`;
            }
        },

        formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(price) + ' F';
        },

        init() {
            // Charger les suggestions tendance au démarrage
            this.$nextTick(() => this.loadTrending());
        }
    }
}
</script>
