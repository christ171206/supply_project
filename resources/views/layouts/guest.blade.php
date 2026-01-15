<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Supply') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md mx-auto">
                <!-- Logo & En-tête -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center gap-2 text-3xl font-bold text-gray-900 hover:text-primary-600 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xl font-bold">
                            S
                        </div>
                        Supply
                    </a>
                    <p class="mt-4 text-gray-600">Votre boutique informatique en ligne</p>
                </div>

                <!-- Carte principale -->
                <div class="bg-white rounded-2xl shadow-xl shadow-blue-100/50 border border-gray-100 p-8 space-y-6">
                    {{ $slot }}
                </div>

                <!-- Pied de page -->
                <div class="mt-8 text-center text-sm text-gray-600">
                    <p>Besoin d'aide ? <a href="/" class="font-semibold text-primary-600 hover:text-primary-700">Retour à l'accueil</a></p>
                </div>
            </div>
        </div>
    </body>
</html>
