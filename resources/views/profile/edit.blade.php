@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] rounded-xl px-8 pt-10 pb-8 mb-6">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Mon profil</div>

        <div>
            <h1 class="font-serif text-[32px] tracking-tight text-white leading-none mb-2">
                {{ $user->name }}
            </h1>
            <div class="flex items-center gap-3 mt-3">
                <span class="font-mono text-[12px] text-white/50">{{ $user->email }}</span>
                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                <span class="font-mono text-[12px] text-white/50">
                    @if($user->role === 'vendeur')
                        🏪 Vendeur
                    @elseif($user->role === 'client')
                        👥 Client
                    @elseif($user->is_admin)
                        👨‍💼 Administrateur
                    @else
                        Utilisateur
                    @endif
                </span>
            </div>
        </div>

        {{-- Flash success --}}
        @if(session('status') === 'profile-updated')
            <div class="mt-5 pt-5 border-t border-white/10 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#22c55e] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[12px] text-white/60 font-light">Votre profil a été mis à jour avec succès</span>
            </div>
        @endif
    </div>

    {{-- Retour --}}
    <a href="{{ route('commandes.index') }}"
       class="inline-flex items-center gap-1.5 text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-6">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Retour
    </a>

    {{-- ════════════════════════════════════════════════════
         STATS PRINCIPALES
    ════════════════════════════════════════════════════ --}}
    @if($user->role === 'client')
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4">
                <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Commandes</div>
                <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $nombreCommandes }}</div>
                <div class="text-[10px] text-[#a0a09a] mt-1">au total</div>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4">
                <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Total dépensé</div>
                <div class="font-mono text-[22px] font-bold text-[#0a0a0a]">
                    {{ number_format($totalDépensé, 0, ',', ' ') }}
                    <span class="text-[10px]">F</span>
                </div>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4">
                <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Favoris</div>
                <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $favoris->count() }}</div>
                <div class="text-[10px] text-[#a0a09a] mt-1">produits</div>
            </div>

            <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4">
                <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Panier moyen</div>
                <div class="font-mono text-[22px] font-bold text-[#0a0a0a]">
                    {{ $nombreCommandes > 0 ? number_format($totalDépensé / $nombreCommandes, 0, ',', ' ') : 0 }}
                    <span class="text-[10px]">F</span>
                </div>
            </div>
        </div>

        {{-- Graphique dépenses par mois --}}
        @if($depensesParMois->count() > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden mb-8">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Dépenses par mois</span>
                </div>
                <div class="p-5">
                    <div style="height: 250px; position: relative;">
                        <canvas id="depensesChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-5 mb-8">
            {{-- Catégories d'achat --}}
            @if($categoriesAchat->count() > 0)
                <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#efefed]">
                        <span class="text-[13px] font-medium text-[#0a0a0a]">Catégories préférées</span>
                    </div>
                    <div class="p-5 space-y-3">
                        @foreach($categoriesAchat as $cat)
                            <div>
                                <div class="flex justify-between items-baseline mb-1.5">
                                    <span class="text-[12px] font-medium text-[#0a0a0a]">{{ $cat->nom }}</span>
                                    <span class="text-[11px] text-[#a0a09a] font-mono">{{ number_format($cat->montant, 0, ',', ' ') }} F</span>
                                </div>
                                <div class="h-2 bg-[#efefed] rounded-full overflow-hidden">
                                    <div class="h-full bg-[#0a0a0a] rounded-full" style="width: {{ ($cat->montant / $categoriesAchat->first()->montant) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Produits favoris --}}
            @if($favoris->count() > 0)
                <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#efefed]">
                        <span class="text-[13px] font-medium text-[#0a0a0a]">Produits favoris</span>
                    </div>
                    <div class="p-0">
                        @foreach($favoris->take(5) as $fav)
                            <div class="px-5 py-3 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                                <div class="text-[12px] font-medium text-[#0a0a0a] truncate">
                                    {{ $fav->produit->nom }}
                                </div>
                                <div class="text-[11px] text-[#a0a09a] mt-0.5">
                                    {{ number_format($fav->produit->prix, 0, ',', ' ') }} F
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Produits achetés --}}
        @if($produitsAchetes->count() > 0)
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden mb-8">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Produits les plus achetés</span>
                </div>
                <div class="p-0">
                    @foreach($produitsAchetes as $produit)
                        <div class="flex items-center justify-between px-5 py-3 border-b border-[#efefed] last:border-b-0 hover:bg-[#f7f7f5] transition-colors">
                            <div>
                                <div class="text-[12px] font-medium text-[#0a0a0a]">{{ $produit->nom }}</div>
                                <div class="text-[11px] text-[#a0a09a] mt-0.5">
                                    {{ $produit->total_quantite }} unité(s) • {{ number_format($produit->montant, 0, ',', ' ') }} F
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- ════════════════════════════════════════════════════
         FORMULAIRE + SIDEBAR
    ════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-[1fr_280px] gap-5 items-start">

        {{-- Formulaire --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#efefed]">
                <span class="text-[13px] font-medium text-[#0a0a0a]">Informations personnelles</span>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="p-5">
                @csrf
                @method('PATCH')

                <div class="mb-5">
                    <label for="name" class="block text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">
                        Nom complet
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] transition text-[13px] text-[#0a0a0a] placeholder-[#a0a09a]"
                    />
                    @error('name')
                        <p class="text-[#dc2626] text-[11px] mt-1 font-light">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] transition text-[13px] text-[#0a0a0a] placeholder-[#a0a09a]"
                    />
                    @error('email')
                        <p class="text-[#dc2626] text-[11px] mt-1 font-light">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="phone" class="block text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">
                        Téléphone
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:ring-1 focus:ring-[#0a0a0a] transition text-[13px] text-[#0a0a0a] placeholder-[#a0a09a]"
                    />
                </div>

                <div class="flex gap-3 pt-4 border-t border-[#efefed]">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-[#0a0a0a] text-white rounded-lg hover:bg-[#2a2a28] transition font-medium text-[13px]">
                        Enregistrer
                    </button>
                    <a href="{{ route('commandes.index') }}" class="flex-1 px-4 py-2.5 bg-[#f7f7f5] text-[#0a0a0a] border border-[#e0e0dc] rounded-lg hover:bg-[#efefed] transition font-medium text-[13px] text-center">
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Compte</span>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div>
                        <div class="text-[10px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Inscription</div>
                        <div class="font-mono text-[12px] text-[#0a0a0a]">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                    @if($user->email_verified_at)
                        <div class="pt-2 border-t border-[#efefed]">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-[#22c55e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span class="text-[11px] font-light text-[#666660]">Email vérifié</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-2.5">
                <a href="{{ route('commandes.index') }}" class="flex items-center gap-2 w-full px-4 py-3 text-[12px] font-medium text-[#0a0a0a] bg-white border border-[#e0e0dc] rounded-lg hover:bg-[#f7f7f5] transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2h-2M9 5c0-1.1.9-2 2-2s2 .9 2 2"/></svg>
                    Commandes
                </a>
                <a href="{{ route('favoris.index') }}" class="flex items-center gap-2 w-full px-4 py-3 text-[12px] font-medium text-[#0a0a0a] bg-white border border-[#e0e0dc] rounded-lg hover:bg-[#f7f7f5] transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    Favoris
                </a>
            </div>
        </div>
    </div>

</div>
</div>

{{-- Chart.js pour le graphique --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($depensesParMois->count() > 0)
    const ctx = document.getElementById('depensesChart').getContext('2d');
    const labels = [
        @foreach($depensesParMois as $dep)
            '{{ \Carbon\Carbon::createFromFormat('Y-m', $dep->mois)->format('M Y') }}',
        @endforeach
    ];
    const data = [
        @foreach($depensesParMois as $dep)
            {{ $dep->total }},
        @endforeach
    ];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Dépenses (F CFA)',
                data: data,
                borderColor: '#0a0a0a',
                backgroundColor: 'rgba(10, 10, 10, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#0a0a0a',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 12 },
                        color: '#666660',
                        padding: 15,
                        usePointStyle: true
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 11 },
                        color: '#a0a09a',
                        callback: function(value) {
                            return number_format(value, 0, '', ' ') + ' F';
                        }
                    },
                    grid: {
                        color: 'rgba(224, 224, 220, 0.3)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 },
                        color: '#a0a09a'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    @endif

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        let n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                let k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endsection
