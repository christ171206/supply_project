@extends('layouts.admin-layout')

@section('title', $category->nom . ' — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.categories.index') }}" class="hover:text-[#0a0a0a] transition-colors">Catégories</a>
    &nbsp;/&nbsp; {{ $category->nom }}
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux catégories
        </a>
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration · Catégories</div>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">{{ $category->nom }}</h1>
                <div class="flex items-center gap-3 mt-3">
                    <span class="font-mono text-[11px] text-white/30">#{{ $category->id }}</span>
                    @if($category->is_active)
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-white/10 text-white/70">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-white/10 text-white/40">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1">
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="flex items-center gap-1.5 bg-white text-[#0a0a0a] text-[12px] font-medium px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                      data-confirm="Supprimer cette catégorie ?"
                      data-confirm-title="Supprimer la catégorie"
                      data-confirm-type="danger"
                      data-confirm-button="Supprimer">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-1.5 bg-white/10 text-white text-[12px] font-medium px-4 py-2.5 rounded-lg
                                   hover:bg-[#fef2f2] hover:text-[#dc2626] transition-all">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                            <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="px-8">
    <div class="grid grid-cols-[1fr_260px] gap-5 items-start">

        {{-- Colonne principale --}}
        <div class="space-y-5">

            {{-- Informations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Informations</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    <div class="flex items-start px-6 py-3.5 gap-6">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] pt-0.5 w-28 flex-shrink-0">Nom</div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $category->nom }}</div>
                    </div>
                    <div class="flex items-start px-6 py-3.5 gap-6">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] pt-0.5 w-28 flex-shrink-0">Description</div>
                        <div class="text-[13px] text-[#666660] font-light leading-relaxed">
                            {{ $category->description ?? '—' }}
                        </div>
                    </div>
                    <div class="flex items-center px-6 py-3.5 gap-6">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-28 flex-shrink-0">Statut</div>
                        @if($category->is_active)
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f7f7f5] text-[#a0a09a]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center px-6 py-3.5 gap-6">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-28 flex-shrink-0">Créée</div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $category->created_at->format('d/m/Y · H:i') }}</div>
                    </div>
                    <div class="flex items-center px-6 py-3.5 gap-6">
                        <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-28 flex-shrink-0">Modifiée</div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $category->updated_at->format('d/m/Y · H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- Produits --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed] flex items-center justify-between">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Produits</span>
                    <span class="font-mono text-[11px] text-[#a0a09a]">{{ $category->produits->count() }}</span>
                </div>
                @if($category->produits->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                                <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Nom</th>
                                <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Prix</th>
                                <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Stock</th>
                                <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Vendeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->produits as $product)
                                <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                                    <td class="px-5 py-3 text-[13px] font-medium text-[#0a0a0a]">{{ $product->nom ?? '—' }}</td>
                                    <td class="px-5 py-3 text-right font-mono text-[12px] text-[#0a0a0a]">
                                        {{ number_format($product->prix ?? 0, 0, ',', ' ') }}
                                        <span class="text-[10px] text-[#a0a09a] font-sans">FCFA</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-mono text-[12px] text-[#0a0a0a]">{{ $product->stock ?? 0 }}</td>
                                    <td class="px-5 py-3 text-[12px] text-[#666660] font-light">
                                        {{ $product->user?->shop_name ?? $product->user?->name ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-[12px] text-[#a0a09a] font-light">Aucun produit dans cette catégorie</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar stats --}}
        <div class="space-y-3">

            @foreach([
                ['v' => $category->produits->count(), 'l' => 'Produits', 'u' => null],
                ['v' => number_format($category->produits->sum('stock'), 0, ',', ' '), 'l' => 'Unités en stock', 'u' => null],
                ['v' => number_format($category->produits->sum('prix'), 0, ',', ' '), 'l' => 'Valeur catalogue', 'u' => 'FCFA'],
            ] as $stat)
                <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 hover:bg-[#f7f7f5] transition-colors">
                    <div class="font-mono text-[26px] font-medium text-[#0a0a0a] leading-none">
                        {{ $stat['v'] }}@if($stat['u'])<span class="text-[11px] text-[#a0a09a] font-sans font-light ml-1">{{ $stat['u'] }}</span>@endif
                    </div>
                    <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mt-1.5">{{ $stat['l'] }}</div>
                </div>
            @endforeach

            {{-- Actions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-[#efefed]">
                    <span class="text-[11px] font-medium text-[#a0a09a] tracking-[0.05em] uppercase">Actions</span>
                </div>
                <div class="divide-y divide-[#efefed]">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="flex items-center gap-2.5 px-5 py-3 text-[12px] font-medium text-[#0a0a0a]
                              hover:bg-[#f7f7f5] transition-colors group">
                        <svg class="w-3.5 h-3.5 text-[#a0a09a] group-hover:text-[#0a0a0a] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Modifier la catégorie
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                          data-confirm="Supprimer cette catégorie ?"
                          data-confirm-type="danger">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-5 py-3 text-[12px] font-medium text-[#dc2626]
                                       hover:bg-[#fef2f2] transition-colors text-left group">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                            Supprimer la catégorie
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
    </div>
</div>
@endsection
