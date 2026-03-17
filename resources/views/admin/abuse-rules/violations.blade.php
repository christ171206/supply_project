@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-3xl text-[#0a0a0a] mb-1">Journal des Violations</h1>
            <p class="text-[13px] text-[#a0a09a]">Toutes les tentatives d'utilisation abusive détectées</p>
        </div>
        <a href="{{ route('admin.abuse-rules.violations.export') }}" class="px-4 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
            ↓ Exporter CSV
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Total Violations</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['total_violations'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Bloquées</div>
            <div class="font-mono text-[28px] font-bold text-red-600">{{ $stats['blocked'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Avertissements</div>
            <div class="font-mono text-[28px] font-bold text-yellow-600">{{ $stats['warned'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Cette Semaine</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['this_week'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Perte Potentielle</div>
            <div class="font-mono text-[12px] font-bold text-[#0a0a0a]">{{ number_format($stats['total_loss'], 0, ',', ' ') }} F</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[250px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par email ou nom..."
                       class="w-full px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px] focus:outline-none focus:border-[#0a0a0a]">
            </div>
            <select name="violation_type" class="px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px]">
                <option value="">Tous les types</option>
                <option value="attempted" {{ request('violation_type') == 'attempted' ? 'selected' : '' }}>Tentatives</option>
                <option value="blocked" {{ request('violation_type') == 'blocked' ? 'selected' : '' }}>Bloquées</option>
                <option value="flagged" {{ request('violation_type') == 'flagged' ? 'selected' : '' }}>Signalées</option>
            </select>
            <select name="rule_id" class="px-3 py-2 border border-[#e0e0dc] rounded-lg text-[13px]">
                <option value="">Toutes les règles</option>
                @foreach($rules as $rule)
                    <option value="{{ $rule->id }}" {{ request('rule_id') == $rule->id ? 'selected' : '' }}>
                        {{ $rule->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'violation_type', 'rule_id']))
                <a href="{{ route('admin.abuse-rules.violations') }}" class="px-4 py-2 text-[#a0a09a] text-[13px] hover:text-[#0a0a0a]">
                    ✕ Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Violations Table --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Règle Violée</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Type</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Action</th>
                        <th class="px-6 py-3 text-right text-[12px] font-medium text-[#0a0a0a]">Perte</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Date</th>
                        <th class="px-6 py-3 text-right text-[12px] font-medium text-[#0a0a0a]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($violations as $log)
                        <tr class="hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div class="font-medium text-[13px] text-[#0a0a0a]">{{ $log->user->name }}</div>
                                    <div class="text-[11px] text-[#a0a09a] mt-1">{{ $log->user->email }}</div>
                                @else
                                    <span class="text-[#a0a09a]">Anonyme</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#0a0a0a]">
                                <a href="{{ route('admin.abuse-rules.show', $log->rule) }}" class="hover:underline font-medium">
                                    {{ $log->rule->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[11px] font-medium
                                    {{ $log->violation_type == 'attempted' ? 'bg-blue-100 text-blue-700' : ($log->violation_type == 'blocked' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $log->getTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[11px] font-medium
                                    {{ $log->action_taken == 'none' ? 'bg-gray-100 text-gray-700' : ($log->action_taken == 'warning' ? 'bg-yellow-100 text-yellow-700' : ($log->action_taken == 'blocked' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}">
                                    {{ ucfirst($log->action_taken ?? 'aucune') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[13px] text-[#0a0a0a]">
                                {{ number_format($log->potential_loss, 0, ',', ' ') }} F
                            </td>
                            <td class="px-6 py-4 text-[12px] text-[#a0a09a]">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openModal({{ $log->id }})"
                                        class="text-[12px] text-[#0a0a0a] font-medium hover:underline">
                                    Détails →
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-[13px] text-[#a0a09a]">Aucune violation trouvée</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($violations->hasPages())
            <div class="px-6 py-4 border-t border-[#e0e0dc]">
                {{ $violations->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Detail Modal --}}
<div id="detailModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-[#e0e0dc] px-6 py-4 flex items-center justify-between">
            <h2 class="font-serif text-lg text-[#0a0a0a]">Détails de la Violation</h2>
            <button onclick="closeModal()" class="text-[#a0a09a] hover:text-[#0a0a0a]">✕</button>
        </div>

        <div id="modalContent" class="p-6">
            {{-- Loading --}}
        </div>
    </div>
</div>

<script>
async function openModal(logId) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('modalContent');

    try {
        const response = await fetch(`/admin/abuse-rules/violations/${logId}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        content.innerHTML = `
            <div class="space-y-4">
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Utilisateur</div>
                    <div class="text-[13px] text-[#0a0a0a]">${data.user_name || 'Anonyme'}</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Règle</div>
                    <div class="text-[13px] text-[#0a0a0a]"><a href="${data.rule_url}" class="text-[#0a0a0a] font-medium hover:underline">${data.rule_name}</a></div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Type de Violation</div>
                    <div class="text-[13px] text-[#0a0a0a]">${data.violation_type_label}</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Perte Potentielle</div>
                    <div class="font-mono text-[13px] font-bold text-[#0a0a0a]">${data.potential_loss} FCFA</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Action Prise</div>
                    <div class="text-[13px] text-[#0a0a0a]">${data.action_label}</div>
                </div>
                <div>
                    <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Date</div>
                    <div class="text-[13px] text-[#0a0a0a]">${data.created_at}</div>
                </div>
                ${data.admin_notes ? `
                    <div>
                        <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Notes Admin</div>
                        <div class="text-[13px] text-[#0a0a0a]">${data.admin_notes}</div>
                    </div>
                ` : ''}
                <div class="pt-4 border-t border-[#e0e0dc]">
                    <button onclick="handleViolation(${logId})" class="w-full px-4 py-2 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                        Traiter la Violation
                    </button>
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
    } catch (error) {
        content.innerHTML = `<div class="text-red-600">Erreur: ${error.message}</div>`;
        modal.classList.remove('hidden');
    }
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function handleViolation(logId) {
    closeModal();
    // Navigate to handle page or open form
    window.location.href = `/admin/abuse-rules/violations/${logId}/handle`;
}

// Close modal on outside click
document.getElementById('detailModal').addEventListener('click', e => {
    if (e.target.id === 'detailModal') closeModal();
});
</script>

@endsection
