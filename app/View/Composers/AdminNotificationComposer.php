<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminNotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $adminNotifications = Notification::where('user_id', Auth::id())
                ->where('lu', false)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get();

            $unreadNotificationsCount = Notification::where('user_id', Auth::id())
                ->where('lu', false)
                ->count();

            $view->with([
                'adminNotifications' => $adminNotifications,
                'unreadNotificationsCount' => $unreadNotificationsCount,
            ]);
        }
    }
}
