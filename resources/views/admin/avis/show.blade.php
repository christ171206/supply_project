@extends('layouts.admin')

@section('title', 'Détail Avis')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <a href="{{ route('admin.avis.index') }}" class="btn btn-link">← Retour</a>
        <h1 class="h3 d-inline-block">Avis: {{ $avis->produit->nom }}</h1>
    </div>

    <div class="row">
        <!-- Infos Avis -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📝 Détails de l'Avis</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Utilisateur</strong></label>
                        <p>{{ $avis->user->name }} ({{ $avis->user->email }})</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Produit</strong></label>
                        <p>
                            <a href="{{ route('admin.products.show', $avis->produit) }}" target="_blank">
                                {{ $avis->produit->nom }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Note</strong></label>
                        <p>
                            <span class="badge bg-warning text-dark">⭐ {{ $avis->note }}/5</span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Commentaire</strong></label>
                        <div class="alert alert-light border">
                            {{ $avis->commentaire }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Statut</strong></label>
                        <p>
                            @if($avis->is_appropriate)
                                <span class="badge bg-success">✅ Approprié</span>
                            @else
                                <span class="badge bg-danger">❌ Censuré</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Créé le</strong></label>
                        <p>{{ $avis->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($avis->deleted_at)
                        <div class="alert alert-danger">
                            <strong>Supprimé par :</strong> {{ $avis->deletedByAdmin->name ?? 'Système' }}<br>
                            <strong>Date :</strong> {{ $avis->deleted_at->format('d/m/Y H:i') }}<br>
                            <strong>Raison :</strong> {{ $avis->delete_reason }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">⚙️ Actions</h5>
                </div>
                <div class="card-body">
                    @if($avis->is_appropriate)
                        <form action="{{ route('admin.avis.delete', $avis) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><strong>Raison de censure</strong></label>
                                <textarea name="reason" class="form-control form-control-sm" required placeholder="Contenu inapproprié...">Contenu inapproprié</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Censurer cet avis ?')">
                                🗑️ Censurer l'avis
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.avis.restore', $avis) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Restaurer cet avis ?')">
                                ↩️ Restaurer l'avis
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.users.show', $avis->user) }}" class="btn btn-secondary w-100 btn-sm">
                        👤 Voir l'utilisateur
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
