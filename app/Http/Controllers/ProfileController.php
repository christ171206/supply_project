<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les stats du client
        $commandes = $user->commandes()->orderBy('created_at', 'desc')->get();
        $totalDépensé = $commandes->sum('total');
        $nombreCommandes = $commandes->count();
        
        // Produits favoris
        $favoris = $user->favorites()->with('produit')->get();
        
        // Catégories d'achat (top 5)
        $categoriesAchat = DB::table('ligne_commandes')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->where('commandes.user_id', $user->id)
            ->select('categories.nom', DB::raw('COUNT(*) as total'), DB::raw('SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as montant'))
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Produits achetés (top 5)
        $produitsAchetes = DB::table('ligne_commandes')
            ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
            ->where('commandes.user_id', $user->id)
            ->select('produits.id', 'produits.nom', DB::raw('SUM(ligne_commandes.quantite) as total_quantite'), DB::raw('SUM(ligne_commandes.quantite * ligne_commandes.prix_unitaire) as montant'))
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('total_quantite')
            ->limit(5)
            ->get();
        
        // Dépenses par mois (derniers 6 mois)
        $depensesParMois = DB::table('commandes')
            ->where('user_id', $user->id)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'), DB::raw('SUM(total) as total'))
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();
        
        return view('profile.edit', [
            'user' => $user,
            'commandes' => $commandes->take(5),
            'totalDépensé' => $totalDépensé,
            'nombreCommandes' => $nombreCommandes,
            'favoris' => $favoris,
            'categoriesAchat' => $categoriesAchat,
            'produitsAchetes' => $produitsAchetes,
            'depensesParMois' => $depensesParMois,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Switch vendor role to client mode.
     */
    public function switchToClient(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Vérifier que l'utilisateur est authentifié
        if (!$user) {
            return Redirect::route('login');
        }

        // Changer le rôle de l'utilisateur à client
        $user->update(['role' => 'client']);

        // Message de succès
        return Redirect::route('client.dashboard')->with('success', 'Vous êtes maintenant en mode client.');
    }
}
