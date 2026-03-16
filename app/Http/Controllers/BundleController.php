<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Produit;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    protected function checkOwnership(Bundle $bundle)
    {
        if ($bundle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $user = auth()->user();

        $bundles = Bundle::where('user_id', $user->id)
            ->with('produits')
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        return view('vendeur.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $user = auth()->user();
        $produits = Produit::where('user_id', $user->id)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        return view('vendeur.bundles.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'prix_bundle' => 'required|numeric|min:0.01',
            'date_debut' => 'required|date|after_or_equal:now',
            'date_fin' => 'required|date|after:date_debut',
            'quantite_disponible' => 'nullable|integer|min:1',
            'produits' => 'required|array|min:2',
            'produits.*' => 'exists:produits,id',
            'quantites' => 'required|array',
            'quantites.*' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['statut'] = 'actif';

        // Vérifier que les produits appartiennent à l'utilisateur
        $user = auth()->user();
        $produitIds = $validated['produits'];

        foreach ($produitIds as $id) {
            if (!Produit::where('id', $id)->where('user_id', $user->id)->exists()) {
                return back()->with('error', 'Vous ne pouvez ajouter que vos propres produits');
            }
        }

        // Créer le bundle sans les produits d'abord
        $bundleData = collect($validated)->except(['produits', 'quantites'])->toArray();
        $bundle = Bundle::create($bundleData);

        // Attacher les produits avec leurs quantités
        foreach ($produitIds as $index => $produitId) {
            $quantite = $validated['quantites'][$index] ?? 1;
            $bundle->produits()->attach($produitId, ['quantite' => $quantite]);
        }

        // Calculer prix original
        $prixOriginal = $bundle->getPrixTotalOriginal();
        $bundle->update(['prix_original' => $prixOriginal]);

        return redirect()->route('vendeur.bundles.show', $bundle->id)
            ->with('success', 'Bundle créé!');
    }

    public function show(Bundle $bundle)
    {
        $this->checkOwnership($bundle);

        return view('vendeur.bundles.show', compact('bundle'));
    }

    public function edit(Bundle $bundle)
    {
        $this->checkOwnership($bundle);

        $user = auth()->user();
        $allProduits = Produit::where('user_id', $user->id)
            ->where('est_actif', true)
            ->orderBy('nom')
            ->get();

        return view('vendeur.bundles.edit', compact('bundle', 'allProduits'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $this->checkOwnership($bundle);

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'prix_bundle' => 'required|numeric|min:0.01',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'quantite_disponible' => 'nullable|integer|min:1',
            'statut' => 'required|in:actif,inactif',
            'produits' => 'required|array|min:2',
            'produits.*' => 'exists:produits,id',
            'quantites' => 'required|array',
            'quantites.*' => 'required|integer|min:1',
        ]);

        $bundleData = collect($validated)->except(['produits', 'quantites'])->toArray();
        $bundle->update($bundleData);

        // Re-synchroniser les produits
        $bundle->produits()->detach();
        foreach ($validated['produits'] as $index => $produitId) {
            $quantite = $validated['quantites'][$index] ?? 1;
            $bundle->produits()->attach($produitId, ['quantite' => $quantite]);
        }

        // Recalculer prix original
        $prixOriginal = $bundle->getPrixTotalOriginal();
        $bundle->update(['prix_original' => $prixOriginal]);

        return redirect()->route('vendeur.bundles.show', $bundle->id)
            ->with('success', 'Bundle mis à jour!');
    }

    public function destroy(Bundle $bundle)
    {
        $this->checkOwnership($bundle);
        $bundle->delete();

        return redirect()->route('vendeur.bundles.index')
            ->with('success', 'Bundle supprimé!');
    }

    public function toggle(Bundle $bundle)
    {
        $this->checkOwnership($bundle);
        $bundle->update(['archive' => !$bundle->archive]);

        return back()->with('success', 'Statut mis à jour!');
    }
}
