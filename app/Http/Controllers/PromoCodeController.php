<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    protected function checkOwnership(PromoCode $promoCode)
    {
        if ($promoCode->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Liste les codes promo du vendeur
     */
    public function index()
    {
        $user = auth()->user();

        $promoCodes = PromoCode::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('vendeur.promo-codes.index', compact('promoCodes'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = auth()->user();
        $produits = Produit::where('user_id', $user->id)
            ->orderBy('nom')
            ->get();

        return view('vendeur.promo-codes.create', compact('produits'));
    }

    /**
     * Stocker un nouveau code promo
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'description' => 'nullable|string|max:500',
            'type_reduction' => 'required|in:pourcentage,montant_fixe',
            'taux_reduction' => 'required|numeric|min:0.01',
            'max_utilisations' => 'nullable|integer|min:1',
            'montant_minimum' => 'nullable|numeric|min:0',
            'montant_maximum' => 'nullable|numeric|min:0',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'produits' => 'nullable|array',
            'produits.*' => 'exists:produits,id',
        ], [
            'code.required' => 'Le code est obligatoire',
            'code.unique' => 'Ce code existe déjà',
            'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou après',
            'date_fin.after' => 'La date de fin doit être après la date de début',
        ]);

        // Normaliser le code (majuscules, sans espaces)
        $validated['code'] = strtoupper(str_replace(' ', '', $validated['code']));
        $validated['user_id'] = $user->id;
        $validated['statut'] = 'actif';

        // Créer le code promo
        $promoCode = PromoCode::create($validated);

        // Attacher les produits ciblés si fournis
        if ($request->has('produits') && !empty($validated['produits'])) {
            $promoCode->produits()->attach($validated['produits']);
        }

        return redirect()->route('vendeur.promo-codes.show', $promoCode->id)
            ->with('success', 'Code promo créé avec succès!');
    }

    /**
     * Afficher les détails d'un code promo
     */
    public function show(PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        $utilisations = $promoCode->utilisations()
            ->with(['commande', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendeur.promo-codes.show', compact('promoCode', 'utilisations'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        $user = auth()->user();
        $produits = Produit::where('user_id', $user->id)
            ->orderBy('nom')
            ->get();

        $produitSélectionnés = $promoCode->produits->pluck('id')->toArray();

        return view('vendeur.promo-codes.edit', compact('promoCode', 'produits', 'produitSélectionnés'));
    }

    /**
     * Mettre à jour un code promo
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'type_reduction' => 'required|in:pourcentage,montant_fixe',
            'taux_reduction' => 'required|numeric|min:0.01',
            'max_utilisations' => 'nullable|integer|min:1',
            'montant_minimum' => 'nullable|numeric|min:0',
            'montant_maximum' => 'nullable|numeric|min:0',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:actif,inactif',
            'produits' => 'nullable|array',
            'produits.*' => 'exists:produits,id',
        ]);

        $promoCode->update($validated);

        // Synchroniser les produits
        if ($request->has('produits')) {
            $promoCode->produits()->sync($validated['produits'] ?? []);
        }

        return redirect()->route('vendeur.promo-codes.show', $promoCode->id)
            ->with('success', 'Code promo mis à jour!');
    }

    /**
     * Archiver/Désarchiver
     */
    public function toggleArchive(PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        $promoCode->update(['archive' => !$promoCode->archive]);

        $message = $promoCode->archive ? 'Code archivé' : 'Code désarchivé';
        return back()->with('success', $message);
    }

    /**
     * Dupliquer un code promo
     */
    public function duplicate(PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        $copy = $promoCode->replicate();
        $copy->code = strtoupper(Str::random(8)); // Générer un nouveau code
        $copy->utilisations = 0;
        $copy->save();

        // Copier les produits ciblés
        $copy->produits()->attach($promoCode->produits->pluck('id'));

        return redirect()->route('vendeur.promo-codes.show', $copy->id)
            ->with('success', "Code promo dupliqué! Nouveau code: {$copy->code}");
    }

    /**
     * Supprimer un code promo
     */
    public function destroy(PromoCode $promoCode)
    {
        $this->checkOwnership($promoCode);

        // Vérifier que le code n'a pas été utilisé
        if ($promoCode->utilisations > 0) {
            return back()->with('error', 'Impossible de supprimer un code qui a été utilisé');
        }

        $code = $promoCode->code;
        $promoCode->delete();

        return redirect()->route('vendeur.promo-codes.index')
            ->with('success', "Code promo $code supprimé!");
    }

    /**
     * Générer un code aléatoire
     */
    public function generateCode()
    {
        $code = strtoupper(Str::random(8));

        // S'assurer que le code est unique
        while (PromoCode::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }

        return response()->json(['code' => $code]);
    }

    /**
     * Vérifier la disponibilité et validité d'un code + calculer la réduction
     */
    public function checkCode(Request $request)
    {
        $code = strtoupper(str_replace(' ', '', $request->input('code', '')));

        // Chercher le code
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo) {
            return response()->json([
                'message' => 'Code promo non trouvé',
            ], 404);
        }

        // Vérifier si le code est actif et valide
        if (!$promo->canBeUsed()) {
            return response()->json([
                'message' => 'Ce code n\'est pas valide ou a expiré',
            ], 422);
        }

        // Récupérer le panier de l'utilisateur
        $user = auth()->user();
        $panier = $user->panier;

        if (!$panier || $panier->items->isEmpty()) {
            return response()->json([
                'message' => 'Votre panier est vide',
            ], 422);
        }

        // Calculer le total du panier
        $total = $panier->items->sum(fn($item) => $item->quantite * $item->prix_unitaire);

        // Vérifier le minimum d'achat
        if ($promo->montant_minimum && $total < $promo->montant_minimum) {
            return response()->json([
                'message' => "Montant minimum: " . number_format($promo->montant_minimum) . " FCFA",
            ], 422);
        }

        // Calculer la réduction
        $reduction = $promo->calculerReduction($total);

        return response()->json([
            'code' => $code,
            'reduction' => $reduction,
            'message' => "Code appliqué! Économies: " . number_format($reduction) . " FCFA",
        ], 200);
    }
}
