{{-- Produits en Vedette Minimaliste - Design Neutral Minimal --}}

<div class="section">
  <div class="section-head">
    <div>
      <div class="section-title">Produits <em>en vedette</em></div>
      <div class="section-subtitle">Nos meilleures ventes de la semaine</div>
    </div>
    <a href="{{ route('produits.catalogue') }}" class="section-link">Voir tout →</a>
  </div>

  <div class="filter-bar">
    <span class="filter-chip active">Tous</span>
    @foreach($categories ?? [] as $cat)
    <span class="filter-chip">{{ $cat->nom ?? $cat }}</span>
    @endforeach
  </div>

  <div class="product-grid">
    @forelse($produits as $produit)
      <x-product-card-minimal :product="$produit" />
    @empty
      <div class="col-span-4 text-center py-12">
        <p class="text-[#666660] text-[13px]">Aucun produit disponible pour le moment</p>
      </div>
    @endforelse
  </div>
</div>
