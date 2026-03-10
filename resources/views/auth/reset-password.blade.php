@extends('layouts.guest')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
    <!-- LEFT PANEL -->
    <div class="bg-white border-r border-gray-200 p-12 sticky top-0 overflow-y-auto">
        <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-black mb-12 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Retour
        </a>

        <div class="mb-8">
            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center mb-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" class="w-4 h-4">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            <span class="text-sm font-medium">Supply</span>
        </div>

        <h2 class="font-serif text-3xl leading-tight mb-3">Nouveau<br><em class="italic text-gray-600">mot de passe</em></h2>
        <p class="text-sm text-gray-600 font-light mb-8 max-w-xs">Créez un mot de passe sécurisé et unique pour protéger votre compte.</p>

        <div class="space-y-5">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">A-z</div>
                <div>
                    <strong class="text-sm">Minimum 8 caractères</strong>
                    <p class="text-xs text-gray-600 font-light">Majuscules, minuscules, chiffres.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">✓</div>
                <div>
                    <strong class="text-sm">Confirmation</strong>
                    <p class="text-xs text-gray-600 font-light">Confirmer votre nouveau mot de passe.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="bg-off-white p-12 flex items-center justify-center">
        <div class="w-full max-w-sm">
            <h1 class="font-serif text-2xl mb-1">Réinitialiser</h1>
            <p class="text-xs text-gray-600 mb-6">Entrez votre nouvelle clé sécurisée.</p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="text-xs font-mono uppercase text-gray-600 mb-2 block">Email</label>
                    <input type="email" value="{{ old('email', $request->email) }}" readonly
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-600" />
                </div>

                <div>
                    <label class="text-xs font-mono uppercase text-gray-600 mb-2 block">Nouveau mot de passe</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-black focus:outline-none transition" />
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-mono uppercase text-gray-600 mb-2 block">Confirmation</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-black focus:outline-none transition" />
                </div>

                <button type="submit" class="w-full bg-black text-white py-2 rounded-lg text-sm font-medium hover:opacity-85 transition">
                    Réinitialiser
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-600">
                    Se souvient ?
                    <a href="{{ route('login') }}" class="text-black font-medium hover:underline">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
