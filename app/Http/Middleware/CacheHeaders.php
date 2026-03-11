<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     * Ajoute les headers pour cache les ressources statiques
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Cache images pour 30 jours
        if ($request->is('storage/*') || $request->is('images/*')) {
            $response->header('Cache-Control', 'public, max-age=2592000'); // 30 jours
            $response->header('Etag', '"' . md5($response->getContent()) . '"');
        }

        // Cache CSS/JS pour 7 jours
        else if ($request->is('build/*') || $request->is('*.css') || $request->is('*.js')) {
            $response->header('Cache-Control', 'public, max-age=604800'); // 7 jours
            $response->header('Etag', '"' . md5($response->getContent()) . '"');
        }

        // Compression GZIP pour HTML/JSON
        if ($response->headers->get('Content-Type')) {
            $contentType = $response->headers->get('Content-Type');
            if (str_contains($contentType, ['text/', 'application/json', 'text/javascript'])) {
                $response->header('Content-Encoding', 'gzip');
            }
        }

        return $response;
    }
}
