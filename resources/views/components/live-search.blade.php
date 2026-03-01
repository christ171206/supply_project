<!-- Real-Time Search Component -->
<div class="relative w-full max-w-md" x-data="liveSearch()">
    <div class="relative">
        <!-- Search Input -->
        <input
            type="text"
            x-model="query"
            @input="search()"
            @keydown.escape="isOpen = false"
            @keydown.enter="goToResults()"
            placeholder="🔍 Chercher un produit..."
            class="w-full px-4 py-2.5 pl-10 pr-10 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:ring-2 focus:ring-primary-200 transition-all text-sm bg-white shadow-sm"
        >

        <!-- Loading Spinner -->
        <div x-show="isLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
            <svg class="animate-spin h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Results Dropdown -->
        <div
            x-show="isOpen && (results.length > 0 || noResults)"
            @click.away="isOpen = false"
            class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto z-50"
        >
            <!-- Results List -->
            <template x-if="results.length > 0">
                <div>
                    <!-- Products -->
                    <template x-each="results" :key="result.id">
                        <a :href="result.url" class="flex items-center gap-3 p-3 hover:bg-primary-50 border-b border-gray-100 transition group cursor-pointer">
                            <!-- Product Image -->
                            <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 group-hover:shadow-md transition">
                                <img :src="result.image" :alt="result.nom" class="w-full h-full object-cover">
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate text-sm" x-text="result.nom"></p>
                                <div class="flex items-center gap-2">
                                    <span class="text-primary-600 font-bold text-sm" x-text="result.prix"></span>
                                    <template x-if="!result.inStock">
                                        <span class="text-xs text-danger-600 font-semibold">Rupture</span>
                                    </template>
                                    <template x-if="result.inStock">
                                        <span class="text-xs text-green-600">En stock</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Arrow Icon -->
                            <span class="text-gray-400 group-hover:text-primary-600 transition">→</span>
                        </a>
                    </template>

                    <!-- View All Results -->
                    <button
                        @click="goToResults()"
                        class="w-full p-3 bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold text-center transition border-t border-gray-100"
                    >
                        Voir tous les résultats (<span x-text="resultCount"></span>)
                    </button>
                </div>
            </template>

            <!-- No Results -->
            <template x-if="noResults">
                <div class="p-6 text-center text-gray-500">
                    <p class="text-sm">😕 Aucun produit trouvé pour "<span x-text="query"></span>"</p>
                    <p class="text-xs text-gray-400 mt-2">Essayez avec un terme différent</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Suggestions (when input is focused but empty) -->
    <template x-if="!query && isFocused && suggestions.length > 0">
        <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
            <p class="px-4 pt-3 text-xs text-gray-500 font-semibold">Suggestions populaires</p>
            <template x-each="suggestions" :key="suggestion">
                <button
                    @click="query = suggestion; search()"
                    class="w-full text-left px-4 py-2 hover:bg-gray-50 transition text-sm text-gray-700"
                >
                    🔍 <span x-text="suggestion"></span>
                </button>
            </template>
        </div>
    </template>
</div>

<script>
function liveSearch() {
    return {
        query: '',
        results: [],
        suggestions: [],
        isOpen: false,
        isFocused: false,
        isLoading: false,
        noResults: false,
        resultCount: 0,
        searchTimeout: null,

        async search() {
            // Clear previous timeout
            if (this.searchTimeout) clearTimeout(this.searchTimeout);

            // Show loading state after 200ms
            this.searchTimeout = setTimeout(() => {
                if (this.query.length > 1) {
                    this.isLoading = true;
                }
            }, 200);

            // Delay actual search by 300ms (debounce)
            this.searchTimeout = setTimeout(async () => {
                if (this.query.length < 2) {
                    this.results = [];
                    this.noResults = false;
                    this.isLoading = false;
                    return;
                }

                try {
                    const response = await fetch('{{ route("search.live") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ q: this.query })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.results = data.results;
                        this.resultCount = data.count;
                        this.noResults = data.count === 0 && this.query.length > 1;
                        this.isOpen = true;
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = [];
                } finally {
                    this.isLoading = false;
                }
            }, 300);
        },

        goToResults() {
            if (this.query.length > 0) {
                window.location.href = `/produits?search=${encodeURIComponent(this.query)}`;
            }
        }
    };
}
</script>
