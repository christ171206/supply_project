@extends('layouts.admin-layout')

@section('title', 'Utilisateur - ' . $user->name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-2 inline-block">← Retour aux utilisateurs</a>
            <h1 class="text-4xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-500 mt-2">{{ ucfirst($user->role) }} • Créé le {{ $user->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="text-right">
            @if($user->is_banned)
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">🚫 BANNI</span>
            @elseif($user->role === 'vendor' && $user->vendor_status === 'pending')
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">⏳ En attente d'approbation</span>
            @elseif($user->email_verified_at)
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">✓ Vérifié</span>
            @else
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-800">⏳ Non vérifié</span>
            @endif
        </div>
    </div>

    <!-- User Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">👤 Informations Personnelles</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</label>
                    <p class="text-gray-900 mt-1">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <p class="text-xs text-green-600 font-semibold mt-1">✓ Vérifié le {{ $user->email_verified_at->format('d/m/Y') }}</p>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Pays</label>
                    <p class="text-gray-900 mt-1">{{ $user->country ?? 'Non spécifié' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Téléphone</label>
                    <p class="text-gray-900 mt-1">{{ $user->phone ?? 'Non spécifié' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Adresse</label>
                    <p class="text-gray-900 mt-1">{{ $user->address ?? 'Non spécifié' }}</p>
                </div>
            </div>
        </div>

        <!-- Role & Status -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">🏷️ Rôle & Statut</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Rôle</label>
                    <div class="mt-2">
                        @if($user->role === 'vendor')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">👨‍💼 Vendeur</span>
                        @elseif($user->role === 'admin')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-purple-100 text-purple-800">👑 Admin</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800">👤 Client</span>
                        @endif
                    </div>
                </div>

                @if($user->role === 'vendor')
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Statut Vendeur</label>
                        <p class="text-gray-900 mt-1">
                            @if($user->vendor_status === 'pending')
                                <span class="text-yellow-700 font-semibold">⏳ En attente d'approbation</span>
                            @elseif($user->vendor_status === 'approved')
                                <span class="text-green-700 font-semibold">✓ Approuvé</span>
                            @elseif($user->vendor_status === 'rejected')
                                <span class="text-red-700 font-semibold">✗ Rejeté</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Nom de Boutique</label>
                        <p class="text-gray-900 mt-1">{{ $user->shop_name ?? 'Non défini' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Téléphone Boutique</label>
                        <p class="text-gray-900 mt-1">{{ $user->boutique_telephone ?? 'Non défini' }}</p>
                    </div>
                @endif

                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Statut Bannissement</label>
                    <div class="mt-2">
                        @if($user->is_banned)
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">🚫 BANNI</span>
                            @if($user->banned_until)
                                <p class="text-xs text-red-600 mt-1">Jusqu'au {{ $user->banned_until->format('d/m/Y') }}</p>
                            @else
                                <p class="text-xs text-red-600 mt-1">Banni indéfiniment</p>
                            @endif
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">✓ Non banni</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Description (if vendor) -->
    @if($user->role === 'vendor')
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Description de Boutique</h2>
            <p class="text-gray-700 leading-relaxed">{{ $user->boutique_description ?? 'Aucune description fournie' }}</p>
        </div>
    @endif

    <!-- Documents (for vendors) -->
    @if($user->role === 'vendor' && $user->documents && $user->documents->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">📄 Documents KYC</h2>
            <div class="space-y-4">
                @foreach($user->documents as $document)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition">
                        <div>
                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                            <p class="text-sm text-gray-600">Créé le {{ $document->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($document->status === 'verified')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">✓ Vérifié</span>
                            @elseif($document->status === 'pending')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">⏳ En attente</span>
                            @elseif($document->status === 'rejected')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">✗ Rejeté</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Products (for vendors) -->
    @if($user->role === 'vendor' && $user->produits && $user->produits->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-cube class="w-6 h-6" /><span>Produits ({{ $user->produits->count() }})</span></h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Produit</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Prix</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Stock</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Créé le</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->produits as $product)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="py-4 px-4">
                                    <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">{{ $product->nom }}</a>
                                </td>
                                <td class="py-4 px-4 text-gray-700">{{ number_format($product->prix, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $product->stock < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $product->stock }} u.
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $product->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-bold text-sm">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Orders (for customers) -->
    @if($user->role === 'customer' && $user->commandes && $user->commandes->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-clipboard class="w-6 h-6" /><span>Commandes ({{ $user->commandes->count() }})</span></h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">N° Commande</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Date</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->commandes as $order)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="py-4 px-4 font-bold text-blue-600">#{{ $order->id }}</td>
                                <td class="py-4 px-4 font-semibold text-green-600">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($order->statut === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->statut === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->statut === 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->statut === 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-bold text-sm">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Disputes -->
    @if($user->disputes && $user->disputes->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-exclamation-triangle class="w-6 h-6" /><span>Litiges ({{ $user->disputes->count() }})</span></h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Titre</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Statut</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Date</th>
                            <th class="text-center py-3 px-4 font-bold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->disputes as $dispute)
                            <tr class="border-b hover:bg-red-50 transition">
                                <td class="py-4 px-4 font-semibold text-gray-900">{{ $dispute->titre }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($dispute->status === 'open') bg-red-100 text-red-800
                                        @elseif($dispute->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($dispute->status === 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $dispute->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="text-red-600 hover:text-red-700 font-bold text-sm">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">⚙️ Actions</h2>
        <div class="flex flex-wrap gap-4">
            @if(!$user->is_banned)
                <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" style="display: inline;"
                      data-confirm="Êtes-vous sûr de vouloir bannir cet utilisateur ?"
                      data-confirm-title="Bannir l'utilisateur"
                      data-confirm-type="danger"
                      data-confirm-button="Bannir">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                        🚫 Bannir cet utilisateur
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.unban', $user->id) }}" style="display: inline;"
                      data-confirm="Êtes-vous sûr de vouloir débannir cet utilisateur ?"
                      data-confirm-title="Débannir l'utilisateur"
                      data-confirm-type="success"
                      data-confirm-button="Débannir">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                        ✓ Débannir cet utilisateur
                    </button>
                </form>
            @endif

            @if($user->role === 'vendor' && $user->vendor_status === 'pending')
                <form method="POST" action="{{ route('admin.users.approve-vendor', $user) }}" style="display: inline;"
                      data-confirm="Approuver ce vendeur ?"
                      data-confirm-title="Approuver le vendeur"
                      data-confirm-type="success"
                      data-confirm-button="Approuver">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                        ✓ Approuver le vendeur
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.reject-vendor', $user) }}" style="display: inline;"
                      data-confirm="Rejeter ce vendeur ?"
                      data-confirm-title="Rejeter le vendeur"
                      data-confirm-type="danger"
                      data-confirm-button="Rejeter">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold transition">
                        ✗ Rejeter le vendeur
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
