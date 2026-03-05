@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-white min-h-screen">
    <!-- En-tête avec retour -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('vendeur.messages') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold mb-4">
                ← Retour aux messages
            </a>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
                    <p class="text-gray-600">{{ $client->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded-lg mb-6" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Produit associé -->
    @if($produit)
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Produit discuté</span></h3>
            <div class="flex gap-4">
                @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                    <img src="{{ asset('storage/produits/' . $produit->images[0]) }}" alt="{{ $produit->nom }}" class="w-24 h-24 object-cover rounded-lg">
                @elseif($produit->image)
                    <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-24 h-24 object-cover rounded-lg">
                @else
                    <div class="w-24 h-24 bg-gray-300 rounded-lg flex items-center justify-center text-gray-600">
                        📷
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">{{ $produit->nom }}</h4>
                    <p class="text-gray-700 line-clamp-2">{{ $produit->description }}</p>
                    <div class="flex gap-4 mt-3">
                        <div>
                            <p class="text-xs text-gray-600">Prix</p>
                            <p class="text-2xl font-bold text-primary-600">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Stock</p>
                            <p class="text-lg font-bold {{ $produit->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $produit->stock > 0 ? $produit->stock . ' unité(s)' : 'Rupture' }}
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('produits.show', $produit->slug) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold h-fit whitespace-nowrap">
                    Voir produit
                </a>
            </div>
        </div>
    @endif

    <!-- Zone de conversation -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <!-- Messages -->
        <div class="h-[600px] overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-white to-gray-50" id="messagesContainer">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-2xl">
                        <!-- Message avec produit (si applicable) -->
                        <div class="flex gap-3 {{ $msg->from_user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <!-- Produit associé -->
                            @if($msg->produit)
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if($msg->produit->images && is_array($msg->produit->images) && count($msg->produit->images) > 0)
                                        <img src="{{ asset('storage/produits/' . $msg->produit->images[0]) }}" alt="{{ $msg->produit->nom }}" class="w-full h-full object-cover rounded-lg border-2 {{ $msg->from_user_id === auth()->id() ? 'border-primary-400' : 'border-gray-300' }}">
                                    @elseif($msg->produit->image)
                                        <img src="{{ asset('storage/produits/' . $msg->produit->image) }}" alt="{{ $msg->produit->nom }}" class="w-full h-full object-cover rounded-lg border-2 {{ $msg->from_user_id === auth()->id() ? 'border-primary-400' : 'border-gray-300' }}">
                                    @else
                                        <div class="w-full h-full bg-gray-300 rounded-lg flex items-center justify-center text-gray-600 text-xs font-bold">
                                            📷
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-600 mt-1 text-center line-clamp-2">{{ $msg->produit->nom }}</p>
                                </div>
                            @endif

                            <!-- Bulle de message -->
                            <div class="flex-1">
                                <div class="{{ $msg->from_user_id === auth()->id() ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-200 text-gray-900 rounded-3xl rounded-tl-none' }} p-4 shadow-md">
                                    <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                                    <p class="text-xs mt-2 {{ $msg->from_user_id === auth()->id() ? 'text-primary-100' : 'text-gray-600' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </p>

                                    @if($msg->from_user_id === auth()->id())
                                        <form action="{{ route('vendeur.messages.delete', $msg->id) }}" method="POST" class="mt-2"
                                              data-confirm="Êtes-vous sûr de vouloir supprimer ce message ?"
                                              data-confirm-title="Supprimer le message"
                                              data-confirm-type="danger"
                                              data-confirm-button="Supprimer">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-primary-100 hover:text-white underline">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-6xl mb-4 flex justify-center"><x-heroicon-o-chat-bubble-left class="w-16 h-16" /></div>
                    <p class="text-gray-600">Aucun message encore. Commencez une conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Formulaire d'envoi -->
        <div class="border-t border-gray-200 p-6 bg-gray-50">
            <form action="{{ route('vendeur.messages.send', $client->id) }}" method="POST" class="flex gap-3">
                @csrf
                <textarea
                    name="contenu"
                    placeholder="Écrivez votre message..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                    rows="3"
                    required
                ></textarea>
                <button
                    type="submit"
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold whitespace-nowrap h-fit flex items-center gap-2"
                >
                    <x-heroicon-o-paper-airplane class="w-5 h-5" /><span>Envoyer</span>
                </button>
            </form>
            @error('contenu')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Infos client -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Infos Client</span></h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Nom</p>
                    <p class="text-gray-900">{{ $client->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Email</p>
                    <p class="text-gray-900">{{ $client->email }}</p>
                </div>
                @if($client->phone)
                    <div>
                        <p class="text-xs text-gray-600 font-semibold">Téléphone</p>
                        <p class="text-gray-900">{{ $client->phone }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Inscrit depuis</p>
                    <p class="text-gray-900">{{ $client->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 md:col-span-2">
            <h3 class=\"text-lg font-bold text-gray-900 mb-4 flex items-center gap-2\"><x-heroicon-o-shopping-cart class=\"w-5 h-5\" /><span>Commandes de ce Client</span></h3>

            @if(isset($commandes) && $commandes->count() > 0)
                <div class="space-y-3">
                    @foreach($commandes as $cmd)
                        <a href="{{ route('vendeur.commandes.show', $cmd->id) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition border-l-4 border-primary-600">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">Commande #{{ $cmd->id }}</p>
                                    <p class="text-xs text-gray-600">{{ $cmd->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-primary-600">{{ number_format($cmd->total, 0, ',', ' ') }} CFA</p>
                                    <p class="text-xs font-semibold
                                        {{ $cmd->statut === 'livree' ? 'text-green-600' : ($cmd->statut === 'expediee' ? 'text-blue-600' : ($cmd->statut === 'confirmee' ? 'text-yellow-600' : 'text-red-600')) }}
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $cmd->statut)) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-600 mb-2 flex items-center justify-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Aucune commande en commun</span></p>
                    <p class="text-xs text-gray-500">Ce client n'a pas de commande contenant vos produits</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-scroll vers le bas
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection
