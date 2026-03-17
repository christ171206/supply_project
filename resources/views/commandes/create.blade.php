@extends('layouts.app')

@section('content')

{{-- Toast container --}}
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none"></div>

<!-- Payment Processing Modal -->
<div id="payment-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-[9999] backdrop-blur-sm animate-fadeIn">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 animate-slideUp">
        <div class="flex flex-col items-center gap-6">
            <!-- Animated spinner -->
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 rounded-full border-4 border-[#efefed]"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#0a0a0a] border-r-[#0a0a0a] animate-spin"></div>
            </div>

            <!-- Status text -->
            <div class="text-center">
                <h3 class="text-[16px] font-medium text-[#0a0a0a] mb-1.5" id="payment-status">
                    Traitement du paiement...
                </h3>
                <p class="text-[13px] text-[#a0a09a] font-light" id="payment-details">
                    Veuillez patienter quelques secondes
                </p>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-[#efefed] rounded-full h-1.5 overflow-hidden">
                <div id="payment-progress" class="bg-[#0a0a0a] h-full transition-all duration-500 rounded-full" style="width: 0%"></div>
            </div>

            <!-- Payment info -->
            <div class="bg-[#f7f7f5] rounded-lg p-4 w-full text-[12px] text-[#2a2a28] space-y-2">
                <div class="flex justify-between">
                    <span class="text-[#a0a09a]">Montant :</span>
                    <span class="font-mono font-medium" id="payment-amount">0 FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#a0a09a]">Moyen :</span>
                    <span class="font-medium" id="payment-method-display">-</span>
                </div>
                <div class="flex justify-between text-[11px]">
                    <span class="text-[#a0a09a]">Statut :</span>
                    <span class="inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                        <span id="payment-status-badge">En cours...</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <button type="button" class="step-indicator cursor-pointer" data-step="{{ $i + 1 }}" style="border:none;background:none;padding:0;"
                        title="Aller à l'étape {{ $i + 1 }}">
                        <div class="flex flex-col items-center">
                            <div class="w-7 h-7 rounded-sm flex items-center justify-center text-[11px] font-mono font-medium transition-all
                                {{ $i === 0 ? 'bg-[#0a0a0a] text-white' : 'bg-[#efefed] text-[#a0a09a]' }}"
                                id="step-{{ $i + 1 }}-indicator">
                                {{ $i + 1 }}
                            </div>
                            <span class="text-[9px] font-medium tracking-[0.06em] uppercase mt-1.5 transition-all
                                {{ $i === 0 ? 'text-[#0a0a0a]' : 'text-[#a0a09a]' }}"
                                id="step-{{ $i + 1 }}-label">
                                {{ $step }}
                            </span>
                        </div>
                    </button>
                    @if(!$loop->last)
                        <div class="w-10 h-px {{ $i === 0 ? 'bg-[#0a0a0a]' : 'bg-[#e0e0dc]' }} mx-2 mb-4 transition-colors"
                             id="step-{{ $i + 1 }}-line"></div>
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

        <!-- STEP 1: LIVRAISON -->
        <div class="step-section" data-step="1">
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
        </div><!-- End STEP 1 -->

        <!-- STEP 2: PAIEMENT -->
        <div class="step-section hidden" data-step="2">
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

                {{-- Code Promo --}}
                <div class="mt-4">
                    <label for="promo_code" class="block text-[10px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-2">
                        Code promo (optionnel)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="promo_code" name="promo_code"
                               placeholder="Entrer un code promo..."
                               class="flex-1 px-3.5 py-3 text-[13px] text-[#0a0a0a] bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg placeholder-[#a0a09a] focus:outline-none focus:border-[#0a0a0a] focus:bg-white transition-colors"
                               autocomplete="off">
                        <button type="button" id="apply-promo-btn"
                                class="px-4 py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity">
                            Appliquer
                        </button>
                    </div>
                    <div id="promo-message" class="mt-2"></div>
                    <input type="hidden" id="applied-promo-code" name="applied_promo_code" value="">
                    <input type="hidden" id="promo-reduction-amount" name="promo_reduction_amount" value="0">
                </div>
            </div>
        </div>

        {{-- ── Conditions ── --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl px-5 py-4 step-content" data-step="2">
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

        {{-- Boutons Step 1 & 2 --}}
        <div class="flex gap-3 step-buttons" id="step-12-buttons">
            <a href="{{ route('panier.index') }}"
               class="flex items-center gap-2 px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Panier
            </a>
            <button type="button" id="next-btn-step1" class="step-next-btn flex-1 flex items-center justify-center gap-2 bg-[#0a0a0a] text-white text-[13px] font-medium py-3 rounded-lg hover:opacity-85 transition-opacity" data-current-step="1">
                Continuer vers le paiement
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
            <button type="button" id="prev-btn-step2" class="hidden step-prev-btn px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all gap-2 flex items-center">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Retour
            </button>
            <button type="button" id="next-btn-step2" class="hidden step-next-btn flex-1 flex items-center justify-center gap-2 bg-[#0a0a0a] text-white text-[13px] font-medium py-3 rounded-lg hover:opacity-85 transition-opacity" data-current-step="2">
                Vérifier la commande
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </div>
        </div><!-- End STEP 2 -->

        <!-- STEP 3: CONFIRMATION -->
        <div class="step-section hidden" data-step="3">
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-[#efefed]">
                <div class="w-5 h-5 bg-[#0a0a0a] rounded-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 16c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                </div>
                <span class="text-[13px] font-medium text-[#0a0a0a]">Vérifier votre commande</span>
            </div>

            <div class="p-5 space-y-4">
                <div class="bg-[#f7f7f5] rounded-lg p-4">
                    <h4 class="text-[12px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Adresse de livraison</h4>
                    <div class="space-y-1">
                        <p class="text-[13px] font-medium text-[#0a0a0a]" id="confirm-adresse"></p>
                        <p class="text-[12px] text-[#666660]" id="confirm-phone"></p>
                    </div>
                </div>

                <div class="bg-[#f7f7f5] rounded-lg p-4">
                    <h4 class="text-[12px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Méthode de paiement</h4>
                    <p class="text-[13px] font-medium text-[#0a0a0a]" id="confirm-payment"></p>
                </div>

                <div class="bg-[#f7f7f5] rounded-lg p-4">
                    <h4 class="text-[12px] font-medium tracking-[0.08em] uppercase text-[#a0a09a] mb-3">Récapitulatif</h4>
                    <div class="space-y-2 text-[13px]">
                        <div class="flex justify-between">
                            <span class="text-[#666660]">Articles</span>
                            <span class="font-mono" id="confirm-subtotal"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#666660]">Livraison</span>
                            <span class="font-mono" id="confirm-shipping"></span>
                        </div>
                        <div class="border-t border-[#e0e0dc] pt-2 mt-2 flex justify-between font-medium">
                            <span>Total</span>
                            <span class="font-mono text-[14px]" id="confirm-total"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Boutons Step 3 --}}
        <div class="flex gap-3">
            <button type="button" id="prev-btn-step3" class="step-prev-btn px-4 py-3 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#666660] hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all gap-2 flex items-center">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Retour
            </button>
            <button type="submit" id="submit-btn"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#0a0a0a] text-white text-[13px] font-medium py-3 rounded-lg hover:opacity-85 active:scale-95 transition-all disabled:opacity-70 disabled:cursor-not-allowed"
                    data-idle-text="Confirmer et payer"
                    data-loading-text="Traitement en cours...">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Confirmer et payer</span>
            </button>
        </div>
        </div><!-- End STEP 3 -->

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
let currentStep = 1;
const TOTAL_STEPS = 3;

document.addEventListener('DOMContentLoaded', () => {
    loadRegions();
    setupEventListeners();
    setupFormValidation();
    setupPromoCodeHandlers();
    setupMultiStepNavigation();
});

function setupMultiStepNavigation() {
    // Step indicators click
    document.querySelectorAll('.step-indicator').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetStep = parseInt(btn.dataset.step);
            if (targetStep < currentStep || validateCurrentStep()) {
                goToStep(targetStep);
            }
        });
    });

    // Next buttons
    document.querySelectorAll('.step-next-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const nextStep = currentStep + 1;
            if (nextStep <= TOTAL_STEPS && validateCurrentStep()) {
                if (nextStep === 3) {
                    populateConfirmationStep();
                }
                goToStep(nextStep);
            }
        });
    });

    // Previous buttons
    document.querySelectorAll('.step-prev-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentStep > 1) {
                goToStep(currentStep - 1);
            }
        });
    });
}

