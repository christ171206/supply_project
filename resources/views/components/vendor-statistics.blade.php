<!-- Vendor Statistics Dashboard Component -->
<div x-data="vendorStats()" x-init="init()" class="space-y-6">
    <!-- Header with Period Selector -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-8 h-8" /><span>Statistiques Commerciales</span></h2>
            <p class="text-gray-600 mt-2">Suivez vos ventes et performances</p>
        </div>
        <div class="flex gap-2">
            <button @click="changePeriod(7)" :class="period === 7 ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300'" class="px-4 py-2 rounded-lg font-medium hover:shadow-md transition">
                7 jours
            </button>
            <button @click="changePeriod(30)" :class="period === 30 ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300'" class="px-4 py-2 rounded-lg font-medium hover:shadow-md transition">
                30 jours
            </button>
            <button @click="changePeriod(90)" :class="period === 90 ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300'" class="px-4 py-2 rounded-lg font-medium hover:shadow-md transition">
                90 jours
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-blue-700 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-banknotes class="w-4 h-4" /><span>Ventes Totales</span></p>
                    <p class="text-3xl font-bold text-blue-900" x-text="formatCurrency(indicators.totalVentes)"></p>
                    <p class="text-blue-600 text-xs mt-2">Période sélectionnée</p>
                </div>
                <div class="text-4xl opacity-20"><x-heroicon-o-banknotes class="w-10 h-10" /></div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-green-700 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-cube class="w-4 h-4" /><span>Commandes</span></p>
                    <p class="text-3xl font-bold text-green-900" x-text="indicators.totalCommandes"></p>
                    <p class="text-green-600 text-xs mt-2">Ordres reçues</p>
                </div>
                <div class="text-4xl opacity-20"><x-heroicon-o-cube class="w-10 h-10" /></div>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-purple-700 text-sm font-semibold mb-2 flex items-center gap-1"><x-heroicon-o-chart-line class="w-4 h-4" /><span>Panier Moyen</span></p>
                    <p class="text-3xl font-bold text-purple-900" x-text="formatCurrency(indicators.averageOrderValue)"></p>
                    <p class="text-purple-600 text-xs mt-2">Par commande</p>
                </div>
                <div class="text-4xl opacity-20">📈</div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-orange-700 text-sm font-semibold mb-2">🛍️ Produits</p>
                    <p class="text-3xl font-bold text-orange-900" x-text="indicators.totalProducts"></p>
                    <p class="text-orange-600 text-xs mt-2">En catalogue</p>
                </div>
                <div class="text-4xl opacity-20">🛍️</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span><x-heroicon-o-chart-bar class="w-5 h-5" /></span>
                Tendance des Ventes
            </h3>
            <div id="salesChart" style="height: 320px;"></div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>🏆</span>
                Top Produits
            </h3>
            <div class="space-y-3">
                <template x-each="topProducts" :key="product.nom">
                    <div class="p-3 bg-gradient-to-r from-primary-50 to-blue-50 rounded-lg border border-primary-200">
                        <div class="flex items-start justify-between mb-1">
                            <p class="font-semibold text-gray-900 text-sm truncate" x-text="product.nom"></p>
                            <span class="text-xs bg-primary-600 text-white px-2 py-1 rounded-full" x-text="product.quantite + ' ventes'"></span>
                        </div>
                        <p class="text-primary-700 font-bold text-sm" x-text="formatCurrency(product.ventes)"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Inventory Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stock Status -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span><x-heroicon-o-cube class="w-5 h-5" /></span>
                État du Stock
            </h3>
            <div id="inventoryChart" style="height: 320px;"></div>
        </div>

        <!-- Summary -->
        <div class="space-y-3">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <p class="text-green-700 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-check-circle class="w-4 h-4" /><span>Stock Bon</span></p>
                <p class="text-2xl font-bold text-green-900" x-text="inventoryStatus['Bon'] || 0"></p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                <p class="text-yellow-700 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-exclamation-triangle class="w-4 h-4" /><span>Stock Bas</span></p>
                <p class="text-2xl font-bold text-yellow-900" x-text="(inventoryStatus['Bas'] || 0) + (inventoryStatus['Critique'] || 0)"></p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                <p class="text-red-700 text-sm font-semibold flex items-center gap-1"><x-heroicon-o-x-circle class="w-4 h-4" /><span>Rupture</span></p>
                <p class="text-2xl font-bold text-red-900" x-text="inventoryStatus['Rupture'] || 0"></p>
            </div>
        </div>
    </div>

    <!-- Customer Satisfaction -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rating Distribution -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span><x-heroicon-o-star class="w-5 h-5" /></span>
                Évaluations Clients
            </h3>
            <div id="ratingChart" style="height: 320px;"></div>
        </div>

        <!-- Satisfaction Score -->
        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl p-6 border border-cyan-200 hover:shadow-lg transition">
            <p class="text-cyan-700 text-sm font-semibold mb-4 flex items-center gap-1"><x-heroicon-o-star class="w-4 h-4" /><span>Satisfaction Client</span></p>

            <div class="mb-6">
                <div class="text-4xl font-black text-cyan-900 mb-2" x-text="customerMetrics.averageRating + '/5'"></div>
                <div class="flex gap-1">
                    <template x-each="[1,2,3,4,5]">
                        <span :class="__.index + 1 <= Math.round(customerMetrics.averageRating) ? 'text-yellow-400' : 'text-gray-300'"><x-heroicon-o-star class="w-5 h-5" /></span>
                    </template>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-cyan-700 text-sm font-semibold mb-1">Critiques</p>
                    <p class="text-2xl font-bold text-cyan-900" x-text="customerMetrics.totalReviews"></p>
                </div>
                <div>
                    <p class="text-cyan-700 text-sm font-semibold mb-1">Satisfaction Positive</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-cyan-200 rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-600 rounded-full" :style="`width: ${customerMetrics.positivePercentage}%`"></div>
                        </div>
                        <span class="font-bold text-cyan-900 text-sm" x-text="customerMetrics.positivePercentage + '%'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@4.0.1/dist/apexcharts.min.js"></script>

