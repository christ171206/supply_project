<?php

namespace App\Http\Controllers\Admin;

use App\Models\Avis;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminAvisController extends Controller
{
    /**
     * Afficher tous les avis avec filtres
     */
    public function index(Request $request): View
    {
        $query = Avis::with(['user', 'produit']);

        // Filtrer par appropriation
        if ($request->has('status')) {
            if ($request->status == 'inappropriate') {
                $query->where('is_appropriate', false);
            } elseif ($request->status == 'appropriate') {
                $query->where('is_appropriate', true);
            }
        }

        // Filtrer par produit
        if ($request->has('produit_id') && $request->produit_id) {
            $query->where('produit_id', $request->produit_id);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where('commentaire', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                ->orWhereHas('produit', fn($q) => $q->where('nom', 'like', '%' . $request->search . '%'));
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'DESC');
        $query->orderBy($sortBy, $sortOrder);

        $avis = $query->paginate(20);

        return view('admin.avis.index', [
            'avis' => $avis,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Afficher un avis en détail
     */
    public function show(Avis $avis): View
    {
        return view('admin.avis.show', [
            'avis' => $avis->load(['user', 'produit', 'deletedByAdmin']),
        ]);
    }

    /**
     * Supprimer un avis (marqué comme inapproprié)
     */
    public function delete(Request $request, Avis $avis)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $avis->only(['is_appropriate', 'deleted_by_admin', 'deleted_at', 'delete_reason']);

        $avis->deleteByAdmin(auth()->user(), $validated['reason']);

        // Log l'action
        AuditService::logUpdate(
            'Avis',
            $avis->id,
            "Avis produit {$avis->produit->nom}",
            $oldValues,
            $avis->only(['is_appropriate', 'deleted_by_admin', 'deleted_at', 'delete_reason'])
        );

        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis supprimé avec succès !');
    }

    /**
     * Restaurer avis supprimé
     */
    public function restore(Avis $avis)
    {
        if (!$avis->deleted_at) {
            return back()->with('error', 'Cet avis n\'a pas été supprimé.');
        }

        $oldValues = $avis->only(['is_appropriate', 'deleted_by_admin', 'deleted_at', 'delete_reason']);

        $avis->restore();

        // Log l'action
        AuditService::logUpdate(
            'Avis',
            $avis->id,
            "Avis produit {$avis->produit->nom}",
            $oldValues,
            $avis->only(['is_appropriate', 'deleted_by_admin', 'deleted_at', 'delete_reason'])
        );

        return back()->with('success', 'Avis restauré avec succès !');
    }

    /**
     * Afficher avis censurés/inappropriés
     */
    public function inappropriate(Request $request): View
    {
        $query = Avis::where('is_appropriate', false)->with(['user', 'produit', 'deletedByAdmin']);

        if ($request->has('search') && $request->search) {
            $query->where('commentaire', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $avis = $query->orderBy('deleted_at', 'DESC')->paginate(20);

        return view('admin.avis.inappropriate', [
            'avis' => $avis,
            'filters' => $request->all(),
        ]);
    }
}
