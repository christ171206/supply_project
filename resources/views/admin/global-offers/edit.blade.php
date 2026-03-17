@extends('layouts.admin')

@section('content')
<div class="max-w-[900px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif text-3xl text-[#0a0a0a] mb-1">Modifier l'Offre</h1>
        <p class="text-[13px] text-[#a0a09a]">{{ $offer->name }}</p>
    </div>

    <form action="{{ route('admin.global-offers.update', $offer) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Informations Générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Nom de l'Offre *</label>
                    <input type="text" name="name" value="{{ $offer->name }}" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">{{ $offer->description }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="isActive" value="1" {{ $offer->is_active ? 'checked' : '' }} class="w-4 h-4">
                    <label for="isActive" class="text-[13px] font-medium text-[#0a0a0a]">Offre active</label>
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
                        <option value="discount_percent" {{ $offer->type === 'discount_percent' ? 'selected' : '' }}>Réduction en pourcentage (%)</option>
                        <option value="discount_fixed" {{ $offer->type === 'discount_fixed' ? 'selected' : '' }}>Réduction montant fixe</option>
                        <option value="free_shipping" {{ $offer->type === 'free_shipping' ? 'selected' : '' }}>Livraison gratuite</option>
                        <option value="buy_x_get_y" {{ $offer->type === 'buy_x_get_y' ? 'selected' : '' }}>Achetez X obtenez Y gratuit</option>
                        <option value="tiered_discount" {{ $offer->type === 'tiered_discount' ? 'selected' : '' }}>Réduction progressive par quantité</option>
                    </select>
                </div>

                {{-- Value --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Valeur *</label>
                    <input type="number" name="value" id="value" value="{{ $offer->value }}" required step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                    <div class="text-[11px] text-[#a0a09a] mt-1" id="valueHelp"></div>
                </div>

                {{-- Max Discount --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Réduction Maximale (optionnel)</label>
                    <input type="number" name="max_discount" value="{{ $offer->max_discount ?? '' }}" step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
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
                        <option value="all" {{ $offer->target_type === 'all' ? 'selected' : '' }}>Tous les produits</option>
                        <option value="category" {{ $offer->target_type === 'category' ? 'selected' : '' }}>Une catégorie spécifique</option>
                        <option value="vendor" {{ $offer->target_type === 'vendor' ? 'selected' : '' }}>Un vendeur spécifique</option>
                        <option value="product" {{ $offer->target_type === 'product' ? 'selected' : '' }}>Un produit spécifique</option>
                    </select>
                </div>

                {{-- Target ID --}}
                <div id="targetIdDiv" class="{{ $offer->target_type === 'all' ? 'hidden' : '' }}">
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2" id="targetIdLabel">
                        {{ match($offer->target_type) {
                            'category' => 'Catégorie',
                            'vendor' => 'Vendeur',
                            'product' => 'Produit',
                            default => ''
                        } }}
                    </label>
                    <select name="target_id" id="targetId" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                        <option value="">Sélectionner</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ $offer->target_id === $category->id ? 'selected' : '' }}>{{ $category->nom }}</option>
                        @endforeach
                        @foreach($vendors ?? [] as $vendor)
                            <option value="{{ $vendor->id }}" {{ $offer->target_id === $vendor->id ? 'selected' : '' }}>{{ $vendor->nom }}</option>
                        @endforeach
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}" {{ $offer->target_id === $product->id ? 'selected' : '' }}>{{ $product->nom }}</option>
                        @endforeach
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
                    <input type="number" name="min_purchase" value="{{ $offer->min_purchase }}" step="0.01" min="0" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Quantité Minimale</label>
                    <input type="number" name="min_quantity" value="{{ $offer->min_quantity }}" min="1" class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Dates --}}
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
            <h2 class="font-serif text-[18px] text-[#0a0a0a] mb-4">Période de Validité</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de Début *</label>
                    <input type="datetime-local" name="start_date" value="{{ $offer->start_date->format('Y-m-d\TH:i') }}" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-[#0a0a0a] mb-2">Date de Fin *</label>
                    <input type="datetime-local" name="end_date" value="{{ $offer->end_date->format('Y-m-d\TH:i') }}" required class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:border-[#0a0a0a] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.global-offers.show', $offer) }}" class="px-4 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] font-medium text-[#0a0a0a] hover:bg-[#f7f7f5]">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                Mettre à Jour
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

// Update value label
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

// Update target type
targetTypeSelect.addEventListener('change', (e) => {
    const type = e.target.value;

    if (type === 'all') {
        targetIdDiv.classList.add('hidden');
    } else {
        targetIdDiv.classList.remove('hidden');

        const labels = {
            'category': 'Catégorie',
            'vendor': 'Vendeur',
            'product': 'Produit',
        };
        targetIdLabel.textContent = labels[type] || '';
    }
});

// Trigger on page load
updateValueLabel();
</script>

@endsection
