@extends('layouts.admin-layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-edit"></i> Éditer Catégorie
            </h2>
            <small class="text-muted">{{ $category->nom }}</small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror"
                                placeholder="Ex: Électroniques" value="{{ old('nom', $category->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="Décrire cette catégorie...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                    value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Actif (visible aux clients)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <p><strong>Date de création :</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Dernière modification :</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Statut :</strong>
                        @if($category->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Produits</h5>
                </div>
                <div class="card-body">
                    @if($category->produits->count() > 0)
                        <p><strong>{{ $category->produits->count() }} produit(s)</strong></p>
                        <ul class="list-group list-group-sm">
                            @foreach($category->produits->take(5) as $product)
                                <li class="list-group-item">
                                    <small>{{ $product->nom ?? 'N/A' }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun produit</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('title', 'Éditer Catégorie')
