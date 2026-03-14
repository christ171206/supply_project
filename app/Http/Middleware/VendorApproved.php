<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur est authentifié et que c'est un vendeur
        if (!$user || $user->role !== 'vendor') {
            return redirect()->route('accueil');
        }

        // Vérifier que le vendeur est approuvé
        if ($user->vendor_status !== 'approved') {
            // Rediriger vers l'accueil
            return redirect()->route('accueil')
                ->with('warning', 'Votre compte vendeur est en cours de vérification. Veuillez vérifier votre email pour plus de détails.');
        }

        return $next($request);
    }
}
