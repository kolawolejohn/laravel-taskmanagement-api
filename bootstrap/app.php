<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\ExceptionHandlerHelper;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $handler = new ExceptionHandlerHelper();

        //Handle ValidationException
        $exceptions->renderable(function (ValidationException $e, $request) use ($handler) {
            return $handler->handleValidationException($e, $request);
        });

        //Handle NotFoundHttpException
        $exceptions->renderable(function (NotFoundHttpException $e, $request) use ($handler) {
            return $handler->handleNotFoundHttpException($e,);
        });

        //Handle NotFoundHttpException
        $exceptions->renderable(function (HttpException $e, $request) use ($handler) {
            return $handler->handleHttpException($e,);
        });

        $exceptions->renderable(function (Throwable $e, $request) use ($handler) {
            if (app()->hasDebugModeEnabled()) {
                return null;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'exception' => get_class($e),
            ], 500);
        });
    })->create();
