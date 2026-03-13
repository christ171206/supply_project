@extends('layouts.admin-layout')

@section('title', 'Profil — Supply Admin')

@section('breadcrumb')
    Espace Admin &nbsp;/&nbsp; Profil
@endsection

@section('content')
<div class="pb-16">

    {{-- HEADER --}}
    <div class="bg-[#0a0a0a] px-8 pt-10 pb-8 mb-8">
        <div class="text-[10px] font-medium tracking-[0.15em] uppercase text-white/40 mb-2">Administration</div>
        <h1 class="font-serif text-[32px] tracking-tight text-white leading-none">Profil</h1>
        <p class="text-[13px] text-white/40 font-light mt-1.5">Informations de votre compte administrateur</p>
    </div>

    <div class="px-8">
    <div class="max-w-xl space-y-5">

        {{-- Flash success --}}
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 bg-[#f0fdf4] border border-[#bbf7d0] rounded-xl">
                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e] flex-shrink-0"></span>
                <p class="text-[12px] text-[#15803d]">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Avatar + infos --}}
        <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
            <div class="px-6 py-5 flex items-center gap-5 border-b border-[#efefed]">
                <div class="w-12 h-12 bg-[#0a0a0a] rounded-md flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[16px] font-medium font-mono">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[14px] font-medium text-[#0a0a0a] truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[12px] text-[#a0a09a] font-light mt-0.5">{{ auth()->user()->email }}</div>
                </div>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-medium px-2 py-1 rounded bg-[#f0fdf4] text-[#15803d] flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>Administrateur
                </span>
            </div>
            <div class="px-6 py-3">
                <p class="text-[11px] text-[#a0a09a] font-light">
                    Pour modifier votre email ou mot de passe, rendez-vous dans
                    <a href="{{ route('admin.security.index') }}" class="text-[#0a0a0a] underline underline-offset-2">Sécurité</a>.
                </p>
            </div>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="bg-white border border-[#e0e0dc] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#efefed]">
                    <span class="text-[12px] font-medium text-[#0a0a0a]">Modifier les informations</span>
                </div>

                <div class="divide-y divide-[#efefed]">

                    {{-- Nom --}}
                    <div class="px-6 py-4">
                        <label for="name" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Nom complet
                        </label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                      focus:bg-white focus:border-[#0a0a0a] outline-none transition-all"
                               required>
                        @error('name')
                            <p class="text-[11px] text-[#dc2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (readonly) --}}
                    <div class="px-6 py-4">
                        <label class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Email
                        </label>
                        <input type="email" value="{{ auth()->user()->email }}"
                               class="w-full bg-[#efefed] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#a0a09a]
                                      cursor-not-allowed"
                               disabled>
                        <p class="text-[11px] text-[#a0a09a] font-light mt-1">Non modifiable depuis cette page</p>
                    </div>

                    {{-- Téléphone --}}
                    <div class="px-6 py-4">
                        <label for="phone" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Téléphone <span class="normal-case tracking-normal font-light">— optionnel</span>
                        </label>
                        <input type="tel" id="phone" name="phone"
                               value="{{ old('phone', auth()->user()->phone ?? '') }}"
                               placeholder="+225 XX XX XX XX"
                               class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                      placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all">
                    </div>

                    {{-- Bio --}}
                    <div class="px-6 py-4">
                        <label for="bio" class="block text-[10px] font-medium tracking-[0.06em] uppercase text-[#a0a09a] mb-1.5">
                            Bio <span class="normal-case tracking-normal font-light">— optionnel</span>
                        </label>
                        <textarea id="bio" name="bio" rows="3"
                                  placeholder="Décrivez votre rôle et responsabilités…"
                                  class="w-full bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg px-3 py-2 text-[13px] text-[#0a0a0a]
                                         placeholder-[#a0a09a] focus:bg-white focus:border-[#0a0a0a] outline-none transition-all resize-none">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-[#efefed] flex items-center gap-3">
                    <button type="submit"
                            class="bg-[#0a0a0a] text-white text-[12px] font-medium px-5 py-2 rounded-lg hover:opacity-85 transition-opacity">
                        Enregistrer
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-[12px] font-medium text-[#666660] border border-[#e0e0dc] px-5 py-2 rounded-lg
                              hover:border-[#2a2a28] hover:text-[#0a0a0a] transition-all">
                        Annuler
                    </a>
                </div>
            </div>
        </form>

    </div>
    </div>
</div>
@endsection
