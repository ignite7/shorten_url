<?php

declare(strict_types=1);

use App\Helpers\InertiaHelper;

it('can append the index of the give page', function (): void {
    $page = 'Home';

    $indexPage = InertiaHelper::indexPage($page);

    expect($indexPage)->toBe("$page/index");
});
