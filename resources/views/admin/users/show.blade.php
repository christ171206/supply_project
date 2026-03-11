@extends('layouts.admin-layout')

@section('title', $user->name . ' — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp;
    <a href="{{ route('admin.users.index') }}" class="hover:text-[#0a0a0a] transition-colors">Utilisateurs</a>
    &nbsp;/&nbsp; {{ $user->name }}
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-1.5 text-[11px] text-white/40 hover:text-white/70 transition-colors mb-4">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Retour aux utilisateurs
        </a>
        <div class="flex items-start justify-between">
            <div>
                <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Utilisateur</div>
                <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">{{ $user->name }}</h1>
                <div class="flex items-center gap-3 mt-3">
                    <span class="font-mono text-[12px] text-white/50">{{ ucfirst($user->role ?? 'client') }}</span>
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                    <span class="font-mono text-[12px] text-white/50">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            @php
                if ($user->is_banned)              { $st = ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Banni']; }
                elseif ($user->role === 'vendor' && ($user->vendor_status ?? '') === 'pending') { $st = ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente']; }
                elseif ($user->email_verified_at)  { $st = ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Vérifié']; }
                else                               { $st = ['bg-[#f7f7f5] text-[#a0a09a]','bg-[#a0a09a]','Non vérifié']; }
            @endphp
            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium px-3 py-1.5 rounded-md {{ $st[0] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $st[1] }}"></span>{{ $st[2] }}
            </span>
        </div>
    </div>

    <div class="px-8 space-y-5">

    {{-- INFOS GÉNÉRALES --}}
    <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-xl overflow-hidden">

        {{-- Informations personnelles --}}
        <div class="bg-white px-6 py-5">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Informations personnelles</div>
            <div class="space-y-3.5">
                @foreach([
                    ['label'=>'Email',     'value'=>$user->email,              'mono'=>true],
                    ['label'=>'Pays',      'value'=>$user->country ?? '—',     'mono'=>false],
                    ['label'=>'Téléphone', 'value'=>$user->phone ?? '—',       'mono'=>true],
                    ['label'=>'Adresse',   'value'=>$user->address ?? '—',     'mono'=>false],
                ] as $row)
                    <div class="flex items-start gap-4">
                        <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0 pt-0.5">{{ $row['label'] }}</span>
                        <span class="{{ $row['mono'] ? 'font-mono text-[12px]' : 'text-[13px]' }} text-[#0a0a0a]">{{ $row['value'] }}</span>
                    </div>
                @endforeach
                @if($user->email_verified_at)
                    <div class="flex items-center gap-1.5 pt-1">
                        <svg class="w-3 h-3 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span class="text-[11px] text-[#15803d] font-light">Email vérifié le {{ $user->email_verified_at->format('d/m/Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Rôle & statut --}}
        <div class="bg-white px-6 py-5">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Rôle & statut</div>
            <div class="space-y-3.5">
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0">Rôle</span>
                    @php
                        $roleBadge = match($user->role ?? '') {
                            'admin'  => 'bg-[#fef2f2] text-[#dc2626]',
                            'vendor' => 'bg-[#fdf6ec] text-[#b45309]',
                            default  => 'bg-[#eff6ff] text-[#2563eb]',
                        };
                    @endphp
                    <span class="inline-flex items-center text-[10px] font-mono font-medium px-2 py-1 rounded {{ $roleBadge }}">
                        {{ ucfirst($user->role ?? 'client') }}
                    </span>
                </div>

                @if($user->role === 'vendor')
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0">Statut</span>
                        @php
                            $vsb = match($user->vendor_status ?? '') {
                                'approved' => ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Approuvé'],
                                'rejected' => ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Rejeté'],
                                default    => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $vsb[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $vsb[1] }}"></span>{{ $vsb[2] }}
                        </span>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0 pt-0.5">Boutique</span>
                        <span class="text-[13px] text-[#0a0a0a]">{{ $user->shop_name ?? '—' }}</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0 pt-0.5">Tél boutique</span>
                        <span class="font-mono text-[12px] text-[#0a0a0a]">{{ $user->boutique_telephone ?? '—' }}</span>
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] w-20 flex-shrink-0">Banni</span>
                    @if($user->is_banned)
                        <div>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#fef2f2] text-[#dc2626]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f87171]"></span>Oui
                            </span>
                            @if($user->banned_until)
                                <div class="text-[11px] text-[#a0a09a] font-light mt-1">Jusqu'au {{ $user->banned_until->format('d/m/Y') }}</div>
                            @else
                                <div class="text-[11px] text-[#a0a09a] font-light mt-1">Indéfiniment</div>
                            @endif
                        </div>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Non
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Description boutique --}}
    @if($user->role === 'vendor' && $user->boutique_description)
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-5">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-3">Description de boutique</div>
            <p class="text-[13px] text-[#2a2a28] font-light leading-relaxed">{{ $user->boutique_description }}</p>
        </div>
    @endif

    {{-- Documents KYC --}}
    @if($user->role === 'vendor' && $user->documents && $user->documents->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Documents KYC</span>
                <a href="{{ route('admin.users.documents', $user) }}"
                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors border-b border-[#e0e0dc] pb-px">
                    Voir tout →
                </a>
            </div>
            @foreach($user->documents as $doc)
                @php
                    $db2 = match($doc->status ?? '') {
                        'verified' => ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Vérifié'],
                        'rejected' => ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Rejeté'],
                        default    => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente'],
                    };
                @endphp
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                    <div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</div>
                        <div class="font-mono text-[11px] text-[#a0a09a]">{{ $doc->created_at->format('d/m/Y') }}</div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $db2[0] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $db2[1] }}"></span>{{ $db2[2] }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Produits (vendor) --}}
    @if($user->role === 'vendor' && $user->produits && $user->produits->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Produits ({{ $user->produits->count() }})</span>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Produit</th>
                        <th class="text-right px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Prix</th>
                        <th class="text-center px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Stock</th>
                        <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">Créé</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->produits as $product)
                        <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-5 py-3.5 text-[13px] font-medium text-[#0a0a0a]">{{ $product->nom }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-[12px] font-medium text-[#0a0a0a]">
                                {{ number_format($product->prix, 0, ',', ' ') }} <span class="text-[10px] text-[#a0a09a] font-sans">F</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="font-mono text-[12px] {{ $product->stock < 5 ? 'text-[#dc2626]' : 'text-[#15803d]' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Commandes (client) --}}
    @if($user->role === 'customer' && $user->commandes && $user->commandes->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Commandes ({{ $user->commandes->count() }})</span>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                        @foreach(['N°','Montant','Statut','Date',''] as $h)
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->commandes as $order)
                        @php
                            $ob = match($order->statut) {
                                'en_attente' => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En attente'],
                                'confirmee'  => ['bg-[#eff6ff] text-[#2563eb]', 'bg-[#60a5fa]','Confirmée'],
                                'expediee'   => ['bg-[#f5f3ff] text-[#7c3aed]', 'bg-[#a78bfa]','Expédiée'],
                                'livree'     => ['bg-[#f0fdf4] text-[#15803d]', 'bg-[#22c55e]','Livrée'],
                                default      => ['bg-[#fef2f2] text-[#dc2626]', 'bg-[#f87171]',ucfirst(str_replace('_',' ',$order->statut))],
                            };
                        @endphp
                        <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-5 py-3.5 font-mono text-[12px] text-[#666660]">#{{ $order->id }}</td>
                            <td class="px-5 py-3.5 font-mono text-[12px] font-medium text-[#0a0a0a]">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $ob[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ob[1] }}"></span>{{ $ob[2] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Litiges --}}
    @if($user->disputes && $user->disputes->count() > 0)
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Litiges ({{ $user->disputes->count() }})</span>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#efefed] bg-[#f7f7f5]">
                        @foreach(['Titre','Statut','Date',''] as $h)
                            <th class="text-left px-5 py-3 text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a]">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->disputes as $dispute)
                        @php
                            $dsb = match($dispute->status ?? 'open') {
                                'open'        => ['bg-[#fef2f2] text-[#dc2626]','bg-[#f87171]','Ouvert'],
                                'in_progress' => ['bg-[#fdf6ec] text-[#b45309]','bg-[#f59e0b]','En cours'],
                                'resolved'    => ['bg-[#f0fdf4] text-[#15803d]','bg-[#22c55e]','Résolu'],
                                default       => ['bg-[#f7f7f5] text-[#a0a09a]','bg-[#a0a09a]','Fermé'],
                            };
                        @endphp
                        <tr class="border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-5 py-3.5 text-[13px] font-medium text-[#0a0a0a]">{{ $dispute->titre }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded {{ $dsb[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dsb[1] }}"></span>{{ $dsb[2] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-[#a0a09a]">{{ $dispute->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.disputes.show', $dispute->id) }}"
                                   class="text-[11px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors">Voir →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="bg-white border border-[#e0e0dc] rounded-xl px-6 py-5">
        <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4">Actions</div>
        <div class="flex flex-wrap gap-2">

            @if(!$user->is_banned)
                <form method="POST" action="{{ route('admin.users.ban', $user->id) }}"
                      data-confirm="Bannir cet utilisateur ?"
                      data-confirm-title="Bannir l'utilisateur"
                      data-confirm-type="danger"
                      data-confirm-button="Bannir">
                    @csrf
                    <button type="submit"
                            class="text-[12px] font-medium text-[#dc2626] border border-[#fecaca] px-4 py-2 rounded-lg
                                   hover:bg-[#fef2f2] transition-all">
                        Bannir l'utilisateur
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.unban', $user->id) }}"
                      data-confirm="Débannir cet utilisateur ?"
                      data-confirm-title="Débannir l'utilisateur"
                      data-confirm-type="success"
                      data-confirm-button="Débannir">
                    @csrf
                    <button type="submit"
                            class="text-[12px] font-medium text-[#15803d] border border-[#bbf7d0] px-4 py-2 rounded-lg
                                   hover:bg-[#f0fdf4] transition-all">
                        Débannir l'utilisateur
                    </button>
                </form>
            @endif

            @if($user->role === 'vendor' && ($user->vendor_status ?? '') === 'pending')
                <form method="POST" action="{{ route('admin.users.approve-vendor', $user) }}"
                      data-confirm="Approuver ce vendeur ?"
                      data-confirm-title="Approuver le vendeur"
                      data-confirm-type="success"
                      data-confirm-button="Approuver">
                    @csrf
                    <button type="submit"
                            class="text-[12px] font-medium text-[#15803d] border border-[#bbf7d0] px-4 py-2 rounded-lg
                                   hover:bg-[#f0fdf4] transition-all">
                        Approuver le vendeur
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.users.reject-vendor', $user) }}"
                      data-confirm="Rejeter ce vendeur ?"
                      data-confirm-title="Rejeter le vendeur"
                      data-confirm-type="danger"
                      data-confirm-button="Rejeter">
                    @csrf
                    <button type="submit"
                            class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-4 py-2 rounded-lg
                                   hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Rejeter le vendeur
                    </button>
                </form>
            @endif

        </div>
    </div>

    </div>
</div>
@endsection
