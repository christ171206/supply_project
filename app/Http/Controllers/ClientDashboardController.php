<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Données pour le graphique - Dépenses des 7 derniers jours
        $graph_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $amount = $user->commandes()
                ->whereDate('created_at', $date)
                ->sum('total');
            $graph_data[$date] = $amount;
        }

        return view('client.dashboard', compact(
            'commandesTotal',
            'commandesRecentes',
            'montantTotal',
            'commandesEnCours',
            'graph_data'
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
     * Annuler une commande (seulement dans les 10 minutes)
     */
    public function cancelCommande(Request $request, $id)
    {
        $commande = Auth::user()->commandes()->findOrFail($id);

        // Vérifier que la commande est en attente
        if ($commande->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être annulées');
        }

        // Vérifier que moins de 10 minutes se sont écoulées
        $minutesEcoulees = now()->diffInMinutes($commande->created_at);
        if ($minutesEcoulees > 10) {
            return redirect()->back()->with('error', 'Le délai d\'annulation de 10 minutes est dépassé');
        }

        // Annuler la commande
        $commande->update(['statut' => 'annulee']);

        // Optionnel: Remettre le stock s'il avait été décrémenté
        foreach ($commande->ligneCommandes as $ligne) {
            if ($ligne->produit) {
                $ligne->produit->increment('stock', $ligne->quantite);
            }
        }

        return redirect()->route('client.commandes')
            ->with('success', 'Votre commande a été annulée avec succès');
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
            'lastname' => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'delivery_latitude' => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('client.profil')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Mettre à jour la photo de profil
     */
    public function updateProfilPhoto(Request $request)
    {
        $validated = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Supprimer l'ancienne photo si elle existe
        if ($validated['profile_photo'] ?? false) {
            if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                Storage::delete($user->profile_photo);
            }

            // Stocker la nouvelle photo
            $path = $request->file('profile_photo')->store('profils/clients', 'public');
            $user->update(['profile_photo' => $path]);
        }

        return redirect()->route('client.profil')->with('success', 'Photo de profil mise à jour avec succès !');
    }
}
