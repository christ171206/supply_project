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
                    class="w-full px-3 py-2.5 border border-[#e0e0dc] rounded-lg text-[13px] text-[#0a0a0a] placeholder:text-[#a0a09a] outline-none focus:border-[#0a0a0a] hover:border-[#a0a09a] transition-colors bg-white"
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
                {{-- Client Test --}}
                <button
                    type="button"
                    onclick="
                        document.getElementById('email').value = 'client@test.com';
                        document.getElementById('password').value = 'password';
                        document.getElementById('email').focus();
                    "
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left group"
                >
                    <div class="text-[12px] font-medium text-[#0a0a0a]">👤 Client Test</div>
                    <div class="text-[11px] text-[#a0a09a] font-mono mt-1">
                        <span class="block">client@test.com</span>
                        <span class="block text-[10px] text-[#666660] font-light">password</span>
                    </div>
                </button>

                {{-- Vendeur Test --}}
                <button
                    type="button"
                    onclick="
                        document.getElementById('email').value = 'testshop@supply.ci';
                        document.getElementById('password').value = 'testshop123';
                        document.getElementById('email').focus();
                    "
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left group"
                >
                    <div class="text-[12px] font-medium text-[#0a0a0a]">🏪 Vendeur Test</div>
                    <div class="text-[11px] text-[#a0a09a] font-mono mt-1">
                        <span class="block">testshop@supply.ci</span>
                        <span class="block text-[10px] text-[#666660] font-light">testshop123</span>
                    </div>
                </button>

                {{-- Admin Test --}}
                <button
                    type="button"
                    onclick="
                        document.getElementById('email').value = 'admin@supply.ci';
                        document.getElementById('password').value = 'admin123';
                        document.getElementById('email').focus();
                    "
                    class="w-full p-3 border border-[#e0e0dc] rounded-lg hover:border-[#0a0a0a] hover:bg-[#f7f7f5] transition-all text-left group"
                >
                    <div class="text-[12px] font-medium text-[#0a0a0a]">👨‍💼 Admin Test</div>
                    <div class="text-[11px] text-[#a0a09a] font-mono mt-1">
                        <span class="block">admin@supply.ci</span>
                        <span class="block text-[10px] text-[#666660] font-light">admin123</span>
                    </div>
                </button>
            </div>

            <div class="mt-4 p-3 bg-[#fef3c7] border border-[#fde68a] rounded-lg text-[11px] text-[#92400e] font-light">
                💡 <strong>Astuce:</strong> Cliquez sur un compte pour remplir automatiquement les champs
            </div>
        </div>

    </div>
</div>
@endsection
