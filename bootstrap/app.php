<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handler untuk ValidationException (harus pertama)
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        });

        // Handler untuk QueryException
        $exceptions->renderable(function (QueryException $e) {
            return response()->view('errors.db', [], 500);
        });

        // Handler fallback
        $exceptions->renderable(function (Throwable $e) {
            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }
            if ($e instanceof TokenMismatchException) {
                return back()->with('error', 'Session expired. Please try again.');
            }
            return response()->view('errors.500', [
                'exception' => $e // Optional: kirim exception ke view untuk debugging
            ], 500);
        });
    })->create();
