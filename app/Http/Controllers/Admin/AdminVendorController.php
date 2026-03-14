<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VendorApprovedMail;
use App\Mail\VendorRejectedMail;
use App\Models\User;
use App\Models\VendorValidation;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminVendorController extends Controller
{
    /**
     * Afficher la liste des vendeurs en attente de validation
     */
    public function index(): View
    {
        $pendingVendors = VendorValidation::pending()
            ->with('vendor')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $approvedVendors = VendorValidation::approved()
            ->with('vendor', 'reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->limit(10)
            ->get();

        $rejectedVendors = VendorValidation::rejected()
            ->with('vendor', 'reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.vendors.index', [
            'pendingVendors' => $pendingVendors,
            'approvedVendors' => $approvedVendors,
            'rejectedVendors' => $rejectedVendors,
            'pendingCount' => VendorValidation::pending()->count(),
            'approvedCount' => VendorValidation::approved()->count(),
            'rejectedCount' => VendorValidation::rejected()->count(),
        ]);
    }

    /**
     * Afficher les détails d'un vendeur en validation
     */
    public function show(VendorValidation $validation): View
    {
        $validation->load('vendor', 'reviewer');

        return view('admin.vendors.show', [
            'validation' => $validation,
            'vendor' => $validation->vendor,
        ]);
    }

    /**
     * Approuver un vendeur
     */
    public function approve(Request $request, VendorValidation $validation): RedirectResponse
    {
        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $vendor = $validation->vendor;

        // Mettre à jour le rôle de l'utilisateur
        $oldRole = $vendor->role;
        $vendor->update([
            'role' => 'vendor',
        ]);

        // Marquer comme approuvé
        $validation->approve(auth()->id(), $validated['review_notes'] ?? null);

        // Enregistrer l'action dans l'audit
        AuditService::logApprove(
            'VendorValidation',
            $validation->id,
            $vendor->name,
            $validated['review_notes'] ?? null
        );

        // Enregistrer le changement de rôle
        AuditService::logUpdate(
            'User',
            $vendor->id,
            $vendor->name,
            ['role' => $oldRole],
            ['role' => 'vendor'],
            'Vendeur approuvé'
        );

        // Envoyer l'email d'approbation au vendeur (avec gestion d'erreur)
        try {
            Mail::to($vendor->email)->send(new VendorApprovedMail($vendor, $validated['review_notes'] ?? null));
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas bloquer l'action
            \Illuminate\Support\Facades\Log::error('Erreur envoi email approbation vendeur', [
                'vendor_id' => $vendor->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.vendors.show', $validation)
            ->with('success', "Vendeur {$vendor->name} approuvé avec succès !");
    }

    /**
     * Rejeter un vendeur
     */
    public function reject(Request $request, VendorValidation $validation): RedirectResponse
    {
        $validated = $request->validate([
            'review_notes' => 'required|string|max:1000',
        ]);

        $vendor = $validation->vendor;

        // Marquer comme rejeté
        $validation->reject(auth()->id(), $validated['review_notes']);

        // Enregistrer l'action dans l'audit
        AuditService::logReject(
            'VendorValidation',
            $validation->id,
            $vendor->name,
            $validated['review_notes']
        );

        // Envoyer l'email de rejet au vendeur (avec gestion d'erreur)
        try {
            Mail::to($vendor->email)->send(new VendorRejectedMail($vendor, $validated['review_notes']));
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas bloquer l'action
            \Illuminate\Support\Facades\Log::error('Erreur envoi email rejet vendeur', [
                'vendor_id' => $vendor->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', "Demande de vendeur de {$vendor->name} rejetée.");
    }

    /**
     * Voir tous les vendeurs approuvés
     */
    public function approved(): View
    {
        $approvedVendors = User::where('role', 'vendor')
            ->with('validationVendeur')
            ->withCount('produits')
            ->paginate(15);

        return view('admin.vendors.approved', [
            'vendors' => $approvedVendors,
        ]);
    }

    /**
     * Suspendre un vendeur actif
     */
    public function suspend(User $user, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        if ($user->role !== 'vendor') {
            abort(404);
        }

        $oldStatus = $user->status;
        $user->update(['status' => 'suspended']);

        // Enregistrer l'action
        AuditService::logUpdate(
            'User',
            $user->id,
            $user->name,
            ['status' => $oldStatus],
            ['status' => 'suspended'],
            $validated['reason']
        );

        return back()->with('success', "Vendeur {$user->name} suspendu avec succès.");
    }

    /**
     * Réactiver un vendeur suspendu
     */
    public function reactivate(User $user): RedirectResponse
    {
        if ($user->role !== 'vendor' || $user->status !== 'suspended') {
            abort(404);
        }

        $oldStatus = $user->status;
        $user->update(['status' => 'active']);

        // Enregistrer l'action
        AuditService::logUpdate(
            'User',
            $user->id,
            $user->name,
            ['status' => $oldStatus],
            ['status' => 'active'],
            'Vendeur réactivé'
        );

        return back()->with('success', "Vendeur {$user->name} réactivé.");
    }
}
