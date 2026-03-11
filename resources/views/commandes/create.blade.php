@extends('layouts.app')

@section('content')

{{-- Toast container --}}
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none"></div>

<div class="min-h-screen bg-[#f7f7f5]">
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- ══════════════════════════════
         HEADER
    ══════════════════════════════ --}}
    <div class="mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-[#a0a09a] mb-2">Supply</div>
        <h1 class="font-serif text-[32px] tracking-tight text-[#0a0a0a] leading-none mb-5">Finaliser la commande</h1>

        {{-- Stepper --}}
        <div class="flex items-center gap-0 max-w-xs">
            @php $steps = ['Livraison','Paiement','Confirmation']; @endphp
            @foreach($steps as $i => $step)
                <div class="flex items-center gap-0">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-sm flex items-center justify-center text-[11px] font-mono font-medium
                            {{ $i < 2 ? 'bg-[#0a0a0a] text-white' : 'bg-[#efefed] text-[#a0a09a]' }}">
                            {{ $i + 1 }}
                        </div>
                        <span class="text-[9px] font-medium tracking-[0.06em] uppercase mt-1.5
                            {{ $i < 2 ? 'text-[#0a0a0a]' : 'text-[#a0a09a]' }}">
                            {{ $step }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div class="w-10 h-px {{ $i < 1 ? 'bg-[#0a0a0a]' : 'bg-[#e0e0dc]' }} mx-2 mb-4"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════
         GRID
    ══════════════════════════════ --}}
    <div class="grid grid-cols-[1fr_300px] gap-6 items-start">

        {{-- FORMULAIRE --}}
        <form action="{{ route('commandes.store') }}" method="POST" id="payment-form" class="space-y-4">
        @csrf

        {{-- ── Section Livraison ── --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-[#efefed]">
                <div class="w-5 h-5 bg-[#0a0a0a] rounded-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <span class="text-[13px] font-medium text-[#0a0a0a]">Adresse de livraison</span>
            </div>

            <div class="p-5 space-y-5">

                {{-- Pays --}}
                <div>
                    <label for="pays" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Pays</label>
                    <input type="text" id="pays" name="pays"
                           value="{{ old('pays', 'Côte d\'Ivoire') }}"
                           placeholder="Ex: Côte d'Ivoire, France…"
                           class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                    @error('pays')
                        <p class="text-[11px] text-[#dc2626] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tabs recherche / manuel --}}
                <div class="flex gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-lg overflow-hidden">
                    <button type="button" data-tab="search"
                            class="location-tab active flex-1 px-4 py-2.5 text-[12px] font-medium bg-[#0a0a0a] text-white transition-colors">
                        Recherche rapide
                    </button>
                    <button type="button" data-tab="manual"
                            class="location-tab flex-1 px-4 py-2.5 text-[12px] font-medium bg-white text-[#666660] hover:text-[#0a0a0a] transition-colors">
                        Sélection manuelle
                    </button>
                </div>

                {{-- Recherche rapide --}}
                <div id="search-tab" class="location-section" style="position:relative; z-index:100;">
                    <div class="relative">
                        <input type="text" id="location-search"
                               placeholder="Tapez une ville, un quartier… (ex: Cocody, Yopougon)"
                               autocomplete="off"
                               class="w-full px-3.5 py-3 pr-10 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                        <svg class="absolute right-3.5 top-3.5 w-3.5 h-3.5 text-[#a0a09a] pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <div id="search-results"
                             class="absolute top-full left-0 right-0 mt-1 bg-white border border-[#e0e0dc] rounded-lg hidden max-h-64 overflow-y-auto"
                             style="box-shadow: 0 8px 24px rgba(0,0,0,0.08);"></div>
                    </div>
                    <p class="text-[10px] text-[#a0a09a] font-light mt-1.5">Meilleur moyen : tapez directement le nom du quartier</p>
                </div>

                {{-- Sélection manuelle --}}
                <div id="manual-tab" class="location-section hidden">
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            ['id' => 'region',   'name' => 'region_id',       'label' => 'Région'],
                            ['id' => 'district', 'name' => 'district_id',     'label' => 'District',  'disabled' => true],
                            ['id' => 'commune',  'name' => 'commune_id',      'label' => 'Commune',   'disabled' => true],
                            ['id' => 'quartier', 'name' => 'quartier_manual', 'label' => 'Quartier',  'disabled' => true],
                        ] as $sel)
                        <div>
                            <label for="{{ $sel['id'] }}" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                                {{ $sel['label'] }}
                            </label>
                            <select id="{{ $sel['id'] }}" name="{{ $sel['name'] }}"
                                    {{ isset($sel['disabled']) ? 'disabled' : '' }}
                                    class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors disabled:text-[#a0a09a] disabled:cursor-not-allowed cursor-pointer">
                                <option value="">— Sélectionner —</option>
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Adresse détaillée --}}
                <div>
                    <label for="adresse_detail" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Adresse détaillée</label>
                    <textarea id="adresse_detail" name="adresse_detail" rows="3" required
                              placeholder="N° de rue, immeuble, point de repère…"
                              class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors resize-none">{{ old('adresse_detail') }}</textarea>
                    @error('adresse_detail')
                        <p class="text-[11px] text-[#dc2626] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Téléphone livraison --}}
                <div>
                    <label for="telephone_livraison" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Téléphone de livraison</label>
                    <input type="tel" id="telephone_livraison" name="telephone_livraison" required
                           placeholder="+225 01 23 45 67 89"
                           inputmode="numeric"
                           class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                    @error('telephone_livraison')
                        <p class="text-[11px] text-[#dc2626] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Section Paiement ── --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-[#efefed]">
                <div class="w-5 h-5 bg-[#0a0a0a] rounded-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <span class="text-[13px] font-medium text-[#0a0a0a]">Méthode de paiement</span>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-2 gap-px bg-[#e0e0dc] border border-[#e0e0dc] rounded-lg overflow-hidden">
                    @php
                        $methods = [
                            ['value' => 'wave',        'label' => 'Wave',          'sub' => 'Paiement sécurisé',          'img' => 'wave.png'],
                            ['value' => 'orange_money', 'label' => 'Orange Money', 'sub' => 'Porte-monnaie Orange',        'img' => 'orange money.png'],
                            ['value' => 'mtn_money',   'label' => 'MTN Money',     'sub' => 'Service MTN',                 'img' => 'mtn money.png'],
                            ['value' => 'moov_money',  'label' => 'Moov Money',    'sub' => 'Service Moov',                'img' => 'moov money.png'],
                        ];
                    @endphp

                    @foreach($methods as $m)
                    <label class="payment-option cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $m['value'] }}" class="hidden" required>
                        <div class="flex items-center gap-3 px-4 py-4 bg-white hover:bg-[#f7f7f5] transition-colors">
                            <img src="{{ asset('images/payments/' . $m['img']) }}"
                                 alt="{{ $m['label'] }}"
                                 class="w-9 h-9 object-cover rounded-md flex-shrink-0">
                            <div>
                                <div class="text-[13px] font-medium text-[#0a0a0a]">{{ $m['label'] }}</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">{{ $m['sub'] }}</div>
                            </div>
                        </div>
                    </label>
                    @endforeach

                    {{-- Paiement par Carte (Stripe) --}}
                    <label class="payment-option cursor-pointer">
                        <input type="radio" name="payment_method" value="card" class="hidden" required>
                        <div class="flex items-center gap-3 px-4 py-4 bg-white hover:bg-[#f7f7f5] transition-colors">
                            <div class="w-9 h-9 rounded-md flex-shrink-0 bg-gradient-to-br from-[#625df8] to-[#0a0a0a] flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="text-[13px] font-medium text-[#0a0a0a]">Carte Bancaire</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">Visa, Mastercard, etc.</div>
                            </div>
                        </div>
                    </label>

                    {{-- À la livraison — pleine largeur --}}
                    <label class="payment-option cursor-pointer col-span-2">
                        <input type="radio" name="payment_method" value="cash" class="hidden" required>
                        <div class="flex items-center gap-3 px-4 py-4 bg-white hover:bg-[#f7f7f5] transition-colors border-t border-[#e0e0dc]">
                            <img src="{{ asset('images/payments/a la livraison.jfif') }}"
                                 alt="À la livraison"
                                 class="w-9 h-9 object-cover rounded-md flex-shrink-0">
                            <div>
                                <div class="text-[13px] font-medium text-[#0a0a0a]">À la livraison</div>
                                <div class="text-[11px] text-[#a0a09a] font-light">Paiement en espèces à la réception</div>
                            </div>
                        </div>
                    </label>
                </div>

                @error('payment_method')
                    <p class="text-[11px] text-[#dc2626] mt-2">{{ $message }}</p>
                @enderror

                {{-- Téléphone paiement mobile --}}
                <div id="phone-payment-section" class="hidden mt-4">
                    <label for="phone_payment" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                        Numéro de téléphone (paiement mobile)
                    </label>
                    <input type="tel" id="phone_payment" name="phone_payment"
                           placeholder="+225 01 23 45 67 89"
                           class="w-full px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors">
                </div>
            </div>
        </div>

        {{-- ── Conditions ── --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="accept_conditions" required
                       class="mt-0.5 w-4 h-4 border border-[#e0e0dc] rounded cursor-pointer accent-[#0a0a0a] flex-shrink-0">
                <span class="text-[13px] text-[#2a2a28] font-light leading-relaxed">
                    J'accepte les <a href="#" class="text-[#0a0a0a] border-b border-[#e0e0dc] pb-px hover:border-[#0a0a0a] transition-colors">conditions d'utilisation</a>
                    et la <a href="#" class="text-[#0a0a0a] border-b border-[#e0e0dc] pb-px hover:border-[#0a0a0a] transition-colors">politique de confidentialité</a>
                </span>
            </label>
            @error('accept_conditions')
                <p class="text-[11px] text-[#dc2626] mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Champs cachés --}}
        <input type="hidden" id="hidden-quartier-id" name="quartier_id" value="">
        <input type="hidden" id="hidden-adresse-livraison" name="adresse_livraison" value="">

        {{-- Boutons --}}
        <div class="flex gap-3">
            <a href="{{ route('panier.index') }}"
               class="flex items-center gap-2 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Panier
            </a>
            <button type="submit" id="submit-btn"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#0a0a0a] text-white text-[13px] font-medium py-3 rounded-lg hover:opacity-85 transition-opacity">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Confirmer la commande
            </button>
        </div>

        </form>

        {{-- RÉSUMÉ (sticky) --}}
        <div class="sticky top-6">
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">

                <div class="px-5 py-4 border-b border-[#efefed]">
                    <span class="text-[13px] font-medium text-[#0a0a0a]">Résumé</span>
                </div>

                {{-- Articles --}}
                <div class="divide-y divide-[#efefed] max-h-72 overflow-y-auto">
                    @forelse($items as $item)
                        <div class="px-4 py-3.5 hover:bg-[#f7f7f5] transition-colors">
                            <div class="text-[13px] font-medium text-[#0a0a0a] leading-snug mb-0.5">{{ $item->produit->nom }}</div>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[11px] text-[#a0a09a] font-light font-mono">{{ $item->quantite }} × {{ number_format($item->prix_unitaire, 0, ',', ' ') }}</span>
                                <span class="text-[12px] font-mono font-medium text-[#0a0a0a]">{{ number_format($item->quantite * $item->prix_unitaire, 0, ',', ' ') }} F</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-[13px] text-[#a0a09a] font-light">Aucun article</div>
                    @endforelse
                </div>

                {{-- Calculs --}}
                <div class="px-5 py-4 border-t border-[#efefed] space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] text-[#666660] font-light">Sous-total</span>
                        <span class="font-mono text-[12px] text-[#0a0a0a]">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] text-[#666660] font-light">Livraison</span>
                        <span class="font-mono text-[12px] text-[#0a0a0a]" id="shipping-cost">
                            @if($total > 100) Gratuite @else 2 500 FCFA @endif
                        </span>
                    </div>
                </div>

                {{-- Total --}}
                <div class="mx-4 mb-4 bg-[#0a0a0a] rounded-lg px-4 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium tracking-[0.08em] uppercase text-white/60">Total TTC</span>
                        <span class="font-mono text-[18px] font-medium text-white" id="total-amount">
                            @if($total > 100)
                                {{ number_format($total, 0, ',', ' ') }}
                            @else
                                {{ number_format($total + 2500, 0, ',', ' ') }}
                            @endif
                            <span class="text-[11px] text-white/50 font-sans font-light ml-0.5">FCFA</span>
                        </span>
                    </div>
                </div>

                {{-- Infos client --}}
                <div class="px-5 pb-4 pt-1 border-t border-[#efefed] space-y-2">
                    <div class="text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">Votre compte</div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-[#0a0a0a] rounded-sm flex items-center justify-center text-white text-[10px] font-medium flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-[12px] font-medium text-[#0a0a0a]">{{ auth()->user()->name }}</div>
                            <div class="text-[11px] text-[#a0a09a] font-light">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                </div>

                {{-- Sécurité --}}
                <div class="mx-4 mb-4 flex items-center gap-2 px-3 py-2.5 border border-[#e0e0dc] rounded-lg">
                    <svg class="w-3.5 h-3.5 text-[#a0a09a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span class="text-[11px] text-[#666660] font-light">Paiement sécurisé et chiffré</span>
                </div>

            </div>
        </div>

    </div>{{-- /grid --}}
</div>
</div>

<style>
/* Payment option selected state */
.payment-option input[type="radio"]:checked + div {
    background-color: #f7f7f5;
    outline: 1.5px solid #0a0a0a;
    outline-offset: -1.5px;
}

/* Input autofill reset */
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0px 1000px #f7f7f5 inset;
    -webkit-text-fill-color: #0a0a0a;
}

/* Scrollbar */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: #f7f7f5; }
::-webkit-scrollbar-thumb { background: #e0e0dc; border-radius: 2px; }
::-webkit-scrollbar-thumb:hover { background: #a0a09a; }

/* Toast */
.toast-notification {
    pointer-events: auto;
    animation: toastIn 0.25s ease forwards;
}
.toast-notification.hide {
    animation: toastOut 0.25s ease forwards;
}
@keyframes toastIn  { from { opacity:0; transform:translateX(24px); } to { opacity:1; transform:translateX(0); } }
@keyframes toastOut { from { opacity:1; transform:translateX(0); }    to { opacity:0; transform:translateX(24px); } }

.toast-success { background:#0a0a0a; color:#fff; }
.toast-error   { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
.toast-info    { background:#f7f7f5; color:#2a2a28; border:1px solid #e0e0dc; }
.toast-warning { background:#fdf6ec; color:#b45309; border:1px solid #fde68a; }

.toast-notification {
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    max-width: 280px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
</style>

<script>
const API_BASE = '{{ url("/api") }}';
let searchTimeout;

document.addEventListener('DOMContentLoaded', () => {
    loadRegions();
    setupEventListeners();
    setupFormValidation();
});

function setupEventListeners() {
    // Tabs
    document.querySelectorAll('.location-tab').forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            const tabName = tab.dataset.tab;

            document.querySelectorAll('.location-tab').forEach(t => {
                const isActive = t.dataset.tab === tabName;
                t.classList.toggle('bg-[#0a0a0a]', isActive);
                t.classList.toggle('text-white', isActive);
                t.classList.toggle('bg-white', !isActive);
                t.classList.toggle('text-[#666660]', !isActive);
            });

            document.querySelectorAll('.location-section').forEach(s => s.classList.add('hidden'));
            document.getElementById(`${tabName}-tab`).classList.remove('hidden');

            if (tabName === 'manual') {
                document.getElementById('location-search').value = '';
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    });

    document.getElementById('region').addEventListener('change', loadDistricts);
    document.getElementById('district').addEventListener('change', loadCommunes);
    document.getElementById('commune').addEventListener('change', loadQuartiers);
    document.getElementById('quartier').addEventListener('change', e => {
        if (e.target.value) document.getElementById('hidden-quartier-id').value = e.target.value;
    });

    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', e => {
            showNotification(`${e.target.value.replace(/_/g, ' ')} sélectionné`, 'info');
            togglePhoneSection();
        });
    });

    document.getElementById('location-search').addEventListener('input', handleLocationSearch);
    document.addEventListener('click', e => {
        if (!e.target.closest('#location-search') && !e.target.closest('#search-results'))
            document.getElementById('search-results').classList.add('hidden');
    });
}

async function loadRegions() {
    try {
        const r = await fetch(`${API_BASE}/delivery-locations/regions`);
        const data = await r.json();
        const select = document.getElementById('region');
        select.innerHTML = '<option value="">— Sélectionner une région —</option>';
        (data.data ?? []).forEach(region => {
            const o = document.createElement('option');
            o.value = region.id; o.textContent = region.name;
            select.appendChild(o);
        });
    } catch(e) { showNotification('Erreur chargement régions', 'error'); }
}

async function loadDistricts() {
    const regionId = document.getElementById('region').value;
    const districtSelect = document.getElementById('district');
    const communeSelect  = document.getElementById('commune');
    const quartierSelect = document.getElementById('quartier');
    if (!regionId) {
        [districtSelect, communeSelect, quartierSelect].forEach(s => { s.disabled = true; s.innerHTML = '<option value="">— Sélectionner —</option>'; });
        return;
    }
    try {
        const r = await fetch(`${API_BASE}/delivery-locations/regions/${regionId}/districts`);
        const data = await r.json();
        districtSelect.innerHTML = '<option value="">— Sélectionner un district —</option>';
        districtSelect.disabled = false;
        (data.data ?? []).forEach(d => { const o = document.createElement('option'); o.value = d.id; o.textContent = d.name; districtSelect.appendChild(o); });
        communeSelect.innerHTML = '<option value="">— Sélectionner —</option>'; communeSelect.disabled = true;
        quartierSelect.innerHTML = '<option value="">— Sélectionner —</option>'; quartierSelect.disabled = true;
    } catch(e) { showNotification('Erreur chargement districts', 'error'); }
}

async function loadCommunes() {
    const districtId = document.getElementById('district').value;
    const communeSelect  = document.getElementById('commune');
    const quartierSelect = document.getElementById('quartier');
    if (!districtId) {
        [communeSelect, quartierSelect].forEach(s => { s.disabled = true; s.innerHTML = '<option value="">— Sélectionner —</option>'; });
        return;
    }
    try {
        const r = await fetch(`${API_BASE}/delivery-locations/districts/${districtId}/communes`);
        const data = await r.json();
        communeSelect.innerHTML = '<option value="">— Sélectionner une commune —</option>';
        communeSelect.disabled = false;
        (data.data ?? []).forEach(c => { const o = document.createElement('option'); o.value = c.id; o.textContent = c.name; communeSelect.appendChild(o); });
        quartierSelect.innerHTML = '<option value="">— Sélectionner —</option>'; quartierSelect.disabled = true;
    } catch(e) { showNotification('Erreur chargement communes', 'error'); }
}

async function loadQuartiers() {
    const communeId = document.getElementById('commune').value;
    const quartierSelect = document.getElementById('quartier');
    if (!communeId) { quartierSelect.disabled = true; quartierSelect.innerHTML = '<option value="">— Sélectionner —</option>'; return; }
    try {
        const r = await fetch(`${API_BASE}/delivery-locations/communes/${communeId}/quartiers`);
        const data = await r.json();
        quartierSelect.innerHTML = '<option value="">— Sélectionner un quartier —</option>';
        quartierSelect.disabled = false;
        (data.data ?? []).forEach(q => { const o = document.createElement('option'); o.value = q.id; o.textContent = q.name; quartierSelect.appendChild(o); });
    } catch(e) { showNotification('Erreur chargement quartiers', 'error'); }
}

function handleLocationSearch() {
    const query = document.getElementById('location-search').value.trim();
    const resultsDiv = document.getElementById('search-results');
    clearTimeout(searchTimeout);
    if (query.length < 1) { resultsDiv.classList.add('hidden'); return; }

    searchTimeout = setTimeout(() => {
        fetch(`${API_BASE}/delivery-locations/search?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.data?.length) {
                    resultsDiv.innerHTML = `<div class="px-4 py-5 text-center text-[12px] text-[#a0a09a] font-light">Aucun résultat</div>`;
                } else {
                    let groups = [], others = [];
                    data.data.forEach(g => g.group.includes('Quartier') ? groups.unshift(g) : others.push(g));
                    groups = [...groups, ...others];
                    let html = '';
                    groups.forEach(group => {
                        html += `<div class="px-4 py-2 bg-[#f7f7f5] text-[9px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] border-b border-[#efefed] sticky top-0">${group.group}</div>`;
                        group.items.forEach(item => {
                            const isQ = item.type === 'quartier';
                            html += `<button type="button" class="search-result-item w-full text-left px-4 py-3 hover:bg-[#f7f7f5] border-b border-[#efefed] last:border-b-0 transition-colors"
                                data-type="${item.type}" data-id="${item.id}" data-name="${item.name}">
                                <div class="text-[13px] ${isQ ? 'font-medium text-[#0a0a0a]' : 'font-light text-[#2a2a28]'}">${item.display || item.name}</div>
                                ${item.breadcrumb ? `<div class="text-[11px] text-[#a0a09a] font-light mt-0.5">${item.breadcrumb}</div>` : ''}
                            </button>`;
                        });
                    });
                    resultsDiv.innerHTML = html;
                    setupSearchResultsListeners();
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(() => {
                resultsDiv.innerHTML = `<div class="px-4 py-5 text-center text-[12px] text-[#dc2626]">Erreur lors de la recherche</div>`;
                resultsDiv.classList.remove('hidden');
            });
    }, 250);
}

function setupSearchResultsListeners() {
    document.querySelectorAll('.search-result-item').forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault();
            const { type, id, name } = item.dataset;
            document.getElementById('location-search').value = name;
            document.getElementById('search-results').classList.add('hidden');

            if (type === 'quartier') {
                document.getElementById('hidden-quartier-id').value = id;
                document.getElementById('hidden-adresse-livraison').value = name;
                const adresse = document.getElementById('adresse_detail');
                if (!adresse.value) adresse.value = `Quartier: ${name}`;
                showNotification(`Quartier "${name}" sélectionné`, 'success');
                setTimeout(() => { adresse.scrollIntoView({ behavior:'smooth', block:'center' }); adresse.focus(); }, 100);
            } else {
                showNotification(`Continuez pour préciser le quartier`, 'info');
            }
        });
    });
}

function togglePhoneSection() {
    const section = document.getElementById('phone-payment-section');
    const phone   = document.getElementById('phone_payment');
    const method  = document.querySelector('input[name="payment_method"]:checked')?.value;
    const show = method && method !== 'cash';
    section.classList.toggle('hidden', !show);
    phone.required = show;
    if (!show) phone.value = '';
}

function setupFormValidation() {
    const form = document.getElementById('payment-form');
    if (!form) return;

    form.addEventListener('submit', e => {
        e.preventDefault();
        const quartierIdFinal = document.getElementById('quartier').value || document.getElementById('hidden-quartier-id').value;
        const adresseDetail   = document.getElementById('adresse_detail').value.trim();
        const telephone       = document.getElementById('telephone_livraison').value.trim();
        const paymentMethod   = document.querySelector('input[name="payment_method"]:checked');
        const phonePayment    = document.getElementById('phone_payment').value.trim();
        const phoneRequired   = !document.getElementById('phone-payment-section').classList.contains('hidden');
        const conditions      = document.querySelector('input[name="accept_conditions"]:checked');

        const errors = [];
        if (!adresseDetail || adresseDetail.length < 5) errors.push('Adresse détaillée invalide (min. 5 caractères)');
        if (telephone.replace(/\D/g, '').length < 10)  errors.push('Téléphone invalide (min. 10 chiffres)');
        if (!paymentMethod)                             errors.push('Sélectionnez un moyen de paiement');
        if (phoneRequired && !phonePayment)             errors.push('Numéro de téléphone requis pour ce paiement');
        if (!conditions)                                errors.push('Acceptez les conditions pour continuer');

        if (errors.length) {
            errors.forEach(err => showNotification(err, 'error'));
            return;
        }

        if (quartierIdFinal) document.getElementById('hidden-quartier-id').value = quartierIdFinal;

        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = 'Traitement en cours…';
        showNotification('Commande en cours d\'envoi…', 'info');

        setTimeout(() => {
            try { form.submit(); }
            catch(err) {
                showNotification(`Erreur: ${err.message}`, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Confirmer la commande';
            }
        }, 800);
    });
}

function showNotification(message, type = 'info') {
    const container = document.getElementById('notification-container');
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
</script>

@endsection
