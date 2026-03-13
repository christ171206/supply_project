<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationCenterController extends Controller
{
    /**
     * Afficher le centre de notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $filter = $request->input('filter', 'all'); // all, unread, read
        $type = $request->input('type', null); // filter by type

        $query = Notification::where('user_id', $user->id);

        // Filtrer par statut de lecture
        if ($filter === 'unread') {
            $query->where('lu', false);
        } elseif ($filter === 'read') {
            $query->where('lu', true);
        }

        // Filtrer par type
        if ($type) {
            $query->where('type', 'like', "{$type}%");
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques
        $unreadCount = Notification::where('user_id', $user->id)->where('lu', false)->count();
        $totalCount = Notification::where('user_id', $user->id)->count();

        return view('notifications.center', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
            'filter' => $filter,
            'type' => $type,
        ]);
    }

    /**
     * Récupérer les notifications récentes (API)
     */
    public function getRecent(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit', 5);

        $recent = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'titre' => $notif->titre,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'lu' => $notif->lu,
                    'created_at' => $notif->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $recent,
            'unread_count' => Notification::where('user_id', $user->id)->where('lu', false)->count(),
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);

        $notification->update([
            'lu' => true,
            'lu_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function clearRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('lu', true)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir le badge count
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('lu', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
