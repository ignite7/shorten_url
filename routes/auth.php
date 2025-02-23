<?php

declare(strict_types=1);

use App\Actions\Auth\SendVerificationCodeEmail;
use App\Actions\Auth\Signup;

Route::post('signup', Signup::class)->name('signup');

Route::post('send-verification-code', SendVerificationCodeEmail::class)->name('send-verification-code');
