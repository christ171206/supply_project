<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminModeController extends Controller
{
    /**
     * Activer le mode client
     * L'administrateur peut naviguer comme un client pour tester
     * mais ne peut pas passer de commande ou gérer le panier
     */
    public function enterClientMode(Request $request): RedirectResponse
    {
        // Vérifier que c'est un admin
        if (!auth()->user()->is_admin) {
            return back()->with('error', 'Accès non autorisé');
        }

        // Stocker l'état en session
        session(['admin_client_mode' => true]);

        // Rediriger vers l'accueil avec un message
        return redirect(route('accueil'))->with('success', 'Mode visualisation client activé. Vous pouvez parcourir la plateforme comme un client.');
    }

    /**
     * Désactiver le mode client
     * Retourner au mode administrateur
     */
    public function exitClientMode(Request $request): RedirectResponse
    {
        session()->forget('admin_client_mode');

        return redirect('/admin/dashboard')->with('success', 'Retour au mode administrateur');
    }

    /**
     * Vérifier si l'admin est en mode client
     */
    public static function isInClientMode(): bool
    {
        return session('admin_client_mode', false);
    }

    /**
     * Obtenir le statut actuel
     */
    public function getStatus()
    {
        return [
            'is_admin' => auth()->user()->is_admin,
            'is_in_client_mode' => self::isInClientMode(),
            'can_purchase' => false,
            'can_manage_cart' => false,
        ];
    }
}
