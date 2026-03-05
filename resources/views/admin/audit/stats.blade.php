@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-chart-bar class="w-8 h-8" /><span>Statistiques d'Audit</span></h1>
        <p class="text-gray-600 mt-2">Analyse détaillée des activités du système</p>
    </div>

    <!-- Période -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période Du</label>
                <input type="date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}" class="px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                <input type="date" name="to_date" value="{{ $toDate->format('Y-m-d') }}" class="px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                🔍 Appliquer
            </button>
        </form>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow p-6">
            <p class="text-sm text-blue-600 font-medium">Total Événements</p>
            <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalEvents }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow p-6">
            <p class="text-sm text-green-600 font-medium">Succès</p>
            <p class="text-3xl font-bold text-green-900 mt-2">{{ $successCount }}</p>
            @if($totalEvents > 0)
                <p class="text-xs text-green-700 mt-2">{{ round(($successCount / $totalEvents) * 100, 1) }}%</p>
            @endif
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow p-6">
            <p class="text-sm text-red-600 font-medium">Échecs</p>
            <p class="text-3xl font-bold text-red-900 mt-2">{{ $failedCount }}</p>
            @if($totalEvents > 0)
                <p class="text-xs text-red-700 mt-2">{{ round(($failedCount / $totalEvents) * 100, 1) }}%</p>
            @endif
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow p-6">
            <p class="text-sm text-yellow-600 font-medium">Attention</p>
            <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $warningCount }}</p>
            @if($totalEvents > 0)
                <p class="text-xs text-yellow-700 mt-2">{{ round(($warningCount / $totalEvents) * 100, 1) }}%</p>
            @endif
        </div>
    </div>

    <!-- Événements par type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📈 Événements par Type</h3>
            <div class="space-y-3">
                @foreach($eventsByType as $event)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ $event->event_type }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = ($event->count / $totalEvents) * 100;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 min-w-fit">{{ $event->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top pays -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🌍 Pays les Plus Actifs</h3>
            <div class="space-y-3">
                @foreach($activeCountries as $country)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ $country->country ?? 'Inconnu' }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = ($country->count / $totalEvents) * 100;
                                @endphp
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 min-w-fit">{{ $country->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Utilisateurs et tentatives échouées -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-user-group class="w-5 h-5" /><span>Utilisateurs les Plus Actifs</span></h3>
            <div class="space-y-3">
                @foreach($activeUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700 font-medium">{{ $user->user?->name ?? 'N/A' }}</span>
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">{{ $user->count }} actions</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><x-heroicon-o-exclamation-triangle class="w-5 h-5" /><span>Tentatives Échouées par IP</span></h3>
            <div class="space-y-3">
                @foreach($failedAttempts as $attempt)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm text-gray-700 font-mono">{{ $attempt->ip_address }}</span>
                        <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">{{ $attempt->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Événements par jour -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📅 Événements par Jour</h3>
        <div class="space-y-2">
            @foreach($eventsByDay as $day)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</span>
                    <div class="flex items-center gap-2">
                        <div class="w-48 bg-gray-200 rounded-full h-3">
                            @php
                                $maxCount = $eventsByDay->max('count');
                                $percentage = ($maxCount > 0) ? ($day->count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="bg-purple-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-900 min-w-fit">{{ $day->count }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Retour -->
    <div class="flex justify-center">
        <a href="{{ route('admin.audit.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg transition">
            ← Retour aux Logs
        </a>
    </div>
</div>
@endsection

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-dark font-weight-bold">
                <i class="fas fa-chart-bar me-2"></i>Statistiques d'Audit (30 jours)
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text small mb-1">Total Actions</p>
                            <h3 class="mb-0">{{ $stats['total_actions'] }}</h3>
                        </div>
                        <i class="fas fa-history fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text small mb-1">Types d\'Actions</p>
                            <h3 class="mb-0">{{ $stats['by_action']->count() }}</h3>
                        </div>
                        <i class="fas fa-tasks fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text small mb-1">Ressources Modifiées</p>
                            <h3 class="mb-0">{{ $stats['by_model']->count() }}</h3>
                        </div>
                        <i class="fas fa-database fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text small mb-1">Admins Actifs</p>
                            <h3 class="mb-0">{{ $stats['by_admin']->count() }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Graphique Actions -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Distribution des Actions</h5>
                </div>
                <div class="card-body">
                    <canvas id="actionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique Ressources -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Ressources Modifiées</h5>
                </div>
                <div class="card-body">
                    <canvas id="modelChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau Admins -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-users"></i> Activité par Admin</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Admin</th>
                            <th>Actions</th>
                            <th>Pourcentage</th>
                            <th>Barre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stats['by_admin'] as $adminId => $data)
                            @php
                                $percentage = ($data['count'] / $stats['total_actions']) * 100;
                            @endphp
                            <tr>
                                <td><strong>{{ $data['admin_name'] }}</strong></td>
                                <td>{{ $data['count'] }}</td>
                                <td>{{ number_format($percentage, 1) }}%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $percentage }}%;" 
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Graphique Actions
    const actionCtx = document.getElementById('actionChart').getContext('2d');
    new Chart(actionCtx, {
        type: 'doughnut',
        data: {
            labels: @json($stats['by_action']->keys()),
            datasets: [{
                data: @json($stats['by_action']->values()),
                backgroundColor: [
                    '#0dcaf0', '#198754', '#dc3545', '#ffc107', '#0d6efd', '#6f42c1',
                    '#fd7e14', '#20c997', '#e83e8c', '#6c757d'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Graphique Modèles
    const modelCtx = document.getElementById('modelChart').getContext('2d');
    new Chart(modelCtx, {
        type: 'bar',
        data: {
            labels: @json($stats['by_model']->keys()),
            datasets: [{
                label: 'Nombre de Modifications',
                data: @json($stats['by_model']->values()),
                backgroundColor: '#0dcaf0',
                borderColor: '#0d6efd',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="card-title">{{ number_format($logsThisWeek) }}</h3>
                    <p class="card-text">Cette Semaine</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="card-title">
                        {{ $logsThisMonth > 0 ? round($logsThisMonth / 30, 1) : 0 }}
                    </h3>
                    <p class="card-text">Logs/Jour (avg)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions les plus fréquentes -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions les Plus Fréquentes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th class="text-end">Nombre</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = $actionStats->sum('count');
                            @endphp
                            @foreach($actionStats as $stat)
                                <tr>
                                    <td>{{ $stat->action }}</td>
                                    <td class="text-end">{{ number_format($stat->count) }}</td>
                                    <td class="text-end">{{ round(($stat->count / $total) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Admins les plus actifs -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Admins Avec le Plus d'Actions</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th class="text-end">Nombre d'Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adminStats as $stat)
                                <tr>
                                    <td>
                                        @if($stat->admin)
                                            <a href="{{ route('admin.audit.by-admin', $stat->admin) }}">
                                                {{ $stat->admin->name }}
                                            </a>
                                        @else
                                            Système
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($stat->count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux Logs
        </a>
    </div>
</div>
@endsection

@section('title', 'Statistiques Audit')
