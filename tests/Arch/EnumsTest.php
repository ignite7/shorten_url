<?php

declare(strict_types=1);

use App\Traits\EnumUtils;

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toExtendNothing()
    ->toUseTrait(EnumUtils::class)
    ->toHaveMethod('values');
