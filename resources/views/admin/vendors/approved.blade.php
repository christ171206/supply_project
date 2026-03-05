@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-check-circle"></i> Vendeurs Approuvés
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-success p-2">
                {{ $vendors->total() }} vendeur(s) approuvé(s)
            </span>
        </div>
    </div>

    @if($vendors->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nom du Magasin</th>
                        <th>Propriétaire</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Produits</th>
                        <th>Date d'Approbation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                <strong>{{ $vendor->shop_name ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->phone ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $vendor->produits_count ?? 0 }} produit(s)
                                </span>
                            </td>
                            <td>
                                <small>{{ $vendor->vendor_approved_at ? $vendor->vendor_approved_at->format('d/m/Y') : '—' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $vendors->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            <strong>Aucun vendeur approuvé.</strong>
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('admin.vendors.pending') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voir les vendeurs en attente
        </a>
    </div>
</div>
@endsection

@section('title', 'Vendeurs Approuvés')
