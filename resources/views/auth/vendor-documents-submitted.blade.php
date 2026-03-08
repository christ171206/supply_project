<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande soumise - Supply</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-accent-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- En-tête avec succès -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-8 py-16 text-center">
                    <!-- Icône d'animation -->
                    <div class="mb-6 flex justify-center">
                        <div class="relative w-24 h-24">
                            <div class="absolute inset-0 bg-white/20 rounded-full animate-pulse"></div>
                            <div class="relative w-full h-full rounded-full bg-white/10 border-2 border-white/30 flex items-center justify-center">
                                <div class="text-6xl animate-bounce">✓</div>
                            </div>
                        </div>
                    </div>

                    <!-- Titre et sous-titre -->
                    <h1 class="text-4xl font-bold text-white mb-2">Excellent !</h1>
                    <p class="text-white/90 text-lg font-medium">Vos documents d'identité ont été reçus avec succès</p>
                </div>

                <!-- Contenu principal -->
                <div class="px-8 py-12 md:px-12 md:py-14">
                    <!-- Message principal -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-8 mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Prochaines étapes
                        </h2>
                        <div class="space-y-4 text-gray-700">
                            <p class="font-medium">
                                Vos documents ont été soumis pour vérification. Voici ce qui va se passer :
                            </p>
                            <ol class="space-y-3 ml-4">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-200 text-green-700 font-bold flex items-center justify-center text-sm">1</span>
                                    <span><strong>Vérification rapide</strong> - Notre équipe examinera vos documents d'identité dans les 24 heures (en jours ouvrés)</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-200 text-green-700 font-bold flex items-center justify-center text-sm">2</span>
                                    <span><strong>Confirmation par email</strong> - Vous recevrez un email à <strong class="text-primary-600">{{ $user->email }}</strong> dès que votre compte sera activé</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-200 text-green-700 font-bold flex items-center justify-center text-sm">3</span>
                                    <span><strong>Accès à votre tableau de bord</strong> - Commencez à ajouter vos produits, gérer vos stocks et accepter les commandes</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Informations importantes -->
                    <div class="space-y-4 mb-10">
                        <!-- Délai estimé -->
                        <div class="flex gap-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex-shrink-0 text-2xl">⏱️</div>
                            <div>
                                <p class="font-bold text-blue-900">Délai estimé</p>
                                <p class="text-blue-800 text-sm mt-1">
                                    Votre compte sera généralement approuvé dans les <strong>24-48 heures</strong>. Vous serez notifié par email dès que c'est fait.
                                </p>
                            </div>
                        </div>

                        <!-- Données utilisateur -->
                        <div class="flex gap-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                            <div class="flex-shrink-0 text-2xl">👤</div>
                            <div>
                                <p class="font-bold text-purple-900">Vérification des données</p>
                                <p class="text-purple-800 text-sm mt-1">
                                    <strong>Nom :</strong> {{ $user->name }}<br>
                                    <strong>Boutique :</strong> {{ $user->shop_name }}<br>
                                    <strong>Téléphone :</strong> {{ $user->phone }}<br>
                                    <strong>Lieu :</strong> {{ $user->address }}
                                </p>
                            </div>
                        </div>

                        <!-- Sécurité et confidentialité -->
                        <div class="flex gap-4 p-4 bg-cyan-50 border border-cyan-200 rounded-lg">
                            <div class="flex-shrink-0 text-2xl">🔒</div>
                            <div>
                                <p class="font-bold text-cyan-900">Sécurité et confidentialité</p>
                                <p class="text-cyan-800 text-sm mt-1">
                                    Vos documents d'identité sont cryptés et stockés en toute sécurité. Ils ne seront jamais partagés avec des tiers et seront utilisés uniquement à des fins de vérification.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Conseils utiles -->
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg mb-10">
                        <p class="font-bold text-amber-900 mb-2">💡 Conseil utile</p>
                        <ul class="text-sm text-amber-800 space-y-1">
                            <li>✓ Conservez la confirmation de cette soumission pour référence</li>
                            <li>✓ Vérifiez votre dossier spam si vous ne recevez pas l'email de confirmation</li>
                            <li>✓ Vous pouvez continuer à explorer Supply en attendant l'approbation</li>
                            <li>✓ Si vous avez des questions, contactez notre support</li>
                        </ul>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a
                            href="{{ route('accueil') }}"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary-500/50 transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l-7-4m0 0l-2-3m2 3v-10a1 1 0 011-1h12a1 1 0 011 1v10m-9 0l7 4m-7-4l-2 3"/>
                            </svg>
                            Retour à l'accueil
                        </a>
                        <a
                            href="mailto:support@supply.ci"
                            class="flex-1 px-6 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contacter le support
                        </a>
                    </div>

                    <!-- Note finale -->
                    <div class="mt-10 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                        <p class="text-sm text-gray-700">
                            <strong>Numéro de suivi :</strong> <code class="text-primary-600 font-bold">{{ strtoupper(substr($user->id, 0, 8)) }}-{{ date('Ymd') }}</code>
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            Conservez ce numéro pour toute correspondance avec notre support
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lien retour discret -->
            <div class="mt-8 text-center">
                <a href="{{ route('accueil') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 font-semibold transition py-2 px-4 rounded-lg hover:bg-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour à la boutique
                </a>
            </div>
        </div>
    </div>
</body>
</html>
