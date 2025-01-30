<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => inertia('Home'))->name('home');

require __DIR__.'/guest.php';
