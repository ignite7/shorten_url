<?php

declare(strict_types=1);

use App\Actions\Urls\ShortenUrl;
use Illuminate\Support\Facades\Route;

Route::name('urls.')
    ->prefix('urls')
    ->group(static function (): void {
        Route::post('/', ShortenUrl::class)->name('store');
    });
