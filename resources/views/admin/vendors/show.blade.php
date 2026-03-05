@extends('layouts.app')

@section('title', 'Détails du Vendeur')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-dark font-weight-bold">
                <i class="fas fa-store me-2"></i>{{ $vendor->name }}
            </h1>
            <p class="text-muted small">Validation: {{ ucfirst($validation->status) }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations du Vendeur -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Nom</small>
                            <p><strong>{{ $vendor->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Email</small>
                            <p><strong>{{ $vendor->email }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Téléphone</small>
                            <p><strong>{{ $vendor->phone ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Adresse</small>
                            <p><strong>{{ $vendor->address ?? 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Date d'Inscription</small>
                            <p><strong>{{ $vendor->created_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Commerciales -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-briefcase"></i> Informations Commerciales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Description du Commerce</small>
                        <p><strong>{{ $validation->business_description ?? 'N/A' }}</strong></p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Numéro d'Enregistrement</small>
                            <p><strong>{{ $validation->business_registration ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Téléphone Professionnel</small>
                            <p><strong>{{ $validation->business_phone ?? 'N/A' }}</strong></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Document Commercial</small>
                        @if ($validation->business_document)
                            <p><a href="{{ Storage::url($validation->business_document) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-file-download"></i> Télécharger
                            </a></p>
                        @else
                            <p><span class="badge bg-secondary">Non fourni</span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if ($validation->status === 'pending')
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Approuver le Vendeur</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendors.approve', $validation) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Notes (optionnel)</label>
                                <textarea class="form-control" name="review_notes" rows="3" placeholder="Remarques d'approbation..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> Approuver
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-times-circle"></i> Rejeter le Vendeur</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendors.reject', $validation) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Raison du Rejet *</label>
                                <textarea class="form-control @error('review_notes') is-invalid @enderror" name="review_notes" rows="3" placeholder="Expliquez la raison du rejet..." required>{{ old('review_notes') }}</textarea>
                                @error('review_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> Rejeter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> Historique de Validation</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <strong>Statut:</strong> {{ ucfirst($validation->status) }}<br>
                    <strong>Reviewer:</strong> {{ $validation->reviewer->name ?? 'N/A' }}<br>
                    <strong>Date d'Examen:</strong> {{ $validation->reviewed_at?->format('d/m/Y H:i') ?? 'N/A' }}<br>
                    <strong>Notes:</strong> {{ $validation->review_notes ?? 'Aucune note' }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
                    <h5 class="mb-0">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nom :</strong> {{ $vendor->name }}
                    </p>
                    <p>
                        <strong>Email :</strong> {{ $vendor->email }}
                    </p>
                    <p>
                        <strong>Téléphone :</strong> {{ $vendor->phone ?? '-' }}
                    </p>
                    <p>
                        <strong>Adresse :</strong> {{ $vendor->address ?? '-' }}
                    </p>
                    <p>
                        <strong>Inscrit le :</strong> {{ $vendor->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations du magasin -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations du Magasin</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Nom du Magasin :</strong> {{ $vendor->shop_name ?? '-' }}
                    </p>
                    <p>
                        <strong>Statut :</strong> 
                        <span class="badge bg-{{ 
                            $vendor->vendor_status === 'approved' ? 'success' :
                            ($vendor->vendor_status === 'rejected' ? 'danger' :
                            ($vendor->vendor_status === 'suspended' ? 'warning' : 'info'))
                        }}">
                            {{ ucfirst($vendor->vendor_status) }}
                        </span>
                    </p>
                    <p>
                        <strong>Approuvé le :</strong> 
                        {{ $vendor->vendor_approved_at ? $vendor->vendor_approved_at->format('d/m/Y') : '-' }}
                    </p>
                    <p>
                        <strong>Notes :</strong> {{ $vendor->vendor_notes ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents -->
    @if($vendor->documents->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Documents Fournis ({{ $vendor->documents->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Soumis le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor->documents as $doc)
                                <tr>
                                    <td>{{ $doc->type }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $doc->status === 'approved' ? 'success' :
                                            ($doc->status === 'rejected' ? 'danger' : 'warning')
                                        }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Produits -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Produits ({{ $vendor->produits->count() ?? 0 }})</h5>
        </div>
        <div class="card-body">
            @if($vendor->produits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor->produits->take(5) as $product)
                                <tr>
                                    <td>{{ $product->nom ?? '-' }}</td>
                                    <td>{{ number_format($product->prix ?? 0, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $product->stock ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->estatif ? 'success' : 'secondary' }}">
                                            {{ $product->estatif ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($vendor->produits->count() > 5)
                    <p class="text-muted text-center">
                        +{{ $vendor->produits->count() - 5 }} produit(s) supplémentaire(s)
                    </p>
                @endif
            @else
                <p class="text-muted">Aucun produit</p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($vendor->vendor_status === 'pending')
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (optionnel)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                    placeholder="Ajouter des notes pour le vendeur..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> Approuver Vendeur
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reason" class="form-label">Raison du rejet *</label>
                                <textarea name="reason" id="reason" class="form-control" rows="3" 
                                    placeholder="Expliquer pourquoi ce vendeur est rejeté..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> Rejeter Vendeur
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif($vendor->vendor_status === 'approved' && !$vendor->activeBan())
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="reason" class="form-label">Raison de la suspension *</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-pause"></i> Suspendre Vendeur
                    </button>
                </form>
            </div>
        </div>
    @elseif($vendor->vendor_status === 'suspended')
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.vendors.reactivate', $vendor) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-play"></i> Réactiver Vendeur
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('admin.vendors.pending') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection

@section('title', 'Détails Vendeur')
