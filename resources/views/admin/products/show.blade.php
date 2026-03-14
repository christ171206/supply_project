@extends('layouts.admin-layout')

@section('title', 'Produit — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#0a0a0a] transition-colors">Produits</a>
    &nbsp;/&nbsp; {{ $produit->nom }}
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux produits
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration · Produits</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">{{ $produit->nom }}</h1>
        <div class="flex items-center gap-6 mt-5 pt-5 border-t border-white/10 flex-wrap">
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ number_format($produit->prix, 0, ',', ' ') }}
                    <span class="text-[11px] text-white/40 font-sans font-light ml-0.5">FCFA</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Prix</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                @php
                    $stockColor = $produit->stock <= 5 ? 'text-[#f87171]' : ($produit->stock <= $produit->stock_minimum ? 'text-[#fbbf24]' : 'text-white');
                @endphp
                <div class="font-mono text-[22px] font-medium {{ $stockColor }} leading-none">{{ $produit->stock }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Stock</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                @if($produit->est_actif)
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f7f7f5] text-[#a0a09a]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                    </span>
                @endif
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1.5">Statut</div>
            </div>
        </div>
    </div>

    <div class="px-8">
    <div class="grid grid-cols-[1fr_260px] gap-5">

        {{-- COLONNE GAUCHE --}}
        <div class="space-y-5">

            {{-- Informations générales --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Informations générales</span>
                </div>
                <div class="grid grid-cols-2 gap-px bg-[#e0e0dc]">
                    <div class="bg-white px-6 py-4">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">SKU</div>
                        <div class="font-mono text-[13px] text-[#0a0a0a]">{{ $produit->sku ?? '—' }}</div>
                    </div>
                    <div class="bg-white px-6 py-4">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Catégorie</div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->categorie?->nom ?? '—' }}</div>
                    </div>
                    <div class="bg-white px-6 py-4">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Vendeur</div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">
                            {{ $produit->vendeur?->shop_name ?? $produit->vendeur?->name ?? 'Vendeur supprimé' }}
                        </div>
                    </div>
                    <div class="bg-white px-6 py-4">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1">Créé le</div>
                        <div class="font-mono text-[12px] text-[#a0a09a]">{{ $produit->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
                @if($produit->description)
                    <div class="px-6 py-4 border-t border-[#efefed]">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">Description</div>
                        <p class="text-[13px] text-[#666660] font-light leading-relaxed">{{ $produit->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Galerie --}}
            @php
                $imagesList = [];
                if ($produit->image) $imagesList[] = $produit->image;
                if ($produit->images && is_array($produit->images)) $imagesList = array_merge($imagesList, $produit->images);
                $imagesList = array_unique($imagesList);
            @endphp

            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed] flex items-center justify-between">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Photos</span>
                    <span class="font-mono text-[11px] text-[#a0a09a]">{{ count($imagesList) }}</span>
                </div>
                <div class="p-5">
                    @if(count($imagesList) > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($imagesList as $index => $img)
                                @php
                                    $imagePath = $img;
                                    if (!str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
                                        if (!str_contains($img, 'produits/')) $imagePath = 'produits/' . $img;
                                        $imagePath = asset('storage/' . $imagePath);
                                    }
                                @endphp
                                <div class="relative group aspect-square overflow-hidden rounded-lg border border-[#e0e0dc] cursor-pointer"
                                     onclick="openModal({{ $index }}, {{ json_encode($imagesList) }})">
                                    <img src="{{ $imagePath }}" alt="{{ $produit->nom }}"
                                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300"
                                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-[#f7f7f5]\'><svg class=\'w-6 h-6 text-[#e0e0dc]\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg></div>'">
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-10 text-center">
                            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                            <p class="text-[12px] text-[#a0a09a] font-light">Aucune photo disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stock --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Stock</span>
                </div>
                <div class="grid grid-cols-2 gap-px bg-[#e0e0dc]">
                    <div class="bg-white px-6 py-5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">Quantité actuelle</div>
                        <div class="font-mono text-[36px] font-medium {{ $stockColor }} leading-none">{{ $produit->stock }}</div>
                    </div>
                    <div class="bg-white px-6 py-5">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">Seuil d'alerte</div>
                        <div class="font-mono text-[36px] font-medium text-[#0a0a0a] leading-none">{{ $produit->stock_minimum }}</div>
                    </div>
                </div>
                @if($produit->stock <= $produit->stock_minimum)
                    <div class="px-6 py-3 flex items-center gap-2 bg-[#fef2f2] border-t border-[#fecaca]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f87171] flex-shrink-0"></span>
                        <span class="text-[12px] text-[#dc2626]">Stock en dessous du seuil minimum — action requise</span>
                    </div>
                @endif
                <div class="px-6 py-3 border-t border-[#efefed]">
                    <p class="text-[11px] text-[#a0a09a] font-light">La gestion du stock est à la charge du vendeur.</p>
                </div>
            </div>

            {{-- Historique stock (aperçu) --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed] flex items-center justify-between">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Historique du stock</span>
                    <a href="{{ route('admin.products.stock-history', $produit->id) }}"
                       class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Voir tout →
                    </a>
                </div>
                @if(isset($stockHistory) && $stockHistory->isNotEmpty())
                    <div class="divide-y divide-[#efefed]">
                        @foreach($stockHistory->take(5) as $history)
                            <div class="px-6 py-3.5 flex items-center justify-between">
                                <div>
                                    <div class="text-[13px] font-medium text-[#0a0a0a]">
                                        {{ ucfirst(str_replace('_', ' ', $history->type ?? 'Mouvement')) }}
                                    </div>
                                    <div class="font-mono text-[10px] text-[#a0a09a] mt-0.5">
                                        {{ $history->created_at->format('d/m/Y · H:i') }}
                                    </div>
                                </div>
                                <span class="font-mono text-[14px] font-medium {{ $history->quantity > 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                                    {{ $history->quantity > 0 ? '+' : '' }}{{ $history->quantity }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-[12px] text-[#a0a09a] font-light">Aucun mouvement enregistré</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- COLONNE DROITE --}}
        <div class="space-y-4">

            {{-- Actions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Actions</span>
                </div>
                <div class="p-4 space-y-2">
                    @if($produit->est_actif)
                        <form method="POST" action="{{ route('admin.products.disable', $produit->id) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-[12px] font-medium text-[#b45309] border border-[#fde68a] px-4 py-2.5 rounded-lg
                                           hover:bg-[#fdf6ec] transition-all text-left flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                </svg>
                                Désactiver le produit
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.products.enable', $produit->id) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-[12px] font-medium text-[#15803d] border border-[#bbf7d0] px-4 py-2.5 rounded-lg
                                           hover:bg-[#f0fdf4] transition-all text-left flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Activer le produit
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.products.stock-history', $produit->id) }}"
                       class="w-full text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2.5 rounded-lg
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Historique détaillé
                    </a>

                    <form method="POST" action="{{ route('admin.products.destroy', $produit->id) }}"
                          data-confirm="Supprimer ce produit ? Cette action est irréversible."
                          data-confirm-title="Supprimer le produit"
                          data-confirm-type="danger"
                          data-confirm-button="Supprimer">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full text-[12px] font-medium text-[#dc2626] border border-[#fecaca] px-4 py-2.5 rounded-lg
                                       hover:bg-[#fef2f2] transition-all text-left flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>

            {{-- Statistiques --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Statistiques</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    @foreach([
                        ['l' => 'Avis clients',   'v' => $produit->nombre_avis ?? 0],
                        ['l' => 'Note moyenne',    'v' => round($produit->note_moyenne ?? 0, 1) . ' / 5'],
                        ['l' => 'Favoris',         'v' => $produit->favorites()->count() ?? 0],
                        ['l' => 'Stock dispo',     'v' => $produit->stock],
                    ] as $s)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <span class="text-[12px] text-[#666660]">{{ $s['l'] }}</span>
                            <span class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ $s['v'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
    </div>

</div>

{{-- Lightbox --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)closeModal()">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeModal()"
                class="absolute -top-9 right-0 text-white/60 hover:text-white transition-colors text-[11px] font-medium tracking-[0.08em] uppercase">
            Fermer ✕
        </button>
        <div class="bg-[#0a0a0a] rounded-xl overflow-hidden">
            <img id="modalImg" src="" alt="" class="w-full max-h-[70vh] object-contain">
            <div class="flex items-center justify-between px-5 py-3 border-t border-white/10">
                <button onclick="prevImg()" class="text-white/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <span id="modalCounter" class="font-mono text-[11px] text-white/40">1 / 1</span>
                <button onclick="nextImg()" class="text-white/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
let _imgs = [], _idx = 0;
const storageBase = '{{ asset("storage") }}';

function openModal(index, images) {
    _imgs = images; _idx = index;
    renderModal();
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = '';
}
function renderModal() {
    let img = _imgs[_idx];
    if (!img.startsWith('http') && !img.startsWith('/')) {
        if (!img.includes('produits/')) img = 'produits/' + img;
        img = storageBase + '/' + img;
    }
    document.getElementById('modalImg').src = img;
    document.getElementById('modalCounter').textContent = (_idx + 1) + ' / ' + _imgs.length;
}
function nextImg() { _idx = (_idx + 1) % _imgs.length; renderModal(); }
function prevImg() { _idx = (_idx - 1 + _imgs.length) % _imgs.length; renderModal(); }
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowRight') nextImg();
    if (e.key === 'ArrowLeft') prevImg();
});
</script>
@endsection

@endsection
