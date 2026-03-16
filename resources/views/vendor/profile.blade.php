@extends('layouts.app')

@section('title', $vendor->shop_name ?? $vendor->name . ' - Profil Vendeur')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="flex gap-6 items-start">
                <!-- Avatar -->
                <div class="w-32 h-32 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                    @if($vendor->profile_photo)
                        <img src="{{ $vendor->profile_photo }}" alt="{{ $vendor->name }}" class="w-full h-full rounded-lg object-cover">
                    @else
                        <span class="text-5xl">👤</span>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h1 class="text-3xl font-display font-bold text-black mb-2">
                        {{ $vendor->shop_name ?? $vendor->name }}
                    </h1>
                    <div class="flex gap-3 mb-4 flex-wrap">
                        @if($vendor->avisRecus && $vendor->avisRecus->avg('note') >= 4.5)
                            <span class="inline-flex items-center gap-1 bg-blue-50 px-3 py-1 rounded text-sm font-semibold">
                                💎 Premier Vendeur
                            </span>
                        @endif
                        @if($vendor->created_at->diffInDays(now()) <= 30)
                            <span class="inline-flex items-center gap-1 bg-yellow-50 px-3 py-1 rounded text-sm font-semibold">
                                🌟 Nouveau
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600 mb-3">{{ $vendor->address ?? 'Adresse non renseignée' }}</p>
                </div>

                <!-- Gamification Stats -->
                <div id="gamificationStats" class="bg-gray-50 border border-gray-200 rounded p-6 min-w-64">
                    <div class="text-center">
                        <p class="text-4xl mb-2" id="tierEmoji">⭐</p>
                        <p class="font-semibold text-black mb-2" id="tierName">Chargement...</p>
                        <div class="h-2 bg-gray-200 rounded-full mb-2" id="levelBar">
                            <div class="h-full bg-black rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-600" id="pointsText">Points: --</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Badges Section -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-bold font-display text-black mb-4">🏆 Badges Déverrouillés</h2>
                    <div id="badgesContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <!-- Badges loaded via JS -->
                        <div class="text-center py-8 col-span-full text-gray-400">Chargement...</div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-smuppercase text-gray-400 font-semibold mb-2">Produits</p>
                        <p class="text-3xl font-mono font-bold" id="productCount">--</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-sm uppercase text-gray-400 font-semibold mb-2">Note Moyenne</p>
                        <p class="text-3xl font-mono font-bold" id="avgRating">--</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-sm uppercase text-gray-400 font-semibold mb-2">Avis Reçus</p>
                        <p class="text-3xl font-mono font-bold" id="reviewCount">--</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-sm uppercase text-gray-400 font-semibold mb-2">Ventes</p>
                        <p class="text-3xl font-mono font-bold" id="saleCount">--</p>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold font-display text-black mb-4">Produits en Vedette</h2>
                    <div id="productsGrid" class="grid grid-cols-2 gap-4">
                        <!-- Products loaded via JS -->
                        <div class="col-span-full text-center py-12 text-gray-400">Chargement des produits...</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Follow Button -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 sticky top-4">
                    <button id="followBtn" class="w-full px-4 py-3 bg-black text-white rounded font-semibold hover:bg-gray-800 transition duration-200 mb-3">
                        👥 Suivre ce Vendeur
                    </button>
                    <p class="text-sm text-gray-600 text-center">
                        <span id="followerCount">--</span> abonnés
                    </p>
                </div>

                <!-- Contact -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                    <h3 class="font-bold text-black mb-4">📞 Contactez-nous</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $vendor->phone ?? 'Non renseigné' }}</p>
                    <p class="text-sm text-gray-600">{{ $vendor->email }}</p>
                </div>

                <!-- Promotions Badge -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">🎉 Offres Spéciales</p>
                    <p class="text-xs text-blue-800">Ce vendeur a des promotions actives. Consultez la boutique pour les bénéficier!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const vendorId = {{ $vendor->id }};

    // Load gamification data
    async function loadGamification() {
        try {
            const response = await fetch(`/api/gamification/profile`);
            const data = await response.json();

            const stats = data.points;
            document.getElementById('tierEmoji').textContent = stats.tier_emoji;
            document.getElementById('tierName').textContent = `${stats.tier.toUpperCase()} - Niveau ${stats.level}`;
            document.getElementById('pointsText').textContent = `${stats.total} points`;

            const levelPercent = (stats.total % 50) / 50 * 100;
            document.querySelector('#gamificationStats .h-full').style.width = levelPercent + '%';

            // Load badges
            loadBadges(data.badges);
        } catch (error) {
            console.error('Error loading gamification:', error);
        }
    }

    function loadBadges(badges) {
        const container = document.getElementById('badgesContainer');
        if (badges.length === 0) {
            container.innerHTML = '<p class="col-span-full text-center text-gray-400 py-8">Aucun badge déverrouillé encore</p>';
            return;
        }

        container.innerHTML = badges.map(badge => `
            <div class="text-center p-3 border border-gray-200 rounded-lg hover:shadow-md transition">
                <p class="text-3xl mb-2">${badge.emoji}</p>
                <p class="font-semibold text-xs text-black">${badge.name}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $vendor->created_at->diffInDays(now()) }}j</p>
            </div>
        `).join('');
    }

    // Load vendor products
    async function loadProducts()  {
        try {
            const response = await fetch(`/vendor/{{ $vendor->id }}/api/products`);
            const products = await response.json();

            const grid = document.getElementById('productsGrid');
            const count = products.length;

            document.getElementById('productCount').textContent = count;

            if (count === 0) {
                grid.innerHTML = '<p class="col-span-full text-gray-400">Aucun produit</p>';
                return;
            }

            grid.innerHTML = products.slice(0, 6).map(p => `
                <a href="/produits/${p.id}" class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                    <div class="bg-gray-100 h-32 flex items-center justify-center text-3xl">
                        ${p.image_emoji || '📦'}
                    </div>
                    <div class="p-3">
                        <h4 class="font-semibold text-sm text-black line-clamp-2">${p.nom}</h4>
                        <p class="font-mono font-bold text-sm">${p.prix.toLocaleString('fr-CI')} F</p>
                    </div>
                </a>
            `).join('');
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadGamification();
        loadProducts();
    });
</script>
@endsection
