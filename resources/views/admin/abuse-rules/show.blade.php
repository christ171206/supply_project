@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.abuse-rules.index') }}" class="text-[12px] text-[#a0a09a] hover:text-[#0a0a0a] flex items-center gap-1">
            ← Retour aux règles
        </a>
        <div class="flex items-center justify-between mt-4">
            <div>
                <h1 class="font-serif text-3xl text-[#0a0a0a]">{{ $rule->name }}</h1>
                <p class="text-[13px] text-[#a0a09a] mt-2">{{ $rule->description }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-[11px] font-medium
                    {{ $rule->is_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $rule->is_enabled ? '✓ Activée' : '✕ Désactivée' }}
                </span>
                <span class="px-3 py-1 rounded-full text-[11px] font-medium
                    {{ $rule->severity == 1 ? 'bg-blue-100 text-blue-700' : ($rule->severity == 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $rule->getSeverityLabel() }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="col-span-2 space-y-6">

            {{-- Rule Details --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h2 class="font-serif text-lg text-[#0a0a0a] mb-4">Configuration</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-[12px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">Type de Règle</dt>
                        <dd class="text-[13px] text-[#0a0a0a]">{{ $rule->getTypeLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-[12px] font-medium text-[#a0a09a] uppercase tracking-wider mb-1">S'Applique À</dt>
                        <dd class="text-[13px] text-[#0a0a0a]">
                            {{ match($rule->applies_to) {
                                'all' => '🌍 Tous les codes promos et coupons',
                                'specific_promo' => '📄 Code promo: ' . ($rule->promoCode?->code ?? 'N/A'),
                                'specific_coupon' => '🎟️ Coupon: ' . ($rule->clientCoupon?->id ?? 'N/A'),
                                'global_offers' => '🏷️ Offres globales',
                                default => $rule->applies_to
                            } }}
                        </dd>
                    </div>
                    @if ($rule->config && count($rule->config) > 0)
                        <div>
                            <dt class="text-[12px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Paramètres</dt>
                            <dd class="space-y-2">
                                @foreach ($rule->config as $key => $value)
                                    <div class="bg-[#f7f7f5] px-3 py-2 rounded-lg text-[12px]">
                                        <span class="font-medium text-[#0a0a0a]">{{ str_replace('_', ' ', ucfirst($key)) }}:</span>
                                        <span class="text-[#a0a09a] ml-2">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Recent Violations --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-serif text-lg text-[#0a0a0a]">Violations Récentes</h2>
                    <a href="{{ route('admin.abuse-rules.violations') }}?rule_id={{ $rule->id }}" class="text-[12px] text-[#0a0a0a] font-medium hover:underline">
                        Voir tout →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-[12px]">
                        <thead class="bg-[#f7f7f5] border-b border-[#e0e0dc]">
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-medium text-[#0a0a0a]">Utilisateur</th>
                                <th class="px-4 py-2 text-left text-[11px] font-medium text-[#0a0a0a]">Type</th>
                                <th class="px-4 py-2 text-left text-[11px] font-medium text-[#0a0a0a]">Perte Potentielle</th>
                                <th class="px-4 py-2 text-left text-[11px] font-medium text-[#0a0a0a]">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0dc]">
                            @forelse($rule->logs()->latest()->limit(5)->get() as $log)
                                <tr class="hover:bg-[#f7f7f5]">
                                    <td class="px-4 py-3">
                                        @if($log->user)
                                            <div class="font-medium">{{ $log->user->name }}</div>
                                            <div class="text-[11px] text-[#a0a09a]">{{ $log->user->email }}</div>
                                        @else
                                            <span class="text-[#a0a09a]">Anonyme</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-[10px] font-medium
                                            {{ $log->violation_type == 'attempted' ? 'bg-blue-100 text-blue-700' : ($log->violation_type == 'blocked' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $log->getTypeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono">{{ number_format($log->potential_loss, 0, ',', ' ') }} F</td>
                                    <td class="px-4 py-3 text-[#a0a09a]">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-[#a0a09a]">
                                        Aucune violation enregistrée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Stats Card --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="font-serif text-lg text-[#0a0a0a] mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Total Violations</div>
                        <div class="font-mono text-2xl font-bold text-[#0a0a0a]">{{ $rule->logs()->count() }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Perte Potentielle</div>
                        <div class="font-mono text-2xl font-bold text-red-600">{{ number_format($rule->logs()->sum('potential_loss'), 0, ',', ' ') }} F</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-medium text-[#a0a09a] uppercase tracking-wider mb-2">Cette Semaine</div>
                        <div class="font-mono text-2xl font-bold text-[#0a0a0a]">
                            {{ $rule->logs()->where('created_at', '>=', now()->subDays(7))->count() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Admin Info --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="font-serif text-lg text-[#0a0a0a] mb-4">Informations</h3>
                <dl class="space-y-3 text-[12px]">
                    <div>
                        <dt class="font-medium text-[#a0a09a] mb-1">Créée par</dt>
                        <dd class="text-[#0a0a0a]">{{ $rule->creator?->name ?? 'Système' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-[#a0a09a] mb-1">Créée le</dt>
                        <dd class="text-[#0a0a0a]">{{ $rule->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-[#a0a09a] mb-1">Modifiée le</dt>
                        <dd class="text-[#0a0a0a]">{{ $rule->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Actions --}}
            <div class="bg-white border border-[#e0e0dc] rounded-lg p-6">
                <h3 class="font-serif text-lg text-[#0a0a0a] mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.abuse-rules.edit', $rule) }}"
                       class="block w-full text-center px-4 py-2 bg-[#0a0a0a] text-white rounded-lg text-[13px] font-medium hover:bg-[#2a2a28]">
                        Modifier
                    </a>
                    <button onclick="toggleRule({{ $rule->id }})"
                            class="block w-full px-4 py-2 border border-[#e0e0dc] text-[#0a0a0a] rounded-lg text-[13px] font-medium hover:bg-[#f7f7f5]">
                        {{ $rule->is_enabled ? 'Désactiver' : 'Activer' }}
                    </button>
                    <form action="{{ route('admin.abuse-rules.destroy', $rule) }}" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full px-4 py-2 bg-red-100 text-red-600 rounded-lg text-[13px] font-medium hover:bg-red-200">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>

        </div>

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
