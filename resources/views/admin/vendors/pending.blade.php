@extends('layouts.admin-layout')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-clock-o"></i> Vendeurs en Attente de Validation
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-warning p-2">
                {{ $vendors->total() }} vendeur(s) en attente
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
                        <th>Date d'Inscription</th>
                        <th>Documents</th>
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
                                <small>{{ $vendor->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                @if($vendor->documents->count() > 0)
                                    <span class="badge bg-info">
                                        {{ $vendor->documents->count() }} document(s)
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Aucun document</span>
                                @endif
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
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle"></i>
            <strong>Parfait !</strong> Aucun vendeur en attente de validation.
        </div>
    @endif
</div>
@endsection

@section('title', 'Vendeurs en Attente')
