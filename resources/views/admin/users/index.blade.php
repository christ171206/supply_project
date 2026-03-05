@extends('layouts.admin-layout')

@section('content')
<style>
    .filter-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-custom {
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table-custom thead {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-custom thead th {
        padding: 1rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-custom tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #6b7280;
    }

    .table-custom tbody tr:hover {
        background-color: #f9fafb;
    }

    .user-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-custom {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .btn-custom {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-view {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .btn-documents {
        background-color: #fef3c7;
        color: #92400e;
    }

    .btn-ban {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-unban {
        background-color: #dcfce7;
        color: #166534;
    }

    .filter-input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        background-color: #667eea;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-filter:hover {
        background-color: #5a67d8;
    }

    .stats-header {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 1.875rem;
        font-weight: 700;
        color: #667eea;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6b7280;
        cursor: pointer;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-danger-modal {
        background-color: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
        padding: 1rem 0;
    }

    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        text-decoration: none;
        color: #667eea;
    }

    .pagination span.active {
        background-color: #667eea;
        color: white;
    }
</style>

<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;" class="flex items-center gap-2"><x-heroicon-o-user-group class="w-8 h-8" /><span>Gestion des Utilisateurs</span></h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Gérez les utilisateurs, vendeurs et administrateurs de la plateforme</p>
    </div>
</div>

<!-- Stats Header -->
<div class="stats-header">
    <div class="stat-box">
        <div class="stat-number">{{ $users->total() }}</div>
        <div class="stat-label">Utilisateurs totaux</div>
    </div>
    <div class="stat-box">
        <div class="stat-number">{{ $users->count() }}</div>
        <div class="stat-label">Sur cette page</div>
    </div>
</div>

<!-- Filtres -->
<div class="filter-card">
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        <div>
            <label class="form-label">Rechercher</label>
            <input type="text" name="search" class="filter-input" placeholder="Nom, email, téléphone..." value="{{ request('search') }}" style="width: 100%;">
        </div>
        <div>
            <label class="form-label">Rôle</label>
            <select name="role" class="filter-input" style="width: 100%;">
                <option value="">Tous les rôles</option>
                <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
                <option value="vendor" {{ request('role') === 'vendor' ? 'selected' : '' }}>Vendeur</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
        </div>
        <div>
            <label class="form-label">Statut</label>
            <select name="status" class="filter-input" style="width: 100%;">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banni</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">🔍 Filtrer</button>
    </form>
</div>

<!-- Tableau des utilisateurs -->
<div class="table-card">
    <table class="table-custom" style="width: 100%;">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Inscrit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <strong style="color: #1f2937;">{{ $user->name }}</strong>
                                @if($user->is_admin)
                                    <span class="badge-custom badge-danger" style="margin-left: 0.5rem;">Admin</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">{{ $user->email }}</code></td>
                    <td>
                        <span class="badge-custom 
                            @if($user->role === 'admin') badge-danger
                            @elseif($user->role === 'vendor') badge-warning
                            @else badge-info
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->is_banned)
                            <span class="badge-custom badge-danger">🚫 Banni</span>
                        @else
                            <span class="badge-custom badge-success">✓ Actif</span>
                        @endif
                    </td>
                    <td style="font-size: 0.875rem;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-custom btn-view">Voir</a>
                            @if($user->role === 'vendor')
                                <a href="{{ route('admin.users.documents', $user) }}" class="btn-custom btn-documents">📄</a>
                            @endif
                            @if(!$user->is_banned)
                                <button type="button" class="btn-custom btn-ban" onclick="openBanModal({{ $user->id }}, '{{ $user->name }}')">Bannir</button>
                            @else
                                <form id="unban-form-{{ $user->id }}" action="{{ route('admin.users.unban', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-custom btn-unban">Débannir</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>


                <!-- Modal de bannissement -->
                <div id="banModal{{ $user->id }}" class="modal-overlay">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">🚫 Bannir {{ $user->name }}</h5>
                            <button class="modal-close" onclick="closeBanModal({{ $user->id }})">×</button>
                        </div>
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">Raison du bannissement *</label>
                                    <select name="reason" class="form-control" required>
                                        <option value="">Sélectionner une raison</option>
                                        <option value="fraud">🚨 Fraude détectée</option>
                                        <option value="late_delivery">⏰ Livraison tardive répétée</option>
                                        <option value="policy_violation">Violation des conditions</option>
                                        <option value="harassment">🤐 Harcèlement utilisateurs</option>
                                        <option value="counterfeit">Produits contrefaits</option>
                                        <option value="other">📝 Autre</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Détails du bannissement *</label>
                                    <textarea name="details" class="form-control" rows="4" required placeholder="Expliquez la raison du bannissement..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Durée (en jours, 0 = permanent)</label>
                                    <input type="number" name="duration" class="form-control" min="0" placeholder="Laissez vide ou 0 pour permanent">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-secondary" onclick="closeBanModal({{ $user->id }})">Annuler</button>
                                <button type="submit" class="btn-danger-modal">Bannir l'utilisateur</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: #9ca3af;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔍</div>
                        <strong>Aucun utilisateur trouvé</strong>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem;">Modifiez vos critères de recherche et réessayez</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="pagination">
        {{-- Lien vers la première page --}}
        @if($users->onFirstPage())
            <span style="opacity: 0.5; cursor: not-allowed;">← Précédent</span>
        @else
            <a href="{{ $users->appends(request()->query())->url(1) }}">← Précédent</a>
        @endif

        {{-- Numéros de pages --}}
        @for($page = 1; $page <= $users->lastPage(); $page++)
            @if($page == $users->currentPage())
                <span class="active">{{ $page }}</span>
            @elseif($page == 1 || $page == $users->lastPage() || abs($page - $users->currentPage()) <= 1)
                <a href="{{ $users->appends(request()->query())->url($page) }}">{{ $page }}</a>
            @elseif($page == 2 && $users->currentPage() > 3)
                <span style="opacity: 0.5;">...</span>
            @endif
        @endfor

        {{-- Lien vers la page suivante --}}
        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}{{ request()->query() ? '&' . http_build_query(request()->query()) : '' }}">Suivant →</a>
        @else
            <span style="opacity: 0.5; cursor: not-allowed;">Suivant →</span>
        @endif
    </div>
@endif

@endsection

<script>
    function openBanModal(userId, userName) {
        const modal = document.getElementById('banModal' + userId);
        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeBanModal(userId) {
        const modal = document.getElementById('banModal' + userId);
        if (modal) {
            modal.classList.remove('active');
        }
    }

    // Fermer le modal si on clique en dehors
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
        }
    });

    // Support de la touche Échap pour fermer les modaux
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });
</script>
