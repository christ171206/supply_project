{{-- Catégories Section Minimaliste --}}
<div class="max-w-7xl mx-auto px-8 py-16 border-b border-gray-200">
    <h2 class="text-2xl font-display font-bold text-black mb-2">Catégories</h2>
    <p class="text-gray-600 text-sm mb-8">Explorez nos collections</p>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @foreach($categories as $categorie)
        <a href="{{ route('produits.catalogue', ['categorie' => $categorie->id]) }}" class="group">
            <div class="relative h-40 mb-4 rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center hover:border-black transition-colors duration-150">
                @if($categorie->image)
                    <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                @else
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                @endif
            </div>
            <p class="text-sm font-medium text-black text-center group-hover:text-gray-600 transition-colors duration-150">{{ $categorie->nom }}</p>
        </a>
        @endforeach
    </div>
</div>
