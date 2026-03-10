@extends('layouts.app')

@section('content')
<!-- Conteneur pour les notifications Toast -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none"></div>

<div class="min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header avec progression -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#0a0a0a] mb-2">
                🛍️ Finaliser votre Commande
            </h1>
            <p class="text-[#666660]">Complétez les étapes ci-dessous pour confirmer votre achat</p>

            <!-- Indicateurs de progression -->
            <div class="flex items-center gap-2 mt-6 max-w-2xl">
                <div class="flex-1 h-1 bg-[#0a0a0a] rounded-full"></div>
                <div class="w-10 h-10 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-sm font-bold">1</div>
                <div class="flex-1 h-1 bg-[#0a0a0a] rounded-full"></div>
                <div class="w-10 h-10 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-sm font-bold">2</div>
                <div class="flex-1 h-1 bg-[#0a0a0a] rounded-full"></div>
                <div class="w-10 h-10 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-sm font-bold">3</div>
                <div class="flex-1 h-1 bg-[#e0e0dc] rounded-full"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-[#666660]">
                <span>Livraison</span>
                <span>Paiement</span>
                <span>Confirmation</span>
            </div>
        </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('commandes.store') }}" method="POST" id="payment-form">
                @csrf

                <!-- Section Livraison -->
                <div class="bg-white rounded-lg border border-[#e0e0dc] shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center font-bold">
                            📍
                        </div>
                        <h2 class="text-2xl font-bold text-[#0a0a0a]">Adresse de Livraison</h2>
                    </div>

                    <!-- Pays (nouveau) -->
                    <div class="mb-6">
                        <label for="pays" class="block text-xs font-bold text-\[#2a2a28\] mb-2 uppercase tracking-wide">
                            🌍 Pays
                        </label>
                        <input
                            type="text"
                            id="pays"
                            name="pays"
                            value="{{ old('pays', 'Côte d\'Ivoire') }}"
                            placeholder="Ex: Côte d'Ivoire, France, Sénégal..."
                            class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base"
                        />
                        @error('pays')
                            <p class="text-\[#dc2626\] text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                        <p class="text-xs text-\[#a0a09a\] mt-1">Entrez le nom du pays</p>
                    </div>

                    <!-- Tabs pour Recherche vs Manuel -->
                    <div class="flex gap-2 mb-6 bg-\[#f7f7f5\] p-1 rounded-lg">
                        <button type="button" class="location-tab active flex-1 px-4 py-2 rounded-md font-semibold transition-all bg-white text-\[#0a0a0a\] border-2 border-\[#0a0a0a\]/30" data-tab="search">
                            🔍 Recherche Rapide
                        </button>
                        <button type="button" class="location-tab flex-1 px-4 py-2 rounded-md font-semibold transition-all text-\[#666660\] border-2 border-transparent" data-tab="manual">
                            📍 Sélection Manuelle
                        </button>
                    </div>

                    <!-- Recherche rapide -->
                    <div id="search-tab" class="location-section mb-8 pb-8 border-b-2 border-gray-100">
                        <div class="relative" style="z-index: 100;">
                            <input
                                type="text"
                                id="location-search"
                                placeholder="Tapez pour chercher (Abidjan, Yopougon, Cocody...)"
                                class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base"
                                autocomplete="off"
                            />
                            <div class="absolute right-4 top-3 text-gray-400 text-lg pointer-events-none">🔎</div>
                            <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-\[#e0e0dc\] rounded-lg mt-1 hidden max-h-72 overflow-y-auto shadow-xl"></div>
                        </div>
                        <p class="text-xs text-\[#a0a09a\] mt-2">✨ Meilleur moyen: tapez une ville, un quartier ou un district</p>
                    </div>

                    <!-- Sélecteurs en cascade améliorés -->
                    <div id="manual-tab" class="location-section mb-8 hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Région -->
                            <div class="flex flex-col">
                                <label for="region" class="block text-xs font-semibold text-\[#666660\] mb-2 uppercase tracking-wide">
                                    Région / Ville
                                </label>
                                <select
                                    id="region"
                                    name="region_id"
                                    class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base bg-white cursor-pointer hover:border-\[#0a0a0a\]/20"
                                >
                                    <option value="">⏳ Chargement...</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div class="flex flex-col">
                                <label for="district" class="block text-xs font-semibold text-\[#666660\] mb-2 uppercase tracking-wide">
                                    District
                                </label>
                                <select
                                    id="district"
                                    name="district_id"
                                    class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base bg-white cursor-pointer hover:border-\[#0a0a0a\]/20 disabled:bg-\[#f7f7f5\] disabled:cursor-not-allowed disabled:text-\[#a0a09a\]"
                                    disabled
                                >
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>

                            <!-- Commune -->
                            <div class="flex flex-col">
                                <label for="commune" class="block text-xs font-semibold text-\[#666660\] mb-2 uppercase tracking-wide">
                                    Commune
                                </label>
                                <select
                                    id="commune"
                                    name="commune_id"
                                    class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base bg-white cursor-pointer hover:border-\[#0a0a0a\]/20 disabled:bg-\[#f7f7f5\] disabled:cursor-not-allowed disabled:text-\[#a0a09a\]"
                                    disabled
                                >
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>

                            <!-- Quartier -->
                            <div class="flex flex-col">
                                <label for="quartier" class="block text-xs font-semibold text-\[#666660\] mb-2 uppercase tracking-wide">
                                    Quartier
                                </label>
                                <select
                                    id="quartier"
                                    name="quartier_manual"
                                    class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base bg-white cursor-pointer hover:border-\[#0a0a0a\]/20 disabled:bg-\[#f7f7f5\] disabled:cursor-not-allowed disabled:text-\[#a0a09a\]"
                                    disabled
                                >
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse détaillée -->
                    <div class="mb-6">
                        <label for="adresse_detail" class="block text-xs font-bold text-\[#2a2a28\] mb-2 uppercase tracking-wide">
                            Adresse Détaillée
                        </label>
                        <textarea
                            id="adresse_detail"
                            name="adresse_detail"
                            rows="3"
                            class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base resize-none"
                            placeholder="Ex: 123 rue Principale, Immeuble A, Appartement 5, près de la pharmacie..."
                            required>{{ old('adresse_detail') }}</textarea>
                        @error('adresse_detail')
                            <p class="text-\[#dc2626\] text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                        <p class="text-xs text-\[#a0a09a\] mt-1">Soyez le plus précis possible pour faciliter la livraison</p>
                    </div>

                    <!-- Téléphone de livraison -->
                    <div>
                        <label for="telephone_livraison" class="block text-xs font-bold text-\[#2a2a28\] mb-2 uppercase tracking-wide">
                            📱 Téléphone de Livraison
                        </label>
                        <input
                            type="tel"
                            id="telephone_livraison"
                            name="telephone_livraison"
                            class="w-full px-4 py-3 border border-\[#e0e0dc\] rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all text-base"
                            placeholder="+225 01 23 45 67 89"
                            inputmode="numeric"
                            required>
                        @error('telephone_livraison')
                            <p class="text-\[#dc2626\] text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                        <p class="text-xs text-\[#a0a09a\] mt-1">Format: 10 chiffres (ex: 0123456789)</p>
                    </div>
                </div>

                <!-- Section Paiement -->
                <div class="bg-white rounded-lg shadow-sm transition-shadow p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center font-bold">
                            💳
                        </div>
                        <h2 class="text-2xl font-bold text-\[#0a0a0a\]">Méthode de Paiement</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Wave -->
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment_method" value="wave" class="hidden" required>
                            <div class="p-4 border border-\[#e0e0dc\] rounded-lg hover:border-emerald-400 hover:bg-emerald-50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/payments/wave.png') }}" alt="Wave" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                                    <div>
                                        <p class="font-bold text-\[#0a0a0a\] text-sm">Wave</p>
                                        <p class="text-xs text-\[#666660\]">Paiement sécurisé</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Orange Money -->
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment_method" value="orange_money" class="hidden" required>
                            <div class="p-4 border border-\[#e0e0dc\] rounded-lg hover:border-orange-400 hover:bg-orange-50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/payments/orange money.png') }}" alt="Orange Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                                    <div>
                                        <p class="font-bold text-\[#0a0a0a\] text-sm">Orange Money</p>
                                        <p class="text-xs text-\[#666660\]">Porte-monnaie Orange</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- MTN Money -->
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment_method" value="mtn_money" class="hidden" required>
                            <div class="p-4 border border-\[#e0e0dc\] rounded-lg hover:border-yellow-400 hover:bg-yellow-50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/payments/mtn money.png') }}" alt="MTN Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                                    <div>
                                        <p class="font-bold text-\[#0a0a0a\] text-sm">MTN Money</p>
                                        <p class="text-xs text-\[#666660\]">Service MTN</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Moov Money -->
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment_method" value="moov_money" class="hidden" required>
                            <div class="p-4 border border-\[#e0e0dc\] rounded-lg hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/payments/moov money.png') }}" alt="Moov Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                                    <div>
                                        <p class="font-bold text-\[#0a0a0a\] text-sm">Moov Money</p>
                                        <p class="text-xs text-\[#666660\]">Service Moov</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- À la Livraison -->
                        <label class="payment-option cursor-pointer sm:col-span-2">
                            <input type="radio" name="payment_method" value="cash" class="hidden" required>
                            <div class="p-4 border border-\[#e0e0dc\] rounded-lg hover:border-green-400 hover:bg-green-50 transition-all duration-200 group">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('images/payments/a la livraison.jfif') }}" alt="À la Livraison" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                                    <div>
                                        <p class="font-bold text-\[#0a0a0a\] text-sm">À la Livraison</p>
                                        <p class="text-xs text-\[#666660\]">Paiement en espèces à la réception</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Champ téléphone dynamique -->
                    <div id="phone-payment-section" class="mt-6 p-4 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg hidden">
                        <label for="phone_payment" class="block text-sm font-semibold text-\[#2a2a28\] mb-2">
                            📱 Numéro de Téléphone (paiement mobile)
                        </label>
                        <input
                            type="tel"
                            name="phone_payment"
                            id="phone_payment"
                            placeholder="+225 01 23 45 67 89"
                            class="w-full px-4 py-2 border-2 border-\[#0a0a0a\]/30 rounded-lg focus:border-\[#0a0a0a\] focus:ring-1 focus:ring-\[#0a0a0a\]/10 transition-all"
                        />
                    </div>

                    @error('payment_method')
                        <p class="text-\[#dc2626\] text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                    @enderror
                </div>

                <!-- Section Conditions -->
                <div class="bg-white rounded-lg shadow-sm transition-shadow p-8">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input
                            type="checkbox"
                            name="accept_conditions"
                            required
                            class="mt-1.5 w-5 h-5 rounded border border-\[#e0e0dc\] cursor-pointer accent-\[#0a0a0a\] flex-shrink-0"
                        />
                        <span class="text-sm text-\[#2a2a28\] group-hover:text-\[#0a0a0a\] leading-relaxed">
                            J'accepte les <a href="#" class="text-\[#0a0a0a\] hover:underline font-semibold">conditions d'utilisation</a>
                            et la <a href="#" class="text-\[#0a0a0a\] hover:underline font-semibold">politique de confidentialité</a>
                        </span>
                    </label>
                    @error('accept_conditions')
                        <p class="text-\[#dc2626\] text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                    @enderror
                </div>

                <!-- Champs cachés pour la recherche rapide -->
                <input type="hidden" id="hidden-quartier-id" name="quartier_id" value="">
                <input type="hidden" id="hidden-adresse-livraison" name="adresse_livraison" value="">

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <a href="{{ route('panier.index') }}" class="px-6 py-4 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition font-bold text-center shadow-sm flex items-center justify-center gap-2">
                        ← Retour au Panier
                    </a>
                    <button type="submit" class="px-6 py-4 bg-[#0a0a0a] hover:bg-[#2a2a28] text-white rounded-lg transition font-bold shadow-sm flex items-center justify-center gap-2 flex-1" id="submit-btn">
                        ✓ Confirmer la Commande
                    </button>
                </div>
            </form>
        </div>

        <!-- Résumé Récapitulatif (Sticky) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm transition-shadow p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="text-2xl">📦</div>
                    <h3 class="text-2xl font-bold text-\[#0a0a0a\]">Résumé</h3>
                </div>

                <!-- Produits -->
                <div class="space-y-4 mb-6 pb-6 border-b-2 border-gray-200 max-h-96 overflow-y-auto">
                    @forelse($items as $item)
                        <div class="flex justify-between items-start text-sm hover:bg-\[#f7f7f5\] p-3 rounded-lg transition">
                            <div class="flex-1">
                                <p class="font-bold text-\[#0a0a0a\]">{{ $item->produit->nom }}</p>
                                <p class="text-\[#666660\] text-xs mt-1">Quantité: <span class="font-semibold">{{ $item->quantite }}</span></p>
                                <p class="text-\[#0a0a0a\] text-xs mt-1">@ {{ number_format($item->prix_unitaire, 0, '', ' ') }} F CFA</p>
                            </div>
                            <p class="font-bold text-\[#0a0a0a\] ml-2">{{ number_format($item->quantite * $item->prix_unitaire, 0, '', ' ') }} F</p>
                        </div>
                    @empty
                        <p class="text-center text-\[#a0a09a\] py-4">Aucun article</p>
                    @endforelse
                </div>

                <!-- Frais de livraison -->
                <div class="space-y-3 mb-6 p-4 bg-[#f7f7f5] rounded-lg">
                    <div class="flex justify-between text-\[#2a2a28\]">
                        <span class="font-semibold">Sous-total</span>
                        <span class="font-bold text-\[#0a0a0a\]">{{ number_format($total, 0, '', ' ') }} F CFA</span>
                    </div>
                    <div class="flex justify-between text-\[#2a2a28\]">
                        <span class="font-semibold flex items-center gap-2">
                            🚚 Livraison
                        </span>
                        <span class="font-bold text-\[#0a0a0a\]" id="shipping-cost">
                            @if($total > 100)
                                Gratuit ✓
                            @else
                                2 500 F CFA
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Total final -->
                <div class="flex justify-between items-center px-4 py-4 bg-[#0a0a0a] rounded-xl text-white">
                    <span class="font-bold text-lg">Total TTC</span>
                    <span class="text-3xl font-bold" id="total-amount">
                        @if($total > 100)
                            {{ number_format($total, 0, '', ' ') }}
                        @else
                            {{ number_format($total + 2500, 0, '', ' ') }}
                        @endif
                        <span class="text-lg ml-1">F CFA</span>
                    </span>
                </div>

                <!-- Infos client -->
                <div class="mt-6 pt-6 border-t-2 border-gray-200 text-sm">
                    <p class="text-\[#666660\] mb-2 font-semibold">Vos informations:</p>
                    <div class="space-y-2">
                        <p class="text-\[#2a2a28\]"><strong>👤 Nom:</strong> <br/><span class="text-\[#0a0a0a\]">{{ auth()->user()->name }}</span></p>
                        <p class="text-\[#2a2a28\]"><strong>📧 Email:</strong> <br/><span class="text-\[#0a0a0a\]">{{ auth()->user()->email }}</span></p>
                    </div>
                </div>

                <!-- Badge sécurité -->
                <div class="mt-6 p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                    <p class="text-xs text-green-700 font-semibold">🔒 Paiement Sécurisé & Chiffré</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS personnalisés -->
<style>
    input[type="radio"] {
        accent-color: #2563eb;
    }

    .payment-option input[type="radio"]:checked + div {
        @apply border-\[#0a0a0a\] bg-\[#f7f7f5\] shadow-md;
    }

    .payment-option input[type="radio"]:checked + div p:first-child {
        @apply text-\[#0a0a0a\] font-bold;
    }

    /* Animation au focus */
    input:focus-visible,
    select:focus-visible,
    textarea:focus-visible {
        outline: none;
    }

    /* Smooth transitions */
    * {
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    /* Scrollbar personnalisée -->
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Toast Notifications */
    .toast-notification {
        animation: slideInRight 0.3s ease-in-out forwards;
        pointer-events: auto;
    }

    .toast-notification.hide {
        animation: slideOutRight 0.3s ease-in-out forwards;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(400px);
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
            transform: translateX(400px);
        }
    }

    .toast-success {
        @apply bg-green-500 text-white;
    }

    .toast-error {
        @apply bg-\[#dc2626\] text-white;
    }

    .toast-info {
        @apply bg-\[#0a0a0a\] text-white;
    }

    .toast-warning {
        @apply bg-yellow-500 text-white;
    }

    /* ========== Payment Method Selection ========== */
    .payment-option input[type="radio"]:checked + div {
        @apply border-\[#0a0a0a\] border-4 bg-\[#f7f7f5\] shadow-lg;
    }

    .payment-option input[type="radio"]:checked + div p:first-child {
        @apply text-\[#0a0a0a\] font-bold;
    }

</style>

<!-- Scripts JavaScript améliorés -->
<script>
    console.log('✅ Script de paiement chargé');
    const API_BASE = '{{ url("/api") }}';
    let searchTimeout;

    // ==================== Initialisation ====================
    document.addEventListener('DOMContentLoaded', () => {
        console.log('🚀 DOMContentLoaded déclenché - Initialisation du formulaire');
        loadRegions();
        setupEventListeners();
        setupFormValidation();
        console.log('✓ Initialisation complète');
    });

    // ==================== Gestion des événements ====================
    function setupEventListeners() {
        // ===== Gestion des Tabs =====
        document.querySelectorAll('.location-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const tabName = tab.dataset.tab;

                // Désactiver tous les tabs
                document.querySelectorAll('.location-tab').forEach(t => {
                    t.classList.remove('active', 'bg-white', 'text-\[#0a0a0a\]', 'border-\[#0a0a0a\]/30');
                    t.classList.add('text-\[#666660\]', 'border-transparent');
                });

                // Cacher toutes les sections
                document.querySelectorAll('.location-section').forEach(section => {
                    section.classList.add('hidden');
                });

                // Activer le tab cliqué
                tab.classList.add('active', 'bg-white', 'text-\[#0a0a0a\]', 'border-\[#0a0a0a\]/30');
                tab.classList.remove('text-\[#666660\]', 'border-transparent');

                // Afficher la section correspondante
                document.getElementById(`${tabName}-tab`).classList.remove('hidden');

                // Effacer la recherche si on passe au manuel
                if (tabName === 'manual') {
                    document.getElementById('location-search').value = '';
                    document.getElementById('search-results').classList.add('hidden');
                }
            });
        });

        // Régions
        document.getElementById('region').addEventListener('change', loadDistricts);

        // Districts
        document.getElementById('district').addEventListener('change', loadCommunes);

        // Communes
        document.getElementById('commune').addEventListener('change', loadQuartiers);

        // Quartiers - Remplir le champ caché avec le quartier sélectionné
        document.getElementById('quartier').addEventListener('change', (e) => {
            const quartierValue = e.target.value;
            const quartierText = e.target.options[e.target.selectedIndex]?.text;
            if (quartierValue) {
                document.getElementById('hidden-quartier-id').value = quartierValue;
                console.log('✓ Quartier manuel sélectionné:', quartierValue, '-', quartierText);
            }
        });

        // Paiements - Sélection visuelle et gestion du téléphone
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                console.log('💳 Moyen de paiement sélectionné:', e.target.value);

                // Mise à jour visuelle
                document.querySelectorAll('.payment-option').forEach(option => {
                    const input = option.querySelector('input[name="payment_method"]');
                    if (input?.checked) {
                        option.querySelector('div').classList.add('ring-2', 'ring-\[#0a0a0a\]');
                        showNotification(`✓ ${e.target.value.replace(/_/g, ' ').toUpperCase()} sélectionné`, 'success');
                    } else {
                        option.querySelector('div').classList.remove('ring-2', 'ring-\[#0a0a0a\]');
                    }
                });

                togglePhoneSection();
            });
        });

        // Recherche
        document.getElementById('location-search').addEventListener('input', handleLocationSearch);

        // Fermer les résultats de recherche au clic
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#location-search') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
    }

    // ==================== Chargement des régions ====================
    async function loadRegions() {
        try {
            const response = await fetch(`${API_BASE}/delivery-locations/regions`);
            const data = await response.json();

            const select = document.getElementById('region');
            select.innerHTML = '<option value="">-- Sélectionner une région --</option>';

            if (data.data && Array.isArray(data.data)) {
                data.data.forEach(region => {
                    const option = document.createElement('option');
                    option.value = region.id;
                    option.textContent = region.name;
                    select.appendChild(option);
                });
            }
        } catch (err) {
            console.error('Erreur lors du chargement des régions:', err);
            showNotification('Erreur lors du chargement', 'error');
        }
    }

    // ==================== Chargement des districts ====================
    async function loadDistricts() {
        const regionId = document.getElementById('region').value;
        const districtSelect = document.getElementById('district');
        const communeSelect = document.getElementById('commune');
        const quartierSelect = document.getElementById('quartier');

        if (!regionId) {
            districtSelect.disabled = true;
            communeSelect.disabled = true;
            quartierSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            communeSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            quartierSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/delivery-locations/regions/${regionId}/districts`);
            const data = await response.json();

            districtSelect.innerHTML = '<option value="">-- Sélectionner un district --</option>';
            districtSelect.disabled = false;

            if (data.data && Array.isArray(data.data)) {
                data.data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }

            // Reset communes et quartiers
            communeSelect.innerHTML = '<option value="">-- Sélectionner une commune --</option>';
            communeSelect.disabled = true;
            quartierSelect.innerHTML = '<option value="">-- Sélectionner un quartier --</option>';
            quartierSelect.disabled = true;
        } catch (err) {
            console.error('Erreur lors du chargement des districts:', err);
            showNotification('Erreur lors du chargement des districts', 'error');
        }
    }

    // ==================== Chargement des communes ====================
    async function loadCommunes() {
        const districtId = document.getElementById('district').value;
        const communeSelect = document.getElementById('commune');
        const quartierSelect = document.getElementById('quartier');

        if (!districtId) {
            communeSelect.disabled = true;
            quartierSelect.disabled = true;
            communeSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            quartierSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/delivery-locations/districts/${districtId}/communes`);
            const data = await response.json();

            communeSelect.innerHTML = '<option value="">-- Sélectionner une commune --</option>';
            communeSelect.disabled = false;

            if (data.data && Array.isArray(data.data)) {
                data.data.forEach(commune => {
                    const option = document.createElement('option');
                    option.value = commune.id;
                    option.textContent = commune.name;
                    communeSelect.appendChild(option);
                });
            }

            // Reset quartiers
            quartierSelect.innerHTML = '<option value="">-- Sélectionner un quartier --</option>';
            quartierSelect.disabled = true;
        } catch (err) {
            console.error('Erreur lors du chargement des communes:', err);
            showNotification('Erreur lors du chargement des communes', 'error');
        }
    }

    // ==================== Chargement des quartiers ====================
    async function loadQuartiers() {
        const communeId = document.getElementById('commune').value;
        const quartierSelect = document.getElementById('quartier');

        if (!communeId) {
            quartierSelect.disabled = true;
            quartierSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/delivery-locations/communes/${communeId}/quartiers`);
            const data = await response.json();

            quartierSelect.innerHTML = '<option value="">-- Sélectionner un quartier --</option>';
            quartierSelect.disabled = false;

            if (data.data && Array.isArray(data.data)) {
                data.data.forEach(quartier => {
                    const option = document.createElement('option');
                    option.value = quartier.id;
                    option.textContent = quartier.name;
                    quartierSelect.appendChild(option);
                });
            }
        } catch (err) {
            console.error('Erreur lors du chargement des quartiers:', err);
            showNotification('Erreur lors du chargement des quartiers', 'error');
        }
    }

    // ==================== Gestion de la recherche ====================
    function handleLocationSearch() {
        const input = document.getElementById('location-search');
        const query = input.value.trim();
        const resultsDiv = document.getElementById('search-results');

        clearTimeout(searchTimeout);

        if (query.length < 1) {
            resultsDiv.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`${API_BASE}/delivery-locations/search?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.data || data.data.length === 0) {
                        resultsDiv.innerHTML = '<div class="px-4 py-4 text-center text-\[#a0a09a\] text-sm">✗ Aucun résultat trouvé</div>';
                    } else {
                        // Réorganiser les résultats pour mettre les Quartiers en premier
                        let reorganizedData = [];
                        let otherGroups = [];

                        data.data.forEach(group => {
                            if (group.group === 'Quartiers' || group.group.includes('Quartier')) {
                                reorganizedData.unshift(group); // Ajouter au début
                            } else {
                                otherGroups.push(group);
                            }
                        });

                        reorganizedData = [...reorganizedData, ...otherGroups];

                        let html = '';
                        reorganizedData.forEach(group => {
                            html += `<div class="px-4 py-3 bg-\[#f7f7f5\] text-xs font-bold text-\[#666660\] sticky top-0 border-b border-gray-200">📌 ${group.group}</div>`;
                            group.items.forEach(item => {
                                const displayText = item.display || item.name;
                                const breadcrumb = item.breadcrumb ? ` <div class="text-\[#a0a09a\] text-xs mt-0.5">${item.breadcrumb}</div>` : '';
                                const isQuartier = item.type === 'quartier' ? 'font-bold text-\[#0a0a0a\]' : '';
                                html += `<button type="button" class="search-result-item w-full text-left px-4 py-3 hover:bg-\[#f7f7f5\] border-b border-gray-100 transition duration-150 flex justify-between items-start ${isQuartier}" data-type="${item.type}" data-id="${item.id}" data-name="${item.name}">
                                    <div class="flex-1">
                                        <div class="font-semibold text-\[#0a0a0a\] text-sm ${isQuartier}">${displayText} ${item.type === 'quartier' ? '✓' : ''}</div>
                                        ${breadcrumb}
                                    </div>
                                    <span class="text-gray-400 ml-2">→</span>
                                </button>`;
                            });
                        });
                        resultsDiv.innerHTML = html;
                        setupSearchResultsListeners();
                    }
                    resultsDiv.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Erreur:', err);
                    resultsDiv.innerHTML = '<div class="px-4 py-4 text-center text-\[#dc2626\] text-sm">⚠️ Erreur lors de la recherche</div>';
                    resultsDiv.classList.remove('hidden');
                });
        }, 250);
    }

    // ==================== Configuration des écouteurs de résultats ====================
    function setupSearchResultsListeners() {
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const type = item.dataset.type;
                const id = item.dataset.id;
                const name = item.dataset.name;

                // Remplir le champ de recherche
                document.getElementById('location-search').value = name;
                document.getElementById('search-results').classList.add('hidden');

                // Remplir et déclencher les changements appropriés
                if (type === 'region') {
                    document.getElementById('region').value = id;
                    document.getElementById('region').dispatchEvent(new Event('change'));
                    showNotification(`ℹ️ Région sélectionnée. Veuillez continuer pour choisir un quartier.`, 'info');
                } else if (type === 'district') {
                    // Besoin de trouver la région d'abord
                    document.getElementById('district').value = id;
                    document.getElementById('district').dispatchEvent(new Event('change'));
                    showNotification(`ℹ️ District sélectionné. Veuillez continuer pour choisir un quartier.`, 'info');
                } else if (type === 'commune') {
                    document.getElementById('commune').value = id;
                    document.getElementById('commune').dispatchEvent(new Event('change'));
                    showNotification(`ℹ️ Commune sélectionnée. Veuillez continuer pour choisir un quartier.`, 'info');
                } else if (type === 'quartier') {
                    document.getElementById('quartier').value = id;
                    document.getElementById('hidden-quartier-id').value = id;
                    document.getElementById('hidden-adresse-livraison').value = name;

                    // Pré-remplir l'adresse détaillée avec le quartier sélectionné
                    const adresseDetailField = document.getElementById('adresse_detail');
                    if (!adresseDetailField.value) {
                        adresseDetailField.value = `Quartier: ${name}`;
                        showNotification(`✓ Quartier "${name}" sélectionné!`, 'success');
                    }

                    // Scroll vers le champ de l'adresse détaillée
                    setTimeout(() => {
                        adresseDetailField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        adresseDetailField.focus();
                    }, 100);
                }
            });
        });
    }

    // ==================== Toggle section téléphone ====================
    function togglePhoneSection() {
        const phoneSection = document.getElementById('phone-payment-section');
        const phoneName = document.getElementById('phone_payment');
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (selectedMethod !== 'cash') {
            phoneSection.classList.remove('hidden');
            phoneName.required = true;
        } else {
            phoneSection.classList.add('hidden');
            phoneName.required = false;
            phoneName.value = '';
        }
    }

    // ==================== Validation du formulaire avant soumission ====================
    function setupFormValidation() {
        const form = document.getElementById('payment-form');
        if (!form) {
            console.error('❌ Formulaire #payment-form introuvable!');
            return;
        }
        console.log('✓ Formulaire trouvé, attachement écouteur submit');

        try {
            form.addEventListener('submit', (e) => {
                try {
                    e.preventDefault();
                    console.log('📋 Validation du formulaire en cours...');

                    // Récupérer tous les champs
                    const quartierIdManual = document.getElementById('quartier').value;
                    const quartierIdHidden = document.getElementById('hidden-quartier-id').value;
                    const quartierIdFinal = quartierIdManual || quartierIdHidden;

                    const adresseDetail = document.getElementById('adresse_detail').value.trim();
                    const telephone = document.getElementById('telephone_livraison').value.trim();
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    const phonePayment = document.getElementById('phone_payment').value.trim();
                    const phonePaymentRequired = !document.getElementById('phone-payment-section').classList.contains('hidden');
                    const acceptConditions = document.querySelector('input[name="accept_conditions"]:checked');

                    console.log('🔍 Récupération des quartiers:');
                    console.log('  - Select #quartier:', quartierIdManual || '(vide)');
                    console.log('  - Hidden #hidden-quartier-id:', quartierIdHidden || '(vide)');
                    console.log('  - Quartier ID final:', quartierIdFinal || '(vide)');
                    console.log('Quartier ID:', quartierIdFinal);
                    console.log('Adresse détaillée:', adresseDetail);
                    console.log('Téléphone:', telephone);
                    console.log('Paiement:', paymentMethod?.value);
                    console.log('Téléphone paiement requis:', phonePaymentRequired, 'Valeur:', phonePayment);
                    console.log('Conditions acceptées:', acceptConditions?.checked);

                    // Vérifications
                    let hasError = false;
                    const errors = [];

                    // Quartier ID est optionnel (voir contrôleur)
                    // if (!quartierIdFinal) {
                    //     errors.push('Veuillez sélectionner un quartier');
                    //     hasError = true;
                    // }

                    if (!adresseDetail || adresseDetail.length < 5) {
                        errors.push('Adresse détaillée invalide (min. 5 caractères)');
                        hasError = true;
                    }

                    // Valider le téléphone - extraire seulement les chiffres
                    const phoneDigitsOnly = telephone.replace(/\D/g, '');
                    if (!telephone || phoneDigitsOnly.length < 10) {
                        errors.push('Téléphone invalide (min. 10 chiffres)');
                        hasError = true;
                    }

                    if (!paymentMethod) {
                        errors.push('Veuillez sélectionner un moyen de paiement');
                        hasError = true;
                    }

                    if (phonePaymentRequired && !phonePayment) {
                        errors.push('Numéro pour paiement mobile requis');
                        hasError = true;
                    }

                    if (!acceptConditions?.checked) {
                        errors.push('Vous devez accepter les conditions');
                        hasError = true;
                    }

                    // Afficher les erreurs
                    if (hasError) {
                        console.error('❌ Erreurs de validation:', errors);
                        errors.forEach(error => {
                            showNotification(`❌ ${error}`, 'error');
                        });
                        return false;
                    }

                    // Si tout est valide, remplir le champ hidden et soumettre
                    console.log('✓ Tous les champs valides');
                    console.log('✓ Remplissage du quartier_id:', quartierIdFinal || '(optionnel)');
                    
                    // Remplir les champs cachés même s'ils sont vides (quartier_id est nullable)
                    if (quartierIdFinal) {
                        document.getElementById('hidden-quartier-id').value = quartierIdFinal;
                    }
                    
                    showNotification('✓ Commande validée, envoi en cours...', 'success');

                    // Désactiver le bouton pour éviter les doubles clics
                    const submitBtn = document.getElementById('submit-btn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Traitement en cours...';

                    // Attendre un peu avant de soumettre
                    setTimeout(() => {
                        try {
                            console.log('📤 ===== DÉBUT DE LA SOUMISSION =====');
                            console.log('📤 Form action:', form.action);
                            console.log('📤 Form method:', form.method);
                            console.log('📤 Token CSRF présent:', !!document.querySelector('input[name="_token"]'));

                            // Créer FormData pour vérifier les données
                            const formData = new FormData(form);
                            console.log('📋 DONNÉES ENVOYÉES:');
                            for (let [key, value] of formData.entries()) {
                                console.log(`  ${key}: ${value}`);
                            }

                            console.log('📤 Appel de form.submit()...');
                            showNotification('✓ Redirection vers votre commande...', 'success');
                            form.submit();
                            console.log('✓ form.submit() a été appelée');
                        } catch (submitError) {
                            console.error('❌ ERREUR:', submitError.message);
                            console.error('Stack:', submitError.stack);
                            showNotification(`❌ Erreur: ${submitError.message}`, 'error');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '✓ Confirmer la Commande';
                        }
                    }, 1000);
                } catch (validationError) {
                    console.error('❌ Erreur dans la validation:', validationError);
                    showNotification(`❌ Erreur validation: ${validationError.message}`, 'error');
                }
            });
        } catch (setupError) {
            console.error('❌ Erreur lors de la configuration:', setupError);
        }
    }
    // ==================== Notifications Toast Visibles ====================
    function showNotification(message, type = 'info') {
        const container = document.getElementById('notification-container');
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type} p-4 rounded-lg shadow-lg max-w-sm text-sm font-semibold`;
        toast.textContent = message;

        container.appendChild(toast);

        // Auto-remove après 4 secondes
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
</script>
@endsection
