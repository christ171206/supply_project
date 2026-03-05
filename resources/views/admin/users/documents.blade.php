@extends('layouts.admin-layout')

@section('title', 'Documents - ' . $user->name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-2 inline-block">← Retour au profil</a>
            <h1 class="text-4xl font-bold text-gray-900">Documents de {{ $user->name }}</h1>
            <p class="text-gray-500 mt-2">Vérification KYC • {{ $user->email }}</p>
        </div>
        <div class="text-right">
            @if($documents->get('verified', collect())->count() === count($documents->flatten()))
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">✓ Tous vérifiés</span>
            @elseif($documents->get('pending', collect())->count() > 0)
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">⏳ En attente</span>
            @elseif($documents->get('rejected', collect())->count() > 0)
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">✗ Rejeté</span>
            @endif
        </div>
    </div>

    <!-- Documents Tabs -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <!-- Pending Documents -->
        @if($documents->get('pending', collect())->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2"><x-heroicon-o-clock class="w-6 h-6" /><span>Documents en Attente ({{ $documents->get('pending', collect())->count() }})</span></h2>
                <div class="space-y-4">
                    @foreach($documents->get('pending', []) as $document)
                        <div class="flex items-start justify-between p-6 border border-yellow-200 rounded-xl bg-yellow-50 hover:bg-yellow-100 transition">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                <p class="text-sm text-gray-600 mt-1">Créé le {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                @if($document->file_path)
                                    <p class="text-xs text-gray-500 mt-2">Fichier : {{ basename($document->file_path) }}</p>
                                @endif
                            </div>
                            <div class="text-right flex gap-3">
                                @if($document->file_path)
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition">
                                        📥 Voir
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.users.approve-document', $document->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm transition">
                                        ✓ Approuver
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject-document', $document->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm transition">
                                        ✗ Rejeter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Verified Documents -->
        @if($documents->get('verified', collect())->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">✓ Documents Vérifiés ({{ $documents->get('verified', collect())->count() }})</h2>
                <div class="space-y-4">
                    @foreach($documents->get('verified', []) as $document)
                        <div class="flex items-start justify-between p-6 border border-green-200 rounded-xl bg-green-50">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                <p class="text-sm text-gray-600 mt-1">Créé le {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                @if($document->verified_at)
                                    <p class="text-xs text-green-700 font-semibold mt-2">Vérifié le {{ $document->verified_at->format('d/m/Y à H:i') }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                @if($document->file_path)
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition">
                                        📥 Voir
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Rejected Documents -->
        @if($documents->get('rejected', collect())->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">✗ Documents Rejetés ({{ $documents->get('rejected', collect())->count() }})</h2>
                <div class="space-y-4">
                    @foreach($documents->get('rejected', []) as $document)
                        <div class="flex items-start justify-between p-6 border border-red-200 rounded-xl bg-red-50 hover:bg-red-100 transition">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-lg">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                <p class="text-sm text-gray-600 mt-1">Créé le {{ $document->created_at->format('d/m/Y à H:i') }}</p>
                                @if($document->rejection_reason)
                                    <p class="text-sm text-red-700 font-semibold mt-2 bg-white p-3 rounded border border-red-200 mt-3">
                                        <strong>Motif du rejet :</strong> {{ $document->rejection_reason }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right flex gap-3">
                                @if($document->file_path)
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition">
                                        📥 Voir
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.users.approve-document', $document->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm transition">
                                        ✓ Approuver
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- No Documents -->
        @if($documents->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-gray-500 text-lg">Aucun document soumis</p>
                <p class="text-gray-400 text-sm mt-2">Ce vendeur n'a pas encore soumis de documents</p>
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Total Documents</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $documents->flatten()->count() }}</p>
                </div>
                <div class="text-4xl opacity-20">📋</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">En Attente</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $documents->get('pending', collect())->count() }}</p>
                </div>
                <div class="text-4xl opacity-20">⏳</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold uppercase tracking-wider">Vérifiés</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $documents->get('verified', collect())->count() }}</p>
                </div>
                <div class="text-4xl opacity-20">✓</div>
            </div>
        </div>
    </div>
</div>
@endsection
