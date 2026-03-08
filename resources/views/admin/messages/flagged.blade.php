@extends('layouts.admin')

@section('title', 'Messages Signalés')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-link">← Tous les messages</a>
        <h1 class="h3 d-inline-block">🚩 Messages Signalés</h1>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>De</th>
                        <th>À</th>
                        <th>Contenu</th>
                        <th>Raison de signalement</th>
                        <th>Signalé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="table-warning">
                            <td>{{ $message->fromUser->name }}</td>
                            <td>{{ $message->toUser->name }}</td>
                            <td><small>{{ Str::limit($message->contenu, 40) }}</small></td>
                            <td><small>{{ $message->flag_reason }}</small></td>
                            <td>{{ $message->flaggedByUser->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-info">👁️</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4"><em>Aucun message signalé</em></td>
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
