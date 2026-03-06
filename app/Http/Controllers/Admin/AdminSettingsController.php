<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSettingsController extends Controller
{
    /**
     * Show admin settings page
     */
    public function index()
    {
        $admin = Auth::user();
        $settings = [
            'theme' => 'light',
            'items_per_page' => 25,
            'notify_orders' => true,
            'notify_vendors' => true,
            'notify_disputes' => true,
            'notify_email' => true,
            'notification_frequency' => 'daily',
            'notification_email' => $admin->email,
        ];

        return view('admin.settings', compact('admin', 'settings'));
    }

    /**
     * Update admin settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'in:light,dark,system',
            'items_per_page' => 'integer|in:10,25,50,100',
            'notify_orders' => 'boolean',
            'notify_vendors' => 'boolean',
            'notify_disputes' => 'boolean',
            'notify_email' => 'boolean',
            'notification_frequency' => 'in:instant,hourly,daily',
            'notification_email' => 'email',
        ]);

        $admin = Auth::user();

        // Store settings in session or database
        session([
            'admin_settings' => $validated,
        ]);

        // Log the action
        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('Settings updated');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès');
    }

    /**
     * Export admin data
     */
    public function exportData(Request $request)
    {
        $admin = Auth::user();

        // Log the action
        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('Data exported');
        }

        // Create a CSV or JSON export of admin data
        $data = [
            'user' => $admin,
            'created_at' => now(),
        ];

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename=admin-data.json',
        ]);
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs()
    {
        if (function_exists('activity')) {
            $logs = activity()
                ->causedBy(Auth::user())
                ->select('log_name', 'description', 'created_at')
                ->latest()
                ->paginate(10);
        } else {
            $logs = collect([]);
        }

        return view('admin.audit-logs', compact('logs'));
    }
}
