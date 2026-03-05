<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserBan;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminUserController extends Controller
{
    /**
     * Lister tous les utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'banned') {
                $query->where('is_banned', true);
            } elseif ($request->input('status') === 'active') {
                $query->where('is_banned', false);
            }
        }

        $users = $query->with('adminRole', 'activeBan')
            ->paginate(15);

        $adminRoles = AdminRole::all();

        return view('admin.users.index', [
            'users' => $users,
            'adminRoles' => $adminRoles,
        ]);
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load('documents', 'bans', 'disputes', 'commandes', 'produits');

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Valider les documents KYC
     */
    public function verifyDocuments(User $user)
    {
        $documents = UserDocument::where('user_id', $user->id)
            ->get()
            ->groupBy('status');

        return view('admin.users.documents', [
            'user' => $user,
            'documents' => $documents,
        ]);
    }

    /**
     * Approuver un document
     */
    public function approveDocument(Request $request, UserDocument $document)
    {
        $admin = auth()->user();
        $document->approve($admin);

        // Vérifier si tous les documents sont approuvés
        $pendingDocs = UserDocument::where('user_id', $document->user_id)
            ->whereIn('status', ['pending', 'rejected'])
            ->count();

        if ($pendingDocs === 0) {
            $document->user->update(['vendor_status' => 'verified']);
        }

        return redirect()->back()->with('success', 'Document approuvé avec succès.');
    }

    /**
     * Rejeter un document
     */
    public function rejectDocument(Request $request, UserDocument $document)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $admin = auth()->user();
        $document->reject($admin, $request->input('reason'));

        return redirect()->back()->with('success', 'Document rejeté avec raison.');
    }

    /**
     * Bannir un utilisateur
     */
    public function ban(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|in:fraud,late_delivery,policy_violation,harassment,counterfeit,other',
            'details' => 'required|string',
            'duration' => 'nullable|integer|min:0', // en jours, 0 = bannissement permanent
        ]);

        $admin = auth()->user();
        $unbannedAt = $request->input('duration')
            ? now()->addDays($request->input('duration'))
            : null;

        $user->ban(
            $admin,
            $request->input('reason'),
            $request->input('details'),
            $unbannedAt
        );

        return redirect()->back()->with('success', 'Utilisateur banni avec succès.');
    }

    /**
     * Débannir un utilisateur
     */
    public function unban(User $user)
    {
        $admin = auth()->user();

        $activeBan = $user->bans()->where('is_active', true)->first();
        if ($activeBan) {
            $activeBan->unban($admin);
        }

        return redirect()->back()->with('success', 'Utilisateur débanni avec succès.');
    }

    /**
     * Assigner un rôle admin
     */
    public function assignAdminRole(Request $request, User $user)
    {
        $request->validate([
            'admin_role_id' => 'nullable|exists:admin_roles,id',
        ]);

        $user->update([
            'admin_role_id' => $request->input('admin_role_id'),
            'is_admin' => $request->filled('admin_role_id'),
        ]);

        return redirect()->back()->with('success', 'Rôle admin assigné avec succès.');
    }

    /**
     * Voir l'historique des actions d'un utilisateur
     */
    public function activityLog(User $user)
    {
        $logs = \App\Models\SecurityLog::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('admin.users.activity-log', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    /**
     * Approuver un vendeur
     */
    public function approveVendor(User $user)
    {
        if ($user->role !== 'vendor') {
            return back()->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        try {
            $user->fill([
                'vendor_status' => 'approved',
                'vendor_approved_at' => now(),
            ])->save();

            return back()->with('success', 'Vendeur approuvé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }

    /**
     * Rejeter un vendeur
     */
    public function rejectVendor(User $user)
    {
        if ($user->role !== 'vendor') {
            return back()->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        try {
            $user->fill([
                'vendor_status' => 'rejected',
            ])->save();

            return back()->with('success', 'Vendeur rejeté');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du rejet : ' . $e->getMessage());
        }
    }
}