function goToStep(step) {
    if (step < 1 || step > TOTAL_STEPS) return;

    // Hide all sections
    document.querySelectorAll('.step-section').forEach(s => s.classList.add('hidden'));

    // Show current section
    document.querySelector(`.step-section[data-step="${step}"]`).classList.remove('hidden');

    // Update stepper UI
    for (let i = 1; i <= TOTAL_STEPS; i++) {
        const indicator = document.getElementById(`step-${i}-indicator`);
        const label = document.getElementById(`step-${i}-label`);
        const line = document.getElementById(`step-${i}-line`);

        const isActive = i === step;
        const isPassed = i < step;

        indicator.classList.toggle('bg-[#0a0a0a]', isActive || isPassed);
        indicator.classList.toggle('text-white', isActive || isPassed);
        indicator.classList.toggle('bg-[#efefed]', !isActive && !isPassed);
        indicator.classList.toggle('text-[#a0a09a]', !isActive && !isPassed);

        label.classList.toggle('text-[#0a0a0a]', isActive || isPassed);
        label.classList.toggle('text-[#a0a09a]', !isActive && !isPassed);

        if (line) {
            line.classList.toggle('bg-[#0a0a0a]', isPassed);
            line.classList.toggle('bg-[#e0e0dc]', !isPassed);
        }
    }

    // Update buttons visibility
    document.getElementById('next-btn-step1').classList.toggle('hidden', step !== 1);
    document.getElementById('prev-btn-step2').classList.toggle('hidden', step !== 2);
    document.getElementById('next-btn-step2').classList.toggle('hidden', step !== 2);
    document.getElementById('prev-btn-step3').classList.toggle('hidden', step !== 3);
    document.getElementById('submit-btn').classList.toggle('hidden', step !== 3);

    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateCurrentStep() {
    const errors = [];

    if (currentStep === 1) {
        // Validate delivery address
        const adresseDetail = document.getElementById('adresse_detail').value.trim();
        const telephone = document.getElementById('telephone_livraison').value.trim();
        const quartierOrSearch = document.getElementById('quartier').value || document.getElementById('location-search').value;

        if (!adresseDetail || adresseDetail.length < 5) {
            errors.push('Adresse détaillée invalide (min. 5 caractères)');
        }
        if (telephone.replace(/\D/g, '').length < 10) {
            errors.push('Téléphone invalide (min. 10 chiffres)');
        }
        if (!quartierOrSearch) {
            errors.push('Sélectionnez un quartier');
        }
    } else if (currentStep === 2) {
        // Validate payment method and conditions
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        const phonePayment = document.getElementById('phone_payment').value.trim();
        const phoneRequired = !document.getElementById('phone-payment-section').classList.contains('hidden');
        const conditions = document.querySelector('input[name="accept_conditions"]:checked');

        if (!paymentMethod) {
            errors.push('Sélectionnez un moyen de paiement');
        }
        if (phoneRequired && !phonePayment) {
            errors.push('Numéro de téléphone requis pour ce paiement');
        }
        if (!conditions) {
            errors.push('Acceptez les conditions pour continuer');
        }
    }

    if (errors.length) {
        errors.forEach(err => showNotification(err, 'error'));
        return false;
    }
    return true;
}

function populateConfirmationStep() {
    const adresseDetail = document.getElementById('adresse_detail').value.trim();
    const telephone = document.getElementById('telephone_livraison').value.trim();
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || '';
    const total = document.getElementById('total-amount').textContent.trim();
    const subtotal = @json($total);
    const shipping = subtotal > 100 ? 'Gratuite' : '2 500 FCFA';

    const paymentLabels = {
        'wave': 'Wave',
        'orange_money': 'Orange Money',
        'mtn_money': 'MTN Money',
        'moov_money': 'Moov Money',
        'card': 'Carte Bancaire',
        'cash': 'À la livraison'
    };

    document.getElementById('confirm-adresse').textContent = adresseDetail;
    document.getElementById('confirm-phone').textContent = telephone;
    document.getElementById('confirm-payment').textContent = paymentLabels[paymentMethod] || paymentMethod;
    document.getElementById('confirm-subtotal').textContent = new Intl.NumberFormat('fr-FR').format(subtotal) + ' FCFA';
    document.getElementById('confirm-shipping').textContent = shipping;
    document.getElementById('confirm-total').textContent = total;
}

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

// Code Promo functionality
function setupPromoCodeHandlers() {
    const applyBtn = document.getElementById('apply-promo-btn');
    const promoInput = document.getElementById('promo_code');
    const messageDiv = document.getElementById('promo-message');

    applyBtn?.addEventListener('click', async (e) => {
        e.preventDefault();
        const code = promoInput.value.trim().toUpperCase();

        if (!code) {
            setPromoMessage('Veuillez entrer un code', 'error');
            return;
        }

        try {
            applyBtn.disabled = true;
            applyBtn.innerHTML = `<span class="opacity-50">Vérification...</span>`;

            // Récupérer le CSRF token du formulaire
            const formElement = document.getElementById('payment-form');
            const csrfToken = formElement ? formElement.querySelector('input[name="_token"]').value : document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch('{{ route("promo-codes.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();

            if (!response.ok) {
                setPromoMessage(data.message || 'Code invalide ou expiré', 'error');
                applyBtn.disabled = false;
                applyBtn.innerHTML = 'Appliquer';
                return;
            }

            // Code is valid
            document.getElementById('applied-promo-code').value = code;
            const reduction = Math.round(data.reduction) || 0;
            document.getElementById('promo-reduction-amount').value = reduction;

            // Update total
            updateTotalWithPromo(reduction);

            setPromoMessage(`Code appliqué! Réduction: ${number_format(reduction)} FCFA`, 'success');
            promoInput.disabled = true;
            applyBtn.disabled = true;
            applyBtn.innerHTML = '✓ Appliqué';

        } catch (error) {
            console.error('Error:', error);
            setPromoMessage('Erreur lors de la vérification', 'error');
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Appliquer';
        }
    });

    // Permettre à l'utilisateur de supprimer le code
    promoInput?.addEventListener('focus', function() {
        if (this.disabled && document.getElementById('applied-promo-code').value) {
            // Montrer option de supprimer
            const msgDiv = document.getElementById('promo-message');
            if (msgDiv.querySelector('.promo-remove-btn')) {
                // Bouton existe déjà
                return;
            }
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'promo-remove-btn mt-2 text-[11px] text-blue-600 hover:underline';
            btn.textContent = 'Supprimer ce code';
            btn.onclick = removePromo;
            msgDiv.appendChild(btn);
        }
    });
}

function setPromoMessage(message, type = 'info') {
    const messageDiv = document.getElementById('promo-message');
    const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
    const textColor = type === 'success' ? 'text-green-700' : 'text-red-700';
    const iconColor = type === 'success' ? 'text-green-600' : 'text-red-600';

    messageDiv.innerHTML = `
        <div class="p-3 ${bgColor} border rounded-lg flex items-start gap-2">
            <svg class="w-4 h-4 ${iconColor} mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                ${type === 'success' ? '<polyline points="20 6 9 17 4 12"/>' : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}
            </svg>
            <span class="text-[12px] ${textColor}">${message}</span>
        </div>
    `;
}

function updateTotalWithPromo(reduction) {
    const subtotal = @json($total);
    const shipping = subtotal > 100 ? 0 : 2500;
    const newTotal = subtotal + shipping - reduction;

    document.getElementById('total-amount').textContent = number_format(newTotal) + ' FCFA';
}

function removePromo() {
    document.getElementById('applied-promo-code').value = '';
    document.getElementById('promo-reduction-amount').value = '0';
    document.getElementById('promo_code').disabled = false;
    document.getElementById('apply-promo-btn').disabled = false;
    document.getElementById('apply-promo-btn').innerHTML = 'Appliquer';
    document.getElementById('promo-message').innerHTML = '';

    const subtotal = @json($total);
    const shipping = subtotal > 100 ? 0 : 2500;
    document.getElementById('total-amount').textContent = number_format(subtotal + shipping) + ' FCFA';
}

function number_format(number) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(number));
}

