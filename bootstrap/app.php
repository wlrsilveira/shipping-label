<?php

use App\Domain\ShippingLabel\Exceptions\DomainException as ShippingLabelDomainException;
use App\Domain\ShippingLabel\Exceptions\ShippingLabelNotFoundException;
use App\Domain\ShippingLabel\Exceptions\UnauthorizedAccessException as ShippingLabelUnauthorizedException;
use App\Domain\User\Exceptions\DomainException as UserDomainException;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/channels.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (
            UserDomainException $e,
            Request $request
        ) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => [
                        'code' => $e->getErrorCode(),
                        'message' => $e->getMessage(),
                    ],
                ], $e->getStatusCode());
            }

            if ($e instanceof InvalidCredentialsException) {
                throw ValidationException::withMessages([
                    'email' => 'The provided credentials are incorrect.',
                ]);
            }

            if ($e instanceof UserAlreadyExistsException) {
                throw ValidationException::withMessages([
                    'email' => $e->getMessage(),
                ]);
            }
            if ($e instanceof UserNotFoundException) {
                abort(404, $e->getMessage());
            }

            abort($e->getStatusCode(), $e->getMessage());
        });

        $exceptions->render(function (
            ShippingLabelDomainException $e,
            Request $request
        ) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => [
                        'code' => $e->getErrorCode(),
                        'message' => $e->getMessage(),
                    ],
                ], $e->getStatusCode());
            }

            if ($e instanceof ShippingLabelNotFoundException) {
                abort(404, $e->getMessage());
            }

            if ($e instanceof ShippingLabelUnauthorizedException) {
                abort(403, $e->getMessage());
            }

            abort($e->getStatusCode(), $e->getMessage());
        });
    })->create();
