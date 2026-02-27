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
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <!-- Logo & En-tête -->
                <div class="mb-8">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary-600 flex items-center justify-center text-white text-2xl font-bold">
                            S
                        </div>
                        <span class="text-3xl font-bold text-gray-900">Supply</span>
                    </a>
                    <p class="mt-4 text-gray-600">Votre boutique informatique en ligne</p>
                </div>

                <!-- Carte principale -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 space-y-6">
