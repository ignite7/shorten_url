<?php

declare(strict_types=1);

use Illuminate\Contracts\Queue\ShouldQueue;

arch('jobs')
    ->expect('App\Jobs')
    ->toHaveMethod('handle')
    ->toHaveConstructor()
    ->toExtendNothing()
    ->toImplement(ShouldQueue::class);
