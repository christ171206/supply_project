<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminDisputeController extends Controller
{
    /**
     * Lister tous les litiges
     */
    public function index(Request $request)
    {
        $query = Dispute::with('user', 'vendor', 'commande');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('vendor', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $disputes = $query->latest()->paginate(15);

        // Compter les litiges par statut
        $openCount = Dispute::where('status', 'open')->count();
        $inProgressCount = Dispute::where('status', 'in_progress')->count();
        $resolvedCount = Dispute::where('status', 'resolved')->count();
        $totalAmount = Dispute::sum('resolution_amount') ?? 0;

        return view('admin.disputes.index', [
            'disputes' => $disputes,
            'openCount' => $openCount,
            'inProgressCount' => $inProgressCount,
            'resolvedCount' => $resolvedCount,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Afficher les détails d'un litige
     */
    public function show(Dispute $dispute)
    {
        $dispute->load('user', 'vendor', 'commande', 'commande.ligneCommandes');

        return view('admin.disputes.show', [
            'dispute' => $dispute,
        ]);
    }

    /**
     * Mettre à jour le statut d'un litige
     */
    public function updateStatus(Request $request, Dispute $dispute)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'notes' => 'nullable|string',
        ]);

        $dispute->update([
            'status' => $request->input('status'),
            'admin_notes' => $request->input('notes') ?? $dispute->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Statut du litige mis à jour.');
    }

    /**
     * Résoudre un litige
     */
    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'resolution' => 'required|in:refund,replacement,partial_refund,no_action',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'required|string',
        ]);

        $admin = auth()->user();
        $amount = null;

        if ($request->input('resolution') !== 'no_action') {
            $amount = $request->input('amount') ?? $dispute->commande->total;
        }

        $dispute->resolve(
            $admin,
            $request->input('resolution'),
            $amount,
            $request->input('notes')
        );

        // TODO: Mettre en place le remboursement si nécessaire
        if ($request->input('resolution') === 'refund' || $request->input('resolution') === 'partial_refund') {
            // Appeler le service de paiement pour effectuer le remboursement
        }

        return redirect()->back()->with('success', 'Litige résolu.');
    }

    /**
     * Fermé un litige résolu
     */
    public function close(Dispute $dispute)
    {
        $dispute->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Litige fermé.');
    }

    /**
     * Lister les litiges en attente d'action
     */
    public function pending()
    {
        $disputes = Dispute::where('status', '!=', 'closed')
            ->with('user', 'vendor', 'commande')
            ->latest()
            ->paginate(15);

        return view('admin.disputes.pending', [
            'disputes' => $disputes,
        ]);
    }
}
