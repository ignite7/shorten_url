<?php

declare(strict_types=1);

use App\Actions\Urls\RedirectToSource;
use App\Actions\Urls\ShortenUrl;
use App\Actions\Urls\ToggleUrlStatus;
use App\Actions\Urls\UpdateAnonymousToken;
use App\Actions\Urls\UpdateUrlSource;
use App\Actions\Urls\ViewUrls;
use Illuminate\Support\Facades\Route;

Route::get('/', ViewUrls::class)->name('home');

Route::get('/{url}', RedirectToSource::class)->name('redirect-to-source');

Route::name('urls.')
    ->prefix('urls')
    ->group(static function (): void {
        Route::post('/', ShortenUrl::class)->name('store');
        Route::put('/{url}/toggle-status', ToggleUrlStatus::class)->name('toggle-status');
        Route::put('/{url}/source', UpdateUrlSource::class)->name('source.update');
        Route::put('anonymous-token', UpdateAnonymousToken::class)->name('anonymous-token.update');
    });
