@extends('layouts.app')

@section('content')
<div class="max-w-[1100px] mx-auto px-8 py-10 pb-20">

    {{-- ── HEADER ── --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <nav class="flex items-center gap-1.5 text-[12px] text-[#a0a09a] mb-3">
                <a href="{{ route('accueil') }}" class="hover:text-[#0a0a0a] transition-colors">Accueil</a>
                <span class="text-[#e0e0dc]">/</span>
                <span class="text-[#0a0a0a] font-medium">Mes Favoris</span>
            </nav>
            <h1 class="font-serif text-[28px] tracking-tight text-[#0a0a0a] leading-none">
                Mes <em class="italic text-[#666660]">Favoris</em>
            </h1>
            <p class="text-[13px] text-[#a0a09a] font-light mt-1">
                @auth
                    {{ $favoris->total() }} produit{{ $favoris->total() > 1 ? 's' : '' }} sauvegardé{{ $favoris->total() > 1 ? 's' : '' }}
                @else
                    <span id="favorite-count">Chargement…</span>
                @endauth
            </p>
        </div>
    </div>

    {{-- ══════════════════════════════
         UTILISATEUR CONNECTÉ
    ══════════════════════════════ --}}
    @auth
        @if($favoris->count() > 0)

            {{-- Grille --}}
            <div class="grid grid-cols-4 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden mb-6">
                @foreach($favoris as $produit)
                    @include('components.carte-produit', ['produit' => $produit])
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($favoris->hasPages())
                <div class="flex items-center justify-center gap-1.5 mb-8">
                    @if($favoris->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-sm cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $favoris->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-sm hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">‹</a>
                    @endif
                    @foreach($favoris->getUrlRange(max(1,$favoris->currentPage()-2), min($favoris->lastPage(),$favoris->currentPage()+2)) as $page => $url)
                        @if($page == $favoris->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[13px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-[13px] font-mono hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($favoris->hasMorePages())
                        <a href="{{ $favoris->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660] text-sm hover:border-[#0a0a0a] hover:text-[#0a0a0a] transition-all">›</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-sm cursor-not-allowed">›</span>
                    @endif
                </div>
            @endif

            {{-- CTA panier --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-5 flex items-center justify-between">
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a]">Prêt à commander ?</div>
                    <div class="text-[12px] text-[#a0a09a] font-light mt-0.5">Consultez votre panier et finalisez votre commande</div>
                </div>
                <a href="{{ route('panier.index') }}"
                   class="flex items-center gap-2 bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    Voir mon panier
                </a>
            </div>

        @else
            {{-- Empty state connecté --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-16 text-center">
                <svg class="w-9 h-9 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Aucun favori pour le moment</h2>
                <p class="text-[13px] text-[#a0a09a] font-light mb-6 max-w-xs mx-auto">
                    Explorez le catalogue et ajoutez vos produits préférés en cliquant sur le cœur
                </p>
                <a href="{{ route('produits.catalogue') }}"
                   class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-6 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    Découvrir des produits
                </a>
            </div>
        @endif

    {{-- ══════════════════════════════
         NON CONNECTÉ (localStorage)
    ══════════════════════════════ --}}
    @else

        {{-- Grille dynamique --}}
        <div id="favorites-container">
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-12 text-center">
                <div class="w-5 h-5 border-2 border-[#e0e0dc] border-t-[#0a0a0a] rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-[13px] text-[#a0a09a] font-light">Chargement de vos favoris…</p>
            </div>
        </div>

        {{-- CTA connexion --}}
        <div class="mt-6 bg-white border border-[#e0e0dc] rounded-xl px-6 py-6">
            <div class="text-[13px] font-medium text-[#0a0a0a] mb-1">Sauvegardez vos favoris</div>
            <p class="text-[12px] text-[#a0a09a] font-light mb-4">
                Connectez-vous pour retrouver vos produits préférés depuis n'importe quel appareil
            </p>
            <div class="flex gap-2">
                <a href="{{ route('login') }}"
                   class="bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    Se connecter
                </a>
                <a href="{{ route('register') }}"
                   class="text-[12px] text-[#666660] border border-[#e0e0dc] px-5 py-2.5 rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                    Créer un compte
                </a>
            </div>
        </div>

    @endauth
</div>

@guest
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const favoriteIds = JSON.parse(localStorage.getItem('favorites') || '[]');
    const container   = document.getElementById('favorites-container');
    const countEl     = document.getElementById('favorite-count');

    if (countEl) countEl.textContent = `${favoriteIds.length} produit${favoriteIds.length > 1 ? 's' : ''} sauvegardé${favoriteIds.length > 1 ? 's' : ''}`;

    if (favoriteIds.length === 0) {
        container.innerHTML = `
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-16 text-center">
                <svg class="w-9 h-9 text-[#e0e0dc] mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2 class="text-[15px] font-medium text-[#0a0a0a] mb-2">Aucun favori pour le moment</h2>
                <p class="text-[13px] text-[#a0a09a] font-light mb-6">Explorez le catalogue et cliquez sur le cœur pour sauvegarder</p>
                <a href="{{ route('produits.catalogue') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-6 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    Découvrir des produits
                </a>
            </div>`;
        return;
    }

    try {
        const response = await fetch(`/api/produits/${favoriteIds.join(',')}`);
        if (!response.ok) throw new Error();
        const produits = await response.json();

        // Grille gap-px style
        let html = `<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#e0e0dc;border:1px solid #e0e0dc;border-radius:12px;overflow:hidden;margin-bottom:24px;">`;

        produits.forEach(p => {
            const imageHtml = p.image
                ? `<img src="/storage/produits/${p.image}" alt="${p.nom}" style="width:100%;height:100%;object-fit:cover">`
                : `<svg style="width:32px;height:32px;color:#e0e0dc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/></svg>`;

            const prix = parseFloat(p.prix).toLocaleString('fr-FR', {minimumFractionDigits:0, maximumFractionDigits:0});

            html += `
                <div style="background:white;display:flex;flex-direction:column;">
                    <a href="/produits/${p.id}" style="display:flex;align-items:center;justify-content:center;height:160px;background:#f7f7f5;overflow:hidden;">
                        ${imageHtml}
                    </a>
                    <div style="padding:14px 16px 16px;flex:1;display:flex;flex-direction:column;gap:4px;">
                        <a href="/produits/${p.id}" style="font-size:13px;font-weight:500;color:#0a0a0a;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-decoration:none;">${p.nom}</a>
                        <div style="font-family:monospace;font-size:14px;font-weight:500;color:#0a0a0a;margin-top:auto;padding-top:8px;">${prix} <span style="font-size:10px;color:#a0a09a;font-weight:400;">FCFA</span></div>
                        <div style="display:flex;gap:6px;margin-top:8px;">
                            <a href="/produits/${p.id}" style="flex:1;text-align:center;font-size:11px;padding:6px 0;border:1px solid #e0e0dc;border-radius:6px;color:#666660;text-decoration:none;">Détails</a>
                            <button onclick="toggleFavorite(${p.id}, event)" data-favorite-btn="${p.id}"
                                style="width:30px;height:30px;border:1px solid #e0e0dc;border-radius:6px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <svg style="width:14px;height:14px;color:#dc2626;fill:#dc2626" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
        });

        html += `</div>`;
        container.innerHTML = html;

    } catch(e) {
        container.innerHTML = `
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-10 text-center">
                <svg class="w-8 h-8 text-[#e0e0dc] mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p class="text-[13px] text-[#a0a09a] font-light">Erreur lors du chargement — rafraîchissez la page</p>
            </div>`;
    }
});
</script>
@endguest

@endsection
