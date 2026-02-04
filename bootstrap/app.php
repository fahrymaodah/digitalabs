<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude webhook routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
        ]);

        // Add referral tracking middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\TrackReferral::class,
        ]);

        // Redirect to Filament login for auth:user middleware
        $middleware->redirectGuestsTo(fn () => route('filament.user.auth.login'));
        $middleware->redirectUsersTo(fn () => route('filament.user.pages.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
