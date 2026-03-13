<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation en cours - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f7f7f5]">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="border-b border-[#e0e0dc] p-8 text-center">
                    <div class="w-16 h-16 bg-[#f7f7f5] rounded-full mx-auto mb-4 flex items-center justify-center border border-[#e0e0dc]">
                        <svg class="w-8 h-8 text-[#0a0a0a] animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"/>
                        </svg>
                    </div>
                    <h1 class="font-serif text-3xl mb-2 text-[#0a0a0a]">Validation en cours</h1>
                    <p class="text-sm text-[#666660]">Votre compte vendeur est en cours de vérification</p>
                </div>

                <!-- Content -->
                <div class="p-8 space-y-8">
                    <!-- Status Box -->
                    <div class="bg-[#f7f7f5] border border-[#e0e0dc] rounded-lg p-6">
                        <h2 class="font-medium text-[#0a0a0a] mb-4">📋 Statut de votre demande</h2>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-xs font-bold">✓</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0a0a0a]">Documents soumis</p>
                                    <p class="text-xs text-[#666660]">Vos documents d'identité ont été reçus avec succès</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-[#e0e0dc] flex items-center justify-center">
                                    <svg class="w-3 h-3 text-[#0a0a0a] animate-spin" fill="currentColor" viewBox="0 0 4 4">
                                        <circle cx="2" cy="0.5" r="0.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#0a0a0a]">En cours de vérification</p>
                                    <p class="text-xs text-[#666660]">Notre équipe examine vos documents (24-48 heures)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full border-2 border-[#e0e0dc]"></div>
                                <div>
                                    <p class="text-sm font-medium text-[#0a0a0a]">Approbation finale</p>
                                    <p class="text-xs text-[#666660]">Accès au tableau de bord après validation</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="border-t border-[#e0e0dc] pt-6">
                        <h2 class="font-medium text-[#0a0a0a] mb-4">ℹ️ Ce que vous devez savoir</h2>
                        <ul class="space-y-3 text-sm text-[#666660]">
                            <li class="flex gap-3">
                                <span class="text-[#0a0a0a] font-medium">•</span>
                                <span>Vos documents sont chiffrés et jamais partagés avec des tiers</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="text-[#0a0a0a] font-medium">•</span>
                                <span>Vous recevrez un email dès que votre compte sera approuvé</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="text-[#0a0a0a] font-medium">•</span>
                                <span>Délai moyen : 24-48 heures (peut varier selon le volume)</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="text-[#0a0a0a] font-medium">•</span>
                                <span>Si votre demande est rejetée, vous recevrez des explications et pourrez réessayer</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Vendor Info -->
                    <div class="border-t border-[#e0e0dc] pt-6">
                        <h2 class="font-medium text-[#0a0a0a] mb-4">📝 Informations vérifiées</h2>
                        <div class="bg-[#f7f7f5] rounded-lg p-4 space-y-2 text-xs">
                            <p><span class="font-medium text-[#0a0a0a]">Nom :</span> {{ $user->name }}</p>
                            <p><span class="font-medium text-[#0a0a0a]">Boutique :</span> {{ $user->shop_name ?? '-' }}</p>
                            <p><span class="font-medium text-[#0a0a0a]">Email :</span> {{ $user->email }}</p>
                            <p><span class="font-medium text-[#0a0a0a]">Téléphone :</span> {{ $user->phone ?? '-' }}</p>
                            <p><span class="font-medium text-[#0a0a0a]">Localité :</span> {{ $user->address ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="border-t border-[#e0e0dc] pt-6 flex gap-3">
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-[#0a0a0a] text-white text-center py-2 rounded-lg hover:opacity-85 font-medium text-sm transition">
                                Retour à l'accueil
                            </button>
                        </form>
                        <a href="mailto:support@supply.ci" class="flex-1 border border-[#e0e0dc] text-center text-[#0a0a0a] py-2 rounded-lg hover:bg-[#f7f7f5] font-medium text-sm transition">
                            Nous contacter
                        </a>
                    </div>

                    <!-- FAQ -->
                    <div class="border-t border-[#e0e0dc] pt-6">
                        <h3 class="font-medium text-[#0a0a0a] mb-4">❓ Questions fréquentes</h3>
                        <div class="space-y-3 text-xs text-[#666660]">
                            <div>
                                <p class="font-medium text-[#0a0a0a]">Combien de temps prend la validation ?</p>
                                <p>En moyenne 24-48 heures, selon le volume de demandes.</p>
                            </div>
                            <div>
                                <p class="font-medium text-[#0a0a0a]">Je n'ai pas reçu d'email de confirmation</p>
                                <p>Vérifiez votre dossier spam ou contacter notre support à support@supply.ci</p>
                            </div>
                            <div>
                                <p class="font-medium text-[#0a0a0a]">Que faire si ma demande est rejetée ?</p>
                                <p>Vous recevrez un email expliquant les raisons. Vous pourrez corriger vos documents et réessayer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-900">
                <p class="font-medium mb-2">💡 Conseil utile</p>
                <p>Pendant que vous attendez l'approbation, préparez vos produits et catégories. Une fois approuvé, vous pourrez commencer à vendre immédiatement !</p>
            </div>
        </div>
    </div>
</body>
</html>
