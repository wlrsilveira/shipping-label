<?php

use App\Domain\User\Exceptions\DomainException as UserDomainException;
use App\Domain\User\Exceptions\InvalidCredentialsException;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\Exceptions\UserNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
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
            // Para APIs, retornar JSON estruturado
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => [
                        'code' => $e->getErrorCode(),
                        'message' => $e->getMessage(),
                    ],
                ], $e->getStatusCode());
            }

            // Para requisições web, converter em ValidationException para exibir no formulário
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

            // Para outras exceções de domínio, retornar erro apropriado
            if ($e instanceof UserNotFoundException) {
                abort(404, $e->getMessage());
            }

            abort($e->getStatusCode(), $e->getMessage());
        });
    })->create();