function setupFormValidation() {
    const form = document.getElementById('payment-form');
    if (!form) return;

    form.addEventListener('submit', e => {
        e.preventDefault();
        console.log('✓ Form submit intercepted');
        console.log('✓ Form action:', form.getAttribute('action'));
        console.log('✓ Form method:', form.getAttribute('method'));

        const quartierIdFinal = document.getElementById('quartier').value || document.getElementById('hidden-quartier-id').value;
        if (quartierIdFinal) document.getElementById('hidden-quartier-id').value = quartierIdFinal;

        // Disable submit button and show loading state
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.setAttribute('data-loading', 'true');

        // Update button content
        const btnText = submitBtn.querySelector('span');
        const btnIcon = submitBtn.querySelector('svg');
        if (btnText) btnText.textContent = submitBtn.getAttribute('data-loading-text');

        // Replace icon with spinner
        if (btnIcon) {
            btnIcon.innerHTML = '';
            btnIcon.classList.add('animate-spin');
            btnIcon.setAttribute('viewBox', '0 0 24 24');
            btnIcon.setAttribute('fill', 'none');
            btnIcon.setAttribute('stroke', 'currentColor');
            btnIcon.setAttribute('stroke-width', '2');
            btnIcon.innerHTML = `<circle cx="12" cy="12" r="10" stroke-opacity="0.2"/><path stroke-linecap="round" d="M12 2a10 10 0 010 20" stroke-dasharray="62.8" stroke-dashoffset="0"/>`;
        }

        // Show payment modal
        console.log('✓ Calling showPaymentAnimation()');
        showPaymentAnimation();
    });
}

