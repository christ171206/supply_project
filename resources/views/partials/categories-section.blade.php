<!-- Catégories Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 bg-gradient-to-b from-blue-50 to-slate-50">
    <div class="text-center mb-16">
        <h2 class="text-5xl font-bold text-gray-900 mb-4">Explorez nos Catégories</h2>
        <p class="text-xl text-gray-600">Trouvez exactement ce qu'il vous faut</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @foreach($categories as $categorie)
        <a href="{{ route('produits.catalogue', ['categorie' => $categorie->id]) }}" class="group card-hover">
            <div class="relative h-40 mb-4 rounded-xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-50">
                @if($categorie->image)
                    <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="flex items-center justify-center h-full bg-gradient-to-br from-primary-100 to-accent-50">
                        <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
            </div>
            <h3 class="font-bold text-gray-900 text-center group-hover:text-primary-600 transition-colors">{{ $categorie->nom }}</h3>
        </a>
        @endforeach
    </div>
</div>
