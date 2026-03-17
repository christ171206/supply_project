@extends('layouts.admin')

@section('content')
<div class="max-w-[900px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif text-3xl text-[#0a0a0a] mb-1">Créer une Offre Globale</h1>
        <p class="text-[13px] text-[#a0a09a]">Configurez une nouvelle offre au niveau de la plateforme</p>
    </div>

    <form action="{{ route('admin.global-offers.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Informations Générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nom de l'Offre *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('name') border-red-500 @enderror" placeholder="Ex: Réduction Printemps">
                    @error('name') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none" placeholder="Décrivez cette offre (visible aux clients)"></textarea>
                </div>
            </div>
        </div>

        {{-- Offer Configuration --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Configuration de l'Offre</h2>

            <div class="space-y-4">
                {{-- Type --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Type d'Offre *</label>
                    <select name="type" id="offerType" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                        <option value="">Sélectionner</option>
                        <option value="discount_percent">Réduction en pourcentage (%)</option>
                        <option value="discount_fixed">Réduction montant fixe</option>
                        <option value="free_shipping">Livraison gratuite</option>
                        <option value="buy_x_get_y">Achetez X obtenez Y gratuit</option>
                        <option value="tiered_discount">Réduction progressive par quantité</option>
                    </select>
                </div>

                {{-- Value --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Valeur *</label>
                    <input type="number" name="value" id="value" required step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none" placeholder="0">
                    <div class="text-[11px] text-[#a0a09a] mt-1" id="valueHelp"></div>
                </div>

                {{-- Max Discount --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Réduction Maximale (optionnel)</label>
                    <input type="number" name="max_discount" step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none" placeholder="Laisser vide pour illimité">
                </div>
            </div>
        </div>

        {{-- Target Configuration --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Cible de l'Offre</h2>

            <div class="space-y-4">
                {{-- Target Type --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Appliquer à *</label>
                    <select name="target_type" id="targetType" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                        <option value="all">Tous les produits</option>
                        <option value="category">Une catégorie spécifique</option>
                        <option value="vendor">Un vendeur spécifique</option>
                        <option value="product">Un produit spécifique</option>
                    </select>
                </div>

                {{-- Target ID --}}
                <div id="targetIdDiv" class="hidden">
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2" id="targetIdLabel"></label>
                    <select name="target_id" id="targetId" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                        <option value="">Sélectionner</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Conditions --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Conditions d'Application</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Montant Minimum</label>
                    <input type="number" name="min_purchase" step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none" placeholder="0 = Aucun">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Quantité Minimale</label>
                    <input type="number" name="min_quantity" value="1" min="1" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Dates --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Période de Validité</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de Début *</label>
                    <input type="datetime-local" name="start_date" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('start_date') border-red-500 @enderror">
                    @error('start_date') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de Fin *</label>
                    <input type="datetime-local" name="end_date" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none @error('end_date') border-red-500 @enderror">
                    @error('end_date') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.global-offers.index') }}" class="px-4 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#0a0a0a] hover:bg-[#f7f7f5]">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                Créer l'Offre
            </button>
        </div>
    </form>

</div>

<script>
const offerTypeSelect = document.getElementById('offerType');
const valueInput = document.getElementById('value');
const valueHelp = document.getElementById('valueHelp');
const targetTypeSelect = document.getElementById('targetType');
const targetIdDiv = document.getElementById('targetIdDiv');
const targetIdLabel = document.getElementById('targetIdLabel');
const targetIdSelect = document.getElementById('targetId');

// Update value label based on type
offerTypeSelect.addEventListener('change', updateValueLabel);
valueInput.addEventListener('input', updateValueLabel);

function updateValueLabel() {
    const type = offerTypeSelect.value;
    const value = valueInput.value || '0';

    const helpTexts = {
        'discount_percent': 'Exemple: 10 = 10% de réduction',
        'discount_fixed': `Exemple: ${value} FCFA de réduction`,
        'free_shipping': 'La valeur est ignorée pour la livraison gratuite',
        'buy_x_get_y': 'Configurez les quantités dans les paramètres avancés',
        'tiered_discount': 'Configurez les paliers dans les paramètres avancés',
    };

    valueHelp.textContent = helpTexts[type] || '';
}

// Update target options
targetTypeSelect.addEventListener('change', async (e) => {
    const type = e.target.value;

    if (type === 'all') {
        targetIdDiv.classList.add('hidden');
        targetIdSelect.removeAttribute('name');
    } else {
        targetIdDiv.classList.remove('hidden');
        targetIdSelect.setAttribute('name', 'target_id');

        const labels = {
            'category': 'Catégorie',
            'vendor': 'Vendeur',
            'product': 'Produit',
        };
        targetIdLabel.textContent = labels[type] || '';

        // Load options
        try {
            const response = await fetch('/admin/global-offers/api/target-options?type=' + type);
            const data = await response.json();

            targetIdSelect.innerHTML = '<option value="">Sélectionner</option>';
            data.data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.label;
                targetIdSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erreur:', error);
        }
    }
});

// Trigger on page load
updateValueLabel();
</script>

@endsection
