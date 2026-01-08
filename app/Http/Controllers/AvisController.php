<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Stocker un nouvel avis
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|min:10|max:1000',
        ], [
            'note.required' => 'Veuillez donner une note',
            'note.min' => 'La note doit être au minimum 1 étoile',
            'commentaire.required' => 'Veuillez écrire un avis',
            'commentaire.min' => 'L\'avis doit contenir au moins 10 caractères',
            'commentaire.max' => 'L\'avis ne peut pas dépasser 1000 caractères',
        ]);

        // Vérifier si l'utilisateur a déjà donné un avis pour ce produit
        $avisExistant = Avis::where('user_id', Auth::id())
            ->where('produit_id', $validated['produit_id'])
            ->first();

        if ($avisExistant) {
            // Mettre à jour l'avis existant
            $avisExistant->update([
                'note' => $validated['note'],
                'commentaire' => $validated['commentaire'],
            ]);
            $message = 'Votre avis a été mis à jour avec succès !';
        } else {
            // Créer un nouvel avis
            Avis::create([
                'user_id' => Auth::id(),
                'produit_id' => $validated['produit_id'],
                'note' => $validated['note'],
                'commentaire' => $validated['commentaire'],
            ]);
            $message = 'Votre avis a été publié avec succès !';
        }

        return redirect()
            ->route('produits.show', $validated['produit_id'])
            ->with('success', $message);
    }

    /**
     * Supprimer un avis (optionnel)
     */
    public function destroy(Avis $avis)
    {
        // Vérifier que l'utilisateur est propriétaire de l'avis
        if ($avis->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $produitId = $avis->produit_id;
        $avis->delete();

        return redirect()
            ->route('produits.show', $produitId)
            ->with('success', 'Votre avis a été supprimé');
    }

    /**
     * Calculer la note moyenne d'un produit
     */
    public static function calculerNoteMoyenne(Produit $produit)
    {
        return $produit->avis()->avg('note') ?? 0;
    }
}
