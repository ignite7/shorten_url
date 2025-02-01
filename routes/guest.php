<?php

declare(strict_types=1);

use App\Actions\Urls\ShortenUrl;
use App\Http\Middleware\AnonymousTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(AnonymousTokenMiddleware::class)
    ->get('/', static fn () => inertia('Home'))
    ->name('home');

Route::name('urls.')
    ->prefix('urls')
    ->group(static function (): void {
        Route::post('/', ShortenUrl::class)->name('store');
    });
