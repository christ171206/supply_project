<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande soumise - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f7f7f5]">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="border-b border-[#e0e0dc] p-8 text-center">
                    <div class="w-12 h-12 bg-[#0a0a0a] rounded-lg mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="font-serif text-2xl mb-1 text-[#0a0a0a]">Merci!</h1>
                    <p class="text-sm text-[#666660]">Vos documents ont été reçus avec succès.</p>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-6">
                    <!-- Steps -->
                    <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg p-6 space-y-4">
                        <h2 class="font-medium mb-4 text-[#0a0a0a]">Prochaines étapes :</h2>
                        <div class="space-y-3">
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-[#e0e0dc] text-[#0a0a0a] font-bold flex items-center justify-center text-sm">1</span>
                                <div>
                                    <strong class="text-sm block text-[#0a0a0a]">Vérification</strong>
                                    <p class="text-xs text-[#666660]">Nos équipes examinent vos documents (24h)</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-[#e0e0dc] text-[#0a0a0a] font-bold flex items-center justify-center text-sm">2</span>
                                <div>
                                    <strong class="text-sm block text-[#0a0a0a]">Email de confirmation</strong>
                                    <p class="text-xs text-[#666660]">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-[#e0e0dc] text-[#0a0a0a] font-bold flex items-center justify-center text-sm">3</span>
                                <div>
                                    <strong class="text-sm block text-[#0a0a0a]">Accès tableau de bord</strong>
                                    <p class="text-xs text-[#666660]">Gérez produits et commandes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timings -->
                    <div class="border-t border-[#e0e0dc] pt-6">
                        <p class="text-xs text-[#666660]"><strong class="text-[#0a0a0a]">Délai estimé :</strong> 24-48 heures</p>
                        <p class="text-xs text-[#666660] mt-2"><strong class="text-[#0a0a0a]">Sécurité :</strong> Vos documents sont chiffrés et jamais partagés.</p>
                    </div>

                    <!-- User info -->
                    <div class="border-t border-[#e0e0dc] pt-6">
                        <p class="text-sm font-medium mb-3 text-[#0a0a0a]">Données vérifiées :</p>
                        <div class="space-y-2 text-xs text-[#666660]">
                            <p><strong class="text-[#0a0a0a]">Nom :</strong> {{ $user->name }}</p>
                            <p><strong class="text-[#0a0a0a]">Boutique :</strong> {{ $user->shop_name ?? '-' }}</p>
                            <p><strong class="text-[#0a0a0a]">Téléphone :</strong> {{ $user->phone ?? '-' }}</p>
                            <p><strong class="text-[#0a0a0a]">Localité :</strong> {{ $user->address ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="border-t border-[#e0e0dc] pt-6 flex gap-3">
                        <a href="{{ route('accueil') }}" class="flex-1 bg-[#0a0a0a] text-white text-center py-2 rounded-lg hover:opacity-85 font-medium text-sm transition">
                            Retour à l'accueil
                        </a>
                        <a href="mailto:support@supply.ci" class="flex-1 border border-[#e0e0dc] text-center text-[#0a0a0a] py-2 rounded-lg hover:bg-[#f7f7f5] font-medium text-sm transition">
                            Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Empêcher uniquement le back button du navigateur
        window.addEventListener('popstate', function() {
            window.location.href = '{{ route("accueil") }}';
        });
    </script>
</body>
</html>
