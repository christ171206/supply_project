{{-- CTA Section Minimaliste --}}
<div class="max-w-7xl mx-auto px-8 py-16">
    <div class="bg-black text-white rounded-lg p-12 text-center">
        <h3 class="text-2xl font-display font-bold mb-3">Rejoignez Supply</h3>
        <p class="text-gray-300 text-sm mb-8 max-w-2xl mx-auto">
            Accédez aux meilleurs produits informatiques, avec livraison à Abidjan et partout en Côte d'Ivoire
        </p>
        @guest
        <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-white text-black font-medium rounded-lg hover:opacity-85 transition-opacity duration-150">
            Créer un compte
        </a>
        @endguest
    </div>
</div>
