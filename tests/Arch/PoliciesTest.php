<?php

declare(strict_types=1);

arch('policies')
    ->expect('App\Policies')
    ->toExtendNothing()
    ->toImplementNothing()
    ->toHaveSuffix('Policy')
    ->toBeFinal()
    ->toBeReadonly();
