<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',   // ✅ ADD THIS
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
	    $middleware->api(prepend: [
        	EnsureFrontendRequestsAreStateful::class,
	    ]);
		// 🔥 THIS ENABLES SESSION (REQUIRED)
		$middleware->appendToGroup('api', [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
		]);

	    $middleware->redirectGuestsTo(fn () => null);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
