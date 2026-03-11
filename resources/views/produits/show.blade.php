@extends('layouts.app')

@section('content')
<div class="max-w-[1100px] mx-auto px-8 py-8 pb-20">

    {{-- ── BREADCRUMB ── --}}
    <nav class="flex items-center gap-1.5 mb-8 text-[12px] text-[#a0a09a]">
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
         LAYOUT PRINCIPAL 2 colonnes
    ══════════════════════════════ --}}
    <div class="grid grid-cols-[1fr_1fr] gap-16 mb-16">

        {{-- ── GALERIE ── --}}
        <div class="sticky top-[72px] self-start">
            {{-- Image principale --}}
            <div class="w-full aspect-square rounded-xl border border-[#e0e0dc] bg-white overflow-hidden flex items-center justify-center mb-3">
                @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                    <img
                        src="{{ asset('storage/produits/' . $produit->images[0]) }}"
                        alt="{{ $produit->nom }}"
                        class="w-full h-full object-cover"
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
            @if($produit->images && is_array($produit->images) && count($produit->images) > 1)
                <div class="flex gap-2 flex-wrap">
                    @foreach($produit->images as $index => $img)
                        <button
                            onclick="document.getElementById('main-image').src='{{ asset('storage/produits/' . $img) }}';
                                     document.querySelectorAll('.thumb-btn').forEach(b=>b.classList.remove('border-[#0a0a0a]'));
                                     this.classList.add('border-[#0a0a0a]')"
                            class="thumb-btn w-14 h-14 rounded-lg border overflow-hidden transition-colors {{ $index === 0 ? 'border-[#0a0a0a]' : 'border-[#e0e0dc] hover:border-[#a0a09a]' }}"
                        >
                            <img src="{{ asset('storage/produits/' . $img) }}" alt="" class="w-full h-full object-cover">
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
        <div>
            {{-- Catégorie eyebrow --}}
            @if($produit->categorie)
                <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">
                    {{ $produit->categorie->nom }}
                </div>
            @endif

            {{-- Nom --}}
            <h1 class="font-serif text-[36px] tracking-tight leading-[1.1] text-[#0a0a0a] mb-5">
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
            <div class="mb-5 pb-5 border-b border-[#efefed]">
                <div class="flex items-baseline gap-3">
                    <span class="font-mono text-[32px] font-medium tracking-tight text-[#0a0a0a] leading-none">
                        {{ number_format($produit->prix, 0, ',', ' ') }}
                    </span>
                    <span class="text-[14px] text-[#a0a09a] font-light">FCFA</span>
                    @if($produit->prix_original && $produit->prix_original > $produit->prix)
                        <span class="text-[13px] text-[#a0a09a] line-through font-mono">{{ number_format($produit->prix_original, 0, ',', ' ') }}</span>
                        <span class="text-[10px] font-medium bg-[#f0fdf4] text-[#15803d] px-2 py-0.5 rounded">
                            -{{ round((($produit->prix_original - $produit->prix) / $produit->prix_original) * 100) }}%
                        </span>
                    @endif
                </div>
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
            <div class="flex flex-col gap-2.5 mb-8">
                <div class="flex gap-2.5">
                    @if($produit->stock > 0)
                        <button
                            type="button"
                            onclick="openQuantityModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->stock }}, {{ $produit->prix }})"
                            class="flex-1 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity"
                        >
                            Ajouter au Panier
                        </button>
                    @else
                        <button disabled class="flex-1 py-3 bg-[#f7f7f5] text-[#a0a09a] text-[13px] font-medium rounded-lg cursor-not-allowed border border-[#e0e0dc]">
                            Indisponible
                        </button>
                    @endif
                    <button
                        onclick="toggleFavorite({{ $produit->id }}, event)"
                        data-favorite-btn="{{ $produit->id }}"
                        class="w-12 h-12 flex items-center justify-center border border-[#e0e0dc] rounded-lg hover:border-[#2a2a28] transition-colors"
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
                        class="w-full flex items-center justify-center gap-2 py-3 border border-[#e0e0dc] rounded-lg text-[13px] text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all"
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
                        class="flex items-center justify-center gap-2 py-3 border border-[#e0e0dc] rounded-lg text-[13px] text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all"
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
                s.parentElement.classList.toggle('text-[#0a0a0a]', j <= i);
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
                s.parentElement.classList.toggle('text-[#0a0a0a]', j <= selectedIndex);
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

@endsection
