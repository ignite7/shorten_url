<?php

declare(strict_types=1);

use App\Actions\Urls\ShortenUrl;
use App\Actions\Urls\ViewUrls;
use Illuminate\Support\Facades\Route;

Route::get('/', ViewUrls::class)->name('home');

Route::name('urls.')
    ->prefix('urls')
    ->group(static function (): void {
        Route::post('/', ShortenUrl::class)->name('store');
    });
