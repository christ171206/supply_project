<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile edit form
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        $admin = Auth::user();
        $admin->update($validated);

        // Log the action
        if (function_exists('activity')) {
            activity()
                ->causedBy($admin)
                ->performedOn($admin)
                ->log('Profile updated');
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil mis à jour avec succès');
    }
}
