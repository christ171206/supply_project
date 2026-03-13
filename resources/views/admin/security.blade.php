@extends('layouts.admin-layout')

@section('title', 'Sécurité — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Sécurité
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Sécurité</h1>
        <p class="text-[13px] text-white/40 font-light mt-1.5">Mot de passe et sessions de connexion</p>
    </div>

    <div class="px-8">
    <div class="max-w-xl space-y-5">

        {{-- Flash --}}
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl">
                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e] flex-shrink-0"></span>
                <p class="text-[12px] text-[#15803d]">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Changer le mot de passe --}}
        <form action="{{ route('admin.security.password') }}" method="POST">
            @csrf
            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Changer le mot de passe</span>
                </div>

                <div class="divide-y divide-[#efefed]">
                    <div class="px-6 py-4">
                        <label for="current_password" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Mot de passe actuel
                        </label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                      focus:bg-white focus:border-[#0a0a0a] outline-none transition-all"
                               required>
                        @error('current_password')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="px-6 py-4">
                        <label for="new_password" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Nouveau mot de passe
                        </label>
                        <input type="password" id="new_password" name="new_password"
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                      focus:bg-white focus:border-[#0a0a0a] outline-none transition-all"
                               required>
                        <p class="text-[11px] text-[#a0a09a] font-light mt-1">Minimum 8 caractères</p>
                        @error('new_password')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="px-6 py-4">
                        <label for="confirm_password" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Confirmer le mot de passe
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                      focus:bg-white focus:border-[#0a0a0a] outline-none transition-all"
                               required>
                        @error('confirm_password')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#efefed]">
                    <button type="submit"
                            class="bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2 rounded-lg hover:opacity-85 transition-opacity">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>

        {{-- 2FA --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#efefed]">
                <span class="text-[12px] font-medium text-[#0a0a0a]">Authentification à deux facteurs</span>
            </div>
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-[13px] font-medium text-[#0a0a0a]">État</span>
                        <span class="inline-flex items-center gap-1 text-[10px] font-mono px-1.5 py-0.5 rounded bg-[#fdf6ec] text-[#b45309]">
                            <span class="w-1 h-1 rounded-full bg-[#f59e0b]"></span>Désactivé
                        </span>
                    </div>
                    <p class="text-[11px] text-[#a0a09a] font-light">Renforcez la sécurité de votre compte</p>
                </div>
                <button type="button"
                        class="text-[11px] font-medium text-[#666660] border border-[#e0e0dc] px-3 py-1.5 rounded-lg
                               hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all flex-shrink-0">
                    Activer
                </button>
            </div>
        </div>

        {{-- Sessions --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#efefed]">
                <span class="text-[12px] font-medium text-[#0a0a0a]">Sessions actives</span>
            </div>
            <div class="divide-y divide-[#efefed]">
                <div class="px-6 py-4 flex items-start justify-between gap-4">
                    <div>
                        <div class="text-[13px] font-medium text-[#0a0a0a]">Chrome · Windows</div>
                        <div class="font-mono text-[10px] text-[#a0a09a] mt-0.5">127.0.0.1</div>
                        <div class="text-[11px] text-[#a0a09a] font-light mt-0.5">Connecté depuis 2 heures</div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d] flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Session actuelle
                    </span>
                </div>
            </div>
            <div class="px-6 py-3 border-t border-[#efefed]">
                <p class="text-[11px] text-[#a0a09a] font-light">Vous n'avez qu'une seule session active.</p>
            </div>
        </div>

    </div>
    </div>
</div>
@endsection
