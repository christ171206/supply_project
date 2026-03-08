@extends('layouts.admin-layout')

@section('title', 'Notifications')

@section('content')
<div class="p-6 space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifications</span>
            </h1>
            <p class="text-gray-600 mt-1">Gérez vos notifications</p>
        </div>
        @if(count($notifications) > 0)
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Marquer tout comme lu
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    @if(count($notifications) > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="bg-white rounded-lg shadow-md border-l-4 p-5 hover:shadow-lg transition
                    @if($notification->type === 'new_vendor_registration') border-orange-500 
                    @elseif($notification->type === 'vendor_documents_submitted') border-purple-500
                    @else border-blue-500
                    @endif
                    @if(!$notification->lu) bg-blue-50 @endif">
                    
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <div class="flex items-center gap-2 mb-2">
                                @if($notification->type === 'new_vendor_registration')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        🏪 Nouveau Vendeur
                                    </span>
                                @elseif($notification->type === 'vendor_documents_submitted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        📄 Documents Soumis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ℹ️ Info
                                    </span>
                                @endif
                                @if(!$notification->lu)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Non lue
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900">{{ $notification->titre }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $notification->message }}</p>
                            <p class="text-gray-400 text-xs mt-2">{{ $notification->created_at->format('d/m/Y à H:i') }}</p>
                        </div>

                        <div class="flex gap-2 ml-4 flex-shrink-0">
                            @if(!$notification->lu)
                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold transition">
                                        ✓ Marquer comme lu
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('notifications.delete', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette notification?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-semibold transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center border border-dashed border-gray-300">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune notification</h3>
            <p class="text-gray-600">Vous n'avez actuellement aucune notification</p>
        </div>
    @endif
</div>

<style>
    .bg-blue-50 {
        background-color: rgba(59, 130, 246, 0.05);
    }
</style>
@endsection
