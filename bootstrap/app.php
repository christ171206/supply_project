<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Enregistrer les middlewares alias
        $middleware->alias([
            'vendeur' => \App\Http\Middleware\VendeurMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // Ajouter le middleware global pour la sécurité logging
        $middleware->web(\App\Http\Middleware\LogSecurityEvents::class);

        // Exclure logout du CSRF (action critique qui doit toujours fonctionner)
        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
