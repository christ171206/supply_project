@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl px-4 py-8">
    {{-- Header --}}
    <div class="mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-4xl font-serif text-gray-900">Centre de Notifications</h1>
            @if($unreadCount > 0)
                <button onclick="markAllAsRead()" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition">
                    ✓ Marquer tout comme lu
                </button>
            @endif
        </div>
        <p class="text-gray-600">{{ $totalCount }} notification(s) • {{ $unreadCount }} non lue(s)</p>
    </div>

    {{-- Filtres --}}
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('notifications.center', ['filter' => 'all']) }}"
           class="px-3 py-2 rounded-lg text-sm {{ $filter === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Tous ({{ $totalCount }})
        </a>
        <a href="{{ route('notifications.center', ['filter' => 'unread']) }}"
           class="px-3 py-2 rounded-lg text-sm {{ $filter === 'unread' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Non lues ({{ $unreadCount }})
        </a>
        <a href="{{ route('notifications.center', ['filter' => 'read']) }}"
           class="px-3 py-2 rounded-lg text-sm {{ $filter === 'read' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Lues
        </a>

        {{-- Type filter --}}
        <select onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 border border-gray-300 text-sm">
            <option value="">Tous les types</option>
            <option value="order_status" {{ $type === 'order_status' ? 'selected' : '' }}>État commande</option>
            <option value="vendor_order" {{ $type === 'vendor_order' ? 'selected' : '' }}>Nouvelles commandes</option>
            <option value="stock_alert" {{ $type === 'stock_alert' ? 'selected' : '' }}>Alertes stock</option>
            <option value="delivery_reminder" {{ $type === 'delivery_reminder' ? 'selected' : '' }}>Rappels livraison</option>
        </select>
    </div>

    @if($notifications->isEmpty())
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-gray-500 text-lg">Aucune notification pour le moment</p>
            <p class="text-gray-400 text-sm mt-2">Vous serez notifié des changements importants ici</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="bg-white border {{ $notification->lu ? 'border-gray-200' : 'border-gray-300 bg-gray-50' }} rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900 break-words">
                                    {{ $notification->titre }}
                                </h3>
                                @if(!$notification->lu)
                                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                                @endif
                            </div>
                            <p class="text-gray-700 text-sm mb-2">{{ $notification->message }}</p>
                            <p class="text-gray-500 text-xs">
                                {{ $notification->created_at->diffForHumans() }}
                                <span class="mx-2">•</span>
                                <span class="inline-block px-2 py-1 bg-gray-100 rounded text-gray-600 text-xs">
                                    {{ getNotificationTypeLabel($notification->type) }}
                                </span>
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 flex-shrink-0">
                            @if(!$notification->lu)
                                <button onclick="markAsRead({{ $notification->id }})"
                                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition"
                                        title="Marquer comme lu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            @endif
                            <button onclick="deleteNotification({{ $notification->id }})"
                                    class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition"
                                    title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $notifications->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<script>
    function markAsRead(id) {
        fetch(`/notifications/${id}/mark-read`, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
    }

    function markAllAsRead() {
        if (confirm('Marquer toutes les notifications comme lues ?')) {
            fetch('/notifications/mark-all-read', { method: 'POST' })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    }

    function deleteNotification(id) {
        if (confirm('Supprimer cette notification ?')) {
            fetch(`/notifications/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    }
</script>

<style>
    .break-words {
        word-break: break-word;
    }
</style>
@endsection
