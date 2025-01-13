<?php

use App\Actions\Urls\ShortenUrl;
use Illuminate\Support\Facades\Route;

Route::name('urls.')
    ->prefix('urls')
    ->group(static function () {
        Route::post('/', ShortenUrl::class)->name('store');
    });

//Route::post('signup', Signup::class)->name('signup');
