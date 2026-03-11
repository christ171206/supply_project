@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">

    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Espace vendeur</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">
            Tableau de Bord
        </h1>
        <p class="text-[13px] text-white/50 font-light mt-2">Gérez votre boutique en ligne</p>

        {{-- KPIs inline dans le header --}}
        <div class="flex items-center gap-6 mt-6 pt-6 border-t border-white/10">
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ number_format($totalVentes, 0, ',', ' ') }}
                    <span class="text-[13px] text-white/40 font-sans font-light">FCFA</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Chiffre d'affaires</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $nombreCommandes }}</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Commandes</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">{{ $tauxCompletion }}%</div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Complétion</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div>
                <div class="font-mono text-[22px] font-medium text-white leading-none">
                    {{ $nombreCommandes > 0 ? number_format($totalVentes / $nombreCommandes, 0, ',', ' ') : '0' }}
                    <span class="text-[13px] text-white/40 font-sans font-light">FCFA</span>
                </div>
                <div class="text-[10px] text-white/40 tracking-[0.08em] uppercase mt-1">Panier moyen</div>
            </div>
        </div>
    </div>

    <div class="px-8 space-y-8">

    {{-- ══════════════════════════════
         STATUT COMMANDES — hover inversé + grands chiffres
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Statut des commandes</div>
        <div class="grid grid-cols-4 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

            <div class="bg-white px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span>
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">En attente</span>
                </div>
                <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">{{ $commandesEnAttente }}</div>
                <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">À valider</div>
            </div>

            <div class="bg-white px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#60a5fa]"></span>
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">Confirmées</span>
                </div>
                <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">{{ $commandesConfirmees }}</div>
                <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">Prêtes</div>
            </div>

            <div class="bg-white px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#a78bfa]"></span>
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">Expédiées</span>
                </div>
                <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">{{ $commandesExpediees }}</div>
                <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">En route</div>
            </div>

            <div class="bg-white px-5 py-6 hover:bg-[#0a0a0a] group transition-colors cursor-default">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                    <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] group-hover:text-white/50 transition-colors">Livrées</span>
                </div>
                <div class="font-mono text-[40px] font-medium text-[#0a0a0a] group-hover:text-white leading-none tracking-tight transition-colors">{{ $commandeslivrees }}</div>
                <div class="text-[11px] text-[#a0a09a] group-hover:text-white/40 font-light mt-2 transition-colors">Complètes</div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════
         ALERTE STOCK CRITIQUE
    ══════════════════════════════ --}}
    @if($produitsStockFaible->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-[#efefed]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>
                <span class="text-[13px] font-medium text-[#0a0a0a]">Stock critique</span>
                <span class="ml-auto text-[10px] font-mono bg-[#fef2f2] text-[#dc2626] px-2 py-0.5 rounded">
                    {{ $produitsStockFaible->count() }} produit{{ $produitsStockFaible->count() > 1 ? 's' : '' }}
                </span>
            </div>
            @foreach($produitsStockFaible as $produit)
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                        <div class="text-[11px] text-[#a0a09a] font-mono font-light mt-0.5">Stock : {{ $produit->stock }} / Min : {{ $produit->stock_minimum }}</div>
                    </div>
                    <a href="{{ route('vendeur.produits.edit', $produit->id) }}"
                       class="text-[11px] font-medium bg-[#0a0a0a] text-white px-3 py-1.5 rounded-md hover:opacity-85 transition-opacity">
                        Modifier
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══════════════════════════════
         ACTIONS RAPIDES
    ══════════════════════════════ --}}
    <div>
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Actions rapides</div>
        <div class="grid grid-cols-3 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

            <a href="{{ route('vendeur.produits.create') }}" class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0 group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Ajouter produit</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/60 transition-colors">Nouveau produit</div>
                </div>
            </a>

            <a href="{{ route('vendeur.produits.index') }}" class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0 group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Gérer produits</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/60 transition-colors">Modifier vos produits</div>
                </div>
            </a>

            <a href="{{ route('vendeur.commandes') }}" class="bg-white px-5 py-5 flex items-center gap-4 hover:bg-[#0a0a0a] group transition-colors">
                <div class="w-9 h-9 border border-[#e0e0dc] rounded-lg bg-[#f7f7f5] flex items-center justify-center flex-shrink-0 group-hover:border-white/20 group-hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4 text-[#666660] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                </div>
                <div>
                    <div class="text-[13px] font-medium text-[#0a0a0a] group-hover:text-white transition-colors">Voir commandes</div>
                    <div class="text-[11px] text-[#a0a09a] font-light group-hover:text-white/60 transition-colors">Toutes vos commandes</div>
                </div>
            </a>

        </div>
    </div>

    {{-- ══════════════════════════════
         DERNIÈRES COMMANDES
    ══════════════════════════════ --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#efefed]">
            <span class="text-[13px] font-medium text-[#0a0a0a]">Dernières commandes</span>
            <a href="{{ route('vendeur.commandes') }}" class="text-[11px] text-[#a0a09a] border-b border-[#e0e0dc] pb-px hover:text-[#0a0a0a] hover:border-[#0a0a0a] transition-all">Voir tout →</a>
        </div>
        @if($derniereCommandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Client</th>
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">N°</th>
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Date</th>
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Montant</th>
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Statut</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($derniereCommandes as $commande)
                            <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                                <td class="px-5 py-3.5 text-[13px] font-medium text-[#0a0a0a]">{{ $commande->user->name }}</td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-[#666660]">#{{ $commande->id }}</td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-[#a0a09a]">{{ $commande->created_at->format('d/m/y') }}</td>
                                <td class="px-5 py-3.5 font-mono text-[12px] font-medium text-[#0a0a0a]">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $badge = match($commande->statut) {
                                            'en_attente' => ['bg-[#fdf6ec] text-[#b45309]', 'bg-[#f59e0b]'],
                                            'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]',  'bg-[#60a5fa]'],
                                            'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]',  'bg-[#a78bfa]'],
                                            'livree'     => ['bg-[#f0fdf4] text-[#15803d]',  'bg-[#22c55e]'],
                                            default      => ['bg-[#f7f7f5] text-[#666660]',  'bg-[#a0a09a]'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-medium font-mono px-2 py-1 rounded {{ $badge[0] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $badge[1] }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('vendeur.commandes.show', $commande->id) }}" class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucune commande reçue</div>
        @endif
    </div>

    {{-- ══════════════════════════════
         TOP PRODUITS + AVIS — 2 colonnes
    ══════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Top produits</span>
            </div>
            @if($topProduits->count() > 0)
                @foreach($topProduits as $index => $produit)
                    <div class="flex items-center gap-4 px-5 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <span class="w-5 text-[11px] font-mono text-[#a0a09a] flex-shrink-0">{{ $index + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-[13px] font-medium text-[#0a0a0a] truncate">{{ $produit->nom }}</div>
                            <div class="text-[11px] text-[#a0a09a] font-light">{{ $produit->categorie->nom }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-mono text-[12px] font-medium text-[#0a0a0a]">{{ number_format($produit->ventes_total ?? 0, 0, ',', ' ') }} FCFA</div>
                            <div class="text-[10px] text-[#a0a09a]">{{ $produit->ventes_nombre ?? 0 }} ventes</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucune vente</div>
            @endif
        </div>

        <div class="bg-white">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Avis clients récents</span>
            </div>
            @if($avisRecents->count() > 0)
                @foreach($avisRecents as $avis)
                    <div class="px-5 py-4 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $avis->user->name }}</div>
                                <div class="text-[11px] text-[#a0a09a] font-light mt-0.5 truncate max-w-[160px]">{{ $avis->produit->nom }}</div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-[11px] {{ $i <= $avis->note ? 'text-[#0a0a0a]' : 'text-[#e0e0dc]' }}">★</span>
                                    @endfor
                                </div>
                                <span class="font-mono text-[10px] text-[#a0a09a]">{{ $avis->created_at->format('d/m/y') }}</span>
                            </div>
                        </div>
                        <p class="text-[12px] text-[#666660] font-light leading-relaxed line-clamp-2">{{ $avis->commentaire }}</p>
                    </div>
                @endforeach
            @else
                <div class="px-5 py-10 text-center text-[13px] text-[#a0a09a] font-light">Aucun avis</div>
            @endif
        </div>

    </div>

    </div>{{-- /px-8 --}}
</div>
@endsection
