@extends('layouts.admin-layout')

@section('title', 'Produits Vedettes — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.products.index') }}" class="hover:text-[#0a0a0a] transition-colors">Produits</a>
    &nbsp;/&nbsp; Vedettes
@endsection

@section('content')
<div class="pb-16">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux produits
        </a>
        <div>
            <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">⭐ Produits Vedettes</h1>
            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-white/10">
                <div>
                    <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $produits->total() }}</div>
                    <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">En vedette</div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                Rechercher
            </label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nom du produit…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(request('search'))
            <a href="{{ route('admin.products.featured') }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Produit</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Catégorie</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Vendeur</th>
                    <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Prix</th>
                    <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Stock</th>
                    <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                    <tr class="border-b border-[#efefed] hover:bg-[#f7f7f5] transition-colors">
                        <td class="px-5 py-4 text-[13px] text-[#0a0a0a] font-medium">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">⭐</span>
                                <a href="{{ route('admin.products.show', $produit) }}"
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $produit->nom }}
                                </a>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-[13px] text-[#666660]">
                            {{ $produit->categorie?->nom ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-[13px] text-[#666660]">
                            {{ $produit->vendeur?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-[13px] font-mono text-[#0a0a0a] font-medium">
                            {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-5 py-4 text-[13px] text-center">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-medium
                                   {{ $produit->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $produit->stock }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.show', $produit) }}"
                                   class="text-[11px] px-2.5 py-1.5 rounded border border-[#e0e0dc] text-[#0a0a0a]
                                          hover:bg-[#f7f7f5] transition-colors">
                                    Voir
                                </a>
                                <form method="POST" action="{{ route('admin.products.toggle-featured', $produit) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-[11px] px-2.5 py-1.5 rounded border border-red-300 text-red-700 bg-red-50
                                                   hover:bg-red-100 transition-colors"
                                            title="Retirer de vedettes">
                                        Retirer ⭐
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-[#a0a09a]">
                            <div class="text-[13px]">Aucun produit vedette trouvé</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($produits->hasPages())
        <div class="flex items-center justify-center gap-2 pt-6">
            {{ $produits->links() }}
        </div>
    @endif

    </div>
</div>
@endsection
