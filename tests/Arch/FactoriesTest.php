<?php

declare(strict_types=1);

use Database\Factories\Traits\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

arch('factories')
    ->expect('Database\Factories')
    ->toExtend(Factory::class)
    ->ignoring('Database\Factories\Traits')
    ->toUse(RefreshOnCreate::class)
    ->toHaveMethod('definition')
    ->ignoring('Database\Factories\Traits')
    ->toOnlyBeUsedIn([
        'App\Models',
        'Database\Seeders',
    ]);
