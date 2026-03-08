@extends('layouts.admin')

@section('title', 'Gestion des Mots Bannissants')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 d-inline-block">🚫 Mots Bannissants</h1>
        <div class="float-end">
            <a href="{{ route('admin.banned-words.create') }}" class="btn btn-success btn-sm">➕ Ajouter</a>
            <a href="{{ route('admin.banned-words.export') }}" class="btn btn-secondary btn-sm">📥 Exporter</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Tous les statuts</option>
                        <option value="active" @selected(request('status') === 'active')>Actif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="severity" class="form-control form-control-sm">
                        <option value="">Toutes sévérités</option>
                        <option value="low" @selected(request('severity') === 'low')>Basse</option>
                        <option value="medium" @selected(request('severity') === 'medium')>Moyenne</option>
                        <option value="high" @selected(request('severity') === 'high')>Haute</option>
                    </select>
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
                        <th>Mot</th>
                        <th>Description</th>
                        <th>Sévérité</th>
                        <th>Statut</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($words as $word)
                        <tr>
                            <td><code>{{ $word->word }}</code></td>
                            <td><small>{{ Str::limit($word->description, 50) }}</small></td>
                            <td>
                                <span class="badge @switch($word->severity)
                                    @case('low') bg-info @break
                                    @case('medium') bg-warning text-dark @break
                                    @case('high') bg-danger @break
                                @endswitch">
                                    {{ ucfirst($word->severity) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.banned-words.toggle', $word) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm @if($word->is_active) btn-success @else btn-secondary @endif">
                                        @if($word->is_active) ✅ Actif @else ❌ Inactif @endif
                                    </button>
                                </form>
                            </td>
                            <td><small>{{ $word->createdByAdmin->name ?? 'Système' }}</small></td>
                            <td>
                                <a href="{{ route('admin.banned-words.edit', $word) }}" class="btn btn-sm btn-warning">✏️</a>
                                <form action="{{ route('admin.banned-words.destroy', $word) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4"><em>Aucun mot bannissant</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $words->links() }}
    </div>
</div>
@endsection
