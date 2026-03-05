@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-clipboard class="w-8 h-8" /><span>Détails du Log d'Audit</span></h1>
            <p class="text-gray-600 mt-2">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        <a href="{{ route('admin.audit.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ← Retour
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Information principale -->
        <div class="md:col-span-2 space-y-6">
            <!-- Événement -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Événement</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">
                            @if($log->event_type === 'login')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #dbeafe; color: #0c4a6e;">🔐 Connexion</span>
                            @elseif($log->event_type === 'logout')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #fecdd3; color: #7f1d1d;">🚪 Déconnexion</span>
                            @elseif($log->event_type === 'create')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #dcfce7; color: #166534;">➕ Création</span>
                            @elseif($log->event_type === 'update')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #fef08a; color: #713f12;">✏️ Modification</span>
                            @elseif($log->event_type === 'delete')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #fee2e2; color: #991b1b;">🗑️ Suppression</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #f3e8ff; color: #6b21a8;">{{ $log->event_type }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Statut</p>
                        <p class="text-lg font-bold mt-1">
                            @if($log->status === 'success')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #dcfce7; color: #166534;">✅ Succès</span>
                            @elseif($log->status === 'failed')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #fee2e2; color: #991b1b;">❌ Échec</span>
                            @elseif($log->status === 'warning')
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #fef3c7; color: #92400e;">⚠️ Attention</span>
                            @endif
                        </p>
                    </div>
                    @if($log->message)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Message</p>
                            <p class="text-gray-700 mt-1 p-3 bg-gray-50 rounded-lg">{{ $log->message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Utilisateur & Appareil -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Utilisateur & Appareil</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Utilisateur</p>
                            <p class="text-gray-900 mt-1 font-semibold">
                                @if($log->user)
                                    <a href="{{ route('admin.audit.by-admin', $log->user_id) }}" class="text-blue-600 hover:underline">
                                        {{ $log->user->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Appareil</p>
                            <p class="text-gray-900 mt-1">{{ $log->device_type ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Navigateur</p>
                            <p class="text-gray-900 mt-1">{{ $log->browser ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Plateforme</p>
                            <p class="text-gray-900 mt-1">{{ $log->platform ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Géolocalisation & IP -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🌍 Géolocalisation & Adresse IP</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">IP</p>
                            <p class="text-gray-900 mt-1 font-mono bg-gray-50 p-2 rounded border">{{ $log->ip_address }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Pays</p>
                            <p class="text-gray-900 mt-1">{{ $log->country ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Ville</p>
                            <p class="text-gray-900 mt-1">{{ $log->city ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow p-6">
                <h3 class="text-sm font-bold text-blue-900 mb-3">📋 Résumé</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-blue-700 font-medium">Date/Heure</p>
                        <p class="text-blue-900 font-semibold mt-1">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <hr class="border-blue-200">
                    <div>
                        <p class="text-blue-700 font-medium">Créé il y a</p>
                        <p class="text-blue-900 font-semibold mt-1">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <hr class="border-blue-200">
                    <div>
                        <p class="text-blue-700 font-medium">ID du Log</p>
                        <p class="text-blue-900 font-mono text-xs mt-1">{{ $log->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Métadonnées -->
            @if($log->metadata)
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔧 Métadonnées</h3>
                    <div class="bg-gray-50 p-3 rounded-lg font-mono text-xs text-gray-700 overflow-auto max-h-64">
                        {{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="space-y-2">
                <a href="{{ route('admin.audit.index') }}" class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    ← Retour aux Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-file-text"></i> Détail du Log d'Audit
            </h2>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>ID :</strong> {{ $log->id }}
                    </p>
                    <p>
                        <strong>Admin :</strong> 
                        @if($log->admin)
                            <a href="{{ route('admin.audit.by-admin', $log->admin) }}">
                                {{ $log->admin->name }}
                            </a>
                        @else
                            Système
                        @endif
                    </p>
                    <p>
                        <strong>Action :</strong> <code>{{ $log->action }}</code>
                    </p>
                    <p>
                        <strong>Modèle :</strong> {{ $log->model_type }}
                    </p>
                    <p>
                        <strong>ID Modèle :</strong> {{ $log->model_id }}
                    </p>
                    <p>
                        <strong>Date/Heure :</strong> {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations IP/User-Agent -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations Technique</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Adresse IP :</strong> 
                        <code>{{ $log->ip_address }}</code>
                    </p>
                    <p>
                        <strong>User-Agent :</strong>
                        <code style="word-break: break-all; font-size: 0.85rem;">
                            {{ $log->user_agent ?? 'N/A' }}
                        </code>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Description</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $log->description }}</p>
        </div>
    </div>

    <!-- Valeurs anciennes et nouvelles -->
    <div class="row">
        {{-- Valeurs anciennes --}}
        @if($log->old_values)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Valeurs Anciennes</h5>
                    </div>
                    <div class="card-body">
                        <pre style="font-size: 0.85rem;">{{ json_encode(json_decode($log->old_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        @endif

        {{-- Valeurs nouvelles --}}
        @if($log->new_values)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Valeurs Nouvelles</h5>
                    </div>
                    <div class="card-body">
                        <pre style="font-size: 0.85rem;">{{ json_encode(json_decode($log->new_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux Logs
        </a>
    </div>
</div>
@endsection

@section('title', 'Détail Log Audit')
