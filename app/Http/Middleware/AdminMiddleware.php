<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié et est un admin
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Vérifier si c'est un administrateur
        if (!$user->isAdmin()) {
            abort(403, 'Accès refusé. Vous ne disposez pas des permissions d\'administrateur.');
        }

        return $next($request);
    }
}
