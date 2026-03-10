{{-- Hero Section Minimaliste - Design Neutral Minimal --}}

<div class="hero">
  <div class="hero-text">
    <div class="hero-eyebrow">
      <div class="hero-eyebrow-line"></div>
      Marketplace B2B · Côte d'Ivoire
    </div>
    <h1 class="hero-title">Le matériel tech,<br><em>sans compromis.</em></h1>
    <p class="hero-desc">Des milliers de produits informatiques sourcés directement auprès de vendeurs vérifiés. Livraison rapide, prix transparents.</p>
    <div class="hero-actions">
      <a href="{{ route('produits.catalogue') }}" class="btn-primary">Explorer le catalogue</a>
      @guest
      <a href="{{ route('register') }}" class="btn-ghost">
        Devenir vendeur
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      @endguest
    </div>
    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">{{ $total_produits ?? '2 400+' }}</div>
        <div class="stat-label">Produits listés</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $total_vendeurs ?? '186' }}</div>
        <div class="stat-label">Vendeurs actifs</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">99%</div>
        <div class="stat-label">Vendeurs vérifiés</div>
      </div>
    </div>
  </div>

  <div class="hero-visual">
    <div class="hero-card-mini">
      <div class="hcm-img">🖥️</div>
      <div>
        <div class="hcm-name">Intel Core i7-13700K</div>
        <div class="hcm-price">480 000 FCFA</div>
      </div>
    </div>
    <div class="hero-card-mini">
      <div class="hcm-img-sq">🎮</div>
      <div class="hcm-name">AMD Ryzen 9</div>
      <div class="hcm-price">680 000 FCFA</div>
    </div>
    <div class="hero-card-mini">
      <div class="hcm-img-sq">💾</div>
      <div class="hcm-name">DDR5 32 Go</div>
      <div class="hcm-price">220 000 FCFA</div>
    </div>
  </div>
</div>

<hr class="page-divider">
