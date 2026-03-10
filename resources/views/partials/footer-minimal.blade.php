{{-- Footer Minimaliste --}}
<footer class="bg-black text-white">
  <div class="max-w-7xl mx-auto px-8 py-16">
    {{-- Top Section --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-12 border-b border-gray-800">
      {{-- Brand --}}
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-white rounded-md flex items-center justify-center">
            <svg class="w-4 h-4 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
          </div>
          <span class="font-display text-lg font-bold">Supply</span>
        </div>
        <p class="text-gray-400 text-sm">Marketplace B2B pour le matériel informatique en Côte d'Ivoire</p>
      </div>

      {{-- Buyers --}}
      <div>
        <h4 class="font-display font-bold text-white mb-4">Acheteurs</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="{{ route('produits.catalogue') }}" class="hover:text-white transition-colors duration-150">Catalogue</a></li>
          <li><a href="{{ route('panier.index') }}" class="hover:text-white transition-colors duration-150">Mon panier</a></li>
          <li><a href="{{ route('favoris.index') }}" class="hover:text-white transition-colors duration-150">Favoris</a></li>
          <li><a href="{{ route('commandes.index') }}" class="hover:text-white transition-colors duration-150">Mes achats</a></li>
        </ul>
      </div>

      {{-- Sellers --}}
      <div>
        <h4 class="font-display font-bold text-white mb-4">Vendeurs</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="{{ route('register') }}" class="hover:text-white transition-colors duration-150">Devenir vendeur</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">Tableau de bord</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">Gestion produits</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">Commandes</a></li>
        </ul>
      </div>

      {{-- Support --}}
      <div>
        <h4 class="font-display font-bold text-white mb-4">Support</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="#" class="hover:text-white transition-colors duration-150">Contact</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">FAQ</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">Conditions</a></li>
          <li><a href="#" class="hover:text-white transition-colors duration-150">Confidentialité</a></li>
        </ul>
      </div>
    </div>

    {{-- Bottom Section --}}
    <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
      <p>© 2026 Supply. Tous droits réservés.</p>
      <p>Plateforme e-commerce · Côte d'Ivoire</p>
    </div>
  </div>
</footer>
