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
                <h1 class="font-serif text-[32px] tracking-tight text-[#0a0a0a] mb-2">Créer un compte</h1>
                <p class="text-[14px] text-[#666660] font-light">Rejoignez Supply gratuitement</p>
            </div>
        </div>

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg">
                <div class="font-medium mb-2 text-[13px] text-[#dc2626]">❌ Erreur lors de l'inscription</div>
                @foreach ($errors->all() as $error)
                    <div class="text-[12px] font-light text-[#dc2626] mb-1">• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Choix Rôle --}}
        <div class="mb-8">
            <div class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-3">Qui êtes-vous ?</div>
            <div class="grid grid-cols-2 gap-3">

                <label class="relative cursor-pointer">
                    <input type="radio" name="role-toggle-group" class="hidden role-toggle" data-role="client" checked required />
                    <div class="role-card p-4 border-2 border-[#0a0a0a] rounded-lg text-center transition-all bg-[#f7f7f5] cursor-pointer">
                        <div class="w-8 h-8 bg-[#0a0a0a] rounded-lg flex items-center justify-center mx-auto mb-2.5">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Je suis client</div>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1">Acheter des produits</div>
                    </div>
                </label>

                <label class="relative cursor-pointer">
                    <input type="radio" name="role-toggle-group" class="hidden role-toggle" data-role="vendor" required />
                    <div class="role-card p-4 border-2 border-[#e0e0dc] rounded-lg text-center transition-all cursor-pointer">
                        <div class="w-8 h-8 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg flex items-center justify-center mx-auto mb-2.5">
                            <svg class="w-4 h-4 text-[#a0a09a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <div class="text-[12px] font-medium text-[#0a0a0a]">Je suis vendeur</div>
                        <div class="text-[10px] text-[#a0a09a] font-light mt-1">Vendre sur Supply</div>
                    </div>
                </label>

            </div>
        </div>

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('register') }}" class="mb-8">
            @csrf
            <input type="hidden" name="role" id="role-input" value="client" />

            {{-- Nom --}}
            <div class="mb-5">
                <label for="name" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       placeholder="Kouassi Jean" required autofocus
                       class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                              placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                @error('name')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       placeholder="votre@email.com" required
                       class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                              placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                @error('email')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            {{-- Pays --}}
            <div class="mb-5">
                <label for="country" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Pays</label>
                <select id="country" name="country" required
                        class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                               outline-none focus:bg-white focus:border-[#0a0a0a] transition-all cursor-pointer">
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

            {{-- Mot de passe --}}
            <div class="mb-5">
                <label for="password" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Mot de passe</label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="••••••••" required
                           class="w-full px-3 py-2.5 pr-10 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                                  placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                    <button type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#a0a09a] hover:text-[#0a0a0a] transition-colors"
                            onclick="const i=document.getElementById('password');i.type=i.type==='password'?'text':'password'">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirmation --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Confirmer le mot de passe</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required
                           class="w-full px-3 py-2.5 pr-10 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                                  placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                    <button type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#a0a09a] hover:text-[#0a0a0a] transition-colors"
                            onclick="const i=document.getElementById('password_confirmation');i.type=i.type==='password'?'text':'password'">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Règles mot de passe --}}
            <div class="mb-6 px-4 py-3 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-[12px] text-[#666660] font-light leading-relaxed space-y-1">
                <div class="text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-2">Conditions</div>
                <div class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-[#a0a09a] flex-shrink-0"></span>8 caractères minimum</div>
                <div class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-[#a0a09a] flex-shrink-0"></span>Au moins une majuscule</div>
                <div class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-[#a0a09a] flex-shrink-0"></span>Au moins un chiffre</div>
            </div>

            {{-- Champs Vendeur (conditionnels) --}}
            <div id="vendor-fields" class="hidden mb-6 pb-6 border-b border-[#efefed] space-y-5">
                <div class="text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a]">Informations boutique</div>
                <div>
                    <label for="shop_name" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Nom de la boutique</label>
                    <input id="shop_name" type="text" name="shop_name" value="{{ old('shop_name') }}"
                           placeholder="Ma Boutique"
                           class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                                  placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                </div>
                <div>
                    <label for="phone" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Téléphone</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                           placeholder="+225 XX XX XX XX XX"
                           class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                                  placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                </div>
                <div>
                    <label for="address" class="block text-[11px] font-medium tracking-[0.05em] uppercase text-[#a0a09a] mb-2">Adresse de la boutique</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}"
                           placeholder="Treicheville, Abidjan"
                           class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                                  placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all" />
                </div>
            </div>

            {{-- CGU --}}
            <div class="mb-6">
                <label class="flex items-start gap-3 cursor-pointer">
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

            <button type="submit"
                    class="w-full py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity disabled:opacity-50"
                    id="submit-btn">
                Créer mon compte
            </button>
        </form>

        {{-- Déjà inscrit --}}
        <div class="px-4 py-3 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-center">
            <p class="text-[12px] text-[#666660] font-light">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-[#0a0a0a] font-medium hover:underline">Se connecter</a>
            </p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios       = document.querySelectorAll('.role-toggle');
    const cards        = document.querySelectorAll('.role-card');
    const roleInput    = document.getElementById('role-input');
    const vendorFields = document.getElementById('vendor-fields');
    const submitBtn    = document.getElementById('submit-btn');
    const form         = document.querySelector('form');

    const ACTIVE   = 'role-card p-4 border-2 border-[#0a0a0a] rounded-lg text-center transition-all bg-[#f7f7f5] cursor-pointer';
    const INACTIVE = 'role-card p-4 border-2 border-[#e0e0dc] rounded-lg text-center transition-all cursor-pointer';

    // Update icon tint inside each card
    function updateIcons(activeIndex) {
        cards.forEach((card, i) => {
            const icon = card.querySelector('div:first-child'); // icon wrapper
            if (i === activeIndex) {
                icon.className = 'w-8 h-8 bg-[#0a0a0a] border border-[#0a0a0a] rounded-lg flex items-center justify-center mx-auto mb-2.5';
                const svg = icon.querySelector('svg');
                if (svg) svg.className = 'w-4 h-4 text-white';
            } else {
                icon.className = 'w-8 h-8 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg flex items-center justify-center mx-auto mb-2.5';
                const svg = icon.querySelector('svg');
                if (svg) svg.className = 'w-4 h-4 text-[#a0a09a]';
            }
        });
    }

    function update() {
        const checked = document.querySelector('.role-toggle:checked');
        if (!checked) return;
        const role = checked.getAttribute('data-role');
        radios.forEach((r, i) => {
            cards[i].className = r.checked ? ACTIVE : INACTIVE;
        });
        updateIcons(Array.from(radios).findIndex(r => r.checked));
        roleInput.value = role;
        vendorFields.classList.toggle('hidden', role !== 'vendor');
    }

    radios.forEach(r => r.addEventListener('change', update));
    cards.forEach((card, i) => card.addEventListener('click', () => {
        radios[i].checked = true;
        update();
    }));

    // Ajouter un état de chargement au bouton de soumission
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ En cours...';
        });
    }

    update();
});
</script>
@endsection
