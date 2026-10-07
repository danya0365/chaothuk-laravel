<?php

use App\Http\Middleware\IsCanAccessBackend;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->prefix('api')->group(base_path('routes/api/v1.php'));
            Route::middleware('web')->prefix('ajax')->group(base_path('routes/ajax.php'));
            Route::middleware('web')->group(base_path('routes/frontend.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);

        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // throttleApi() only registers the named limiter — the middleware still
            // has to be added, otherwise requests to /api are never throttled.
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':240,60',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'IsCanAccessBackend' => IsCanAccessBackend::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            // An unauthenticated API caller must get a 401 JSON body, not a 302 to
            // an HTML login page — the mobile client cannot follow a web redirect.
            if ($request->is('api') || $request->is('api/*')) {
                return null;
            }

            if ($request->is('backend') || $request->is('backend/*')) {
                return route('backend.auth.login');
            }

            return route('frontend.auth.login');
        });

        //$middleware->append(Cors::class); //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Everything under /api is a machine client: answer with JSON, never HTML.
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api') || $request->is('api/*')) {
                return response()->json([
                    'message' => 'กรุณาเข้าสู่ระบบ',
                ], 401);
            }
        });
    })->create();
