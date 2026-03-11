@extends('layouts.admin-layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-folder"></i> {{ $category->nom }}
            </h2>
            <small class="text-muted">Catégorie ID: {{ $category->id }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Éditer
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;"
                data-confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?"
                data-confirm-title="Supprimer la catégorie"
                data-confirm-type="danger"
                data-confirm-button="Supprimer">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- Informations -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nom :</strong> {{ $category->nom }}
                    </p>
                    <p>
                        <strong>Description :</strong>
                        {{ $category->description ?? 'Aucune description' }}
                    </p>
                    <p>
                        <strong>Statut :</strong>
                        @if($category->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </p>
                    <p>
                        <strong>Créée le :</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <strong>Modifiée le :</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Produits -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Produits ({{ $category->produits->count() }})</h5>
                </div>
                @if($category->produits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Vendeur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->produits as $product)
                                    <tr>
                                        <td>{{ $product->nom ?? 'N/A' }}</td>
                                        <td>{{ number_format($product->prix ?? 0, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $product->stock ?? 0 }}</td>
                                        <td>
                                            @if($product->user)
                                                <small>{{ $product->user->shop_name ?? $product->user->name }}</small>
                                            @else
                                                <small class="text-muted">N/A</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body">
                        <p class="text-muted mb-0">Aucun produit dans cette catégorie</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $category->produits->count() }}</h4>
                        <small class="text-muted">Produits</small>
                    </div>
                    <div class="mb-3">
                        <h4 class="mb-0">
                            {{ number_format($category->produits->sum('stock'), 0, ',', ' ') }}
                        </h4>
                        <small class="text-muted">Unités en stock</small>
                    </div>
                    <div>
                        <h4 class="mb-0">
                            {{ number_format($category->produits->sum('prix'), 0, ',', ' ') }}
                        </h4>
                        <small class="text-muted">Valeur totale produits</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection

@section('title', 'Détails Catégorie')
