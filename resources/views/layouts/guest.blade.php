<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Supply') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#0a0a0a">
        <meta name="description" content="Plateforme e-commerce minimaliste pour acheter et vendre avec élégance">
        <meta name="app-name" content="Supply">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Supply">
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" type="image/png" href="/icons/icon-192x192.png">
        <link rel="apple-touch-icon" href="/icons/apple-touch-icon-180x180.png">

        <!-- Fonts from Google -->
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body bg-white text-black antialiased">
        @yield('content')
    </body>
</html>
