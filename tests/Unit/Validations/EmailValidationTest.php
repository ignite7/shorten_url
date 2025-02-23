<?php

declare(strict_types=1);

use App\Models\User;
use App\Validations\EmailValidation;
use Illuminate\Validation\Rule;

it('has the correct source rules', function (): void {
    $rules = EmailValidation::rules();

    expect($rules)
        ->toBeArray()
        ->toMatchArray([
            'email' => ['required', 'email', Rule::unique(User::class, 'email')],
        ]);
});

it('has the correct validation attributes', function (): void {
    $validationAttributes = EmailValidation::validationAttributes();

    expect($validationAttributes)
        ->toBeArray()
        ->toBe([
            'email' => 'email address',
        ]);
});
