@extends('layouts.app')

@section('content')
<div class="max-w-[1100px] mx-auto px-4 sm:px-6 md:px-8 py-6 sm:py-8 pb-20">

    {{-- ── BREADCRUMB ── --}}
    <nav class="flex items-center gap-1.5 mb-6 sm:mb-8 text-[11px] sm:text-[12px] text-[#a0a09a] overflow-x-auto">
        <a href="{{ route('accueil') }}" class="hover:text-[#0a0a0a] transition-colors">Accueil</a>
        <span class="text-[#e0e0dc]">/</span>
        <a href="{{ route('produits.catalogue') }}" class="hover:text-[#0a0a0a] transition-colors">Catalogue</a>
        @if($produit->categorie)
            <span class="text-[#e0e0dc]">/</span>
            <a href="{{ route('produits.catalogue', ['categorie' => $produit->categorie->id]) }}" class="hover:text-[#0a0a0a] transition-colors">
                {{ $produit->categorie->nom }}
            </a>
        @endif
        <span class="text-[#e0e0dc]">/</span>
        <span class="text-[#0a0a0a] font-medium">{{ Str::limit($produit->nom, 40) }}</span>
    </nav>

    {{-- ══════════════════════════════
         LAYOUT PRINCIPAL responsive
    ══════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr] gap-8 md:gap-16 mb-12 md:mb-16">

        {{-- ── GALERIE ── --}}
        <div class="sticky top-[72px] self-start md:order-first order-first" style="z-index: 10;">
            {{-- Détecter ancien vs nouveau format d'images --}}
            @php
                $productImages = $produit->images && is_array($produit->images)
                    ? array_map(function($img) {
                        return strpos($img, 'produits/') === 0 ? $img : 'produits/' . $img;
                    }, $produit->images)
                    : [];
            @endphp

            {{-- Image principale --}}
            <div class="w-full aspect-square rounded-xl border border-[#e0e0dc] bg-white overflow-hidden flex items-center justify-center mb-3">
                @if($productImages && count($productImages) > 0)
                    <img
                        src="{{ asset('storage/' . $productImages[0]) }}"
                        alt="{{ $produit->nom }}"
                        class="w-full h-full object-cover lazy"
                        loading="lazy"
                        id="main-image"
                    >
                @elseif($produit->image)
                    <img
                        src="{{ asset('storage/produits/' . $produit->image) }}"
                        alt="{{ $produit->nom }}"
                        class="w-full h-full object-cover"
                        id="main-image"
                    >
                @else
                    <svg class="w-16 h-16 text-[#e0e0dc]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($productImages && count($productImages) > 1)
                <div class="flex gap-2 flex-wrap md:flex-nowrap">
                    @foreach($productImages as $index => $img)
                        <button
                            onclick="document.getElementById('main-image').src='{{ asset('storage/' . $img) }}';     document.querySelectorAll('.thumb-btn').forEach(b=>b.classList.remove('border-[#0a0a0a]'));     this.classList.add('border-[#0a0a0a]')"
                            class="thumb-btn flex-1 md:flex-none w-12 h-12 sm:w-14 sm:h-14 md:w-14 md:h-14 rounded-lg border overflow-hidden transition-colors {{ $index === 0 ? 'border-[#0a0a0a]' : 'border-[#e0e0dc] hover:border-[#a0a09a]' }}"
                        >
                            <img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-full object-cover lazy" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @elseif($produit->image)
                <div class="flex gap-2">
                    <div class="w-14 h-14 rounded-lg border border-[#0a0a0a] overflow-hidden">
                        <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
            @endif
        </div>

        {{-- ── INFOS PRODUIT ── --}}
        <div class="order-last md:order-last">
            {{-- Catégorie eyebrow --}}
            @if($produit->categorie)
                <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">
                    {{ $produit->categorie->nom }}
                </div>
            @endif

            {{-- Nom --}}
            <h1 class="font-serif text-xl sm:text-3xl md:text-4xl lg:text-[36px] tracking-tight leading-[1.1] text-[#0a0a0a] mb-4 sm:mb-5">
                {{ $produit->nom }}
            </h1>

            {{-- Vendeur --}}
            @if($produit->vendeur)
                <div class="flex items-center gap-3 py-4 border-t border-b border-[#efefed] mb-6">
                    <div class="w-8 h-8 bg-[#0a0a0a] text-white rounded-lg flex items-center justify-center text-[12px] font-medium flex-shrink-0">
                        {{ strtoupper(substr($produit->vendeur->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-0.5">Vendu par</div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->vendeur->shop_name ?? $produit->vendeur->name }}</div>
                    </div>
                </div>
            @endif

            {{-- Prix --}}
            <div class="mb-4 sm:mb-5 pb-4 sm:pb-5 border-b border-[#efefed]">
                @php
                    $flashSale = null;
                    if($produit->categorie) {
                        $flashSale = \App\Models\FlashSale::where('categorie_id', $produit->categorie->id)
                            ->where('statut', 'actif')
                            ->whereDate('date_fin', '>=', now())
                            ->first();
                    }
                    $prixAffiche = $flashSale && $flashSale->isActive() ? $flashSale->prixReduit($produit->prix) : $produit->prix;
                @endphp
                <div class="flex flex-wrap items-baseline gap-2 sm:gap-3 mb-3">
                    @if($flashSale && $flashSale->isActive())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-[11px] font-medium">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                            Vente Flash -{{ $flashSale->pourcentage_reduction }}%
                        </span>
                    @endif
                    <span class="font-mono text-2xl sm:text-3xl md:text-4xl lg:text-[32px] font-medium tracking-tight text-[#0a0a0a] leading-none">
                        {{ number_format($prixAffiche, 0, ',', ' ') }}
                    </span>
                    <span class="text-[13px] sm:text-[14px] text-[#a0a09a] font-light">FCFA</span>
                    @if($flashSale && $flashSale->isActive())
                        <span class="text-[13px] text-[#a0a09a] line-through font-mono">{{ number_format($produit->prix, 0, ',', ' ') }}</span>
                    @elseif($produit->prix_original && $produit->prix_original > $produit->prix)
                        <span class="text-[13px] text-[#a0a09a] line-through font-mono">{{ number_format($produit->prix_original, 0, ',', ' ') }}</span>
                        <span class="text-[10px] font-medium bg-[#f0fdf4] text-[#15803d] px-2 py-0.5 rounded">
                            -{{ round((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100) }}%
                        </span>
                    @endif
                </div>
                @if($flashSale && $flashSale->isActive())
                    <p class="text-[12px] text-[#a0a09a]">
                        ⏱ Offre valide jusqu'au {{ $flashSale->date_fin->format('d/m/Y') }}
                    </p>
                @endif
            </div>

            {{-- Stock --}}
            <div class="flex items-center gap-2 mb-6 pb-6 border-b border-[#efefed]">
                @if($produit->stock > 0)
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                    <span class="text-[13px] text-[#666660] font-light">{{ $produit->stock }} en stock</span>
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
                    <span class="text-[13px] text-[#666660] font-light">Rupture de stock</span>
                @endif
            </div>

            {{-- Description --}}
            <div class="mb-8">
                <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Description</div>
                <p class="text-[14px] text-[#666660] font-light leading-relaxed">{{ $produit->description }}</p>
            </div>

            {{-- CTAs --}}
            <div class="flex flex-col gap-2.5 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row gap-2.5">
                    @if($produit->stock > 0)
                        <button
                            type="button"
                            onclick="openQuantityModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->stock }}, {{ $produit->prix }})"
                            class="flex-1 py-3 sm:py-4 bg-[#0a0a0a] text-white text-[12px] sm:text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity"
                        >
                            Ajouter au Panier
                        </button>
                    @else
                        <button disabled class="flex-1 py-3 sm:py-4 bg-[#f7f7f5] text-[#a0a09a] text-[12px] sm:text-[13px] font-medium rounded-lg cursor-not-allowed border border-[#e0e0dc]">
                            Indisponible
                        </button>
                    @endif
                    <button
                        onclick="toggleFavorite({{ $produit->id }}, event)"
                        data-favorite-btn="{{ $produit->id }}"
                        class="w-12 sm:w-14 h-12 sm:h-14 flex items-center justify-center border border-[#e0e0dc] rounded-lg hover:border-[#2a2a28] transition-colors flex-shrink-0"
                        title="Ajouter aux favoris"
                    >
                        <svg class="w-4 h-4 text-[#666660]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </div>

                {{-- Contacter vendeur --}}
                @if($produit->vendeur)
                    <button
                        type="button"
                        onclick="openContactModal({{ $produit->vendeur->id }}, '{{ addslashes($produit->vendeur->shop_name ?? $produit->vendeur->name) }}', {{ $produit->id }}, '{{ addslashes($produit->nom) }}')"
                        class="w-full flex items-center justify-center gap-2 py-3 sm:py-4 border border-[#e0e0dc] rounded-lg text-[12px] sm:text-[13px] text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all"
                    >
                        <svg class="w-4 h-4 text-[#0a0a0a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Contacter le vendeur
                    </button>
                @endif

                {{-- WhatsApp — sobre, pas de vert plein écran --}}
                @if($produit->vendeur)
                    <a
                        href="https://wa.me/{{ config('services.whatsapp.contact_phone') }}?text={{ urlencode('Bonjour, je suis intéressé par : ' . $produit->nom . ' — ' . url(route('produits.show', $produit->id))) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center gap-2 py-3 sm:py-4 border border-[#e0e0dc] rounded-lg text-[12px] sm:text-[13px] text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all"
                    >
                        <svg class="w-4 h-4 text-[#22c55e]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                        </svg>
                        Contacter sur WhatsApp
                    </a>
                @endif
            </div>

            {{-- Tableau infos produit --}}
            <div class="border border-[#e0e0dc] rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-[#f7f7f5] border-b border-[#efefed]">
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a]">Informations produit</span>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-3 border-b border-r border-[#efefed]">
                        <div class="text-[11px] text-[#a0a09a] mb-1">Référence</div>
                        <div class="text-[12px] font-mono font-medium text-[#0a0a0a]">#{{ $produit->id }}</div>
                    </div>
                    <div class="px-4 py-3 border-b border-[#efefed]">
                        <div class="text-[11px] text-[#a0a09a] mb-1">Catégorie</div>
                        <div class="text-[12px] font-mono font-medium text-[#0a0a0a]">{{ $produit->categorie?->nom ?? '—' }}</div>
                    </div>
                    <div class="px-4 py-3 border-r border-[#efefed]">
                        <div class="text-[11px] text-[#a0a09a] mb-1">Stock</div>
                        <div class="text-[12px] font-mono font-medium text-[#0a0a0a]">{{ $produit->stock }} unités</div>
                    </div>
                    <div class="px-4 py-3">
                        <div class="text-[11px] text-[#a0a09a] mb-1">Statut</div>
                        <div class="text-[12px] font-mono font-medium {{ $produit->est_actif ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                            {{ $produit->est_actif ? 'Actif' : 'Inactif' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════
         AVIS CLIENTS
    ══════════════════════════════ --}}
    <div class="mb-12">
        {{-- En-tête avec note moyenne --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-5 flex items-start justify-between md:flex-row flex-col gap-6">
                {{-- Note moyenne --}}
                <div class="flex items-center gap-6">
                    @if($nombreAvis > 0)
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#0a0a0a] font-mono mb-1">
                                {{ number_format($noteMoyenne, 1) }}
                            </div>
                            <div class="flex gap-0.5 text-[#0a0a0a] text-lg justify-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= round($noteMoyenne) ? '' : 'opacity-30' }}">★</span>
                                @endfor
                            </div>
                            <p class="text-[12px] text-[#a0a09a]">{{ $nombreAvis }} avis{{ $nombreAvis > 1 ? 's' : '' }}</p>
                        </div>

                        {{-- Distribution des notes --}}
                        <div class="space-y-2">
                            @php $notes = [5, 4, 3, 2, 1]; @endphp
                            @foreach($notes as $note)
                                <div class="flex items-center gap-2">
                                    <span class="text-[12px] text-[#a0a09a] w-6 text-right font-mono">{{ $note }}★</span>
                                    <div class="w-24 h-2 bg-[#efefed] rounded-full overflow-hidden">
                                        <div class="h-full bg-[#0a0a0a] rounded-full transition-all"
                                             style="width: {{ ($distributionNotes[$note] / max(1, $nombreAvis)) * 100 }}%">
                                        </div>
                                    </div>
                                    <span class="text-[11px] text-[#a0a09a] w-8 text-right">{{ $distributionNotes[$note] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="w-10 h-10 text-[#e0e0dc] mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345h5.518c.486 0 .688.657.324.891l-4.46 3.596a.563.563 0 00-.168.606l2.125 5.111c.307.763-.278 1.575-1.048 1.575a.563.563 0 01-.472-.257l-4.46-3.596a.563.563 0 00-.686 0l-4.46 3.596a.563.563 0 01-.473.257c-.77 0-1.355-.812-1.048-1.575l2.125-5.111a.563.563 0 00-.168-.606l-4.46-3.596c-.364-.233-.162-.89.324-.89h5.518a.563.563 0 00.475-.345L11.48 3.5z"/>
                            </svg>
                            <p class="text-[13px] text-[#a0a09a] font-light">Aucun avis pour le moment</p>
                        </div>
                    @endif
                </div>

                {{-- CTA --}}
                @auth
                    <div class="flex-1 md:text-right">
                        <p class="text-[12px] text-[#a0a09a] mb-3">
                            @if($nombreAvis === 0)
                                Soyez le premier à laisser un avis
                            @else
                                Vous avez acheté ce produit ?
                            @endif
                        </p>
                        <a href="#review-form" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                            Laisser un avis
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Liste des avis --}}
        @if($nombreAvis > 0)
            <div class="border border-[#e0e0dc] rounded-xl overflow-hidden mb-6">
                @foreach($avis as $av)
                    <div class="px-5 py-5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 bg-[#0a0a0a] text-white rounded-md flex items-center justify-center text-[12px] font-medium flex-shrink-0">
                                    {{ strtoupper(substr($av->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $av->user->name }}</div>
                                    <div class="text-[11px] text-[#a0a09a]">{{ $av->created_at->locale('fr')->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex gap-0.5 text-[#0a0a0a] text-sm ml-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $av->note ? '' : 'opacity-30' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[13px] text-[#666660] font-light leading-relaxed mb-3">{{ $av->commentaire }}</p>
                        @auth
                            @if(auth()->id() === $av->user_id)
                                <div class="flex gap-2">
                                    <form action="{{ route('avis.destroy', $av->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[11px] text-[#dc2626] hover:underline cursor-pointer">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>

            {{-- Pagination avis --}}
            @if($avis->hasPages())
                <div class="flex justify-center mb-6">
                    {{ $avis->links('pagination::tailwind') }}
                </div>
            @endif
        @endif

        {{-- Formulaire avis --}}
        @auth
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden" id="review-form">
                <div class="px-5 py-4 border-b border-[#efefed] bg-[#f7f7f5]">
                    <div class="text-[13px] font-medium text-[#0a0a0a]">✍️ Laisser un avis</div>
                    <div class="text-[12px] text-[#a0a09a] font-light mt-1">Aidez les autres clients à faire le bon choix</div>
                </div>
                <div class="p-5">
                    <form action="{{ route('avis.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                        {{-- Sélecteur d'étoiles --}}
                        <div>
                            <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Votre note</label>
                            <div class="flex gap-1" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer text-3xl text-[#e0e0dc] hover:text-[#0a0a0a] transition-colors">
                                        <input type="radio" name="note" value="{{ $i }}" class="hidden rating-input" required {{ old('note') == $i ? 'checked' : '' }}>
                                        <span class="rating-star">★</span>
                                    </label>
                                @endfor
                            </div>
                            @error('note')
                                <p class="text-[11px] text-[#dc2626] mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Commentaire --}}
                        <div>
                            <label class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Commentaire</label>
                            <textarea
                                name="commentaire"
                                rows="4"
                                placeholder="Partagez votre expérience avec ce produit…"
                                minlength="10"
                                maxlength="1000"
                                required
                                class="w-full border border-[#e0e0dc] rounded-lg px-4 py-3 text-[13px] font-light text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] hover:border-[#a0a09a] transition-all resize-none bg-white"
                            >{{ old('commentaire') }}</textarea>
                            <div class="flex justify-between mt-1.5">
                                <span class="text-[10px] text-[#a0a09a]">Minimum 10 caractères</span>
                                <span class="text-[10px] text-[#a0a09a]" id="char-count">0 / 1000</span>
                            </div>
                            @error('commentaire')
                                <p class="text-[11px] text-[#dc2626] mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-[#0a0a0a] text-white text-[13px] font-medium px-5 py-3 rounded-lg hover:opacity-85 transition-opacity">
                                Publier mon avis
                            </button>
                            <button type="reset" class="px-5 py-3 border border-[#e0e0dc] text-[13px] text-[#666660] rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-8 text-center">
                <p class="text-[13px] text-[#a0a09a] font-light mb-4">Connectez-vous pour laisser un avis</p>
                <a href="{{ route('login') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    Se connecter
                </a>
            </div>
        @endauth
    </div>

    {{-- ══════════════════════════════
         BUNDLES CONTENANT CE PRODUIT
    ══════════════════════════════ --}}
    @php
        $produitId = $produit->id;
        $bundlesAvecProduit = \App\Models\Bundle::whereHas('produits', function($q) use ($produitId) {
            $q->where('produit_id', $produitId);
        })->where('statut', 'actif')->with('produits')->limit(4)->get();
    @endphp
    @if($bundlesAvecProduit && count($bundlesAvecProduit) > 0)
        <div class="mb-12 md:mb-16">
            <div class="flex items-baseline justify-between mb-6">
                <h2 class="font-serif text-[22px] tracking-tight">
                    Offres <em class="italic text-[#666660]">groupées</em>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($bundlesAvecProduit as $bundle)
                    @php
                        $prixOriginalBundle = $bundle->produits_sum_prix ?? 0;
                        $economie = $prixOriginalBundle - $bundle->prix;
                        $pourcentageEconomie = $prixOriginalBundle > 0 ? round(($economie / $prixOriginalBundle) * 100) : 0;
                    @endphp
                    <div class="border border-[#e0e0dc] rounded-xl overflow-hidden hover:border-[#2a2a28] hover:shadow-md transition-all duration-200 bg-white group cursor-pointer"
                         onclick="openBundleModal({{ $bundle->id }}, '{{ addslashes($bundle->nom) }}', {{ $bundle->prix }}, {{ $prixOriginalBundle }})">
                        {{-- En-tête du bundle --}}
                        <div class="px-5 py-4 border-b border-[#efefed] bg-gradient-to-r from-[#f7f7f5] to-white">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex-1">
                                    <h3 class="font-serif text-[15px] text-[#0a0a0a] leading-tight mb-1">
                                        {{ $bundle->nom }}
                                    </h3>
                                    <p class="text-[12px] text-[#a0a09a] font-light">
                                        {{ $bundle->produits->count() }} produit{{ $bundle->produits->count() > 1 ? 's' : '' }}
                                    </p>
                                </div>
                                @if($economie > 0)
                                    <span class="inline-flex items-center px-2.5 py-1.5 bg-green-100 text-green-700 rounded-lg text-[10px] font-medium whitespace-nowrap">
                                        Économisez {{ number_format($economie, 0, ',', ' ') }} FCFA
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Contenu du bundle --}}
                        <div class="px-5 py-4">
                            {{-- Mini liste des produits --}}
                            <div class="space-y-2 mb-4">
                                @foreach($bundle->produits->take(2) as $itemBundle)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span class="text-[12px] text-[#666660] font-light">
                                            {{ Str::limit($itemBundle->nom, 35) }}
                                        </span>
                                    </div>
                                @endforeach
                                @if($bundle->produits->count() > 2)
                                    <div class="flex items-center gap-2 pt-1">
                                        <div class="w-3.5 h-3.5"></div>
                                        <span class="text-[12px] text-[#a0a09a] italic">
                                            +{{ $bundle->produits->count() - 2 }} autre{{ $bundle->produits->count() - 2 > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Séparateur --}}
                            <div class="h-[1px] bg-[#efefed] my-4"></div>

                            {{-- Pricing --}}
                            <div class="flex items-end justify-between">
                                <div>
                                    <div class="text-[11px] text-[#a0a09a] font-light mb-1">Prix du bundle</div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-mono font-medium text-[18px] text-[#0a0a0a]">
                                            {{ number_format($bundle->prix, 0, ',', ' ') }}
                                        </span>
                                        <span class="text-[12px] text-[#a0a09a]">FCFA</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[11px] text-[#a0a09a] font-light mb-1">Au lieu de</div>
                                    <div class="text-[12px] font-mono text-[#a0a09a] line-through">
                                        {{ number_format($prixOriginalBundle, 0, ',', ' ') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer CTA --}}
                        <div class="px-5 py-3 border-t border-[#efefed] bg-[#f7f7f5]">
                            <button type="button" onclick="event.stopPropagation(); openQuantityModal({{ $bundle->id }}, '{{ addslashes($bundle->nom) }}', 999, {{ $bundle->prix_bundle }})"
                                    class="w-full py-2.5 bg-[#0a0a0a] text-white text-[12px] font-medium rounded-lg
                                    group-hover:opacity-85 transition-all duration-150 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════
         PRODUITS SIMILAIRES
    ══════════════════════════════ --}}
    @if($produitsSimilaires && count($produitsSimilaires) > 0)
        <div>
            <div class="flex items-baseline justify-between mb-6">
                <h2 class="font-serif text-[22px] tracking-tight">
                    Produits <em class="italic text-[#666660]">similaires</em>
                </h2>
                <a href="{{ route('produits.catalogue', ['categorie' => $produit->categorie?->id]) }}" class="text-[12px] text-[#a0a09a] border-b border-[#e0e0dc] pb-px hover:text-[#0a0a0a] hover:border-[#0a0a0a] transition-all">
                    Voir tout →
                </a>
            </div>
            <div class="grid grid-cols-4 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">
                @foreach($produitsSimilaires as $similaire)
                    @include('components.carte-produit', ['produit' => $similaire])
                @endforeach
            </div>
        </div>
    @endif

</div>

{{-- ══════════════════════════════
     MODAL CONTACT VENDEUR
══════════════════════════════ --}}
<div id="contactVendorModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)closeContactModal()">
    <div class="bg-white rounded-xl w-full max-w-md overflow-hidden" onclick="event.stopPropagation()">

        <div class="flex items-center justify-between px-5 py-4 border-b border-[#efefed]">
            <div>
                <div class="text-[13px] font-medium text-[#0a0a0a]">Contacter le vendeur</div>
                <div id="modalVendorName" class="text-[12px] text-[#a0a09a] font-light mt-0.5"></div>
            </div>
            <button onclick="closeContactModal()" class="w-7 h-7 flex items-center justify-center rounded-md border border-[#e0e0dc] text-[#a0a09a] hover:text-[#0a0a0a] hover:border-[#2a2a28] transition-all text-lg leading-none">×</button>
        </div>

        <div class="p-5">
            @auth
                <div id="successMessage" class="hidden mb-4 flex items-center gap-2 px-3 py-2.5 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg text-[13px] text-[#15803d]">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    Message envoyé avec succès
                </div>
                <div id="formError" class="hidden mb-4 px-3 py-2.5 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
                    <span id="errorMessage"></span>
                </div>

                <form id="contactForm" class="space-y-4" onsubmit="return submitContactForm(event)">
                    @csrf
                    <input type="hidden" name="destinataire_id" id="modalVendorId">
                    <input type="hidden" name="produit_id" id="modalProduitId">

                    <div>
                        <div class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">Sujet</div>
                        <input type="text" id="sujet" name="sujet" readonly
                            class="w-full border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#a0a09a] bg-[#f7f7f5] outline-none">
                    </div>

                    <div>
                        <div class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-1.5">Message</div>
                        <textarea id="message" name="contenu" rows="4"
                            placeholder="Posez votre question au vendeur…"
                            minlength="5" required oninput="validateMessageLength(this)"
                            class="w-full border border-[#e0e0dc] rounded-lg px-3 py-2.5 text-[13px] font-light text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors resize-none bg-white"
                        ></textarea>
                        <div id="charWarning" class="text-[11px] text-[#a0a09a] mt-1">Minimum 5 caractères</div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="button" onclick="closeContactModal()"
                            class="flex-1 py-2.5 text-[12px] text-[#666660] border border-[#e0e0dc] rounded-lg hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                            Annuler
                        </button>
                        <button type="submit" id="submitContactBtn" disabled
                            class="flex-1 py-2.5 text-[12px] font-medium bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition-opacity disabled:opacity-30 disabled:cursor-not-allowed">
                            Envoyer
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-6">
                    <p class="text-[13px] text-[#a0a09a] font-light mb-4">Vous devez être connecté pour contacter un vendeur</p>
                    <a href="{{ route('login') }}" class="inline-block bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                        Se connecter
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

{{-- Recommandations produits --}}
<x-product-recommendations
    :products="$similarProducts ?? []"
    title="Produits similaires"
    type="similar" />

<x-product-recommendations
    :products="$frequentlyBought ?? []"
    title="Les clients ont aussi acheté"
    type="together" />

<script>
function openContactModal(vendorId, vendorName, productId, productName) {
    @auth
        vendorId = parseInt(vendorId); productId = parseInt(productId);
        if (!vendorId) { alert('Ce produit n\'a pas de vendeur associé'); return; }
        document.getElementById('contactVendorModal').classList.remove('hidden');
        document.getElementById('modalVendorName').textContent = vendorName;
        document.getElementById('modalVendorId').value = vendorId;
        document.getElementById('modalProduitId').value = productId;
        document.getElementById('sujet').value = 'Demande sur : ' + productName;
        document.getElementById('message').focus();
        document.body.style.overflow = 'hidden';
    @else
        window.location.href = '{{ route('login') }}';
    @endauth
}
function closeContactModal() {
    document.getElementById('contactVendorModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeContactModal(); });

function validateMessageLength(textarea) {
    const len = textarea.value.trim().length;
    const warn = document.getElementById('charWarning');
    const btn  = document.getElementById('submitContactBtn');
    if (len < 5) {
        warn.textContent = `${len} / 5 caractères minimum`;
        warn.className = 'text-[11px] text-[#dc2626] mt-1';
        btn.disabled = true;
    } else {
        warn.textContent = '✓ Message valide';
        warn.className = 'text-[11px] text-[#15803d] mt-1';
        btn.disabled = false;
    }
}

async function submitContactForm(event) {
    event.preventDefault();
    const message = document.getElementById('message').value.trim();
    const vendorId = parseInt(document.getElementById('modalVendorId').value);
    const productId = parseInt(document.getElementById('modalProduitId').value);
    const errorDiv = document.getElementById('formError');
    const successDiv = document.getElementById('successMessage');
    const btn = document.getElementById('submitContactBtn');
    errorDiv.classList.add('hidden');
    successDiv.classList.add('hidden');
    if (message.length < 5 || !vendorId) return false;
    btn.disabled = true; btn.textContent = 'Envoi…';
    try {
        const res = await fetch('{{ route("messages.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value, 'Accept': 'application/json' },
            body: JSON.stringify({ destinataire_id: vendorId, produit_id: productId, contenu: message })
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join(', ') : data.message);
        successDiv.classList.remove('hidden');
        document.getElementById('message').value = '';
        setTimeout(closeContactModal, 2500);
    } catch (err) {
        document.getElementById('errorMessage').textContent = err.message;
        errorDiv.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'Envoyer';
    }
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    // ──── SYSTÈME D'NOTES INTERACTIF ────
    const labels = document.querySelectorAll('#rating-stars label');
    const stars  = document.querySelectorAll('#rating-stars .rating-star');
    const inputs = document.querySelectorAll('#rating-stars .rating-input');

    labels.forEach((label, i) => {
        label.addEventListener('mouseenter', () => {
            stars.forEach((s, j) => {
                if (j <= i) {
                    s.classList.add('text-[#0a0a0a]');
                    s.classList.remove('text-[#e0e0dc]');
                } else {
                    s.classList.add('text-[#e0e0dc]');
                    s.classList.remove('text-[#0a0a0a]');
                }
            });
        });
        label.addEventListener('click', () => {
            inputs[i].checked = true;
        });
    });

    const ratingContainer = document.getElementById('rating-stars');
    if (ratingContainer) {
        ratingContainer.addEventListener('mouseleave', () => {
            const selectedIndex = Array.from(inputs).findIndex(inp => inp.checked);
            stars.forEach((s, j) => {
                if (selectedIndex >= 0) {
                    if (j <= selectedIndex) {
                        s.classList.add('text-[#0a0a0a]');
                        s.classList.remove('text-[#e0e0dc]');
                    } else {
                        s.classList.add('text-[#e0e0dc]');
                        s.classList.remove('text-[#0a0a0a]');
                    }
                } else {
                    // Reset default
                    s.classList.add('text-[#e0e0dc]');
                    s.classList.remove('text-[#0a0a0a]');
                }
            });
        });
    }

    // ──── COMPTEUR DE CARACTÈRES ────
    const textarea = document.querySelector('textarea[name="commentaire"]');
    if (textarea) {
        const updateCount = () => {
            const count = textarea.value.length;
            const counter = document.getElementById('char-count');
            if (counter) {
                counter.textContent = `${count} / 1000`;
            }
        };
        textarea.addEventListener('input', updateCount);
        // Initialiser
        updateCount();
    }
});

</script>

{{-- ══════════════════════════════════════════
    Section RECOMMANDATIONS
══════════════════════════════════════════ --}}
<div class="max-w-[1100px] mx-auto px-4 sm:px-6 md:px-8 py-12 sm:py-16 border-t border-[#e0e0dc]">
    {{-- Conteneur pour les recommandations --}}
    <div id="recommendationsContainer" class="space-y-12">
        {{-- Les recommandations seront chargées ici via JavaScript --}}
    </div>
</div>

<script>
// ═════════════════════════════════════════
// SYSTÈME DE RECOMMANDATIONS
// ═════════════════════════════════════════

const productId = {{ $produit->id }};

async function loadRecommendations() {
    const container = document.getElementById('recommendationsContainer');
    const recommendations = [
        { endpoint: 'similar', order: 1 },
        { endpoint: 'bought-together', order: 2 },
        { endpoint: 'popular', order: 3 },
    ];

    // Charger les recommandations en parallèle
    const promises = recommendations.map(rec =>
        fetch(`/api/recommendations/${rec.endpoint}/${productId}`)
            .then(r => r.json())
            .then(data => ({ ...data, order: rec.order }))
            .catch(() => null)
    );

    const results = await Promise.all(promises);
    const validResults = results.filter(r => r && r.produits.length > 0).sort((a, b) => a.order - b.order);

    // Afficher les recommandations
    validResults.forEach(recommendation => {
        container.insertAdjacentHTML('beforeend', renderRecommendationSection(recommendation));
    });

    // Si aucune recommandation, charger trending
    if (validResults.length === 0) {
        try {
            const trending = await fetch('/api/recommendations/trending').then(r => r.json());
            if (trending.produits.length > 0) {
                container.insertAdjacentHTML('beforeend', renderRecommendationSection(trending));
            }
        } catch (e) {
            console.log('Pas de recommandations disponibles');
        }
    }
}

function renderRecommendationSection(recommendation) {
    const { titre, produits } = recommendation;

    const productsHTML = produits.map(p => `
        <a href="/produits/${p.id}" class="group">
            <div class="border border-[#e0e0dc] rounded-lg overflow-hidden hover:border-[#0a0a0a] transition-all hover:shadow-sm">
                {{-- Image --}}
                <div class="aspect-square bg-[#f7f7f5] overflow-hidden flex items-center justify-center">
                    <img
                        src="${p.image ? '/storage/' + p.image : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e0e0dc%22 width=%22200%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2248%22 text-anchor=%22middle%22 dy=%22.3em%22%3E📦%3C/text%3E%3C/svg%3E'}"
                        alt="${p.nom}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                        loading="lazy"
                    />
                </div>
                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-medium text-[13px] text-[#0a0a0a] line-clamp-2 mb-2">${p.nom}</h3>
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-mono font-bold text-[14px] text-[#0a0a0a]">${new Intl.NumberFormat('fr-FR').format(p.prix)} F</span>
                        ${p.badge ? `<span class="text-[10px] font-medium text-[#f59e0b]">${p.badge}</span>` : ''}
                    </div>
                    {{-- Notes --}}
                    ${p.nombre_avis > 0 ? `
                        <div class="flex items-center gap-1 mb-3">
                            <div class="flex gap-0.5">
                                ${'★'.repeat(Math.floor(p.note))}<span class="text-[#e0e0dc]">${'★'.repeat(5 - Math.floor(p.note))}</span>
                            </div>
                            <span class="text-[10px] text-[#a0a09a]">(${p.nombre_avis})</span>
                        </div>
                    ` : ''}
                    {{-- Stock status --}}
                    <div class="text-[11px] ${p.stock > 0 ? 'text-[#15803d]' : 'text-[#dc2626]'}">
                        ${p.stock > 0 ? `✓ ${p.stock} en stock` : '✗ Rupture'}
                    </div>
                </div>
            </div>
        </a>
    `).join('');

    return `
        <div>
            <h2 class="text-[14px] font-medium text-[#0a0a0a] mb-6">${titre}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                ${productsHTML}
            </div>
        </div>
    `;
}

// Charger les recommandations au démarrage
document.addEventListener('DOMContentLoaded', loadRecommendations);
</script>

@endsection
