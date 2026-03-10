@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- En-tête avec retour -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('vendeur.messages') }}" class="inline-flex items-center gap-2 text-[#0a0a0a] hover:text-[#666660] font-medium mb-4 text-sm">
                ← Retour
            </a>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-[#0a0a0a] rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[#0a0a0a]" style="font-family: 'Instrument Serif', serif;">{{ $client->name }}</h1>
                    <p class="text-[#a0a09a] text-sm">{{ $client->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if($message = Session::get('success'))
        <div class="bg-[#f0fdf4] border border-[#15803d] text-[#15803d] px-4 py-4 rounded-lg mb-6 text-sm" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Produit associé -->
    @if($produit)
        <div class="mb-8 bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc]">
            <h3 class="text-sm font-bold text-[#0a0a0a] mb-4 uppercase tracking-[0.05em]">Produit discuté</h3>
            <div class="flex gap-4">
                @if($produit->images && is_array($produit->images) && count($produit->images) > 0)
                    <img src="{{ asset('storage/produits/' . $produit->images[0]) }}" alt="{{ $produit->nom }}" class="w-24 h-24 object-cover rounded-lg border border-[#e0e0dc]">
                @elseif($produit->image)
                    <img src="{{ asset('storage/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-24 h-24 object-cover rounded-lg border border-[#e0e0dc]">
                @else
                    <div class="w-24 h-24 bg-[#e0e0dc] rounded-lg flex items-center justify-center text-[#666660] text-xs font-bold">
                        [Image]
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="text-base font-bold text-[#0a0a0a]">{{ $produit->nom }}</h4>
                    <p class="text-[#666660] line-clamp-2 text-sm">{{ $produit->description }}</p>
                    <div class="flex gap-4 mt-3">
                        <div>
                            <p class="text-xs text-[#a0a09a] font-semibold">Prix</p>
                            <p class="text-lg font-bold text-[#0a0a0a]" style="font-family: 'Geist Mono', monospace;">{{ number_format($produit->prix, 0, ',', ' ') }} F</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#a0a09a] font-semibold">Stock</p>
                            <p class="text-base font-bold {{ $produit->stock > 0 ? 'text-[#15803d]' : 'text-[#dc2626]' }}">
                                {{ $produit->stock > 0 ? $produit->stock . ' unité(s)' : 'Rupture' }}
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('produits.show', $produit->slug) }}" class="px-4 py-2 bg-[#0a0a0a] text-white rounded-lg hover:bg-[#333] transition font-semibold h-fit whitespace-nowrap text-sm">
                    Voir produit
                </a>
            </div>
        </div>
    @endif

    <!-- Zone de conversation -->
    <div class="bg-white rounded-lg border border-[#e0e0dc] overflow-hidden mb-8">
        <!-- Messages -->
        <div class="h-[600px] overflow-y-auto p-6 space-y-4 bg-white" id="messagesContainer">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->from_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-2xl">
                        <!-- Message avec produit (si applicable) -->
                        <div class="flex gap-3 {{ $msg->from_user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <!-- Produit associé -->
                            @if($msg->produit)
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if($msg->produit->images && is_array($msg->produit->images) && count($msg->produit->images) > 0)
                                        <img src="{{ asset('storage/produits/' . $msg->produit->images[0]) }}" alt="{{ $msg->produit->nom }}" class="w-full h-full object-cover rounded-lg border border-[#e0e0dc]">
                                    @elseif($msg->produit->image)
                                        <img src="{{ asset('storage/produits/' . $msg->produit->image) }}" alt="{{ $msg->produit->nom }}" class="w-full h-full object-cover rounded-lg border border-[#e0e0dc]">
                                    @else
                                        <div class="w-full h-full bg-[#e0e0dc] rounded-lg flex items-center justify-center text-[#666660] text-xs font-bold">
                                            [img]
                                        </div>
                                    @endif
                                    <p class="text-xs text-[#a0a09a] mt-1 text-center line-clamp-2">{{ $msg->produit->nom }}</p>
                                </div>
                            @endif

                            <!-- Bulle de message -->
                            <div class="flex-1">
                                <div class="{{ $msg->from_user_id === auth()->id() ? 'bg-[#0a0a0a] text-white rounded-2xl rounded-tr-none' : 'bg-[#f7f7f5] text-[#0a0a0a] rounded-2xl rounded-tl-none' }} p-4">
                                    <p class="text-sm leading-relaxed">{{ $msg->contenu }}</p>
                                    <p class="text-xs mt-2 text-[#a0a09a]">
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
                                            <button type="submit" class="text-xs text-white hover:opacity-70 underline">
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
                    <p class="text-[#a0a09a] text-sm">Aucun message encore. Commencez une conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Formulaire d'envoi -->
        <div class="border-t border-[#e0e0dc] p-6 bg-white">
            <form action="{{ route('vendeur.messages.send', $client->id) }}" method="POST" class="flex gap-3">
                @csrf
                <textarea
                    name="contenu"
                    placeholder="Écrivez votre message..."
                    class="flex-1 px-4 py-3 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] resize-none text-[#0a0a0a] text-sm placeholder-[#a0a09a]"
                    rows="3"
                    required
                    style="font-family: 'Geist', -apple-system, BlinkMacSystemFont, sans-serif;"
                ></textarea>
                <button
                    type="submit"
                    class="px-6 py-3 bg-[#0a0a0a] text-white rounded-lg hover:bg-[#333] transition font-semibold whitespace-nowrap h-fit text-sm"
                >
                    Envoyer
                </button>
            </form>
            @error('contenu')
                <p class="text-[#dc2626] text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Infos client -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc]">
            <h3 class="text-sm font-bold text-[#0a0a0a] mb-4 uppercase tracking-[0.05em]">Client</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Nom</p>
                    <p class="text-[#0a0a0a] text-sm">{{ $client->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Email</p>
                    <p class="text-[#0a0a0a] text-sm">{{ $client->email }}</p>
                </div>
                @if($client->phone)
                    <div>
                        <p class="text-xs text-[#a0a09a] font-semibold">Téléphone</p>
                        <p class="text-[#0a0a0a] text-sm">{{ $client->phone }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-[#a0a09a] font-semibold">Inscrit depuis</p>
                    <p class="text-[#0a0a0a] text-sm">{{ $client->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#f7f7f5] rounded-lg p-6 border border-[#e0e0dc] md:col-span-2">
            <h3 class="text-sm font-bold text-[#0a0a0a] mb-4 uppercase tracking-[0.05em]">Commandes</h3>

            @if(isset($commandes) && $commandes->count() > 0)
                <div class="space-y-3">
                    @foreach($commandes as $cmd)
                        <a href="{{ route('vendeur.commandes.show', $cmd->id) }}" class="block p-4 bg-white rounded-lg hover:bg-[#f7f7f5] transition border border-[#e0e0dc]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-[#0a0a0a] text-sm">Commande #{{ $cmd->id }}</p>
                                    <p class="text-xs text-[#a0a09a]">{{ $cmd->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-[#0a0a0a] text-sm" style="font-family: 'Geist Mono', monospace;">{{ number_format($cmd->total, 0, ',', ' ') }} F</p>
                                    <p class="text-xs font-semibold
                                        {{ $cmd->statut === 'livree' ? 'text-[#15803d]' : ($cmd->statut === 'expediee' ? 'text-[#0a0a0a]' : ($cmd->statut === 'confirmee' ? 'text-[#92400e]' : 'text-[#dc2626]')) }}
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $cmd->statut)) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-white rounded-lg border border-[#e0e0dc]">
                    <p class="text-[#a0a09a] mb-2 text-sm">Aucune commande en commun</p>
                    <p class="text-xs text-[#a0a09a]">Ce client n'a pas de commande contenant vos produits</p>
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
