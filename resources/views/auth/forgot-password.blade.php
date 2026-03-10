@extends('layouts.guest')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
    <!-- LEFT PANEL -->
    <div class="bg-white border-r border-gray-200 p-12 sticky top-0 overflow-y-auto">
        <a href="{{ route('accueil') }}" class="auth-back inline-flex items-center gap-2 text-sm text-gray-600 hover:text-black mb-12 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Retour à l'accueil
        </a>

        <div class="auth-brand mb-8">
            <div class="auth-brand-icon w-8 h-8 bg-black rounded-lg flex items-center justify-center mb-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" class="w-4 h-4">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
            </div>
            <span class="text-sm font-medium">Supply</span>
        </div>

        <h2 class="font-serif text-3xl leading-tight mb-3">Mot de passe<br><em class="italic text-gray-600">oublié ?</em></h2>
        <p class="text-sm text-gray-600 font-light mb-8 max-w-xs">Pas de souci ! Nous vous aiderons à réinitialiser votre mot de passe en quelques étapes.</p>

        <div class="space-y-5">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">✉</div>
                <div>
                    <strong class="text-sm">Email de réinitialisation</strong>
                    <p class="text-xs text-gray-600 font-light">Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">🔗</div>
                <div>
                    <strong class="text-sm">Lien sécurisé</strong>
                    <p class="text-xs text-gray-600 font-light">Cliquez le lien du mail pour créer un nouveau mot de passe.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">🔐</div>
                <div>
                    <strong class="text-sm">Mot de passe fort</strong>
                    <p class="text-xs text-gray-600 font-light">Choisissez un nouveau mot de passe sécurisé et unique.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="bg-off-white p-12 flex items-center justify-center">
        <div class="w-full max-w-sm">
            <h1 class="font-serif text-2xl mb-1">Réinitialiser</h1>
            <p class="text-xs text-gray-600 mb-6">Entrez votre email pour recevoir le lien de réinitialisation.</p>

            @if (session('status'))
                <div class="mb-4 p-3 bg-white border border-gray-200 rounded-lg text-xs text-black">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-mono uppercase text-gray-600 mb-2 block">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="vous@example.com"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-black focus:outline-none transition" />
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-black text-white py-2 rounded-lg text-sm font-medium hover:opacity-85 transition">
                    Envoyer le lien
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-600">
                    Vous vous souvenez ?
                    <a href="{{ route('login') }}" class="text-black font-medium hover:underline">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
