<?php

namespace App\Http\Controllers\Vendeur;

use App\Models\Produit;
use App\Models\Commande;
use App\Models\Message;
use App\Models\StockMouvement;
use Illuminate\Routing\Controller;

class EspaceVendeurController extends Controller
{
    /**
     * 📊 APERÇU (Page principale de l'Espace Vendeur)
     */
    public function apercu()
    {
        $user = auth()->user();

        // Récupérer les données du vendeur
        $produits = Produit::where('user_id', $user->id)->get();
        $commandes = Commande::whereHas('vendeur', function ($q) use ($user) {
            $q->where('id', $user->id);
        })->get();

        // Statistiques
        $stats = [
            'total_produits' => $produits->count(),
            'produits_en_stock' => $produits->where('stock', '>', 0)->count(),
            'stock_critique' => $produits->filter(fn($p) => $p->isStockCritique())->count(),
            'commandes_en_cours' => $commandes->whereIn('statut', ['en_attente', 'confirmée'])->count(),
            'commandes_terminees' => $commandes->whereIn('statut', ['livrée'])->count(),
            'chiffre_affaires' => $commandes->where('statut', 'livrée')->sum('total'),
        ];

        // Dernières commandes (5)
        $dernieres_commandes = $commandes->sortByDesc('created_at')->take(5);

        // Produits critiques (3)
        $produits_critiques = $produits->filter(fn($p) => $p->isStockCritique())->take(3);

        // Messages récents (3)
        $messages_recents = Message::where('to_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('vendeur.apercu', compact(
            'user',
            'stats',
            'dernieres_commandes',
            'produits_critiques',
            'messages_recents'
        ));
    }

    /**
     * 📜 HISTORIQUE DES COMMANDES
     */
    public function historiqueCommandes()
    {
        $user = auth()->user();

        $commandes = Commande::whereHas('vendeur', function ($q) use ($user) {
            $q->where('id', $user->id);
        })
            ->where('statut', 'livrée')
            ->latest()
            ->paginate(15);

        return view('vendeur.commandes.historique', compact('commandes'));
    }

    /**
     * ⚙️ PAGE PROFIL VENDEUR
     */
    public function profil()
    {
        $user = auth()->user();
        return view('vendeur.profil', compact('user'));
    }

    /**
     * ⚙️ METTRE À JOUR PROFIL VENDEUR
     */
    public function updateProfil(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string',
            'shop_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès');
    }
}
