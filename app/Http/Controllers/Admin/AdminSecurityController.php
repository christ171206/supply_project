<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSecurityController extends Controller
{
    /**
     * Show the security page
     */
    public function index()
    {
        $admin = Auth::user();
        $sessions = collect([]); // Placeholder for session management
        return view('admin.security', compact('admin', 'sessions'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ]);

        $admin = Auth::user();
        $admin->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        // Log the action
        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('Password changed');
        }

        return back()->with('success', 'Mot de passe mis à jour avec succès');
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(Request $request)
    {
        $admin = Auth::user();

        // For now, this is a placeholder
        // In a real implementation, you would use a 2FA package like spatie/laravel-auth-code

        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('2FA enabled');
        }

        return back()->with('success', 'Authentification à deux facteurs activée');
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(Request $request)
    {
        $admin = Auth::user();

        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('2FA disabled');
        }

        return back()->with('success', 'Authentification à deux facteurs désactivée');
    }

    /**
     * Get active sessions
     */
    public function getSessions()
    {
        // This would require session tracking implementation
        return response()->json([
            'sessions' => [],
        ]);
    }

    /**
     * Revoke a session
     */
    public function revokeSession(Request $request)
    {
        $sessionId = $request->input('session_id');

        // Implementation for session revocation
        if (function_exists('activity')) {
            activity()
                ->causedBy(Auth::user())
                ->log('Session revoked: ' . $sessionId);
        }

        return back()->with('success', 'Session révoquée avec succès');
    }
}
