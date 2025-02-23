<?php

declare(strict_types=1);

use App\Validations\SourceValidation;

it('has the correct source rules', function (): void {
    $rules = SourceValidation::rules();

    expect($rules)
        ->toBeArray()
        ->toBe([
            'source' => ['required', 'url', 'min:10', 'max:255'],
        ]);
});

it('has the correct validation attributes', function (): void {
    $validationAttributes = SourceValidation::validationAttributes();

    expect($validationAttributes)
        ->toBeArray()
        ->toBe([
            'source' => 'destination URL',
        ]);
});
