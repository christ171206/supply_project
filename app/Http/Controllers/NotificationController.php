<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'admin connecté
     */
    public function index(): View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de la notification
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update([
            'lu' => true,
            'lu_at' => now(),
        ]);

        return back()->with('message', 'Notification marquée comme lue');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(): RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return back()->with('message', 'Toutes les notifications sont marquées comme lues');
    }

    /**
     * Supprimer une notification
     */
    public function delete(Notification $notification): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de la notification
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('message', 'Notification supprimée');
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function deleteAllRead(): RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('lu', true)
            ->delete();

        return back()->with('message', 'Notifications lues supprimées');
    }
}
