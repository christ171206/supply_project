@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-8">
        <a href="{{ route('admin.abuse-rules.index') }}" class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] flex items-center gap-1">
            ← Retour aux règles
        </a>
        <h1 class="font-serif text-3xl text-[#0a0a0a] mt-4">Créer une Règle Anti-Abus</h1>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-[13px] font-medium text-red-900 mb-3">Erreurs de validation:</div>
            <ul class="list-disc list-inside text-[12px] text-red-800 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.abuse-rules.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- General Info Section --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-lg text-[#0a0a0a] mb-6">Informations Générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nom de la Règle</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                           placeholder="ex: Limite d'utilisation par utilisateur">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                              placeholder="Expliquez l'objectif de cette règle...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Rule Configuration Section --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-lg text-[#0a0a0a] mb-6">Configuration de la Règle</h2>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Type de Règle</label>
                        <select name="rule_type" id="ruleType" required
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
                            <option value="">-- Sélectioner --</option>
                            <option value="limit_per_user" {{ old('rule_type') == 'limit_per_user' ? 'selected' : '' }}>Limite par utilisateur</option>
                            <option value="limit_per_day" {{ old('rule_type') == 'limit_per_day' ? 'selected' : '' }}>Limite quotidienne globale</option>
                            <option value="limit_per_week" {{ old('rule_type') == 'limit_per_week' ? 'selected' : '' }}>Limite hebdomadaire</option>
                            <option value="limit_per_month" {{ old('rule_type') == 'limit_per_month' ? 'selected' : '' }}>Limite mensuelle</option>
                            <option value="min_account_age" {{ old('rule_type') == 'min_account_age' ? 'selected' : '' }}>Âge minimum du compte</option>
                            <option value="min_cart_value" {{ old('rule_type') == 'min_cart_value' ? 'selected' : '' }}>Valeur minimale du panier</option>
                            <option value="max_discount_per_day" {{ old('rule_type') == 'max_discount_per_day' ? 'selected' : '' }}>Max réductions par jour</option>
                            <option value="forbidden_combination" {{ old('rule_type') == 'forbidden_combination' ? 'selected' : '' }}>Combinaisons interdites</option>
                            <option value="excluded_categories" {{ old('rule_type') == 'excluded_categories' ? 'selected' : '' }}>Catégories exclues</option>
                            <option value="excluded_vendors" {{ old('rule_type') == 'excluded_vendors' ? 'selected' : '' }}>Vendeurs exclus</option>
                            <option value="max_quantity_per_order" {{ old('rule_type') == 'max_quantity_per_order' ? 'selected' : '' }}>Quantité maximum par commande</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Sévérité</label>
                        <select name="severity" required
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
                            <option value="1" {{ old('severity') == '1' ? 'selected' : '' }}>Info (notification)</option>
                            <option value="2" {{ old('severity') == '2' ? 'selected' : '' }}>Avertissement</option>
                            <option value="3" {{ old('severity') == '3' ? 'selected' : '' }}>Blocage (refuser la promo)</option>
                        </select>
                    </div>
                </div>

                {{-- Dynamic Config Fields --}}
                <div id="configFields" class="bg-[#f7f7f5] rounded-lg p-4 space-y-3">
                    {{-- Max Uses (for limit_per_user, limit_per_day, etc) --}}
                    <div id="maxUsesField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nombre Maximum d'Utilisation</label>
                        <input type="number" name="config[max_uses]" min="1"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_uses', '') }}"
                               placeholder="ex: 5">
                        <p class="text-[11px] text-[#a0a09a] mt-1">Nombre de fois que ce code promo peut être utilisé</p>
                    </div>

                    {{-- Min Days (for min_account_age) --}}
                    <div id="minDaysField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Âge Minimum du Compte (jours)</label>
                        <input type="number" name="config[min_days]" min="0"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.min_days', '') }}"
                               placeholder="ex: 7">
                        <p class="text-[11px] text-[#a0a09a] mt-1">Le compte doit avoir cet âge minimum pour utiliser la promo</p>
                    </div>

                    {{-- Min Value (for min_cart_value) --}}
                    <div id="minValueField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Valeur Minimum du Panier (FCFA)</label>
                        <input type="number" name="config[min_value]" min="0" step="100"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.min_value', '') }}"
                               placeholder="ex: 10000">
                    </div>

                    {{-- Max Discount (for max_discount_per_day) --}}
                    <div id="maxDiscountField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Max Réductions par Jour (FCFA)</label>
                        <input type="number" name="config[max_discount]" min="0" step="100"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_discount', '') }}"
                               placeholder="ex: 50000">
                    </div>

                    {{-- Max Quantity --}}
                    <div id="maxQuantityField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Quantité Maximum par Commande</label>
                        <input type="number" name="config[max_quantity]" min="1"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_quantity', '') }}"
                               placeholder="ex: 10">
                    </div>
                </div>
            </div>
        </div>

        {{-- Applies To Section --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-lg text-[#0a0a0a] mb-6">S'Applique À</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Scope</label>
                    <select name="applies_to" id="appliesTo" required
                           class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
                        <option value="all" {{ old('applies_to') == 'all' ? 'selected' : '' }}>Tous les codes promos et coupons</option>
                        <option value="specific_promo" {{ old('applies_to') == 'specific_promo' ? 'selected' : '' }}>Code promo spécifique</option>
                        <option value="specific_coupon" {{ old('applies_to') == 'specific_coupon' ? 'selected' : '' }}>Coupon client spécifique</option>
                        <option value="global_offers" {{ old('applies_to') == 'global_offers' ? 'selected' : '' }}>Offres globales de plateforme</option>
                    </select>
                </div>

                <div id="appliesIdField" style="display: none;">
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Sélectioner l'Élément</label>
                    <select name="applies_to_id"
                           class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
                        <option value="">-- Choisir --</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                Créer la Règle
            </button>
            <a href="{{ route('admin.abuse-rules.index') }}" class="px-6 py-2.5 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg text-[13px] font-medium hover:bg-[#f7f7f5]">
                Annuler
            </a>
        </div>
    </form>

</div>

<script>
const ruleTypeSelect = document.getElementById('ruleType');
const appliesToSelect = document.getElementById('appliesTo');
const configFields = document.getElementById('configFields');
const appliesIdField = document.getElementById('appliesIdField');

function updateConfigFields(ruleType) {
    // Hide all fields first
    document.getElementById('maxUsesField').style.display = 'none';
    document.getElementById('minDaysField').style.display = 'none';
    document.getElementById('minValueField').style.display = 'none';
    document.getElementById('maxDiscountField').style.display = 'none';
    document.getElementById('maxQuantityField').style.display = 'none';

    // Show relevant fields based on rule type
    switch(ruleType) {
        case 'limit_per_user':
        case 'limit_per_day':
        case 'limit_per_week':
        case 'limit_per_month':
            document.getElementById('maxUsesField').style.display = 'block';
            break;
        case 'min_account_age':
            document.getElementById('minDaysField').style.display = 'block';
            break;
        case 'min_cart_value':
            document.getElementById('minValueField').style.display = 'block';
            break;
        case 'max_discount_per_day':
            document.getElementById('maxDiscountField').style.display = 'block';
            break;
        case 'max_quantity_per_order':
            document.getElementById('maxQuantityField').style.display = 'block';
            break;
    }
}

function updateAppliesToFields(appliesTo) {
    const appliesIdSelect = appliesIdField.querySelector('select');

    if (appliesTo === 'all') {
        appliesIdField.style.display = 'none';
        appliesIdSelect.name = '';
    } else {
        appliesIdField.style.display = 'block';
        appliesIdSelect.name = 'applies_to_id';

        // Load options based on type
        loadAppliesOptions(appliesTo, appliesIdSelect);
    }
}

async function loadAppliesOptions(appliesTo, selectElement) {
    try {
        const response = await fetch(`/admin/abuse-rules/get-applies-options?type=${appliesTo}`);
        const options = await response.json();

        selectElement.innerHTML = '<option value="">-- Choisir --</option>';
        options.forEach(option => {
            const optionEl = document.createElement('option');
            optionEl.value = option.id;
            optionEl.textContent = option.name;
            selectElement.appendChild(optionEl);
        });
    } catch (error) {
        console.error('Erreur:', error);
    }
}

ruleTypeSelect.addEventListener('change', e => updateConfigFields(e.target.value));
appliesToSelect.addEventListener('change', e => updateAppliesToFields(e.target.value));

// Initialize on page load
updateConfigFields(ruleTypeSelect.value);
updateAppliesToFields(appliesToSelect.value);
</script>

@endsection
