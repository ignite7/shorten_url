<?php

declare(strict_types=1);

namespace App\Helpers;

final readonly class InertiaHelper
{
    /**
     * @param  string  $page
     * @return string
     */
    public static function indexPage(string $page): string
    {
        return "{$page}/index";
    }
}
