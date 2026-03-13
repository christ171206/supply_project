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
                    Se connecter
                </h1>
                <p class="text-[14px] text-[#666660] font-light">
                    Accédez à votre compte Supply
                </p>
            </div>
        </div>

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-lg text-[13px] text-[#dc2626]">
                <div class="font-medium mb-2">Erreur de connexion</div>
                @foreach ($errors->all() as $error)
                    <div class="text-[12px] font-light">• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="mb-6 px-4 py-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg text-[13px] text-[#15803d]">
                {{ session('status') }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('login') }}" class="mb-8">
            @csrf

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
                    autofocus
                    class="w-full px-3 py-2.5 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                           placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all"
                />
                @error('email')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
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
                        class="w-full px-3 py-2.5 pr-10 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a]
                               placeholder-[#a0a09a] outline-none focus:bg-white focus:border-[#0a0a0a] transition-all"
                    />
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#a0a09a] hover:text-[#0a0a0a] transition-colors"
                        onclick="const input = document.getElementById('password'); input.type = input.type === 'password' ? 'text' : 'password';"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="mt-1 text-[11px] text-[#dc2626] font-light">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 accent-[#0a0a0a] cursor-pointer" />
                    <span class="text-[12px] text-[#666660] font-light">Se souvenir de moi</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] border-b border-[#e0e0dc] pb-px transition-all">
                    Mot de passe oublié ?
                </a>
            </div>

            <button
                type="submit"
                class="w-full py-3 bg-[#0a0a0a] text-white text-[13px] font-medium rounded-lg hover:opacity-85 transition-opacity"
            >
                Se connecter
            </button>
        </form>

        {{-- Pas encore de compte --}}
        <div class="px-4 py-3 bg-[#f7f7f5] border border-[#efefed] rounded-lg text-center mb-8">
            <p class="text-[12px] text-[#666660] font-light">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-[#0a0a0a] font-medium hover:underline">
                    Créer un compte
                </a>
            </p>
        </div>

        {{-- Comptes de test --}}
        <div class="border-t border-[#efefed] pt-8">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-[#a0a09a] mb-4 flex items-center gap-2">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                Comptes de test — Cliquez pour remplir
            </div>

            <div class="space-y-2">
                {{-- Client --}}
                <button type="button"
                    onclick="document.getElementById('email').value='client@test.com';document.getElementById('password').value='password';document.getElementById('email').focus();"
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 bg-[#0a0a0a] rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">Client</span>
                    </div>
                    <div class="font-mono text-[11px] text-[#a0a09a] pl-7">client@test.com
                        <span class="text-[10px] text-[#666660] font-sans font-light ml-2">password</span>
                    </div>
                </button>

                {{-- Vendeur --}}
                <button type="button"
                    onclick="document.getElementById('email').value='testshop@supply.ci';document.getElementById('password').value='testshop123';document.getElementById('email').focus();"
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 bg-[#0a0a0a] rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">Vendeur</span>
                    </div>
                    <div class="font-mono text-[11px] text-[#a0a09a] pl-7">testshop@supply.ci
                        <span class="text-[10px] text-[#666660] font-sans font-light ml-2">testshop123</span>
                    </div>
                </button>

                {{-- Admin --}}
                <button type="button"
                    onclick="document.getElementById('email').value='admin@supply.ci';document.getElementById('password').value='admin123';document.getElementById('email').focus();"
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 bg-[#0a0a0a] rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span class="text-[12px] font-medium text-[#0a0a0a]">Admin</span>
                    </div>
                    <div class="font-mono text-[11px] text-[#a0a09a] pl-7">admin@supply.ci
                        <span class="text-[10px] text-[#666660] font-sans font-light ml-2">admin123</span>
                    </div>
                </button>
            </div>

            {{-- Astuce --}}
            <div class="mt-4 p-3 bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg text-[11px] text-[#666660] font-light">
                Cliquez sur un compte pour remplir automatiquement les champs de connexion.
            </div>
        </div>

    </div>
</div>
@endsection
