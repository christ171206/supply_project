@extends('layouts.admin')

@section('title', 'Gestion des Messages')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 d-inline-block">💬 Messages</h1>
        <div class="float-end">
            <a href="{{ route('admin.messages.flagged') }}" class="btn btn-warning btn-sm">
                🚩 Messages Signalés
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Tous les statuts</option>
                        <option value="flagged" @selected(request('status') === 'flagged')>Signalés</option>
                        <option value="deleted" @selected(request('status') === 'deleted')>Supprimés</option>
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
                        <th>De</th>
                        <th>À</th>
                        <th>Contenu</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="@if($message->is_flagged) table-warning @elseif($message->deleted_at) table-danger @endif">
                            <td>{{ $message->fromUser->name ?? 'N/A' }}</td>
                            <td>{{ $message->toUser->name ?? 'N/A' }}</td>
                            <td><small>{{ Str::limit($message->contenu, 50) }}</small></td>
                            <td>
                                @if($message->deleted_at)
                                    <span class="badge bg-danger">🗑️ Supprimé</span>
                                @elseif($message->is_flagged)
                                    <span class="badge bg-warning">🚩 Signalé</span>
                                @else
                                    <span class="badge bg-success">✅ OK</span>
                                @endif
                            </td>
                            <td><small>{{ $message->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-info">👁️</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4"><em>Aucun message</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection
