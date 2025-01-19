<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

arch('seeders')
    ->expect('Database\Seeders')
    ->toExtend(Seeder::class)
    ->toHaveMethod('run')
    ->toOnlyBeUsedIn('Database\Seeders')
    ->toHaveSuffix('Seeder');
