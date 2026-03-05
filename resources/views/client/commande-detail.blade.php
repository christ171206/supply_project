@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Retour -->
        <a href="{{ route('client.commandes') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-8">
            <x-heroicon-o-arrow-left class="w-5 h-5" /><span>Retour aux commandes</span>
        </a>

        <!-- Header -->
        <div class="mb-12 bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">COMMANDE</p>
                    <h1 class="text-4xl font-bold text-gray-900 mt-2">N°{{ $commande->id }}</h1>
                    <p class="text-gray-600 mt-2">{{ $commande->created_at->format('d M Y à H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-2 rounded-full text-sm font-bold inline-block" style="
                        background-color: @switch($commande->statut)
                            @case('livree')#dbeafe @break
                            @case('expediee') #dbeafe @break
                            @case('confirmee') #fef3c7 @break
                            @case('annulee') #fee2e2 @break
                            @default #f3f4f6 @endswitch;
                        color: @switch($commande->statut)
                            @case('livree') #065f46 @break
                            @case('expediee') #1e40af @break
                            @case('confirmee') #92400e @break
                            @case('annulee') #991b1b @break
                            @default #111827 @endswitch;">
                        @switch($commande->statut)
                            @case('en_attente') <x-heroicon-o-clock class="w-4 h-4 inline mr-1" /><span>En attente</span> @break
                            @case('confirmee') <x-heroicon-o-check-circle class="w-4 h-4 inline mr-1" /><span>Confirmée</span> @break
                            @case('expediee') <x-heroicon-o-truck class="w-4 h-4 inline mr-1" /><span>Expédiée</span> @break
                            @case('livree') <x-heroicon-o-check-circle class="w-4 h-4 inline mr-1" /><span>Livrée</span> @break
                            @case('annulee') <x-heroicon-o-x-circle class="w-4 h-4 inline mr-1" /><span>Annulée</span> @break
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Suivi de la Commande - Stepper -->
        <div class="mb-12 bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2"><x-heroicon-o-map-pin class="w-6 h-6" /><span>Suivi de votre commande</span></h2>

            <div class="flex items-center justify-between relative">
                <!-- Ligne de progression -->
                <div class="absolute left-0 right-0 top-1/2 transform -translate-y-1/2 h-1 bg-gray-200"></div>

                @php
                    $steps = [
                        'en_attente' => ['label' => 'Validée', 'icon' => '✓', 'color' => 'yellow'],
                        'confirmee' => ['label' => 'Confirmée', 'icon' => '✓', 'color' => 'blue'],
                        'expediee' => ['label' => 'Expédiée', 'icon' => '�', 'color' => 'indigo'],
                        'livree' => ['label' => 'Livrée', 'color' => 'green']
                    ];

                    $statusOrder = ['en_attente', 'confirmee', 'expediee', 'livree'];
                    $currentIndex = array_search($commande->statut, $statusOrder);
                @endphp

                @foreach($statusOrder as $index => $status)
                    @php
                        $isActive = $index <= $currentIndex;
                        $isCurrent = $status === $commande->statut;
                        $step = $steps[$status];
                        $bgColor = $isActive ? 'bg-' . $step['color'] . '-500' : 'bg-gray-300';
                        $textColor = $isActive ? 'text-' . $step['color'] . '-600' : 'text-gray-600';
                    @endphp

                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold mb-3 {{ $bgColor }} transition-all duration-300 {{ $isCurrent ? 'ring-4 ring-' . $step['color'] . '-200 scale-125' : '' }}">
                            {{ $step['icon'] }}
                        </div>
                        <p class="text-xs font-semibold text-center {{ $textColor }} max-w-20">{{ $step['label'] }}</p>
                        @if($isCurrent)
                            <p class="text-xs text-indigo-600 font-bold mt-1">Actuellement ici</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Message de statut -->
            <div class="mt-8 p-4 rounded-lg {{ $commande->statut === 'livree' ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200' }}">
                <p class="text-sm font-semibold {{ $commande->statut === 'livree' ? 'text-green-700' : 'text-blue-700' }} flex items-start gap-2">
                    @switch($commande->statut)
                        @case('en_attente')
                            <x-heroicon-o-clock class="w-5 h-5 flex-shrink-0 mt-0.5" /><span>Votre commande est en cours de traitement. Elle sera confirmée très bientôt.</span>
                        @break
                        @case('confirmee')
                            <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" /><span>Votre commande a été confirmée et sera expédiée dans les prochaines 24h.</span>
                        @break
                        @case('expediee')
                            <x-heroicon-o-truck class="w-5 h-5 flex-shrink-0 mt-0.5" /><span>Votre commande est en route! Elle devrait arriver entre 2 et 5 jours.</span>
                        @break
                        @case('livree')
                            <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" /><span>Commande livrée avec succès! Merci de votre achat. Avez-vous apprécié ce produit? Laissez un avis!</span>
                        @break
                        @default
                            <x-heroicon-o-information-circle class="w-5 h-5 flex-shrink-0 mt-0.5" /><span>Statut: {{ $commande->statut }}</span>
                    @endswitch
                </p>
            </div>
        </div>

        <!-- Grille Principale -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Colonne Gauche - Infos et Articles -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Infos Commande -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-lg">
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Informations de la Commande</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Date de Commande</p>
                            <p class="text-lg font-bold text-gray-900 mt-2">{{ $commande->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Mode de Paiement</p>
                            <p class="text-lg font-bold text-gray-900 mt-2">
                                @if($commande->mode_paiement == 'mobile_money') <x-heroicon-o-device-phone-mobile class="w-4 h-4 inline mr-1" /><span>Mobile Money</span>
                                @elseif($commande->mode_paiement == 'carte_bancaire') <x-heroicon-o-credit-card class="w-4 h-4 inline mr-1" /><span>Carte Bancaire</span>
                                @else <x-heroicon-o-truck class="w-4 h-4 inline mr-1" /><span>À la livraison</span> @endif
                            </p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Statut Paiement</p>
                            <p class="text-lg font-bold mt-2 flex items-center gap-2">
                                @if($commande->paiement_confirme)
                                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" /><span class="text-green-600">Payé</span>
                                @else
                                    <x-heroicon-o-clock class="w-4 h-4" /><span class="text-orange-600 flex items-center gap-1">En attente</span>
                                @endif
                            </p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-semibold">Montant Total</p>
                            <p class="text-lg font-bold text-green-600 mt-2">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Articles -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-lg">
                            <x-heroicon-o-cube class="w-5 h-5" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Articles Commandés</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Produit</th>
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Vendeur</th>
                                    <th class="text-center py-3 px-4 font-bold text-gray-700">Quantité</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">P.U.</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commande->ligneCommandes as $ligne)
                                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition">
                                        <td class="py-4 px-4">
                                            <a href="{{ route('produits.show', $ligne->produit_id) }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                                                {{ $ligne->produit->nom ?? 'Produit supprimé' }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($ligne->produit && $ligne->produit->vendeur)
                                                <span class="text-sm text-gray-600 flex items-center gap-1">
                                                    <x-heroicon-o-building-storefront class="w-4 h-4" />
                                                    {{ $ligne->produit->vendeur->name }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center font-semibold">{{ $ligne->quantite }}</td>
                                        <td class="py-4 px-4 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                        <td class="py-4 px-4 text-right font-bold text-green-600">{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">Aucun article</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Adresse Livraison -->
                @if($commande->adresse_livraison)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-lg">
                                <x-heroicon-o-home class="w-5 h-5" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Adresse de Livraison</h3>
                        </div>
                        <p class="text-gray-700 whitespace-pre-wrap bg-orange-50 p-4 rounded-lg">{{ $commande->adresse_livraison }}</p>
                    </div>
                @endif
            </div>

            <!-- Colonne Droite - Résumé -->
            <div class="lg:col-span-1">
                <!-- Résumé Paiement -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Résumé Financier</h3>

                    <div class="space-y-4 pb-6 border-b border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($commande->total * 0.85, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de port:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($commande->total * 0.15, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div class="pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-3xl font-bold text-green-600">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('commandes.download-pdf', $commande->id) }}" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 px-4 rounded-lg font-bold transition">
                            <x-heroicon-o-document-arrow-down class="w-5 h-5" /><span>Télécharger la Facture</span>
                        </a>
                    </div>

                    @php
                        $secondesEcoulees = now()->diffInSeconds($commande->created_at);
                        $minutesEcoulees = $secondesEcoulees / 60;
                        $minutesRestantes = 10 - $minutesEcoulees;
                        $peutAnnuler = $commande->statut === 'en_attente' && $minutesEcoulees < 10;
                    @endphp

                    @if($peutAnnuler)
                        <div class="mt-4">
                            <form action="{{ route('client.commande.cancel', $commande->id) }}" method="POST" class="w-full"
                                  data-confirm="Êtes-vous sûr de vouloir annuler cette commande?"
                                  data-confirm-title="Annuler la commande"
                                  data-confirm-type="warning"
                                  data-confirm-button="Annuler">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg font-bold transition">
                                    <x-heroicon-o-no-symbol class="w-5 h-5" /><span>Annuler la commande</span>
                                </button>
                            </form>
                            <p class="text-xs text-red-600 mt-2 text-center flex items-center justify-center gap-1"><x-heroicon-o-clock class="w-3 h-3" /><span>Vous avez {{ round($minutesRestantes, 1) }} minute(s) pour annuler</span></p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <p class="text-blue-900 text-sm font-semibold flex items-center gap-2"><x-heroicon-o-light-bulb class="w-5 h-5" /><span>Besoin d'aide?</span></p>
                    <a href="{{ route('client.messages') }}" class="mt-4 block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-bold transition flex items-center justify-center gap-2">
                        <x-heroicon-o-chat-bubble-left class="w-5 h-5" /><span>Contacter le vendeur</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
