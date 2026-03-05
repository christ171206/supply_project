@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-list-ul"></i> Gestion des Catégories
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Catégorie
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher une catégorie..." 
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ ($filters['sort_by'] ?? '') == 'created_at' ? 'selected' : '' }}>
                            Récentes d'abord
                        </option>
                        <option value="nom" {{ ($filters['sort_by'] ?? '') == 'nom' ? 'selected' : '' }}>
                            Nom (A-Z)
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des catégories -->
    @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Produits</th>
                        <th>Statut</th>
                        <th>Date d'Ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->nom }}</strong>
                            </td>
                            <td>
                                <small>{{ Str::limit($category->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->produits_count }} produit(s)</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $category->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                    method="POST" style="display: inline;"
                                    data-confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?"
                                    data-confirm-title="Supprimer la catégorie"
                                    data-confirm-type="danger"
                                    data-confirm-button="Supprimer">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Aucune catégorie trouvée
        </div>
    @endif
</div>
@endsection

@section('title', 'Catégories')
