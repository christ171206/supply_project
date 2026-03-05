@extends('layouts.admin-layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2"><x-heroicon-o-clipboard class="w-8 h-8" /><span>Activité de {{ $user->name }}</span></h1>
            <p class="text-gray-600 mt-2">Total: {{ $logs->total() }} événements</p>
        </div>
        <a href="{{ route('admin.audit.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ← Retour au Journal
        </a>
    </div>

    <!-- Informations utilisateur -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-blue-600 uppercase font-semibold">Nom</p>
                <p class="text-lg font-bold text-blue-900 mt-1">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 uppercase font-semibold">Email</p>
                <p class="text-lg font-bold text-blue-900 mt-1">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-600 uppercase font-semibold">Rôle</p>
                <p class="text-lg font-bold text-blue-900 mt-1">
                    @if($user->is_admin) Admin @elseif($user->is_vendor) Vendor @else Client @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-blue-600 uppercase font-semibold">Inscrit le</p>
                <p class="text-lg font-bold text-blue-900 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date/Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">IP / Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Appareil</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                    @if($log->event_type === 'login')
                                        style="background-color: #dbeafe; color: #0c4a6e;"
                                    @elseif($log->event_type === 'logout')
                                        style="background-color: #fecdd3; color: #7f1d1d;"
                                    @elseif($log->event_type === 'create')
                                        style="background-color: #dcfce7; color: #166534;"
                                    @elseif($log->event_type === 'update')
                                        style="background-color: #fef08a; color: #713f12;"
                                    @elseif($log->event_type === 'delete')
                                        style="background-color: #fee2e2; color: #991b1b;"
                                    @else
                                        style="background-color: #f3e8ff; color: #6b21a8;"
                                    @endif
                                >
                                    {{ $log->event_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->status === 'success')
                                    <span style="background-color: #dcfce7; color: #166534;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">✅ Succès</span>
                                @elseif($log->status === 'failed')
                                    <span style="background-color: #fee2e2; color: #991b1b;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">❌ Échec</span>
                                @elseif($log->status === 'warning')
                                    <span style="background-color: #fef3c7; color: #92400e;" class="inline-block px-3 py-1 rounded-full text-xs font-semibold">⚠️ Attention</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-xs">
                                    <div>{{ $log->ip_address }}</div>
                                    <div class="text-gray-500">{{ $log->city }}, {{ $log->country }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-xs">
                                    <div>{{ $log->device_type }}</div>
                                    <div class="text-gray-500">{{ $log->browser }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="{{ route('admin.audit.show', $log) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                📭 Aucun événement pour cet utilisateur
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
