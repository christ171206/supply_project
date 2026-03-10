@extends('vendeur.layout-dashboard')

@section('content')
<div class="pb-20">

    {{-- ══════════════════════════════
         HEADER — fond noir
    ══════════════════════════════ --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-3">Configuration</div>
        <h1 class="font-serif text-[36px] tracking-tight text-white leading-none">
            Paramètres
        </h1>
        <p class="text-[13px] text-white/50 font-light mt-2">Configurez votre boutique et paramètres</p>
    </div>

    <div class="px-8">
    {{-- Messages Flash --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-[#f0fdf4] border border-[#bbf7d0] text-[#15803d] rounded-lg text-[13px]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-[#fef2f2] border border-[#fecaca] text-[#dc2626] rounded-lg text-[13px]">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale: Formulaire -->
        <div class="lg:col-span-2">
            <!-- Paramètres Boutique -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Paramètres Boutique</h2>

                <form method="POST" action="{{ route('vendeur.parametres.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nom Boutique -->
                    <div>
                        <label class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Nom de la Boutique</label>
                        <input type="text" name="shop_name"
                               value="{{ auth()->user()->shop_name ?? '' }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] text-[13px]"
                               placeholder="Nom de votre boutique">
                        @error('shop_name')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Boutique -->
                    <div>
                        <label class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Description Boutique</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] text-[13px]"
                                  placeholder="Décrivez votre boutique...">{{ auth()->user()->description ?? '' }}</textarea>
                        @error('description')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Téléphone</label>
                        <input type="tel" name="phone"
                               value="{{ auth()->user()->phone ?? '' }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] text-[13px]"
                               placeholder="+225 XXXXXXXXXX">
                        @error('phone')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Adresse</label>
                        <input type="text" name="address"
                               value="{{ auth()->user()->address ?? '' }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] text-[13px]"
                               placeholder="Adresse">
                        @error('address')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Minimum Défaut -->
                    <div>
                        <label class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Stock Minimum par Défaut</label>
                        <input type="number" name="stock_minimum_defaut"
                               value="{{ auth()->user()->stock_minimum_defaut ?? 10 }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none hover:border-[#a0a09a] text-[13px]"
                               placeholder="10">
                        @error('stock_minimum_defaut')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton Sauvegarder -->
                    <div class="pt-4 border-t border-[#e0e0dc]">
                        <button type="submit" class="px-6 py-2.5 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-medium text-[13px]">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Colonne sidebar: Actions rapides -->
        <div class="space-y-6">
            <!-- Compte -->
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="text-lg font-medium text-[#0a0a0a] mb-4">Compte</h3>

                <div class="space-y-3">
                    <div class="text-[13px]">
                        <p class="text-[11px] text-[#a0a09a] uppercase">Email</p>
                        <p class="font-medium text-[#0a0a0a]">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="text-[13px]">
                        <p class="text-[11px] text-[#a0a09a] uppercase">Statut</p>
                        <p class="font-medium"><span class="inline-block px-2 py-1 bg-[#f0fdf4] text-[#15803d] rounded text-[11px] font-medium">Actif</span></p>
                    </div>
                    <div class="text-[13px]">
                        <p class="text-[11px] text-[#a0a09a] uppercase">Membre depuis</p>
                        <p class="font-medium text-[#0a0a0a]">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <hr class="my-4 border-[#e0e0dc]">

                <a href="{{ route('vendeur.profil') }}" class="block w-full px-4 py-2 bg-[#0a0a0a] text-white rounded-lg hover:opacity-85 transition font-medium text-[12px] text-center">
                    Voir Profil
                </a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
