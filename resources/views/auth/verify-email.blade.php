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

        <h2 class="font-serif text-3xl leading-tight mb-3">Vérifiez votre<br><em class="italic text-gray-600">email</em></h2>
        <p class="text-sm text-gray-600 font-light mb-8 max-w-xs">Un lien de confirmation a été envoyé à votre boîte mail.</p>

        <div class="space-y-5">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">✉</div>
                <div>
                    <strong class="text-sm">Vérifier</strong>
                    <p class="text-xs text-gray-600 font-light">Cliquez le lien dans votre email.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0 text-xs">↻</div>
                <div>
                    <strong class="text-sm">Renvoyer</strong>
                    <p class="text-xs text-gray-600 font-light">Cliquez ci-dessous pour envoyer à nouveau.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="bg-off-white p-12 flex items-center justify-center">
        <div class="w-full max-w-sm">
            <h1 class="font-serif text-2xl mb-1">Vérification</h1>
            <p class="text-xs text-gray-600 mb-6">Un lien a été envoyé à votre email.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-3 bg-white border border-gray-200 rounded-lg text-xs text-black">
                    Nouveau lien de vérification envoyé.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                @csrf
                <button type="submit" class="w-full bg-black text-white py-2 rounded-lg text-sm font-medium hover:opacity-85 transition">
                    Renvoyer lien
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-200 pt-6">
                <a href="{{ route('logout.get') }}" class="text-xs text-gray-600 hover:text-black font-medium">
                    Se déconnecter
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
