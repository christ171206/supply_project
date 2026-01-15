<!-- Produits en vedette -->
<div class="relative py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gray-900 mb-4">Produits en Vedette</h2>
            <p class="text-xl text-gray-600">Nos meilleures ventes de la semaine</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($produits as $produit)
                @include('components.carte-produit', ['produit' => $produit])
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">Aucun produit disponible pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
