@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.disputes.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Retour aux litiges
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">⚖️ Litige #{{ $dispute->id }}</h1>
                <p class="text-gray-600 mt-2">{{ $dispute->subject ?? 'Détails du litige' }}</p>
            </div>
            <span class="px-4 py-2 @if($dispute->status === 'open') bg-red-100 text-red-800 @elseif($dispute->status === 'in_progress') bg-yellow-100 text-yellow-800 @elseif($dispute->status === 'resolved') bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif rounded-full font-semibold">
                {{ ucfirst($dispute->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-clipboard class="w-5 h-5" /><span>Description</span></h2>
                <p class="text-gray-700 leading-relaxed">{{ $dispute->description ?? 'Aucune description' }}</p>
            </div>

            <!-- Participants -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-user-group class="w-5 h-5" /><span>Participants</span></h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <!-- Requester -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium mb-3">DEMANDEUR</p>
                        <div class="space-y-2">
                            <p class="font-bold text-gray-900">{{ $dispute->requester->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $dispute->requester->email ?? 'N/A' }}</p>
                            @if($dispute->requester->phone)
                                <p class="text-sm text-gray-600">{{ $dispute->requester->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Respondent -->
                    <div class="p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium mb-3">DÉFENDEUR</p>
                        <div class="space-y-2">
                            <p class="font-bold text-gray-900">{{ $dispute->respondent->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $dispute->respondent->email ?? 'N/A' }}</p>
                            @if($dispute->respondent->phone)
                                <p class="text-sm text-gray-600">{{ $dispute->respondent->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Order -->
            @if($dispute->commande)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-cube class="w-5 h-5" /><span>Commande Associée</span></h2>
                    
                    <div class="p-4 bg-gray-50 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID Commande:</span>
                            <span class="font-bold text-gray-900">#{{ $dispute->commande->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Montant:</span>
                            <span class="font-bold text-green-600">{{ number_format($dispute->commande->total, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut:</span>
                            <span class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $dispute->commande->statut)) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="text-gray-600">Créée le:</span>
                            <span class="text-gray-900">{{ $dispute->commande->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.orders.show', $dispute->commande->id) }}" class="mt-4 block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Voir la Commande Complète →
                    </a>
                </div>
            @endif

            <!-- Admin Notes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📝 Notes Admin</h2>
                <div class="p-4 bg-blue-50 rounded-lg text-gray-900 whitespace-pre-wrap">
                    {{ $dispute->admin_notes ?? 'Aucune note' }}
                </div>
            </div>
        </div>

        <!-- Right Section - Actions -->
        <div class="space-y-6">
            <!-- Dispute Info Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-purple-900 mb-4">ℹ️ Informations</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-purple-700 font-medium">Montant en Litige</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ number_format($dispute->resolution_amount ?? 0, 0, ',', ' ') }} XOF
                        </p>
                    </div>
                    <div class="border-t border-purple-200 pt-3">
                        <p class="text-purple-700 font-medium">Créé le</p>
                        <p class="text-purple-900 mt-1">{{ $dispute->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="border-t border-purple-200 pt-3">
                        <p class="text-purple-700 font-medium">Dernier Update</p>
                        <p class="text-purple-900 mt-1">{{ $dispute->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            @if($dispute->status !== 'closed')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Changer le Statut</h3>
                    
                    <form method="POST" action="{{ route('admin.disputes.update-status', $dispute->id) }}" class="space-y-3">
                        @csrf
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="open" @selected($dispute->status === 'open')>🔴 Ouvert</option>
                            <option value="in_progress" @selected($dispute->status === 'in_progress')>🟡 En cours</option>
                            <option value="resolved" @selected($dispute->status === 'resolved')>🔵 Résolu</option>
                            <option value="closed" @selected($dispute->status === 'closed')>⚪ Fermé</option>
                        </select>
                        <textarea name="notes" placeholder="Ajouter une note..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 h-24"></textarea>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Mettre à jour
                        </button>
                    </form>
                </div>

                <!-- Resolution Actions -->
                @if($dispute->status === 'in_progress')
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-check-circle class="w-5 h-5" /><span>Résoudre le Litige</span></h3>
                        
                        <form method="POST" action="{{ route('admin.disputes.resolve', $dispute->id) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Résolution</label>
                                <select name="resolution" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sélectionner...</option>
                                    <option value="refund">Remboursement complet</option>
                                    <option value="partial_refund">🟠 Remboursement partiel</option>
                                    <option value="replacement">Remplacement</option>
                                    <option value="no_action">⏭️ Aucune action</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Marquer Résolu
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            @if($dispute->status === 'resolved')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Close Litige</h3>
                    
                    <form method="POST" action="{{ route('admin.disputes.close', $dispute->id) }}">
                        @csrf
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Fermer le Litige
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
