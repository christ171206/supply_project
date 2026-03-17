@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-3xl text-[#0a0a0a] mb-1">Règles Anti-Abus</h1>
            <p class="text-[13px] text-[#a0a09a]">Prévenez les utilisations abusives des promos et coupons</p>
        </div>
        <a href="{{ route('admin.abuse-rules.create') }}" class="px-4 py-2.5 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
            + Créer Règle
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Règles Totales</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['total_rules'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Activées</div>
            <div class="font-mono text-[28px] font-bold text-green-600">{{ $stats['enabled_rules'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Violations Total</div>
            <div class="font-mono text-[28px] font-bold text-red-600">{{ $stats['total_violations'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Récentes (7j)</div>
            <div class="font-mono text-[28px] font-bold text-[#0a0a0a]">{{ $stats['recent_violations'] }}</div>
        </div>
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-4">
            <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Perte Potentielle</div>
            <div class="font-mono text-[12px] font-bold text-[#0a0a0a]">{{ number_format($stats['total_potential_loss'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    {{-- Rules List --}}
    <div class="bg-white border border-[#e0e0dc] rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Nom</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Type</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">S'applique à</th>
                        <th class="px-6 py-3 text-left text-[12px] font-medium text-[#0a0a0a]">Sévérité</th>
                        <th class="px-6 py-3 text-center text-[12px] font-medium text-[#0a0a0a]">Violations</th>
                        <th class="px-6 py-3 text-right text-[12px] font-medium text-[#0a0a0a]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e0e0dc]">
                    @forelse($rules as $rule)
                        <tr class="hover:bg-[#f7f7f5] transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-[13px] text-[#0a0a0a]">{{ $rule->name }}</div>
                                <div class="text-[11px] text-[#a0a09a] mt-1">{{ $rule->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-[12px]">
                                <span class="px-2 py-1 bg-[#f7f7f5] rounded text-[#0a0a0a] font-medium">
                                    {{ $rule->getTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[12px] text-[#0a0a0a]">
                                {{ match($rule->applies_to) {
                                    'all' => '🌍 Tous',
                                    'specific_promo' => '📄 Code promo spécifique',
                                    'specific_coupon' => '🎟️ Coupon spécifique',
                                    'global_offers' => '🏷️ Offres globales',
                                    default => $rule->applies_to
                                } }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[11px] font-medium
                                    {{ $rule->severity == 1 ? 'bg-blue-100 text-blue-700' : ($rule->severity == 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $rule->getSeverityLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-[12px] font-medium">
                                {{ $rule->logs()->count() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Toggle --}}
                                    <button onclick="toggleRule({{ $rule->id }})"
                                            class="p-2 {{ $rule->is_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} rounded-lg hover:opacity-75">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>
                                        </svg>
                                    </button>

                                    {{-- View --}}
                                    <a href="{{ route('admin.abuse-rules.show', $rule) }}" class="p-2 bg-[#f7f7f5] text-[#0a0a0a] rounded-lg hover:bg-[#e0e0dc]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.abuse-rules.edit', $rule) }}" class="p-2 bg-[#f7f7f5] text-[#0a0a0a] rounded-lg hover:bg-[#e0e0dc]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.abuse-rules.destroy', $rule) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-[13px] text-[#a0a09a]">Aucune règle créée</div>
                                <a href="{{ route('admin.abuse-rules.create') }}" class="text-[12px] text-[#0a0a0a] font-medium mt-2 inline-block">
                                    Créer la première règle →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($rules->hasPages())
            <div class="px-6 py-4 border-t border-[#e0e0dc]">
                {{ $rules->links() }}
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="mt-6">
        <a href="{{ route('admin.abuse-rules.violations') }}" class="text-[13px] text-[#0a0a0a] font-medium hover:underline">
            Voir le journal des violations →
        </a>
    </div>

</div>

<script>
async function toggleRule(ruleId) {
    try {
        const response = await fetch(`/admin/abuse-rules/${ruleId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}
</script>

@endsection
