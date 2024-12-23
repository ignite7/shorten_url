<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return inertia('Home', [
        'name' => 'Kai',
    ]);
})->name('home');


require __DIR__ . '/guest.php';
