<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord client
     */
    public function index()
    {
        $user = Auth::user();
        $commandesTotal = $user->commandes()->count();
        $commandesRecentes = $user->commandes()->latest()->take(5)->get();
        $montantTotal = $user->commandes()->sum('total');
        $commandesEnCours = $user->commandes()->whereIn('statut', ['en_attente', 'confirmee', 'expediee'])->count();

        return view('client.dashboard', compact(
            'commandesTotal',
            'commandesRecentes',
            'montantTotal',
            'commandesEnCours'
        ));
    }

    /**
     * Afficher les commandes du client
     */
    public function commandes()
    {
        $user = Auth::user();
        $commandes = $user->commandes()->latest()->paginate(10);

        return view('client.commandes', compact('commandes'));
    }

    /**
     * Afficher le détail d'une commande
     */
    public function commandeDetail($id)
    {
        $commande = Auth::user()->commandes()->with('ligneCommandes.produit.vendeur')->findOrFail($id);

        return view('client.commande-detail', compact('commande'));
    }

    /**
     * Afficher les messages
     */
    public function messages()
    {
        $user = Auth::user();
        $conversations = $user->messagesEnvoyes()
            ->latest('updated_at')
            ->paginate(10);

        return view('client.messages', compact('conversations'));
    }

    /**
     * Afficher le profil
     */
    public function profil()
    {
        $user = Auth::user();

        return view('client.profil', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfil(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('client.dashboard')->with('success', 'Profil mis à jour avec succès !');
    }
}
