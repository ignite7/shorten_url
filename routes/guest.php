<?php

use App\Actions\Guest\Url\Store;
use Illuminate\Support\Facades\Route;

Route::name('urls.')
    ->prefix('urls')
    ->group(static function () {
        Route::post('/', Store::class)->name('store');
    });

//Route::post('signup', Signup::class)->name('signup');
