@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-8">
        <a href="{{ route('admin.abuse-rules.index') }}" class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] flex items-center gap-1">
            ← Retour aux règles
        </a>
        <h1 class="font-serif text-3xl text-[#0a0a0a] mt-4">Modifier la Règle</h1>
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

    <form action="{{ route('admin.abuse-rules.update', $rule) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- General Info Section --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-lg text-[#0a0a0a] mb-6">Informations Générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nom de la Règle</label>
                    <input type="text" name="name" value="{{ old('name', $rule->name) }}" required
                           class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                           placeholder="ex: Limite d'utilisation par utilisateur">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                              placeholder="Expliquez l'objectif de cette règle...">{{ old('description', $rule->description) }}</textarea>
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
                            <option value="limit_per_user" {{ old('rule_type', $rule->rule_type) == 'limit_per_user' ? 'selected' : '' }}>Limite par utilisateur</option>
                            <option value="limit_per_day" {{ old('rule_type', $rule->rule_type) == 'limit_per_day' ? 'selected' : '' }}>Limite quotidienne globale</option>
                            <option value="limit_per_week" {{ old('rule_type', $rule->rule_type) == 'limit_per_week' ? 'selected' : '' }}>Limite hebdomadaire</option>
                            <option value="limit_per_month" {{ old('rule_type', $rule->rule_type) == 'limit_per_month' ? 'selected' : '' }}>Limite mensuelle</option>
                            <option value="min_account_age" {{ old('rule_type', $rule->rule_type) == 'min_account_age' ? 'selected' : '' }}>Âge minimum du compte</option>
                            <option value="min_cart_value" {{ old('rule_type', $rule->rule_type) == 'min_cart_value' ? 'selected' : '' }}>Valeur minimale du panier</option>
                            <option value="max_discount_per_day" {{ old('rule_type', $rule->rule_type) == 'max_discount_per_day' ? 'selected' : '' }}>Max réductions par jour</option>
                            <option value="forbidden_combination" {{ old('rule_type', $rule->rule_type) == 'forbidden_combination' ? 'selected' : '' }}>Combinaisons interdites</option>
                            <option value="excluded_categories" {{ old('rule_type', $rule->rule_type) == 'excluded_categories' ? 'selected' : '' }}>Catégories exclues</option>
                            <option value="excluded_vendors" {{ old('rule_type', $rule->rule_type) == 'excluded_vendors' ? 'selected' : '' }}>Vendeurs exclus</option>
                            <option value="max_quantity_per_order" {{ old('rule_type', $rule->rule_type) == 'max_quantity_per_order' ? 'selected' : '' }}>Quantité maximum par commande</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Sévérité</label>
                        <select name="severity" required
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
                            <option value="1" {{ old('severity', $rule->severity) == 1 ? 'selected' : '' }}>Info (notification)</option>
                            <option value="2" {{ old('severity', $rule->severity) == 2 ? 'selected' : '' }}>Avertissement</option>
                            <option value="3" {{ old('severity', $rule->severity) == 3 ? 'selected' : '' }}>Blocage (refuser la promo)</option>
                        </select>
                    </div>
                </div>

                {{-- Enable/Disable Toggle --}}
                <div class="flex items-center gap-3 p-3 bg-[#f7f7f5] rounded-lg">
                    <input type="checkbox" id="isEnabled" name="is_enabled" value="1"
                           {{ $rule->is_enabled ? 'checked' : '' }}
                           class="w-4 h-4 cursor-pointer">
                    <label for="isEnabled" class="text-[13px] font-medium text-[#0a0a0a] cursor-pointer">
                        Règle activée
                    </label>
                </div>

                {{-- Dynamic Config Fields --}}
                <div id="configFields" class="bg-[#f7f7f5] rounded-lg p-4 space-y-3">
                    {{-- Max Uses --}}
                    <div id="maxUsesField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nombre Maximum d'Utilisation</label>
                        <input type="number" name="config[max_uses]" min="1"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_uses', $rule->config['max_uses'] ?? '') }}"
                               placeholder="ex: 5">
                    </div>

                    {{-- Min Days --}}
                    <div id="minDaysField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Âge Minimum du Compte (jours)</label>
                        <input type="number" name="config[min_days]" min="0"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.min_days', $rule->config['min_days'] ?? '') }}"
                               placeholder="ex: 7">
                    </div>

                    {{-- Min Value --}}
                    <div id="minValueField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Valeur Minimum du Panier (FCFA)</label>
                        <input type="number" name="config[min_value]" min="0" step="100"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.min_value', $rule->config['min_value'] ?? '') }}"
                               placeholder="ex: 10000">
                    </div>

                    {{-- Max Discount --}}
                    <div id="maxDiscountField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Max Réductions par Jour (FCFA)</label>
                        <input type="number" name="config[max_discount]" min="0" step="100"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_discount', $rule->config['max_discount'] ?? '') }}"
                               placeholder="ex: 50000">
                    </div>

                    {{-- Max Quantity --}}
                    <div id="maxQuantityField" style="display: none;">
                        <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Quantité Maximum par Commande</label>
                        <input type="number" name="config[max_quantity]" min="1"
                               class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]"
                               value="{{ old('config.max_quantity', $rule->config['max_quantity'] ?? '') }}"
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
                        <option value="all" {{ old('applies_to', $rule->applies_to) == 'all' ? 'selected' : '' }}>Tous les codes promos et coupons</option>
                        <option value="specific_promo" {{ old('applies_to', $rule->applies_to) == 'specific_promo' ? 'selected' : '' }}>Code promo spécifique</option>
                        <option value="specific_coupon" {{ old('applies_to', $rule->applies_to) == 'specific_coupon' ? 'selected' : '' }}>Coupon client spécifique</option>
                        <option value="global_offers" {{ old('applies_to', $rule->applies_to) == 'global_offers' ? 'selected' : '' }}>Offres globales de plateforme</option>
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

        {{-- Stats Section --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-lg text-[#0a0a0a] mb-4">Statistiques</h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Violations</div>
                    <div class="font-mono text-2xl font-bold text-[#0a0a0a]">{{ $rule->logs()->count() }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Perte Potentielle</div>
                    <div class="font-mono text-2xl font-bold text-[#0a0a0a]">{{ number_format($rule->logs()->sum('potential_loss'), 0, ',', ' ') }} F</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Créée le</div>
                    <div class="text-[13px] text-[#0a0a0a]">{{ $rule->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                Enregistrer les Modifications
            </button>
            <a href="{{ route('admin.abuse-rules.show', $rule) }}" class="px-6 py-2.5 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg text-[13px] font-medium hover:bg-[#f7f7f5]">
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
    document.getElementById('maxUsesField').style.display = 'none';
    document.getElementById('minDaysField').style.display = 'none';
    document.getElementById('minValueField').style.display = 'none';
    document.getElementById('maxDiscountField').style.display = 'none';
    document.getElementById('maxQuantityField').style.display = 'none';

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
            {{ $rule->applies_to_id ? "if (option.id === " . $rule->applies_to_id . ") optionEl.selected = true;" : "" }}
            selectElement.appendChild(optionEl);
        });
    } catch (error) {
        console.error('Erreur:', error);
    }
}

ruleTypeSelect.addEventListener('change', e => updateConfigFields(e.target.value));
appliesToSelect.addEventListener('change', e => updateAppliesToFields(e.target.value));

updateConfigFields(ruleTypeSelect.value);
updateAppliesToFields(appliesToSelect.value);
</script>

@endsection
