<?php

declare(strict_types=1);

arch('helpers')
    ->expect('App\Helpers')
    ->toExtendNothing()
    ->toImplementNothing()
    ->toHaveSuffix('Helper')
    ->toBeFinal()
    ->toBeReadonly();
