
<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'active' => EnsureUserIsActive::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (
            TokenMismatchException $exception,
            Request $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi telah kedaluwarsa.',
                ], 419);
            }

            return response()->view('errors.419', [], 419);
        });

        $exceptions->render(function (
            HttpExceptionInterface $exception,
            Request $request
        ) {
            $status = $exception->getStatusCode();

            if (!in_array($status, [403, 404], true)) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $status === 403
                        ? 'Akses ditolak.'
                        : 'Halaman tidak ditemukan.',
                ], $status);
            }

            return response()->view("errors.{$status}", [], $status);
        });

    })->create();
