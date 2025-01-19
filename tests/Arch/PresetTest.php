<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel()->ignoring([
    'App\Http\Middleware',
]);

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal();

arch('ensure no extends')
    ->expect('App')
    ->classes()
    ->not->toBeAbstract();

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Http\Requests',
        'App\Http\Middleware',
        'App\Jobs',
        'App\Models',
        'App\Providers',
        'App\Actions',
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Http\Requests',
        'App\Http\Middleware',
        'App\Jobs',
        'App\Models',
        'App\Providers',
        'App\Actions',
    ]);

arch('annotations')
    ->expect('App')
    ->toHavePropertiesDocumented()
    ->toHaveMethodsDocumented();
