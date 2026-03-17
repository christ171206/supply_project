<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalOffer;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminGlobalOfferController extends Controller
{
    /**
     * Display all global offers
     */
    public function index()
    {
        $offers = GlobalOffer::with('creator')
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        $stats = [
            'total' => GlobalOffer::count(),
            'active' => GlobalOffer::active()->count(),
            'upcoming' => GlobalOffer::where('start_date', '>', now())->count(),
            'expired' => GlobalOffer::where('end_date', '<', now())->count(),
            'total_discount_given' => GlobalOffer::sum('total_discount_given'),
            'total_usage' => GlobalOffer::sum('usage_count'),
        ];

        return view('admin.global-offers.index', compact('offers', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Categorie::where('is_active', true)->get(['id', 'nom']);
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->get(['id', 'nom']);
        $products = Produit::where('est_actif', true)->get(['id', 'nom']);

        return view('admin.global-offers.create', compact('categories', 'vendors', 'products'));
    }

    /**
     * Store new offer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:discount_percent,discount_fixed,free_shipping,buy_x_get_y,tiered_discount',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'target_type' => 'required|in:all,category,vendor,product',
            'target_id' => 'nullable|numeric',
            'min_purchase' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'config' => 'nullable|json',
        ]);

        try {
            $offer = GlobalOffer::create([
                ...$validated,
                'created_by' => auth()->id(),
                'is_active' => true,
            ]);

            return redirect()->route('admin.global-offers.show', $offer)
                ->with('success', 'Offre créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show offer details
     */
    public function show(GlobalOffer $offer)
    {
        $offer->load('creator');

        // Get related items
        $targetInfo = null;
        if ($offer->target_id) {
            $targetInfo = match ($offer->target_type) {
                'category' => Categorie::find($offer->target_id),
                'vendor' => User::find($offer->target_id),
                'product' => Produit::find($offer->target_id),
                default => null,
            };
        }

        // Get usage stats
        $stats = [
            'usage_count' => $offer->usage_count,
            'total_discount' => $offer->total_discount_given,
            'avg_discount' => $offer->usage_count > 0 ? round($offer->total_discount_given / $offer->usage_count, 2) : 0,
            'is_active' => $offer->isActiveNow(),
            'days_remaining' => $offer->end_date->diffInDays(now()),
        ];

        return view('admin.global-offers.show', compact('offer', 'targetInfo', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(GlobalOffer $offer)
    {
        $categories = Categorie::where('is_active', true)->get(['id', 'nom']);
        $vendors = User::where('role', 'vendor')->where('status', 'approved')->get(['id', 'nom']);
        $products = Produit::where('est_actif', true)->get(['id', 'nom']);

        return view('admin.global-offers.edit', compact('offer', 'categories', 'vendors', 'products'));
    }

    /**
     * Update offer
     */
    public function update(Request $request, GlobalOffer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:discount_percent,discount_fixed,free_shipping,buy_x_get_y,tiered_discount',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'target_type' => 'required|in:all,category,vendor,product',
            'target_id' => 'nullable|numeric',
            'min_purchase' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'sometimes|boolean',
            'config' => 'nullable|json',
        ]);

        try {
            $offer->update([
                ...$validated,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.global-offers.show', $offer)
                ->with('success', 'Offre mise à jour');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Toggle offer active status
     */
    public function toggle(GlobalOffer $offer)
    {
        $offer->update([
            'is_active' => !$offer->is_active,
            'updated_by' => auth()->id(),
        ]);

        $status = $offer->is_active ? 'activée' : 'désactivée';

        return response()->json([
            'success' => true,
            'message' => "Offre {$status}",
            'is_active' => $offer->is_active,
        ]);
    }

    /**
     * Duplicate offer
     */
    public function duplicate(GlobalOffer $offer)
    {
        try {
            $newOffer = $offer->replicate();
            $newOffer->name = $offer->name . ' (Copie)';
            $newOffer->created_by = auth()->id();
            $newOffer->is_active = false;
            // Set new dates (same duration from now)
            $duration = $offer->end_date->diffInDays($offer->start_date);
            $newOffer->start_date = now()->addDay();
            $newOffer->end_date = now()->addDays($duration + 1);
            $newOffer->usage_count = 0;
            $newOffer->total_discount_given = 0;
            $newOffer->save();

            return redirect()->route('admin.global-offers.edit', $newOffer)
                ->with('success', 'Offre dupliquée');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Delete offer
     */
    public function destroy(GlobalOffer $offer)
    {
        try {
            $offer->delete();

            return redirect()->route('admin.global-offers.index')
                ->with('success', 'Offre supprimée');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Get offer stats (API)
     */
    public function getStats(GlobalOffer $offer)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $offer->id,
                'name' => $offer->name,
                'type' => $offer->type,
                'usage_count' => $offer->usage_count,
                'total_discount' => $offer->total_discount_given,
                'avg_discount' => $offer->usage_count > 0 ? round($offer->total_discount_given / $offer->usage_count, 2) : 0,
                'is_active' => $offer->isActiveNow(),
                'status' => $offer->isActiveNow() ? 'active' : ($offer->start_date > now() ? 'upcoming' : 'expired'),
            ]
        ]);
    }

    /**
     * Test offer calculation
     */
    public function testCalculation(Request $request)
    {
        $validated = $request->validate([
            'offer_id' => 'required|exists:global_offers,id',
            'cart_total' => 'required|numeric|min:0',
            'items' => 'required|array',
        ]);

        try {
            $offer = GlobalOffer::find($validated['offer_id']);
            $result = $offer->calculateCartDiscount($validated['items'], $validated['cart_total']);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available products for offer targeting
     */
    public function getTargetOptions(Request $request)
    {
        $type = $request->input('type');

        $options = match ($type) {
            'category' => Categorie::where('is_active', true)
                ->get(['id', 'nom as label']),
            'vendor' => User::where('role', 'vendor')
                ->where('status', 'approved')
                ->get(['id', 'nom as label']),
            'product' => Produit::where('est_actif', true)
                ->get(['id', 'nom as label']),
            default => collect([]),
        };

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    /**
     * Export offers data
     */
    public function export(Request $request)
    {
        $query = GlobalOffer::with('creator');

        // Filter by status if requested
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        $offers = $query->get();

        // Create CSV
        $filename = 'global-offers-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Header
        fputcsv($handle, [
            'Nom',
            'Type',
            'Valeur',
            'Cible',
            'Date Début',
            'Date Fin',
            'Statut',
            'Utilisations',
            'Total Réduit',
            'Créé par',
        ]);

        // Data
        foreach ($offers as $offer) {
            fputcsv($handle, [
                $offer->name,
                $offer->getTypeLabel(),
                $offer->type === 'discount_percent' ? $offer->value . '%' : $offer->value . ' FCFA',
                $offer->getTargetDescription(),
                $offer->start_date->format('d/m/Y H:i'),
                $offer->end_date->format('d/m/Y H:i'),
                $offer->is_active ? 'Actif' : 'Inactif',
                $offer->usage_count,
                $offer->total_discount_given . ' FCFA',
                $offer->creator->nom,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