<script>
function vendorStats() {
    return {
        period: 7,
        indicators: { totalVentes: 0, totalCommandes: 0, averageOrderValue: 0, totalProducts: 0 },
        topProducts: [],
        inventoryStatus: { 'Bon': 0, 'Bas': 0, 'Critique': 0, 'Rupture': 0 },
        customerMetrics: { averageRating: 0, totalReviews: 0, positivePercentage: 0 },
        chartData: { dates: [], ventes: [], commandes: [] },

        async init() {
            await Promise.all([
                this.loadSalesData(),
                this.loadInventoryStatus(),
                this.loadCustomerMetrics()
            ]);
        },

        async changePeriod(days) {
            this.period = days;
            await this.loadSalesData();
        },

        async loadSalesData() {
            try {
                const response = await fetch(`{{ route('vendeur:statistics.sales') }}?days=${this.period}`);
                const data = await response.json();
                if (data.success) {
                    this.chartData = data.data;
                    this.indicators = data.data.indicators;
                    this.topProducts = data.data.topProducts;
                    this.$nextTick(() => this.renderSalesChart());
                }
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
            }
        },

        async loadInventoryStatus() {
            try {
                const response = await fetch(`{{ route('vendeur:statistics.inventory') }}`);
                const data = await response.json();
                if (data.success) {
                    this.inventoryStatus = data.data;
                    this.$nextTick(() => this.renderInventoryChart());
                }
            } catch (error) {
                console.error('Erreur lors du chargement du stock:', error);
            }
        },

        async loadCustomerMetrics() {
            try {
                const response = await fetch(`{{ route('vendeur:statistics.customers') }}`);
                const data = await response.json();
                if (data.success) {
                    this.customerMetrics = data.data;
                    this.$nextTick(() => this.renderRatingChart());
                }
            } catch (error) {
                console.error('Erreur lors du chargement des métriques client:', error);
            }
        },

        renderSalesChart() {
            const options = {
                chart: { type: 'area', height: 320, sparkline: { enabled: false }, toolbar: { show: true } },
                dataLabels: { enabled: false },
                series: [
                    { name: 'Ventes (XOF)', data: this.chartData.ventes },
                    { name: 'Commandes', data: this.chartData.commandes, yaxis: { opposite: true } }
                ],
                xaxis: { categories: this.chartData.dates },
                colors: ['#7C3AED', '#06B6D4'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0 } },
                yaxis: [
                    { title: { text: 'Ventes (XOF)', style: { color: '#7C3AED' } }, labels: { style: { colors: '#7C3AED' } } },
                    { opposite: true, title: { text: 'Commandes', style: { color: '#06B6D4' } }, labels: { style: { colors: '#06B6D4' } } }
                ]
            };
            new ApexCharts(document.getElementById('salesChart'), options).render();
        },

        renderInventoryChart() {
            const options = {
                chart: { type: 'donut' },
                series: [this.inventoryStatus['Bon'], this.inventoryStatus['Bas'], this.inventoryStatus['Critique'], this.inventoryStatus['Rupture']],
                labels: ['✅ Bon', '⚠️ Bas', '🔴 Critique', '❌ Rupture'],
                colors: ['#10B981', '#F59E0B', '#EF4444', '#7F1D1D'],
                plotOptions: { pie: { donut: { size: '75%' } } }
            };
            new ApexCharts(document.getElementById('inventoryChart'), options).render();
        },

        renderRatingChart() {
            const rating = this.customerMetrics.ratingDistribution;
            const options = {
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Nombre d\'avis', data: [rating[5], rating[4], rating[3], rating[2], rating[1]] }],
                xaxis: { categories: ['⭐⭐⭐⭐⭐', '⭐⭐⭐⭐', '⭐⭐⭐', '⭐⭐', '⭐'] },
                colors: ['#FBBF24'],
                plotOptions: { bar: { horizontal: true } }
            };
            new ApexCharts(document.getElementById('ratingChart'), options).render();
        },

        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', maximumFractionDigits: 0 }).format(value || 0);
        }
    };
}
</script>
