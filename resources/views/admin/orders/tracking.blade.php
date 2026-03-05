@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.orders.delivery-overview') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour aux livraisons
        </a>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-cube class="w-8 h-8" /><span>Suivi de la Commande #{{ $commande->id }}</span></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations de la commande (gauche) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Détails de la Commande</span></h2>
                
                <div class="space-y-4">
                    <!-- Client -->
                    <div>
                        <p class="text-sm text-gray-600 font-medium">CLIENT</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $commande->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $commande->user->email }}</p>
                    </div>

                    <!-- Montant -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 font-medium">MONTANT TOTAL</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ number_format($commande->total, 0, ',', ' ') }} XOF
                        </p>
                    </div>

                    <!-- Statut commande -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 font-medium">STATUT COMMANDE</p>
                        <div class="mt-2">
                            @if($commande->statut === 'en_attente')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">⏳ En attente</span>
                            @elseif($commande->statut === 'en_cours')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">🔄 En cours</span>
                            @elseif($commande->statut === 'prete')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">✅ Prête</span>
                            @elseif($commande->statut === 'cancelled')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">❌ Annulée</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ $commande->statut }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Statut livraison -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 font-medium">STATUT LIVRAISON</p>
                        <div class="mt-2">
                            @if($commande->delivery_status === 'pending')
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">⏳ En attente</span>
                            @elseif($commande->delivery_status === 'picked_up')
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">📦 Enlevée</span>
                            @elseif($commande->delivery_status === 'in_transit')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">🚚 En transit</span>
                            @elseif($commande->delivery_status === 'delivered')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✅ Livrée</span>
                            @elseif($commande->delivery_status === 'failed')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">❌ Échouée</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ $commande->delivery_status }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Livraison attendue -->
                    @if($commande->expected_delivery_date)
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600 font-medium">LIVRAISON PRÉVUE</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($commande->expected_delivery_date)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @endif

                    <!-- Adresse -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 font-medium">ADRESSE DE LIVRAISON</p>
                        <p class="text-sm text-gray-900 leading-relaxed">
                            {{ $commande->adresse_detail ?? $commande->adresse_livraison ?? 'Non spécifiée' }}
                        </p>
                        @if($commande->telephone_livraison)
                            <p class="text-sm text-gray-600 mt-2">📞 {{ $commande->telephone_livraison }}</p>
                        @endif
                    </div>

                    <!-- Date de commande -->
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 font-medium">DATE DE COMMANDE</p>
                        <p class="text-sm text-gray-900">
                            {{ $commande->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique de suivi (droite) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">🕐 Historique de Suivi</h2>

                @if($tracking->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">❌ Aucun événement de suivi enregistré</p>
                        <p class="text-gray-400 text-sm mt-2">Les informations de livraison s'afficheront ici une fois que le colis sera en transit.</p>
                        
                        <!-- Message si table n'existe pas -->
                        @if(!\Illuminate\Support\Facades\Schema::hasTable('delivery_trackings'))
                            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                <p class="text-amber-800 text-sm">
                                    <strong>⚠️ Note:</strong> La table de suivi n'existe pas encore. Exécutez les migrations avec:
                                </p>
                                <code class="block mt-2 text-xs bg-amber-100 p-2 rounded text-amber-900">
                                    php artisan migrate
                                </code>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($tracking as $event)
                            <div class="flex gap-4">
                                <!-- Timeline dot -->
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-2 flex-shrink-0">
                                        @if($event->status === 'picked_up')
                                            <span class="text-lg">📦</span>
                                        @elseif($event->status === 'in_transit')
                                            <span class="text-lg">🚚</span>
                                        @elseif($event->status === 'delivered')
                                            <span class="text-lg">✅</span>
                                        @elseif($event->status === 'failed')
                                            <span class="text-lg">❌</span>
                                        @else
                                            <span class="text-lg">📍</span>
                                        @endif
                                    </div>
                                    @if(!$loop->last)
                                        <div class="w-1 h-12 bg-gray-200"></div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="pb-2 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-gray-900">
                                                @if($event->status === 'picked_up')
                                                    Colis enlevé
                                                @elseif($event->status === 'in_transit')
                                                    En transit
                                                @elseif($event->status === 'delivered')
                                                    Livré
                                                @elseif($event->status === 'failed')
                                                    Livraison échouée
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $event->status)) }}
                                                @endif
                                            </p>
                                            @if($event->notes)
                                                <p class="text-sm text-gray-600 mt-1">{{ $event->notes }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                            {{ $event->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    @if($event->latitude && $event->longitude)
                                        <p class="text-xs text-gray-500 mt-2">
                                            📍 {{ $event->latitude }}, {{ $event->longitude }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Actions admin -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Actions Admin</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.orders.show', $commande) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition">
                        👁️ Voir Détails Complets
                    </a>
                    
                    @if($commande->statut !== 'cancelled')
                        <a href="{{ route('admin.orders.show', $commande) }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition">
                            ✏️ Modifier Commande
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.orders.index') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition">
                        ← Retour à la Liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
