@extends('layouts.admin')

@section('title', 'Détail Message')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-link">← Retour</a>
        <h1 class="h3 d-inline-block">Message</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📨 Détails du Message</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>De :</strong></label>
                        <p><a href="{{ route('admin.users.show', $message->fromUser) }}" target="_blank">{{ $message->fromUser->name }}</a> ({{ $message->fromUser->email }})</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>À :</strong></label>
                        <p><a href="{{ route('admin.users.show', $message->toUser) }}" target="_blank">{{ $message->toUser->name }}</a></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Contenu</strong></label>
                        <div class="alert alert-light border" style="min-height: 100px;">
                            {{ $message->contenu }}
                        </div>
                    </div>

                    @if($message->is_flagged)
                        <div class="alert alert-warning">
                            <strong>🚩 Signalé par :</strong> {{ $message->flaggedByUser->name ?? 'Système' }}<br>
                            <strong>Raison :</strong> {{ $message->flag_reason }}
                        </div>
                    @endif

                    @if($message->deleted_at)
                        <div class="alert alert-danger">
                            <strong>🗑️ Supprimé par :</strong> {{ $message->deletedByAdmin->name ?? 'Système' }}<br>
                            <strong>Raison :</strong> {{ $message->delete_reason }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">⚙️ Actions</h5>
                </div>
                <div class="card-body">
                    @if(!$message->deleted_at)
                        <form action="{{ route('admin.messages.delete', $message) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><strong>Raison de suppression</strong></label>
                                <textarea name="reason" class="form-control form-control-sm" required placeholder="Contenu inapproprié...">Contenu inapproprié</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Supprimer ce message ?')">
                                🗑️ Supprimer
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.messages.restore', $message) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">↩️ Restaurer</button>
                        </form>
                    @endif

                    @if($message->is_flagged && !$message->deleted_at)
                        <form action="{{ route('admin.messages.dismiss-flag', $message) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-info w-100 btn-sm">
                                ✅ Rejeter le signalement
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
