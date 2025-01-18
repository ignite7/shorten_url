<?php

declare(strict_types=1);

use App\Http\Middleware\HandleInertiaRequests;

it('can get the share data keys', function (): void {
    $middleware = new HandleInertiaRequests();

    $share = $middleware->share(request());

    expect($share)->toHaveKeys([
        'errors',
        'auth.user',
        'ziggy',
        'flash.message',
        'flash.type',
    ]);
});
