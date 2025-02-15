<?php

declare(strict_types=1);

use App\Rules\SourceRule;

it('has the correct source rules', function (): void {
    $rules = SourceRule::rules();

    expect($rules)->toBe([
        'source' => ['required', 'url', 'min:10', 'max:255'],
    ]);
});
