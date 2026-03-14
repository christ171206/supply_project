<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserBan;
use App\Models\AdminRole;
use App\Services\AuditService;
use App\Mail\DocumentRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

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
            ->orderBy('created_at', 'desc')
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
            $document->user->update(['vendor_status' => 'approved']);
        }

        return redirect()->back()->with('success', 'Document approuvé avec succès.');
    }

    /**
     * Rejeter un document
     */
    public function rejectDocument(Request $request, UserDocument $document)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $admin = auth()->user();
        $reason = $request->input('reason');

        // Rejeter le document
        $document->reject($admin, $reason);

        // Envoyer un mail au vendeur (avec gestion d'erreur)
        $user = $document->user;
        try {
            Mail::to($user->email)->send(new \App\Mail\DocumentRejectedMail($user, $document, $reason));
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas bloquer l'action
            \Illuminate\Support\Facades\Log::error('Erreur envoi email rejet document', [
                'document_id' => $document->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Log dans l'audit
        AuditService::logUpdate(
            'UserDocument',
            $document->id,
            "Document {$document->document_type} de {$user->name}",
            ['status' => 'pending'],
            ['status' => 'rejected', 'rejection_reason' => $reason],
            "Document rejeté: {$reason}"
        );

        return redirect()->back()->with('success', 'Document rejeté avec succès. Un email a été envoyé au vendeur.');
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

            // Envoyer l'email d'approbation
            try {
                \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\VendorApprovedMail($user));
            } catch (\Exception $emailError) {
                \Illuminate\Support\Facades\Log::warning(
                    "Erreur envoi email approbation vendeur #" . $user->id . ": " . $emailError->getMessage()
                );
                // Continuer même si l'email échoue
            }

            return back()->with('success', 'Vendeur approuvé avec succès. Un email de confirmation a été envoyé.');
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
