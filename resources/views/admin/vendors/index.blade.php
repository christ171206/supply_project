@extends('layouts.app')

@section('title', 'Gestion des Vendeurs')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 text-dark font-weight-bold">
                <i class="fas fa-store me-2"></i>Gestion des Vendeurs
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-warning me-2">
                <i class="fas fa-hourglass-half"></i> En attente: {{ $pendingCount }}
            </span>
            <span class="badge bg-success me-2">
                <i class="fas fa-check-circle"></i> Approuvé: {{ $approvedCount }}
            </span>
            <span class="badge bg-danger">
                <i class="fas fa-times-circle"></i> Rejeté: {{ $rejectedCount }}
            </span>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#pending" role="tab">
                <i class="fas fa-hourglass-half"></i> En Attente
                <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#approved" role="tab">
                <i class="fas fa-check-circle"></i> Approuvés
                <span class="badge bg-success ms-2">{{ $approvedCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#rejected" role="tab">
                <i class="fas fa-times-circle"></i> Rejetés
                <span class="badge bg-danger ms-2">{{ $rejectedCount }}</span>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Pending Vendors -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            @if ($pendingVendors->count() > 0)
                <div class="row">
                    @foreach ($pendingVendors as $vendor)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-warning">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">
                                        <i class="fas fa-hourglass-half"></i> {{ $vendor->vendor->name }}
                                    </h5>
                                    <p class="card-text small text-muted">
                                        <strong>Email:</strong> {{ $vendor->vendor->email }}<br>
                                        <strong>Téléphone:</strong> {{ $vendor->vendor->phone }}<br>
                                        <strong>Date:</strong> {{ $vendor->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Voir Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingVendors->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun vendeur en attente.
                </div>
            @endif
        </div>

        <!-- Approved Vendors -->
        <div class="tab-pane fade" id="approved" role="tabpanel">
            @if ($approvedVendors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Vendeur</th>
                                <th>Email</th>
                                <th>Approuvé par</th>
                                <th>Date d'approbation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvedVendors as $vendor)
                                <tr>
                                    <td><strong>{{ $vendor->vendor->name }}</strong></td>
                                    <td>{{ $vendor->vendor->email }}</td>
                                    <td>{{ $vendor->reviewer->name ?? 'N/A' }}</td>
                                    <td>{{ $vendor->reviewed_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $vendor->id }}">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun vendeur approuvé.
                </div>
            @endif
        </div>

        <!-- Rejected Vendors -->
        <div class="tab-pane fade" id="rejected" role="tabpanel">
            @if ($rejectedVendors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Vendeur</th>
                                <th>Email</th>
                                <th>Rejeté par</th>
                                <th>Raison</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rejectedVendors as $vendor)
                                <tr>
                                    <td><strong>{{ $vendor->vendor->name }}</strong></td>
                                    <td>{{ $vendor->vendor->email }}</td>
                                    <td>{{ $vendor->reviewer->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($vendor->review_notes, 50) }}</td>
                                    <td>{{ $vendor->reviewed_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun vendeur rejeté.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
