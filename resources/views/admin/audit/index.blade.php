@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-clipboard class="w-8 h-8" /><span>Journal d'Audit</span></h1>
        <p class="text-gray-600 mt-2">Supervision complète des activités du système</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow p-6">
            <p class="text-sm text-blue-600 font-medium">Événements (24h)</p>
            <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['events_24h'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow p-6">
            <p class="text-sm text-green-600 font-medium">Utilisateurs (24h)</p>
            <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['unique_users_24h'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow p-6">
            <p class="text-sm text-red-600 font-medium">Échecs (24h)</p>
            <p class="text-3xl font-bold text-red-900 mt-2">{{ $stats['failed_24h'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow p-6">
            <p class="text-sm text-purple-600 font-medium">Événements (7j)</p>
            <p class="text-3xl font-bold text-purple-900 mt-2">{{ $stats['events_7d'] }}</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">🔍 Filtres</h3>
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Utilisateur</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'Événement</label>
                    <select name="event_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        @foreach($eventTypes as $type)
                            <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                @if($status === 'success') Succès
                                @elseif($status === 'failed') Échec
                                @elseif($status === 'warning') Attention
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                    <select name="country" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        🔍 Appliquer
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-2 px-4 rounded-lg transition text-center">
                        🔄 Réinitialiser
                    </a>
                </div>
            </div>
        </form>

        <div class="mt-4 flex justify-between">
            <a href="{{ route('admin.audit.stats') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                📊 Statistiques Détaillées
            </a>
            <a href="{{ route('admin.audit.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                📥 Exporter CSV
            </a>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date/Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">IP / Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Appareil</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <a href="{{ route('admin.audit.by-admin', $log->user_id) }}" class="text-blue-600 hover:underline">
                                    {{ $log->user?->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                    @if($log->event_type === 'login')
                                        style="background-color: #dbeafe; color: #0c4a6e;"
                                    @elseif($log->event_type === 'logout')
                                        style="background-color: #fecdd3; color: #7f1d1d;"
                                    @elseif($log->event_type === 'create')
                                        style="background-color: #dcfce7; color: #166534;"
                                    @elseif($log->event_type === 'update')
                                        style="background-color: #fef08a; color: #713f12;"
                                    @elseif($log->event_type === 'delete')
                                        style="background-color: #fee2e2; color: #991b1b;"
                                    @else
                                        style="background-color: #f3e8ff; color: #6b21a8;"
                                    @endif
                                >
                                    {{ $log->event_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->status === 'success')
                                    <span style="background-color: #dcfce7; color: #166534;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">✅ Succès</span>
                                @elseif($log->status === 'failed')
                                    <span style="background-color: #fee2e2; color: #991b1b;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">❌ Échec</span>
                                @elseif($log->status === 'warning')
                                    <span style="background-color: #fef3c7; color: #92400e;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">⚠️ Attention</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-xs">
                                    <div>{{ $log->ip_address }}</div>
                                    <div class="text-gray-500">{{ $log->city }}, {{ $log->country }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-xs">
                                    <div>{{ $log->device_type }}</div>
                                    <div class="text-gray-500">{{ $log->browser }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="{{ route('admin.audit.show', $log) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                📭 Aucun log d'audit trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-dark font-weight-bold">
                <i class="fas fa-history me-2"></i>Logs d'Audit
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.audit.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Exporter
            </a>
            <a href="{{ route('admin.audit.stats') }}" class="btn btn-info">
                <i class="fas fa-chart-pie"></i> Statistiques
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Admin</label>
                    <select name="admin_id" class="form-select">
                        <option value="">Tous les admins</option>
                        @foreach ($admins as $id => $name)
                            <option value="{{ $id }}" {{ request('admin_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-select">
                        <option value="">Toutes les actions</option>
                        @foreach ($actions as $key => $label)
                            <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Du</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Au</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list"></i> Historique des Actions</h5>
        </div>
        <div class="card-body">
            @if ($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date/Heure</th>
                                <th>Admin</th>
                                <th>Action</th>
                                <th>Ressource</th>
                                <th>Détails</th>
                                <th>IP</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <strong>{{ $log->admin->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            strpos($log->action, 'create') !== false ? 'success' :
                                            (strpos($log->action, 'update') !== false ? 'info' :
                                            (strpos($log->action, 'delete') !== false ? 'danger' :
                                            (strpos($log->action, 'approve') !== false ? 'success' :
                                            (strpos($log->action, 'reject') !== false ? 'warning' : 'secondary'))))
                                        }}">
                                            {{ $log->getActionLabelAttribute() }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $log->model_name }}</strong><br>
                                        <small class="text-muted">{{ $log->model_type }} #{{ $log->model_id }}</small>
                                    </td>
                                    <td>
                                        @if ($log->reason)
                                            <small class="text-muted">{{ Str::limit($log->reason, 50) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $log->ip_address }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Aucun log à afficher.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
                <div class="col-md-3">
                    <label for="admin_id" class="form-label">Admin</label>
                    <select name="admin_id" id="admin_id" class="form-select">
                        <option value="">-- Tous --</option>
                        @foreach($admins as $id => $name)
                            <option value="{{ $id }}" {{ $filters['admin_id'] ?? '' == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="action" class="form-label">Action</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">-- Toutes --</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}" {{ $filters['action'] ?? '' == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="from_date" class="form-label">Du</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" 
                        value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">Au</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" 
                        value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    @if($logs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date/Heure</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Modèle</th>
                        <th>ID</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            </td>
                            <td>{{ $log->admin?->name ?? 'Système' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $actions[$log->action] ?? $log->action }}
                                </span>
                            </td>
                            <td>{{ $log->model_type }}</td>
                            <td>{{ $log->model_id }}</td>
                            <td>
                                <small>{{ Str::limit($log->description, 50) }}</small>
                            </td>
                            <td>
                                <small><code>{{ $log->ip_address }}</code></small>
                            </td>
                            <td>
                                <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Aucun log d'audit trouvé
        </div>
    @endif
</div>
@endsection

@section('title', 'Journal d\'Audit')
