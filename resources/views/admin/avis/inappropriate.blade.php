@extends('layouts.admin')

@section('title', 'Avis Censurés')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.avis.index') }}" class="btn btn-link">← Tous les avis</a>
        <h1 class="h3 d-inline-block">⚠️ Avis Censurés</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Utilisateur</th>
                        <th>Note</th>
                        <th>Raison de censure</th>
                        <th>Censurée par</th>
                        <th>Date censure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avis as $avi)
                        <tr class="table-danger">
                            <td><strong>{{ $avi->produit->nom }}</strong></td>
                            <td>{{ $avi->user->name }}</td>
                            <td><span class="badge bg-info">⭐ {{ $avi->note }}/5</span></td>
                            <td><small>{{ $avi->delete_reason }}</small></td>
                            <td>{{ $avi->deletedByAdmin->name ?? 'Système' }}</td>
                            <td><small>{{ $avi->deleted_at?->format('d/m/Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('admin.avis.show', $avi) }}" class="btn btn-sm btn-info">👁️</a>
                                <form action="{{ route('admin.avis.restore', $avi) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">↩️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4"><em>Aucun avis censuré</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $avis->links() }}
    </div>
</div>
@endsection
