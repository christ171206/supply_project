<!-- Security Logs Section for Profile -->
<!-- Add this to resources/views/client/profil.blade.php -->

<div class="mt-12 border-t pt-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">🔒 Historique de Sécurité</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Last Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dernière Activité</h3>
            @php
                /** @var \App\Models\SecurityLog|null $lastActivity */
                $lastActivity = auth()->user()->securityLogs()->latest()->first();
            @endphp
            @if($lastActivity)
                <div class="space-y-2">
                    <p class="text-gray-700">
                        <span class="font-semibold">Événement:</span>
                        {!! $lastActivity->getEventLabel() !!}
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold">Date:</span>
                        {{ $lastActivity->created_at->format('d/m/Y à H:i') }}
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold">Appareil:</span>
                        {{ $lastActivity->getDeviceLabel() }}
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold">Localisation:</span>
                        {{ $lastActivity->getLocationLabel() }}
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold">IP:</span>
                        <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $lastActivity->ip_address }}</code>
                    </p>
                </div>
            @else
                <p class="text-gray-500">Aucune activité enregistrée</p>
            @endif
        </div>

        <!-- Suspicious Activity Alert -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Activités Suspectes</h3>
            @php
                $suspiciousActivity = auth()->user()->securityLogs()
                    ->where('status', 'failed')
                    ->latest()
                    ->limit(3)
                    ->get();
            @endphp
            @if($suspiciousActivity->count() > 0)
                <div class="space-y-3">
                    @foreach($suspiciousActivity as $log)
                        <div class="bg-red-50 border border-red-200 rounded p-3">
                            <p class="text-sm text-red-800">
                                <strong>Tentative échouée</strong><br>
                                {{ $log->created_at->format('d/m/Y H:i') }} • {{ $log->ip_address }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">✅ Aucune activité suspecte détectée</p>
            @endif
        </div>
    </div>

    <!-- Full Security Log Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">📋 Historique Complet (Dernières 10 activités)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Date/Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Appareil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(auth()->user()->securityLogs()->latest()->limit(10)->get() as $log)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                {!! $log->getEventLabel() !!}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $log->getDeviceLabel() }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: @if($log->status === 'success') #dcfce7 @else #fee2e2 @endif; color: @if($log->status === 'success') #166534 @else #991b1b @endif;">
                                    {{ $log->status === 'success' ? '✅ Succès' : '❌ Échoué' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucune activité enregistrée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Conseils de Sécurité</h3>
        <ul class="text-blue-800 space-y-2 text-sm">
            <li>✓ Utilisez un mot de passe fort (min 12 caractères)</li>
            <li>✓ Ne partagez jamais votre mot de passe</li>
            <li>✓ Déconnectez-vous après chaque utilisation</li>
            <li>✓ Vérifiez régulièrement cet historique pour détecter les accès suspectes</li>
            <li>✓ Si vous voyez une activité inconnue, changez immédiatement votre mot de passe</li>
        </ul>
    </div>
</div>
