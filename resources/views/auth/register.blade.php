@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-white px-4 py-16">
    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="mb-12">
            <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] hover:text-[#0a0a0a] transition-colors mb-8">
                ← Retour à l'accueil
            </a>
            <div class="mb-6">
                <h1 class="font-serif text-[32px] tracking-tight text-[#0a0a0a] mb-2">
                    Créer un compte
                </h1>
                <p class="text-[14px] text-[#666660] font-light">
                    Rejoignez Supply gratuitement
                </p>
            </div>
        </div>

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
                <div class="font-medium mb-2">Erreur d'inscription</div>
                @foreach ($errors->all() as $error)
                    <div class="text-[12px] font-light">• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Choix Rôle --}}
        <div class="mb-8">
            <div class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Qui êtes-vous ?</div>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer group">
                    <input type="radio" name="role-toggle-group" class="hidden role-toggle" data-role="client" checked required />
                    <div class="role-card p-4 border-2 border-[#0a0a0a] rounded-lg text-center transition-all bg-[#f7f7f5] cursor-pointer">
                        <div class="text-2xl mb-2">🛍️</div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Je suis client</div>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1">Acheter des produits</div>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input type="radio" name="role-toggle-group" class="hidden role-toggle" data-role="vendor" required />
                    <div class="role-card p-4 border-2 border-[#e0e0dc] rounded-lg text-center transition-all cursor-pointer">
                        <div class="text-2xl mb-2">🏪</div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Je suis vendeur</div>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1">Vendre sur Supply</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('register') }}" class="mb-8">
            @csrf

            {{-- Rôle (hidden) --}}
            <input type="hidden" name="role" id="role-input" value="client" />

            <div class="mb-5">
                <label for="name" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                    Nom complet
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Jean Dupont"
                    required
                    autofocus
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
                />
                @error('name')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="votre@email.com"
                    required
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
                />
                @error('email')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="country" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                    Pays
                </label>
                <select
                    id="country"
                    name="country"
                    required
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white cursor-pointer"
                >
                    <option value="">Sélectionner un pays</option>
                    <option value="CI" {{ old('country') === 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                    <option value="SN" {{ old('country') === 'SN' ? 'selected' : '' }}>Sénégal</option>
                    <option value="ML" {{ old('country') === 'ML' ? 'selected' : '' }}>Mali</option>
                    <option value="BF" {{ old('country') === 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                    <option value="BJ" {{ old('country') === 'BJ' ? 'selected' : '' }}>Bénin</option>
                    <option value="TG" {{ old('country') === 'TG' ? 'selected' : '' }}>Togo</option>
                </select>
                @error('country')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                    Mot de passe
                </label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white pr-10"
                    />
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#a0a09a] hover:text-[#0a0a0a] transition-colors"
                        onclick="const input = document.getElementById('password'); input.type = input.type === 'password' ? 'text' : 'password';"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                    Confirmer mot de passe
                </label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white pr-10"
                    />
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#a0a09a] hover:text-[#0a0a0a] transition-colors"
                        onclick="const input = document.getElementById('password_confirmation'); input.type = input.type === 'password' ? 'text' : 'password';"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6 px-3 py-2.5 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-[12px] text-[#666660] font-light leading-relaxed">
                <div class="font-medium text-[#0a0a0a] mb-1.5">Conditions :</div>
                <ul class="space-y-1">
                    <li>✓ Au minimum 8 caractères</li>
                    <li>✓ Contenir au moins une majuscule</li>
                    <li>✓ Contenir au moins un chiffre</li>
                </ul>
            </div>

            {{-- Champs Vendeur (conditionnels) --}}
            <div id="vendor-fields" class="hidden mb-6 pb-6 border-b border-[#efefed] space-y-5">
                <div>
                    <label for="shop_name" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Nom de la boutique
                    </label>
                    <input
                        id="shop_name"
                        type="text"
                        name="shop_name"
                        value="{{ old('shop_name') }}"
                        placeholder="Ma Boutique"
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
                    />
                </div>

                <div>
                    <label for="phone" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Téléphone
                    </label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+225 XX XX XX"
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
                    />
                </div>

                <div>
                    <label for="address" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">
                        Adresse
                    </label>
                    <input
                        id="address"
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="123 Rue de la Paix"
                        class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
                    />
                </div>
            </div>

            {{-- Accepter conditions --}}
            <div class="mb-6">
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" name="terms" required class="w-4 h-4 mt-0.5 accent-[#0a0a0a] cursor-pointer flex-shrink-0" />
                    <span class="text-[12px] text-[#666660] font-light leading-relaxed">
                        J'accepte les 
                        <a href="#" class="text-[#0a0a0a] font-medium hover:underline">conditions d'utilisation</a>
                        et la 
                        <a href="#" class="text-[#0a0a0a] font-medium hover:underline">politique de confidentialité</a>
                    </span>
                </label>
                @error('terms')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity"
            >
                Créer mon compte
            </button>
        </form>

        {{-- Déjà inscrit --}}
        <div class="px-4 py-3 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-center">
            <p class="text-[12px] text-[#666660] font-light">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-[#0a0a0a] font-medium hover:underline">
                    Se connecter
                </a>
            </p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleRadios = document.querySelectorAll('.role-toggle');
    const roleCards = document.querySelectorAll('.role-card');
    const roleInput = document.getElementById('role-input');
    const vendorFields = document.getElementById('vendor-fields');

    function updateCardStyles() {
        // Find which radio is checked
        const checkedRadio = document.querySelector('.role-toggle:checked');
        if (!checkedRadio) return;

        const checkedRole = checkedRadio.getAttribute('data-role');
        
        // Update all cards
        roleRadios.forEach((radio, index) => {
            const card = roleCards[index];
            const isChecked = radio.checked;
            
            if (isChecked) {
                card.className = 'role-card p-4 border-2 border-[#0a0a0a] rounded-lg text-center transition-all bg-[#f7f7f5] cursor-pointer';
            } else {
                card.className = 'role-card p-4 border-2 border-[#e0e0dc] rounded-lg text-center transition-all cursor-pointer';
            }
        });

        // Update form input and vendor fields
        roleInput.value = checkedRole;
        if (checkedRole === 'vendor') {
            vendorFields.classList.remove('hidden');
        } else {
            vendorFields.classList.add('hidden');
        }
    }

    // Attach change listeners to all radios
    roleRadios.forEach(radio => {
        radio.addEventListener('change', updateCardStyles);
    });

    // Attach click listeners to cards as well (better UX)
    roleCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            roleRadios[index].checked = true;
            updateCardStyles();
        });
    });

    // Initial state
    updateCardStyles();
});
</script>
@endsection
