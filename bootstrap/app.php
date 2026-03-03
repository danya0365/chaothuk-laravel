<?php

use App\Console\Commands\DailyIssuePoint;
use App\Http\Middleware\IsCanAccessBackend;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
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
            //\Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'IsCanAccessBackend' => IsCanAccessBackend::class
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('frontend/*')) {
                return route('frontend.auth.login');
            }
            return route('login');
        });

        //$middleware->append(Cors::class); //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
