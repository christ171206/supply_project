@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-white to-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Hero -->
        <div class="mb-12">
            <div class="text-5xl mb-4">📱</div>
            <h1 class="text-4xl font-display mb-4 text-black">Supply en tant qu'App Native</h1>
            <p class="text-xl text-gray-600 leading-relaxed">
                Supply est maintenant une Progressive Web App (PWA). Installez-la comme une application native
                sur votre téléphone, tablette ou ordinateur.
            </p>
        </div>

        <!-- Installation Guide -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Android -->
            <div class="bg-white rounded-lg border border-gray-200 p-8 hover:border-black transition">
                <div class="text-3xl mb-3">🤖</div>
                <h2 class="text-2xl font-display mb-4">Android Chrome</h2>
                <ol class="space-y-3 text-gray-600">
                    <li class="flex gap-3">
                        <span class="text-black font-bold">1.</span>
                        <span>Ouvrez Supply dans Chrome</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">2.</span>
                        <span>Appuyez sur Menu (⋮)</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">3.</span>
                        <span>Appuyez « Installer l'app »</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">4.</span>
                        <span>Confirmez l'installation</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">5.</span>
                        <span>Supply s'ajoute à votre écran d'accueil ✓</span>
                    </li>
                </ol>
            </div>

            <!-- iOS -->
            <div class="bg-white rounded-lg border border-gray-200 p-8 hover:border-black transition">
                <div class="text-3xl mb-3">🍎</div>
                <h2 class="text-2xl font-display mb-4">iOS Safari</h2>
                <ol class="space-y-3 text-gray-600">
                    <li class="flex gap-3">
                        <span class="text-black font-bold">1.</span>
                        <span>Ouvrez Safari</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">2.</span>
                        <span>Visitez supply.app</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">3.</span>
                        <span>Appuyez le bouton Partage (↑)</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">4.</span>
                        <span>Sélectionnez « Sur l'écran d'accueil »</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">5.</span>
                        <span>Supply fonctionne comme app native ✓</span>
                    </li>
                </ol>
            </div>

            <!-- Chrome Desktop -->
            <div class="bg-white rounded-lg border border-gray-200 p-8 hover:border-black transition">
                <div class="text-3xl mb-3">💻</div>
                <h2 class="text-2xl font-display mb-4">Chrome/Edge Desktop</h2>
                <ol class="space-y-3 text-gray-600">
                    <li class="flex gap-3">
                        <span class="text-black font-bold">1.</span>
                        <span>Visitez le site</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">2.</span>
                        <span>Cliquez l'icône d'installation (barre d'adresse)</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">3.</span>
                        <span>Cliquez « Installer »</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">4.</span>
                        <span>Supply apparaît dans vos applications</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">5.</span>
                        <span>Accès depuis le menu Démarrer ✓</span>
                    </li>
                </ol>
            </div>

            <!-- Windows -->
            <div class="bg-white rounded-lg border border-gray-200 p-8 hover:border-black transition">
                <div class="text-3xl mb-3">🪟</div>
                <h2 class="text-2xl font-display mb-4">Windows Desktop</h2>
                <ol class="space-y-3 text-gray-600">
                    <li class="flex gap-3">
                        <span class="text-black font-bold">1.</span>
                        <span>Ouvrez dans Chrome ou Edge</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">2.</span>
                        <span>Menu → Apps → "Installer Supply"</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">3.</span>
                        <span>Confirmez l'installation</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">4.</span>
                        <span>Supply s'ouvre en window autonome</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-black font-bold">5.</span>
                        <span>Raccourci disponible partout ✓</span>
                    </li>
                </ol>
            </div>
        </div>

        <!-- Features -->
        <div class="mb-12">
            <h2 class="text-3xl font-display mb-6 text-black">✨ Fonctionnalités PWA</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">📱</span>
                    <div>
                        <h3 class="font-semibold text-black">Installation</h3>
                        <p class="text-sm text-gray-600">Installez Supply comme une véritable application</p>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">📡</span>
                    <div>
                        <h3 class="font-semibold text-black">Mode Offline</h3>
                        <p class="text-sm text-gray-600">Utilisation sans connexion Internet</p>
                    </div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">🔔</span>
                    <div>
                        <h3 class="font-semibold text-black">Notifications Push</h3>
                        <p class="text-sm text-gray-600">Restez informé en temps réel</p>
                    </div>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">⚡</span>
                    <div>
                        <h3 class="font-semibold text-black">Performance</h3>
                        <p class="text-sm text-gray-600">Chargement ultra-rapide avec cache</p>
                    </div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">🔄</span>
                    <div>
                        <h3 class="font-semibold text-black">Sync</h3>
                        <p class="text-sm text-gray-600">Synchronisation automatique en arrière-plan</p>
                    </div>
                </div>
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 flex gap-3">
                    <span class="text-2xl">📲</span>
                    <div>
                        <h3 class="font-semibold text-black">Cross-Platform</h3>
                        <p class="text-sm text-gray-600">Sur tous vos appareils</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Info -->
        <div class="bg-gray-900 text-white rounded-lg p-8 mb-12">
            <h2 class="text-2xl font-display mb-4">🔧 Détails Techniques</h2>
            <div class="grid md:grid-cols-2 gap-8 text-sm font-mono">
                <div>
                    <p class="text-gray-400 mb-2">Manifest:</p>
                    <p class="text-green-400">/manifest.json ✓</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-2">Service Worker:</p>
                    <p class="text-green-400">/service-worker.js ✓</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-2">Icônes:</p>
                    <p class="text-green-400">/icons/*.png ✓</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-2">HTTPS:</p>
                    <p class="text-yellow-400">Localhost accepté ✓</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-2">Cache Strategy:</p>
                    <p class="text-green-400">Network-first + Cache-first</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-2">Display Mode:</p>
                    <p class="text-green-400">Standalone</p>
                </div>
            </div>
        </div>

        <!-- Links -->
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="/" class="px-6 py-3 bg-black text-white rounded-lg font-semibold hover:bg-gray-800 transition">
                ← Retour
            </a>
            <a href="/manifest.json" class="px-6 py-3 bg-gray-100 text-black rounded-lg font-semibold hover:bg-gray-200 transition">
                📋 Voir Manifest
            </a>
            <a href="{{ asset('service-worker.js') }}" class="px-6 py-3 bg-gray-100 text-black rounded-lg font-semibold hover:bg-gray-200 transition">
                🔧 Voir Service Worker
            </a>
        </div>

        <!-- Windows Guide Link -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">👉 Sur Windows? Suivez ce guide simple:</p>
            <a href="https://github.com/your-repo/blob/main/INSTALL_SIMPLE.md" target="_blank" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                📖 Guide d'installation Windows (3 étapes)
            </a>
        </div>

        <!-- Footer note -->
        <p class="text-center text-gray-500 text-sm mt-12">
            PWA Configuration • Supply v{{ config('app.version', '1.0') }}
            <br class="md:hidden">
            <span class="hidden md:inline">•</span>
            Documentation: /PWA_SETUP.md
        </p>
    </div>
</div>

<script>
    // Display PWA status
    console.log('=== PWA Status ===');
    console.log('Service Worker supported:', 'serviceWorker' in navigator);
    console.log('Manifest loaded:', document.querySelector('link[rel="manifest"]') !== null);
    console.log('Standalone capable:', window.navigator.standalone === true ||
                                   document.querySelector('meta[name="apple-mobile-web-app-capable"]') !== null);

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(regs => {
            console.log('Service Workers registered:', regs.length);
            regs.forEach(reg => console.log('- Scope:', reg.scope));
        });
    }
</script>
@endsection
