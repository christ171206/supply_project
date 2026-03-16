<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Categorie;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    protected function checkOwnership(FlashSale $flashSale)
    {
        if ($flashSale->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $user = auth()->user();

        $flashSales = FlashSale::where('user_id', $user->id)
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        return view('vendeur.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('vendeur.flash-sales.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'pourcentage_reduction' => 'required|numeric|min:1|max:99',
            'date_debut' => 'required|date|after_or_equal:now',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['statut'] = 'actif';

        $flashSale = FlashSale::create($validated);

        return redirect()->route('vendeur.flash-sales.show', $flashSale->id)
            ->with('success', 'Vente flash créée!');
    }

    public function show(FlashSale $flashSale)
    {
        $this->checkOwnership($flashSale);

        $produits = $flashSale->categorie->produits()
            ->where('est_actif', true)
            ->paginate(10);

        return view('vendeur.flash-sales.show', compact('flashSale', 'produits'));
    }

    public function edit(FlashSale $flashSale)
    {
        $this->checkOwnership($flashSale);
        $categories = Categorie::orderBy('nom')->get();

        return view('vendeur.flash-sales.edit', compact('flashSale', 'categories'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $this->checkOwnership($flashSale);

        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'pourcentage_reduction' => 'required|numeric|min:1|max:99',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:actif,inactif',
        ]);

        $flashSale->update($validated);

        return redirect()->route('vendeur.flash-sales.show', $flashSale->id)
            ->with('success', 'Vente flash mise à jour!');
    }

    public function destroy(FlashSale $flashSale)
    {
        $this->checkOwnership($flashSale);
        $flashSale->delete();

        return redirect()->route('vendeur.flash-sales.index')
            ->with('success', 'Vente flash supprimée!');
    }

    public function toggle(FlashSale $flashSale)
    {
        $this->checkOwnership($flashSale);
        $flashSale->update(['archive' => !$flashSale->archive]);

        return back()->with('success', 'Statut mis à jour!');
    }
}
