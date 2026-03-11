@extends('layouts.admin-layout')

@section('title', 'Catégories — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Catégories
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Administration</div>
        <div class="flex items-start justify-between">
            <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Catégories</h1>
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-1.5 bg-white text-[#0a0a0a] text-[12px] font-medium
                      px-4 py-2.5 rounded-lg hover:opacity-85 transition-opacity mt-1">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouvelle catégorie
            </a>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl px-4 py-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e] flex-shrink-0"></span>
            <span class="text-[12px] text-[#15803d]">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fef2f2] border border-[#fecaca] rounded-xl px-4 py-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#f87171] flex-shrink-0"></span>
            <span class="text-[12px] text-[#dc2626]">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET"
          class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Rechercher</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Nom de la catégorie…"
                   class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                          placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
        </div>
        <div class="w-44">
            <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">Trier par</label>
            <select name="sort_by"
                    class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                           focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                <option value="created_at" {{ ($filters['sort_by'] ?? '') == 'created_at' ? 'selected' : '' }}>Récentes</option>
                <option value="nom"        {{ ($filters['sort_by'] ?? '') == 'nom'        ? 'selected' : '' }}>Nom (A–Z)</option>
            </select>
        </div>
        <button type="submit"
                class="bg-[#0a0a0a] text-white text-[12px] font-medium px-4 py-2 rounded-lg hover:opacity-85 transition-opacity flex items-center gap-1.5">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Filtrer
        </button>
        @if(!empty($filters['search']) || !empty($filters['sort_by']))
            <a href="{{ route('admin.categories.index') }}"
               class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px self-end mb-0.5">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Tableau --}}
    @if($categories->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Nom</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Description</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Produits</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Ajouté</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-5 py-3.5 text-[13px] font-medium text-[#0a0a0a]">{{ $category->nom }}</td>
                            <td class="px-5 py-3.5 text-[12px] text-[#666660] font-light max-w-[220px] truncate">
                                {{ Str::limit($category->description, 55) ?: '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-center font-mono text-[12px] text-[#0a0a0a]">
                                {{ $category->produits_count }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f7f7f5] text-[#a0a09a]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#a0a09a]"></span>Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">
                                {{ $category->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('admin.categories.show', $category) }}"
                                       class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-2.5 py-1.5 rounded-lg
                                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                          data-confirm="Supprimer cette catégorie ?"
                                          data-confirm-title="Supprimer la catégorie"
                                          data-confirm-type="danger"
                                          data-confirm-button="Supprimer">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-[11px] font-medium text-[#dc2626] border border-[#fecaca] px-2.5 py-1.5 rounded-lg
                                                       hover:bg-[#fef2f2] transition-all">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-[11px] font-mono text-[#a0a09a]">
                    {{ $categories->firstItem() }}–{{ $categories->lastItem() }} / {{ $categories->total() }}
                </div>
                <div class="flex items-center gap-1">
                    @if($categories->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">←</span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">←</a>
                    @endif
                    @foreach($categories->getUrlRange(max(1,$categories->currentPage()-2),min($categories->lastPage(),$categories->currentPage()+2)) as $page => $url)
                        @if($page == $categories->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-[#0a0a0a] text-white rounded-lg text-[11px] font-mono">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px] font-mono">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}"
                           class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#666660]
                                  hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all text-[11px]">→</a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center border border-[#e0e0dc] rounded-lg text-[#e0e0dc] text-[11px] cursor-default">→</span>
                    @endif
                </div>
            </div>
        @endif

    @else
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-16 text-center">
            <div class="w-10 h-10 border border-[#e0e0dc] rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-[#0a0a0a] mb-1">Aucune catégorie trouvée</p>
            <p class="text-[12px] text-[#a0a09a] font-light mb-4">Créez votre première catégorie pour organiser vos produits</p>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-1.5 bg-[#0a0a0a] text-white text-[12px] font-medium
                      px-4 py-2 rounded-lg hover:opacity-85 transition-opacity">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Créer une catégorie
            </a>
        </div>
    @endif

    </div>
</div>
@endsection
