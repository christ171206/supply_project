<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    /**
     * Créer un admin pour tester
     */
    public function createTestAdmin()
    {
        // Vérifier si un admin test existe déjà
        $admin = User::where('email', 'admin@test.local')->first();

        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.local',
                'password' => Hash::make('Admin123!'),
                'is_admin' => true,
                'role' => 'admin',
                'country' => 'Côte d\'Ivoire',
            ]);
            return response()->json(['message' => 'Admin créé', 'admin' => $admin], 201);
        }

        return response()->json(['message' => 'Admin existe déjà', 'admin' => $admin]);
    }

    /**
     * Lister les admins
     */
    public function listAdmins()
    {
        $admins = User::where('is_admin', true)->get(['id', 'name', 'email', 'created_at']);
        return response()->json(['admins' => $admins, 'count' => $admins->count()]);
    }
}
