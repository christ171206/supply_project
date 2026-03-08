@extends('layouts.admin')

@section('title', 'Gestion des Avis')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 d-inline-block">📊 Avis Produits</h1>
        <div class="float-end">
            <a href="{{ route('admin.avis.inappropriate') }}" class="btn btn-warning btn-sm">
                ⚠️ Avis Censurés
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Tous les statuts</option>
                        <option value="appropriate" @selected(request('status') === 'appropriate')>Appropriés</option>
                        <option value="inappropriate" @selected(request('status') === 'inappropriate')>Censurés</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-control form-control-sm">
                        <option value="created_at" @selected(request('sort_by') === 'created_at')>Date</option>
                        <option value="note" @selected(request('sort_by') === 'note')>Note</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Utilisateur</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avis as $avi)
                        <tr>
                            <td>
                                <strong>{{ $avi->produit->nom ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $avi->user->name ?? 'Anonyme' }}</td>
                            <td>
                                <span class="badge bg-info">⭐ {{ $avi->note }}/5</span>
                            </td>
                            <td>
                                <small>{{ Str::limit($avi->commentaire, 50) }}</small>
                            </td>
                            <td>
                                @if($avi->is_appropriate)
                                    <span class="badge bg-success">✅ Approprié</span>
                                @else
                                    <span class="badge bg-danger">❌ Censuré</span>
                                @endif
                            </td>
                            <td><small>{{ $avi->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('admin.avis.show', $avi) }}" class="btn btn-sm btn-info">👁️</a>
                                @if($avi->is_appropriate)
                                    <form action="{{ route('admin.avis.delete', $avi) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="reason" value="Contenu inapproprié">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Censurer cet avis ?')">🗑️</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.avis.restore', $avi) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">↩️ Restaurer</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <em>Aucun avis trouvé</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $avis->links() }}
    </div>
</div>
@endsection
