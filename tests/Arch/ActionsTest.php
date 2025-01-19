<?php

declare(strict_types=1);

use Lorisleiva\Actions\Concerns\AsObject;

arch('actions')
    ->expect('App\Actions')
    ->toHaveMethod('handle')
    ->toExtendNothing()
    ->toImplementNothing()
    ->toUseTraits([
        AsObject::class,
    ]);
