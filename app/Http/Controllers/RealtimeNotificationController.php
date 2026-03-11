<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeNotificationController extends Controller
{
    /**
     * Initialize Pusher for the user
     * GET /api/notifications/init
     */
    public function init(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get Pusher configuration
        $pusherConfig = [
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.region') ?? 'mt1',
            'useTLS' => true,
        ];

        // Determine channels based on user role
        $channels = [];

        if ($user->hasRole('vendeur')) {
            $channels[] = 'vendor-notifications.' . $user->id;
        }

        if ($user->hasRole('client')) {
            $channels[] = 'user-notifications.' . $user->id;
        }

        $channels[] = 'user-messages.' . $user->id;

        return response()->json([
            'pusher' => $pusherConfig,
            'channels' => $channels,
            'user_id' => $user->id,
            'user_name' => $user->prenom . ' ' . $user->nom,
        ]);
    }

    /**
     * Get all notifications for the user
     * GET /api/notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get notifications from database if stored
        // For now, return empty array - notifications are handled by Pusher events
        return response()->json([
            'notifications' => [],
            'unread_count' => 0,
        ]);
    }

    /**
     * Play notification sound
     * GET /api/notifications/sound
     */
    public function sound()
    {
        // Simple notification sound indicator
        return response()->json([
            'sound' => 'notification-sound.mp3',
            'enabled' => true,
        ]);
    }

    /**
     * Test notification
     * POST /api/notifications/test
     */
    public function test(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Dispatch a test event
        \Illuminate\Support\Facades\Event::dispatch(
            new \App\Events\OrderCreated(
                \App\Models\Commande::first() ?? new \App\Models\Commande()
            )
        );

        return response()->json([
            'message' => 'Test notification sent',
        ]);
    }
}
