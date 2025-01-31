<?php

declare(strict_types=1);

use App\Http\Middleware\AnonymousTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(AnonymousTokenMiddleware::class)
    ->get('/', static fn () => inertia('Home'))
    ->name('home');

require __DIR__ . '/guest.php';
