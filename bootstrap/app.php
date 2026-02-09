<?php

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return ApiResponse::error(
                        'Validation failed',
                        422,
                        $e->getMessage(),
                    );
                }

                if ($e->getPrevious() instanceof ModelNotFoundException) {
                    return ApiResponse::error(
                        'Resource not found',
                        404,
                        $e->getMessage(),
                    );
                }

                return ApiResponse::error(
                    'Internal server error',
                    500,
                    $e->getMessage(),
                );
            }

            throw $e;
        });
    })->create();