function showPaymentAnimation() {
    const modal = document.getElementById('payment-modal');
    const progressBar = document.getElementById('payment-progress');
    const statusText = document.getElementById('payment-status');
    const detailsText = document.getElementById('payment-details');
    const amountDisplay = document.getElementById('payment-amount');
    const methodDisplay = document.getElementById('payment-method-display');

    // Get payment details
    const total = document.getElementById('total-amount').textContent.trim();
    const method = document.querySelector('input[name="payment_method"]:checked')?.value || '';

    const methodLabels = {
        'wave': 'Wave',
        'orange_money': 'Orange Money',
        'mtn_money': 'MTN Money',
        'moov_money': 'Moov Money',
        'card': 'Carte Bancaire',
        'cash': 'À la livraison'
    };

    // Update modal content
    amountDisplay.textContent = total;
    methodDisplay.textContent = methodLabels[method] || method;

    // Show modal
    modal.classList.remove('hidden');
    progressBar.style.width = '0%';
    statusText.textContent = 'Traitement du paiement...';
    detailsText.textContent = 'Veuillez patienter quelques secondes';

    console.log('✓ Payment animation started');

    // Simulate payment processing
    const steps = [
        { progress: 20, delay: 400, text: 'Vérification des données...', details: 'Validation de l\'adresse' },
        { progress: 50, delay: 400, text: 'Connexion au prestataire...', details: 'Authentification sécurisée' },
        { progress: 80, delay: 600, text: 'Confirmation du paiement...', details: 'Traitement en cours' },
        { progress: 100, delay: 500, text: 'Paiement réussi! ✓', details: 'Redirection en cours...' }
    ];

    let currentStep = 0;

    function processNextStep() {
        if (currentStep >= steps.length) {
            // Payment complete, submit form
            console.log('✓ All animation steps complete, submitting form...');
            console.log('✓ Form element:', document.getElementById('payment-form'));

            setTimeout(() => {
                modal.classList.add('hidden');
                const form = document.getElementById('payment-form');
                console.log('✓ Submitting form to:', form.getAttribute('action'));
                console.log('✓ Form method:', form.getAttribute('method'));
                console.log('✓ Form ID:', form.id);

                // Verify all required fields
                const requiredFields = form.querySelectorAll('[required]');
                console.log('✓ Required fields count:', requiredFields.length);
                requiredFields.forEach((field, i) => {
                    const isEmpty = field.type === 'checkbox' ? !field.checked : !field.value;
                    console.log(`  Field ${i + 1} (${field.name}): ${isEmpty ? '❌ EMPTY' : '✓ filled'}`);
                });

                // Submit the form
                form.submit();
                console.log('✓ Form.submit() called!');
            }, 800);
            return;
        }

        const step = steps[currentStep];
        progressBar.style.width = step.progress + '%';
        statusText.textContent = step.text;
        detailsText.textContent = step.details;

        currentStep++;
        setTimeout(processNextStep, step.delay);
    }

    processNextStep();
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

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-slideUp {
    animation: slideUp 0.4s ease-out cubic-bezier(0.16, 1, 0.3, 1);
}

/* Button loading state styles */
#submit-btn[data-loading="true"] {
    pointer-events: none;
}

#submit-btn[data-loading="true"] svg {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Toast notification styles */
.toast-notification {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background-color: #0a0a0a;
    color: white;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    animation: slideInRight 0.3s ease-out;
}

.toast-notification.toast-success {
    background-color: #10b981;
}

.toast-notification.toast-error {
    background-color: #ef4444;
}

.toast-notification.hide {
    animation: slideOutRight 0.3s ease-out forwards;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

/* Payment modal backdrop blur effect */
#payment-modal.hidden {
    display: none;
}
</style>

@endsection
