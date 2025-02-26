<?php

declare(strict_types=1);

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            $statusCode = $response->getStatusCode();

            if (in_array($statusCode, [500, 503, 404, 403]) && ! app()->environment(['local', 'testing'])) {
                return inertia('Error/index', [
                    'status' => $statusCode,
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            }

            if ($statusCode === 419) {
                FlashHelper::message('The page expired, please try again.', FlashMessageType::ERROR);

                return back();
            }

            if ($statusCode === 429) {
                FlashHelper::message('Too many requests, please try again later.', FlashMessageType::ERROR);

                return back();
            }

            return $response;
        });
    })->create();
